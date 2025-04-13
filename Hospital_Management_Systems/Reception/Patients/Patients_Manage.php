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

// Delete Patient
if (isset($_POST['delete'])) {
    $patient_id = $_POST['patient_id'];
    $delete_sql = "DELETE FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $patient_id);

    if ($stmt->execute()) {
        echo "<script>alert('Patient record deleted successfully!'); window.location.href='Patients_Manage.php';</script>";
    } else {
        echo "<script>alert('Error deleting record!');</script>";
    }
}

// Search filter by date of birth
$search_dob = "";
if (isset($_POST['search'])) {
    $search_dob = $_POST['dob'];
}

// Fetch patient data
$sql = "SELECT * FROM patients";
if (!empty($search_dob)) {
    $sql .= " WHERE date_of_birth = ?";
}
$stmt = $conn->prepare($sql);

if (!empty($search_dob)) {
    $stmt->bind_param("s", $search_dob);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #eef2f7;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #03303b;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Search Form */
.search-container {
    text-align: center;
    margin-bottom: 20px;
}

input[type="date"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
    width: 200px;
    transition: 0.3s;
}

input[type="date"]:focus {
    border-color: #03303b;
    box-shadow: 0 0 5px rgba(3, 30, 59, 0.3);
}

.search-btn {
    background-color: #03303b;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.search-btn:hover {
    background-color: #021e2a;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 14px;
    text-align: left;
    font-size: 16px;
}

th {
    background-color: #03303b;
    color: white;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f8f9fc;
}

tr:hover {
    background-color: #e9eef5;
}

/* Action Buttons */
button {
    padding: 10px 14px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    transition: 0.3s;
}

.edit-btn {
    background-color: #f4b400;
    color: black;
}

.edit-btn:hover {
    background-color: #d49a00;
}

.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #b51d2e;
}

/* No records message */
td[colspan] {
    text-align: center;
    font-weight: bold;
    padding: 15px;
    font-size: 18px;
    color: #555;
}


    </style>
</head>
<body>

<h2>Manage Patients</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <label for="dob">Search by Date of Birth: </label>
        <input type="date" name="dob" value="<?php echo $search_dob; ?>" required>
        <button type="submit" name="search" class="search-btn">Search</button>
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
        <th>Date of Appointment</th>
        <th>Actions</th>
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
                    <td>{$row['date_of_appointment']}</td>
                    <td>
                        <form method='POST' action='Patients_Update.php' style='display:inline;'>
                            <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                            <button type='submit' class='edit-btn'>Edit</button>
                        </form>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                            <button type='submit' name='delete' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='11' style='text-align: center;'>No patients found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
