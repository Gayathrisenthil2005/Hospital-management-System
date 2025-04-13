<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("C:/wamp/www/Hospital_Management_Systems/BackEnd/dbconnection.php");

// Fetch room details
$sql = "SELECT room_id, room_type, room_number, status_id, booking_date, occupancy_status FROM rooms";
$result = $conn->query($sql);

// Check if there are records
if ($result->num_rows == 0) {
    die("No room details found.");
}

// Store results in an array (compatible with older PHP versions)
$rooms = array();
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        .report-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            text-align: left;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgb(3, 30, 59);
            color: white;
            text-align: left;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
        }
        .word-btn {
            background-color: #ffc107;
            color: black;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Rooms Report</h2>
    <table>
        <tr>
            <th>Room ID</th>
            <th>Room Type</th>
            <th>Room Number</th>
            <th>Status ID</th>
            <th>Booking Date</th>
            <th>Occupancy Status</th>
        </tr>
        <?php foreach ($rooms as $room) { ?>
        <tr>
            <td><?php echo htmlspecialchars($room['room_id']); ?></td>
            <td><?php echo htmlspecialchars($room['room_type']); ?></td>
            <td><?php echo htmlspecialchars($room['room_number']); ?></td>
            <td><?php echo htmlspecialchars($room['status_id']); ?></td>
            <td><?php echo htmlspecialchars($room['booking_date']); ?></td>
            <td><?php echo htmlspecialchars($room['occupancy_status']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <div class="button-container">
        <button class="print-btn" onclick="window.print()">Print</button>
        <form method="POST" action="Generate_Rooms_Word.php" style="display: inline;">
            <button type="submit" class="word-btn">Download as Word</button>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
