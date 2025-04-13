<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employee details based on ID
$employee = null;
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
}

// Handle Employee Update
if (isset($_POST['update'])) {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['employee_name'];
    $dob = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile_number'];
    $department = $_POST['department'];
    $date_of_joining = $_POST['date_of_joining'];
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    // Determine if email & password should be updated (only for non-Housekeeping & non-Watchman)
    if ($department !== "Housekeeping" && $department !== "Watchman") {
        $email = $_POST['email'];

        // Hash password only if it's updated
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE employees SET employee_name=?, date_of_birth=?, age=?, gender=?, mobile_number=?, department=?, date_of_joining=?, email=?, password=? WHERE employee_id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssisssssss", $name, $dob, $age, $gender, $mobile, $department, $date_of_joining, $email, $password, $employee_id);
        } else {
            $update_sql = "UPDATE employees SET employee_name=?, date_of_birth=?, age=?, gender=?, mobile_number=?, department=?, date_of_joining=?, email=? WHERE employee_id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssissssss", $name, $dob, $age, $gender, $mobile, $department, $date_of_joining, $email, $employee_id);
        }
    } else {
        // Update only fields relevant to Housekeeping & Watchman
        $update_sql = "UPDATE employees SET employee_name=?, date_of_birth=?, age=?, gender=?, mobile_number=?, department=?, date_of_joining=? WHERE employee_id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssisssss", $name, $dob, $age, $gender, $mobile, $department, $date_of_joining, $employee_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Employee record updated successfully!'); window.location.href='Employees_View.php';</script>";
    } else {
        echo "<script>alert('Error updating record!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Edit Employee Details</h2>

<?php if ($employee): ?>
<form method="POST">
    <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">

    <label>Name:</label>
    <input type="text" name="employee_name" value="<?php echo $employee['employee_name']; ?>" required>

    <label>Date of Birth:</label>
    <input type="date" name="date_of_birth" value="<?php echo $employee['date_of_birth']; ?>" required>

    <label>Age:</label>
    <input type="number" name="age" value="<?php echo $employee['age']; ?>" required>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="Male" <?php echo ($employee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($employee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo ($employee['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <label>Mobile Number:</label>
    <input type="text" name="mobile_number" value="<?php echo $employee['mobile_number']; ?>" required>

    <label>Department:</label>
    <select name="department" required>
        <option value="Reception" <?php echo ($employee['department'] == 'Reception') ? 'selected' : ''; ?>>Reception</option>
        <option value="Doctor" <?php echo ($employee['department'] == 'Doctor') ? 'selected' : ''; ?>>Doctor</option>
        <option value="Lab" <?php echo ($employee['department'] == 'Lab') ? 'selected' : ''; ?>>Lab</option>
        <option value="Admin" <?php echo ($employee['department'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="Housekeeping" <?php echo ($employee['department'] == 'Housekeeping') ? 'selected' : ''; ?>>Housekeeping</option>
        <option value="Watchman" <?php echo ($employee['department'] == 'Watchman') ? 'selected' : ''; ?>>Watchman</option>
    </select>

    <label>Date of Joining:</label>
    <input type="date" name="date_of_joining" value="<?php echo $employee['date_of_joining']; ?>" required>

    <?php if ($employee['department'] !== 'Housekeeping' && $employee['department'] !== 'Watchman'): ?>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required>

        <label>New Password (Leave blank to keep current password):</label>
        <input type="password" name="password">
    <?php endif; ?>

    <button type="submit" name="update">Update</button>
</form>
<?php else: ?>
<p style="text-align: center; color: red;">Employee not found. Please try again.</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
