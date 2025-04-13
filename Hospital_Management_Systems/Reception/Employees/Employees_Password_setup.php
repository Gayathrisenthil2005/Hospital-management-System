<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Employee Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Employee Deleted Successfully!'); window.location.href='Employees_View.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee!');</script>";
    }
    $stmt->close();
}

// Fetch employees from Reception, Doctor, and Lab departments
$sql = "SELECT employee_id, employee_name, date_of_birth, age, gender, mobile_number, date_of_joining, department FROM employees WHERE department IN ('Reception', 'Doctor', 'Lab')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
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
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        .edit-btn {
            background-color: #007bff;
        }
        .delete-btn {
            background-color: red;
        }
    </style>
</head>
<body>

<h2>View Employees (Reception, Doctor, Lab)</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Mobile Number</th>
        <th>Date of Joining</th>
        <th>Department</th>
        <th>Action</th>
    </tr>
    
    <?php 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['employee_id']) . "</td>
                <td>" . htmlspecialchars($row['employee_name']) . "</td>
                <td>" . (!empty($row['date_of_birth']) ? htmlspecialchars($row['date_of_birth']) : "N/A") . "</td>
                <td>" . (!empty($row['age']) ? htmlspecialchars($row['age']) : "N/A") . "</td>
                <td>" . (!empty($row['gender']) ? htmlspecialchars($row['gender']) : "N/A") . "</td>
                <td>" . (!empty($row['mobile_number']) ? htmlspecialchars($row['mobile_number']) : "N/A") . "</td>
                <td>" . (!empty($row['date_of_joining']) ? htmlspecialchars($row['date_of_joining']) : "N/A") . "</td>
                <td>" . (!empty($row['department']) ? htmlspecialchars($row['department']) : "N/A") . "</td>
                <td>
                    <a href='Employees_Password_setup(1).php?id=" . $row['employee_id'] . "' class='edit-btn'>Set Password</a>
                    
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='9' style='text-align:center;'>No employees found</td></tr>";
    }
    ?>
</table>

<?php
$conn->close();
?>

</body>
</html>
