<?php
// Include database connection
include '../../BackEnd/dbconnection.php'; // Adjust the path if needed

// Initialize variables
$room_id = "";
$room_type = "";
$occupancy_status = "";
$booking_date = "";
$status_id = "";
$where_conditions = array();
$result = null; // Set result to null initially

// Fetch dropdown options from database
$room_id_query = "SELECT DISTINCT room_id FROM rooms ORDER BY room_id ASC";
$room_type_query = "SELECT DISTINCT room_type FROM rooms ORDER BY room_type ASC";
$occupancy_status_query = "SELECT DISTINCT occupancy_status FROM rooms";
$booking_date_query = "SELECT DISTINCT booking_date FROM rooms ORDER BY booking_date ASC";
$status_id_query = "SELECT DISTINCT status_id FROM rooms";

// Execute queries
$room_id_result = mysqli_query($conn, $room_id_query);
$room_type_result = mysqli_query($conn, $room_type_query);
$occupancy_status_result = mysqli_query($conn, $occupancy_status_query);
$booking_date_result = mysqli_query($conn, $booking_date_query);
$status_id_result = mysqli_query($conn, $status_id_query);

// Handle search filters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "SELECT * FROM rooms WHERE 1=1"; // Base query

    if (!empty($_POST['room_id'])) {
        $room_id = $_POST['room_id'];
        $where_conditions[] = "room_id = '" . mysqli_real_escape_string($conn, $room_id) . "'";
    }
    if (!empty($_POST['room_type'])) {
        $room_type = $_POST['room_type'];
        $where_conditions[] = "room_type = '" . mysqli_real_escape_string($conn, $room_type) . "'";
    }
    if (!empty($_POST['occupancy_status'])) {
        $occupancy_status = $_POST['occupancy_status'];
        $where_conditions[] = "occupancy_status = '" . mysqli_real_escape_string($conn, $occupancy_status) . "'";
    }
    if (!empty($_POST['booking_date'])) {
        $booking_date = $_POST['booking_date'];
        $where_conditions[] = "booking_date = '" . mysqli_real_escape_string($conn, $booking_date) . "'";
    }
    if (!empty($_POST['status_id'])) {
        $status_id = $_POST['status_id'];
        $where_conditions[] = "status_id = '" . mysqli_real_escape_string($conn, $status_id) . "'";
    }

    if (!empty($where_conditions)) {
        $query .= " AND " . implode(" AND ", $where_conditions);
    }

    // Execute query only after form submission
    $result = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Rooms Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        h2 {
            padding: 15px;
            margin: 10px;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            width: 70%;
            margin: 10px auto;
        }
        label {
            font-weight: bold;
            flex: 1;
            text-align: right;
            margin-right: 10px;
        }
        select, input {
            flex: 2;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: rgb(4, 33, 63);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: rgb(13, 41, 71);
        }
        table {
            width: 90%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            margin-left: auto;
            margin-right: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>All Rooms Report</h2>
<div class="container">
    <form method="post">
        <div class="form-group">
            <label>Select Room ID:</label>
            <select name="room_id">
                <option value="">All</option>
                <?php while ($row = mysqli_fetch_assoc($room_id_result)) { ?>
                    <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_id']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Room Type:</label>
            <select name="room_type">
                <option value="">All</option>
                <?php while ($row = mysqli_fetch_assoc($room_type_result)) { ?>
                    <option value="<?php echo $row['room_type']; ?>"><?php echo $row['room_type']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Occupancy Status:</label>
            <select name="occupancy_status">
                <option value="">All</option>
                <?php while ($row = mysqli_fetch_assoc($occupancy_status_result)) { ?>
                    <option value="<?php echo $row['occupancy_status']; ?>"><?php echo ucfirst($row['occupancy_status']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Booking Date:</label>
            <select name="booking_date">
                <option value="">All</option>
                <?php while ($row = mysqli_fetch_assoc($booking_date_result)) { ?>
                    <option value="<?php echo $row['booking_date']; ?>"><?php echo $row['booking_date']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Status ID:</label>
            <select name="status_id">
                <option value="">All</option>
                <?php while ($row = mysqli_fetch_assoc($status_id_result)) { ?>
                    <option value="<?php echo $row['status_id']; ?>"><?php echo $row['status_id']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Search</button>
    </form>

    <?php if ($result !== null && mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Room ID</th>
                <th>Room Type</th>
                <th>Room Number</th>
                <th>Occupancy Status</th>
                <th>Booking Date</th>
                <th>Status ID</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['room_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['occupancy_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status_id']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php elseif ($result !== null): ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>
</body>
</html>
<?php mysqli_close($conn); ?>
