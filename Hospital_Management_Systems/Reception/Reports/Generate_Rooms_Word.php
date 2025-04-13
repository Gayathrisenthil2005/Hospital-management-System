<?php
require_once('../../BackEnd/dbconnection.php'); // Adjust the path if needed

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=All_Rooms_Report.doc");
header("Pragma: no-cache");
header("Expires: 0");

echo "<html>";
echo "<head><meta charset='UTF-8'></head>";
echo "<body>";
echo "<h2 style='text-align:center;'>All Rooms Report</h2>";
echo "<table border='1' style='width:100%; border-collapse:collapse;' cellpadding='5'>";
echo "<tr>
        <th style='background-color:#007bff; color:white;'>Room ID</th>
        <th style='background-color:#007bff; color:white;'>Room Type</th>
        <th style='background-color:#007bff; color:white;'>Room Number</th>
        <th style='background-color:#007bff; color:white;'>Occupancy Status</th>
        <th style='background-color:#007bff; color:white;'>Booking Date</th>
      </tr>";

// Fetch room data from database
$query = "SELECT * FROM rooms";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>" . htmlspecialchars($row['room_id']) . "</td>
            <td>" . htmlspecialchars($row['room_type']) . "</td>
            <td>" . htmlspecialchars($row['room_number']) . "</td>
            <td>" . htmlspecialchars($row['occupancy_status']) . "</td>
            <td>" . htmlspecialchars($row['booking_date']) . "</td>
          </tr>";
}

echo "</table>";
echo "</body>";
echo "</html>";

mysqli_close($conn);
?>
