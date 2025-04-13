<?php
$servername = "localhost";
$username = "root"; // Default WAMP/XAMPP username
$password = ""; // Default WAMP/XAMPP password
$database = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Patient ID from URL
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : "";

// Fetch Available Rooms
$sql = "SELECT room_id, room_type, room_number FROM rooms WHERE occupancy_status = 'available'";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching rooms: " . $conn->error);
}

// Function to Generate Sequential Status ID (STR001, STR002, ...)
function generateStatusID($conn) {
    $query = "SELECT status_id FROM rooms ORDER BY status_id DESC LIMIT 1"; 
    $res = $conn->query($query);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $last_id = $row['status_id']; // Example: STR005

        // Extract numeric part from STR005 -> 5
        $numeric_part = intval(substr($last_id, 3));

        // Increment by 1
        $new_id = $numeric_part + 1;

        // Format as STR001, STR002, STR003, etc.
        return "STR" . str_pad($new_id, 3, "0", STR_PAD_LEFT);
    } else {
        // If no records exist, start from STR001
        return "STR001";
    }
}

// Handle Room Booking
if (isset($_POST['book'])) {
    $room_data = explode("|", $_POST['room_id']); // Split room_id and room_number
    $room_id = $room_data[0];
    $room_number = $room_data[1];

    // Fetch room type based on room_id
    $room_query = "SELECT room_type FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($room_query);
    $stmt->bind_param("s", $room_id);
    $stmt->execute();
    $room_result = $stmt->get_result();
    $stmt->close();

    if ($room_result->num_rows > 0) {
        $room_row = $room_result->fetch_assoc();
        $room_type = $room_row['room_type'];
    } else {
        echo "<script>alert('Error fetching room type!');</script>";
        exit;
    }

    $status_id = generateStatusID($conn); // Generate status_id

    if (!empty($patient_id) && !empty($room_id) && !empty($room_number)) {
        // Start transaction manually
        $conn->autocommit(false);

        try {
            // Update Room Occupancy with Booking Date
            $update_sql = "UPDATE rooms 
                           SET occupancy_status = 'occupied', status_id = ?, booking_date = CURDATE() 
                           WHERE room_id = ? AND room_number = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sss", $status_id, $room_id, $room_number);
            if (!$stmt->execute()) {
                throw new Exception("Error updating room: " . $stmt->error);
            }
            $stmt->close();

            // Update Patients Table with Room Info
            $update_patient_sql = "UPDATE patients 
                                   SET room_id = ?, room_no = ?, room_type = ? 
                                   WHERE patient_id = ?";
            $stmt = $conn->prepare($update_patient_sql);
            $stmt->bind_param("ssss", $room_id, $room_number, $room_type, $patient_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating patient record: " . $stmt->error);
            }
            $stmt->close();

            // Commit transaction
            $conn->commit();
            $conn->autocommit(true);

            echo "<script>alert('Room booked successfully! Status ID: $status_id'); window.location.href='Rooms_Inpatients.php';</script>";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $conn->autocommit(true);
            echo "<script>alert('Error booking room: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Please select a room!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border-radius: 5px;
        }
        .book-btn {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        .book-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Book a Room for Patient</h2>

<div class="form-container">
    <form method="POST">
        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">

        <label>Select Room:</label>
        <select name="room_id" required>
            <option value="">-- Select Room --</option>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['room_id']}|{$row['room_number']}'>{$row['room_id']} - {$row['room_type']} (Room No: {$row['room_number']})</option>";
                }
            } else {
                echo "<option value=''>No available rooms</option>";
            }
            ?>
        </select>

        <button type="submit" name="book" class="book-btn">Book</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
