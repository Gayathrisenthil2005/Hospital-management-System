<?php
session_start();
include("../BackEnd/dbconnection.php"); // Ensure correct database connection

// Fetch unique room types for dropdown
$room_types_query = "SELECT DISTINCT room_type FROM rooms";
$room_types_result = mysqli_query($conn, $room_types_query);

// Fetch unique floors for dropdown
$floors_query = "SELECT DISTINCT LEFT(room_id, 2) AS floor FROM rooms";
$floors_result = mysqli_query($conn, $floors_query);

// Function to generate a unique Room ID and Room Number
function generateRoomDetails($conn, $floor, $room_type) {
    // Fetch the highest existing room number for the selected floor & room type
    $query = "SELECT MAX(room_number) AS last_room FROM rooms WHERE LEFT(room_id, 2) = '$floor' AND room_type = '$room_type'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Increment the room number for the new room
    $new_room_number = $row['last_room'] ? $row['last_room'] + 1 : ($floor * 100 + 1); // Default starts from 101

    // Generate Room ID based on type
    if ($room_type == "Emergency Ward") {
        $room_id = "{$floor}-EW";
    } elseif ($room_type == "Private Room") {
        $room_id = "{$floor}-PR{$new_room_number}";
    } elseif ($room_type == "General Ward") {
        $room_id = "G01"; // General Ward has a fixed ID
    } else {
        $room_id = "{$floor}-R{$new_room_number}";
    }

    return array($room_id, $new_room_number);
}

// Handle room addition
if (isset($_POST['add_room'])) {
    $floor = mysqli_real_escape_string($conn, $_POST['floor']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);

    // Generate Room ID & Room Number
    list($room_id, $room_number) = generateRoomDetails($conn, $floor, $room_type);
    $occupancy_status = "available"; // Default status

    // Insert new room into database
    $insert_query = "INSERT INTO rooms (room_id, room_type, room_number, occupancy_status) 
                     VALUES ('$room_id', '$room_type', '$room_number', '$occupancy_status')";

    if (mysqli_query($conn, $insert_query)) {
        $success = "Room added successfully! Room ID: $room_id, Room Number: $room_number";
    } else {
        $error = "Error adding room: " . mysqli_error($conn);
    }
}

// Handle room deletion
if (isset($_GET['delete'])) {
    $room_id = mysqli_real_escape_string($conn, $_GET['delete']);

    $delete_query = "DELETE FROM rooms WHERE room_id = '$room_id'";
    if (mysqli_query($conn, $delete_query)) {
        $success = "Room deleted successfully!";
    } else {
        $error = "Error deleting room: " . mysqli_error($conn);
    }
}

// Fetch all rooms from the database
$rooms_query = "SELECT * FROM rooms ORDER BY room_number ASC";
$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms | Hospital Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: rgb(3, 30, 59);
            font-size: 28px;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 15px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            background: rgb(3, 30, 59);
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }

        .btn:hover {
            background: rgb(2, 20, 40);
            transform: scale(1.05);
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: rgb(3, 30, 59);
            color: white;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Manage Rooms</h1>

    <div class="container">
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="POST">
            <div class="form-group">
                <label>Select Floor:</label>
                <select name="floor" required>
                    <option value="">Select Floor</option>
                    <?php while ($floor = mysqli_fetch_assoc($floors_result)) { ?>
                        <option value="<?php echo $floor['floor']; ?>"><?php echo $floor['floor']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Select Room Type:</label>
                <select name="room_type" required>
                    <option value="">Select Room Type</option>
                    <?php while ($room = mysqli_fetch_assoc($room_types_result)) { ?>
                        <option value="<?php echo $room['room_type']; ?>"><?php echo $room['room_type']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn" name="add_room">Add Room</button>
        </form>
    </div>

    <div class="container table-container">
        <h2>Room List</h2>
        <table>
            <tr>
                <th>Room ID</th>
                <th>Room Type</th>
                <th>Room Number</th>
                <th>Occupancy Status</th>
            </tr>
            <?php while ($room = mysqli_fetch_assoc($rooms_result)) { ?>
                <tr>
                    <td><?php echo $room['room_id']; ?></td>
                    <td><?php echo $room['room_type']; ?></td>
                    <td><?php echo $room['room_number']; ?></td>
                    <td><?php echo ucfirst($room['occupancy_status']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
