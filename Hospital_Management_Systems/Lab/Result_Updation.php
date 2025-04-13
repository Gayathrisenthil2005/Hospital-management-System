<?php
session_start();
include("../BackEnd/dbconnection.php"); // Ensure correct database connection

// Fetch all test details where test_result is 'Pending' or 'Processing'
$query = "
    SELECT lr.test_id, lr.test_name, lr.patient_id, p.age, p.doctor_name, lr.test_result 
    FROM lab_records lr
    JOIN patients p ON lr.patient_id = p.patient_id
    WHERE lr.test_result IN ('Pending', 'Processing')
";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Test Results</title>
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

        .table-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }

        .btn {
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: rgb(2, 20, 40);
        }
    </style>
</head>
<body>

    <h1>Update Test Results</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Test Name</th>
                    <th>Patient ID</th>
                    <th>Age</th>
                    <th>Doctor Name</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['test_id']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['patient_id']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['test_result']; ?></td>
                        <td>
                            <a href="Update_Result.php?test_id=<?php echo $row['test_id']; ?>&patient_id=<?php echo $row['patient_id']; ?>" class="btn">Update</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
