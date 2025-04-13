<?php
session_start();
include("../../BackEnd/dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_name = $_POST['employee_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $mobile_number = $_POST['mobile_number'];
    $department = $_POST['department'];
    $date_of_joining = $_POST['date_of_joining'];

    // Calculate age from DOB
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    // Generate Employee ID in format EM001, EM002...
    $query = "SELECT employee_id FROM employees ORDER BY employee_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $last_id = intval(substr($row['employee_id'], 2)); // Remove 'EM' and get the number
            $employee_id = "EM" . str_pad($last_id + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $employee_id = "EM001";
        }
    } else {
        die("Error fetching last employee ID: " . mysqli_error($conn));
    }

    // Ensure column names match your database table
    $sql = "INSERT INTO employees (employee_id, employee_name, date_of_birth, age, gender, mobile_number, department, date_of_joining) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $employee_id, $employee_name, $date_of_birth, $age, $gender, $mobile_number, $department, $date_of_joining);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Employee Added Successfully!'); window.location.href='Employees_Add.php';</script>";
        } else {
            echo "Error executing statement: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
      body {
    font-family: 'Arial', sans-serif;
    background-color: #eef5f9;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #003366;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Form Container */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    border-left: 6px solid #003366;
    transition: 0.3s;
}

.form-container:hover {
    transform: scale(1.01);
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    font-size: 16px;
    color: #333;
}

.form-group input, 
.form-group select, 
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    transition: 0.3s;
}

.form-group input:focus, 
.form-group select:focus, 
.form-group textarea:focus {
    border-color: #0056b3;
    box-shadow: 0px 0px 6px rgba(0, 86, 179, 0.3);
    outline: none;
}

/* Gender Radio Buttons */
.gender-group {
    display: flex;
    gap: 15px;
    align-items: center;
}

.gender-group input {
    accent-color: #003366;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: 14px;
    background-color: #003366;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    border-radius: 6px;
    transition: background 0.3s, transform 0.2s;
}

.btn-submit:hover {
    background-color: #002244;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 480px) {
    .form-container {
        padding: 20px;
    }
    
    .btn-submit {
        font-size: 16px;
        padding: 12px;
    }
}

    </style>
</head>
<body>

  

    <div class="form-container">
        <form method="post">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="employee_name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" required>
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <input type="radio" name="gender" value="Male" required> Male
                <input type="radio" name="gender" value="Female" required> Female
                <input type="radio" name="gender" value="Other" required> Other
            </div>

            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile_number" pattern="\d{10}" title="Enter a 10-digit mobile number" placeholder="Enter mobile number" required>
            </div>

            <div class="form-group">
                <label>Department:</label>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="Reception">Reception</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Lab">Laboratory</option>
                    <option value="Admin">Administration</option>
                    <option value="Housekeeping">Housekeeping</option>
                    <option value="Watchman">Watchman</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date of Joining:</label>
                <input type="date" name="date_of_joining" required>
            </div>

            <button type="submit" class="btn-submit">Add Employee</button>
        </form>
    </div>

</body>
</html>
