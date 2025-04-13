<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if employee ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Employee ID.");
}

$employee_id = $_GET['id'];

// Fetch existing employee details
$sql = "SELECT employee_id, employee_name FROM employees WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['set_password'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // ⚠ Stores password in plain text

    // Update email and password in database
    $update_sql = "UPDATE employees SET email = ?, password = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sss", $email, $password, $employee_id);

    if ($stmt->execute()) {
        echo "<script>alert('Password Set Successfully!'); window.location.href='Employees_View.php';</script>";
    } else {
        echo "<script>alert('Error updating password!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Employee Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            margin: auto;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background-color: rgb(15, 45, 114);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-submit:hover {
            background-color: rgb(78, 78, 75);
        }
    </style>
</head>
<body>

    <h1>Set Password for <?= htmlspecialchars($employee['employee_name']) ?></h1>

    <div class="form-container">
        <form method="post">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required placeholder="Enter email">
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>

            <button type="submit" name="set_password" class="btn-submit">Set Password</button>
        </form>
    </div>

</body>
</html>
