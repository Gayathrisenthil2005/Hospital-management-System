<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if patient_id is passed
if (!isset($_GET['patient_id'])) {
    die("Patient ID not provided.");
}

$patient_id = $_GET['patient_id'];
$room_id = "";
$room_number = "";
$room_type = "";
$status_id = "";
$occupancy_status = "";

// Fetch patient and room details
$sql = "SELECT r.room_id, r.room_number, r.room_type, r.status_id, r.occupancy_status
        FROM rooms r
        INNER JOIN patients p ON p.room_id = r.room_id
        WHERE p.patient_id = '$patient_id'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $room_id = $row['room_id'];
    $room_number = $row['room_number'];
    $room_type = $row['room_type'];
    $status_id = $row['status_id'];
    $occupancy_status = $row['occupancy_status'];
} else {
    die("No room found for the given patient.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_room_number = $_POST['room_number'];
    $new_room_type = $_POST['room_type'];
    $new_status_id = $_POST['status_id'];
    $new_occupancy_status = $_POST['occupancy_status'];

    $update_sql = "UPDATE rooms 
                   SET room_number = '$new_room_number', 
                       room_type = '$new_room_type', 
                       status_id = '$new_status_id', 
                       occupancy_status = '$new_occupancy_status' 
                   WHERE room_id = '$room_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Room details updated successfully.'); window.location.href='Manage_Rooms.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Room Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Room Details</h2>
    <form method="POST">
        <label>Room Number:</label>
        <input type="number" name="room_number" value="<?php echo $room_number; ?>" required>

        <label>Room Type:</label>
        <input type="text" name="room_type" value="<?php echo $room_type; ?>" required>

        <label>Status ID:</label>
        <input type="text" name="status_id" value="<?php echo $status_id; ?>">

        <label>Occupancy Status:</label>
        <select name="occupancy_status">
            <option value="occupied" <?php if ($occupancy_status == 'occupied') echo 'selected'; ?>>Occupied</option>
            <option value="available" <?php if ($occupancy_status == 'available') echo 'selected'; ?>>Available</option>
        </select>

        <button type="submit" class="submit-btn">Update Room</button>
    </form>
</div>

</body>
</html>
