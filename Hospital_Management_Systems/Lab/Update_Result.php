<?php
session_start();
include("../BackEnd/dbconnection.php"); // Ensure correct database connection

// Check if test_id and patient_id are provided
if (!isset($_GET['test_id']) || !isset($_GET['patient_id'])) {
    echo "<script>alert('Invalid Request'); window.location.href='Result_Updation.php';</script>";
    exit();
}

$test_id = mysqli_real_escape_string($conn, $_GET['test_id']);
$patient_id = mysqli_real_escape_string($conn, $_GET['patient_id']);

// Fetch test details
$query = "
    SELECT lr.test_id, lr.test_name, lr.patient_id, p.patient_name, p.age, p.doctor_name, lr.test_result 
    FROM lab_records lr
    JOIN patients p ON lr.patient_id = p.patient_id
    WHERE lr.test_id = '$test_id' AND lr.patient_id = '$patient_id'
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$test = mysqli_fetch_assoc($result);

if (!$test) {
    echo "<script>alert('Test not found'); window.location.href='Result_Updation.php';</script>";
    exit();
}

// Handle form submission
if (isset($_POST['update_result'])) {
    $new_result = mysqli_real_escape_string($conn, $_POST['test_result']);

    // Update test result in `lab_records` table
    $update_lab_query = "
        UPDATE lab_records 
        SET test_result = '$new_result'
        WHERE test_id = '$test_id' AND patient_id = '$patient_id'
    ";

    // Update test result in `patients` table
    $update_patient_query = "
        UPDATE patients 
        SET test_result = '$new_result'
        WHERE patient_id = '$patient_id'
    ";

    if (mysqli_query($conn, $update_lab_query) && mysqli_query($conn, $update_patient_query)) {
        echo "<script>alert('Test Result Updated Successfully!'); window.location.href='Result_Updation.php';</script>";
    } else {
        echo "<script>alert('Error Updating Result');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Test Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: rgb(3, 30, 59);
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: rgb(2, 20, 40);
        }
    </style>
</head>
<body>

    <h1>Update Test Result</h1>

    <div class="form-container">
        <form method="POST">
            <div class="input-group">
                <label>Test ID:</label>
                <input type="text" value="<?php echo $test['test_id']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Test Name:</label>
                <input type="text" value="<?php echo $test['test_name']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Patient ID:</label>
                <input type="text" value="<?php echo $test['patient_id']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Patient Name:</label>
                <input type="text" value="<?php echo $test['patient_name']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Age:</label>
                <input type="text" value="<?php echo $test['age']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Doctor Name:</label>
                <input type="text" value="<?php echo $test['doctor_name']; ?>" disabled>
            </div>

            <div class="input-group">
                <label>Update Test Result:</label>
                <select name="test_result" required>
                    <option value="">-- Select Result --</option>
                    <option value="Normal">Normal</option>
                    <option value="Abnormal">Abnormal</option>
                    <option value="Positive">Positive</option>
                    <option value="Negative">Negative</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <button type="submit" name="update_result">Update Result</button>
        </form>
    </div>

</body>
</html>
