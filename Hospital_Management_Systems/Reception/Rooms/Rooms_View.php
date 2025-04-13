<?php
$servername = "localhost";
$username = "root"; // Default WAMP/XAMPP username
$password = ""; // Default WAMP/XAMPP password
$database = "hospital"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search filter
$room_type_filter = isset($_GET['room_type']) ? $_GET['room_type'] : "";

// Build SQL Query
$sql = "SELECT r.room_id, r.room_type, r.room_number, r.occupancy_status, 
               r.status_id, r.booking_date, p.patient_id 
        FROM rooms r 
        LEFT JOIN patients p ON r.room_id = p.room_id AND p.patient_type = 'inpatient'";

if (!empty($room_type_filter)) {
    $sql .= " WHERE r.room_type = ?";
}

// Prepare and execute query
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

if (!empty($room_type_filter)) {
    $stmt->bind_param("s", $room_type_filter);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }
        .filter-container {
            margin-bottom: 20px;
            text-align: center;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Hospital Room Details</h2>

<!-- Room Type Filter -->
<div class="filter-container">
    <form method="GET">
        <label>Filter by Room Type:</label>
        <select name="room_type">
            <option value="">All</option>
            <option value="General Ward" <?= ($room_type_filter == "General Ward") ? "selected" : "" ?>>General Ward</option>
            <option value="Emergency Ward" <?= ($room_type_filter == "Emergency Ward") ? "selected" : "" ?>>Emergency Ward</option>
            <option value="Room" <?= ($room_type_filter == "Room") ? "selected" : "" ?>>Room</option>
            <option value="Private Room" <?= ($room_type_filter == "Private Room") ? "selected" : "" ?>>Private Room</option>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<table>
    <tr>
        <th>Room ID</th>
        <th>Room Type</th>
        <th>Room Number</th>
        <th>Occupancy Status</th>
        <th>Status ID</th>
        <th>Booking Date</th>
        <th>Patient ID</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['room_id']}</td>
                    <td>{$row['room_type']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['occupancy_status']}</td>
                    <td>" . (!empty($row['status_id']) ? $row['status_id'] : 'N/A') . "</td>
                    <td>" . (!empty($row['booking_date']) ? $row['booking_date'] : 'N/A') . "</td>
                    <td>" . (!empty($row['patient_id']) ? $row['patient_id'] : 'Available') . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7' style='text-align: center;'>No rooms found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
