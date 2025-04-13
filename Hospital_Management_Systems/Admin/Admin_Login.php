<?php
session_start();
require_once("../BackEnd/dbconnection.php"); // Ensure database connection

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Fix: Use `department = 'Admin'` instead of `role = 'Admin'`
    $query = "SELECT employee_id, employee_name, password FROM employees WHERE email = ? AND department = 'Admin'";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // ✅ Case-insensitive password check
        if (strtolower($password) === strtolower($admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['employee_id'];
            $_SESSION['admin_name'] = $admin['employee_name'];

            // ✅ Redirect to Admin Dashboard
            header("Location: http://localhost/Hospital_Management_Systems/Admin/Admin_Main.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Hospital Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body {
            background: url('../Images/img2.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); z-index: 0;
        }
        .login-container {
            position: relative; z-index: 1; background: rgba(255, 255, 255, 0.53);
            padding: 30px; border-radius: 10px; width: 380px; text-align: center;
            backdrop-filter: blur(10px); box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.99);
            color: black;
        }
        h2 { margin-bottom: 20px; font-size: 24px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: rgb(2, 12, 22); }
        .form-group { margin-bottom: 15px; }
        input {
            width: 100%; padding: 12px; margin-top: 5px; border: none; border-radius: 5px;
            font-size: 16px; color: #333;
        }
        input:focus { outline: none; box-shadow: 0 0 5px #28a745; }
        .btn {
            background: rgb(244, 248, 245); color: black; padding: 12px; width: 100%;
            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;
            font-weight: bold; text-transform: uppercase;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }
        .btn:hover { background: rgb(18, 19, 18); transform: scale(1.05); color: white; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <form method="post">
        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn" name="login">Login</button>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </form>
</div>

</body>
</html>
