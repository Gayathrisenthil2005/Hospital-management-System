<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "hospital"; 

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_value = "";
$search_dob = "";

$sql = "SELECT p.patient_id, p.patient_name, p.date_of_birth, p.age, p.gender, p.mobile_number, 
               p.patient_type, p.doctor_name, r.room_id, r.room_number, r.room_type, 
               r.status_id, r.booking_date, r.occupancy_status
        FROM patients p
        INNER JOIN rooms r ON p.room_id = r.room_id 
        WHERE p.transaction_id IS NOT NULL";

// Search by Patient ID, Name, or Date of Birth
if (isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    $search_dob = $_POST['search_dob'];

    if (!empty($search_value)) {
        $sql .= " AND (p.patient_id LIKE '%$search_value%' OR p.patient_name LIKE '%$search_value%')";
    }

    if (!empty($search_dob)) {
        $sql .= " AND p.date_of_birth = '$search_dob'";
    }
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"], button {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
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
        .update-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .update-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Manage Inpatient Rooms</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <input type="text" name="search_value" placeholder="Search by Patient ID or Name" value="<?php echo $search_value; ?>">
        <input type="date" name="search_dob" value="<?php echo $search_dob; ?>">
        <button type="submit" name="search">Search</button>
    </form>
</div>

<table>
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Patient Type</th>
        <th>Doctor</th>
        <th>Room ID</th>
        <th>Room Number</th>
        <th>Room Type</th>
        <th>Status</th>
        <th>Booking Date</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['patient_type']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['room_id']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['room_type']}</td>
                    <td>{$row['status_id']}</td>
                    <td>{$row['booking_date']}</td>
                    <td>
                        <a href='Update_Rooms.php?patient_id={$row['patient_id']}' class='update-btn'>Update</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='15' style='text-align: center;'>No inpatients found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
