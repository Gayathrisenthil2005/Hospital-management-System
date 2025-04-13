<?php
session_start();
require_once("../BackEnd/dbconnection.php"); // Ensure database connection

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query to check if the lab technician exists (case-insensitive email)
    $query = "SELECT employee_id, employee_name, password FROM employees WHERE LOWER(email) = LOWER(?) AND department = 'Lab'";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $lab_technician = $result->fetch_assoc();

            // Verify password (case-sensitive)
            if ($password === $lab_technician['password']) {
                // Regenerate session ID for security
                session_regenerate_id(true);

                $_SESSION['lab_id'] = $lab_technician['employee_id'];
                $_SESSION['lab_name'] = $lab_technician['employee_name'];

                // Redirect to Lab_Main.php
                header("Location: http://localhost/Hospital_Management_Systems/Lab/Lab_Main.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Technician Login | Hospital Management System</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: url('../Images/img7.jpg') no-repeat center center fixed;
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.53);
            padding: 30px;
            border-radius: 10px;
            width: 380px;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.99);
            color: black;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgb(2, 12, 22);
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 5px #28a745;
        }

        .btn {
            background: rgb(244, 248, 245);
            color: black;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }

        .btn:hover {
            background: rgb(18, 19, 18);
            transform: scale(1.05);
            color: white;
        }

        /* Error Message */
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Lab Technician Login</h2>

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
