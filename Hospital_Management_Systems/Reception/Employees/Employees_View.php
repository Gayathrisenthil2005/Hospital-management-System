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

// Handle Filters
$department = isset($_GET['department']) ? $_GET['department'] : "";
$search_dob = isset($_GET['dob']) ? $_GET['dob'] : "";

// Build query based on filters
$sql = "SELECT * FROM employees WHERE 1=1";
$params = array();
$types = "";

if (!empty($department)) {
    $sql .= " AND department = ?";
    array_push($params, $department);
    $types .= "s";
}

if (!empty($search_dob)) {
    $sql .= " AND date_of_birth = ?";
    array_push($params, $search_dob);
    $types .= "s";
}

$stmt = $conn->prepare($sql);

// Bind Parameters
if (!empty($params)) {
    if (count($params) == 1) {
        $stmt->bind_param($types, $params[0]);
    } elseif (count($params) == 2) {
        $stmt->bind_param($types, $params[0], $params[1]);
    }
}

$stmt->execute();
$result = $stmt->get_result();
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
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }
        select, input[type="date"], button {
            padding: 10px;
            margin: 5px;
            font-size: 16px;
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

<h2>View Employees</h2>

<div class="filter-container">
    <form method="GET">
        <label>Filter by Department:</label>
        <select name="department">
            <option value="">All</option>
            <option value="Reception" <?= ($department == "Reception") ? "selected" : "" ?>>Reception</option>
            <option value="Doctor" <?= ($department == "Doctor") ? "selected" : "" ?>>Doctor</option>
            <option value="Lab" <?= ($department == "Lab") ? "selected" : "" ?>>Lab</option>
            <option value="Admin" <?= ($department == "Admin") ? "selected" : "" ?>>Admin</option>
            <option value="Housekeeping" <?= ($department == "Housekeeping") ? "selected" : "" ?>>Housekeeping</option>
            <option value="Watchman" <?= ($department == "Watchman") ? "selected" : "" ?>>Watchman</option>
        </select>

        <label>Search by Date of Birth:</label>
        <input type="date" name="dob" value="<?= htmlspecialchars($search_dob) ?>">

        <button type="submit">Search</button>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Department</th>
        <th>Contact</th>
        <th>Email</th>
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
                <td>" . (!empty($row['department']) ? htmlspecialchars($row['department']) : "N/A") . "</td>
                <td>" . (!empty($row['mobile_number']) ? htmlspecialchars($row['mobile_number']) : "N/A") . "</td>
                <td>" . (!empty($row['email']) ? htmlspecialchars($row['email']) : "N/A") . "</td>
                <td>
                    <a href='Employees_Edit.php?id=" . $row['employee_id'] . "' class='edit-btn'>Edit</a>
                    <a href='Employees_View.php?delete_id=" . $row['employee_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='9' style='text-align:center;'>No employees found</td></tr>";
    }
    ?>
</table>

<?php
$stmt->close();
$conn->close();
?>

</body>
</html>
