<?php
$servername = "localhost";
$username = "root"; // Default WAMP username
$password = ""; // Default WAMP password
$database = "hospital"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search variable
$search_dob = "";

// Check if form is submitted
if (isset($_POST['search'])) {
    $search_dob = $_POST['dob'];
    $sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, address, mobile_number, patient_type, doctor_name, date_of_appointment 
            FROM patients WHERE date_of_birth = '$search_dob'";
} else {
    $sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, address, mobile_number, patient_type, doctor_name, date_of_appointment FROM patients";
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
    <title>Patients View</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #eef2f3;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: rgb(3, 30, 59);
    font-size: 28px;
    margin-bottom: 20px;
}

.container {
    max-width: 90%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Search Form */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
}

input[type="date"] {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.search-btn {
    background-color: rgb(3, 30, 59);
    color: white;
    padding: 8px 15px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.search-btn:hover {
    background-color: rgb(5, 50, 90);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: rgb(3, 30, 59);
    color: white;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

td {
    color: #333;
}

td[colspan] {
    text-align: center;
    font-weight: bold;
    padding: 15px;
    font-size: 18px;
}

    </style>
</head>
<body>

<h2>Patient Records</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <label for="dob">Search by Date of Birth:</label>
        <input type="date" name="dob" id="dob" value="<?php echo $search_dob; ?>">
        <button type="submit" name="search">Search</button>
    </form>
</div>

<table>
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Address</th>
        <th>Contact No</th>
        <th>Patient Type</th>
        <th>Doctor</th>
        <th>Date of Appointment</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['mobile_number']}</td>
                    <td>{$row['patient_type']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['date_of_appointment']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10' style='text-align: center;'>No results found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
