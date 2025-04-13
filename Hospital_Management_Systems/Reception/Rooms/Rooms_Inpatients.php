<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search variable
$search_dob = "";

// Base SQL Query
$sql = "SELECT patient_id, patient_name, date_of_birth, age, patient_type, doctor_name, transaction_id, room_id, room_no, room_type 
        FROM patients WHERE patient_type = 'inpatient'";

// Check if search by Date of Birth is applied
if (isset($_POST['search']) && !empty($_POST['dob'])) {
    $search_dob = $_POST['dob'];
    $search_dob = $conn->real_escape_string($search_dob); // Prevent SQL injection
    $sql .= " AND date_of_birth = '$search_dob'";
}

$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inpatients List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="date"], button {
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
        .book-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .book-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Inpatient Records</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <label for="dob">Search by Date of Birth:</label>
        <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($search_dob); ?>">
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
        <th>Transaction ID</th>
        <th>Room ID</th>
        <th>Room No</th>
        <th>Room Type</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $roomId = !empty($row['room_id']) ? $row['room_id'] : "Not Assigned";
            $roomNo = !empty($row['room_no']) ? $row['room_no'] : "Not Assigned";
            $roomType = !empty($row['room_type']) ? $row['room_type'] : "Not Assigned";
            
            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['patient_type']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['transaction_id']}</td>
                    <td>{$roomId}</td>
                    <td>{$roomNo}</td>
                    <td>{$roomType}</td>
                    <td>
                        <a href='Book_Rooms.php?patient_id={$row['patient_id']}' class='book-btn'>Book Room</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='11' style='text-align: center;'>No inpatients found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
