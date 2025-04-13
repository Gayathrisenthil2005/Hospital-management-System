<?php
session_start();
include("../BackEnd/dbconnection.php"); // Ensure correct database connection

// Function to get total counts safely
function getTotalCount($conn, $table, $condition = "") {
    $query = "SELECT COUNT(*) as total FROM $table";
    if ($condition !== "") {
        $query .= " WHERE $condition";
    }
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return 0; // If table doesn't exist, return 0
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

// Fetch total counts from the database
$total_test_types = getTotalCount($conn, "labs"); // Count all test types

// Count tests that are **completed** (i.e., test_result is NOT 'Pending' or 'Processing')
$completed_tests_today = getTotalCount($conn, "lab_records", "test_date = CURDATE() AND test_result NOT IN ('Pending', 'Processing')");

// Count tests that are **waiting for results** (i.e., test_result is 'Pending' or 'Processing')
$waiting_tests_today = getTotalCount($conn, "lab_records", "test_date = CURDATE() AND test_result IN ('Pending', 'Processing')");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Dashboard</title>
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
            font-size: 28px;
            margin-bottom: 20px;
        }

        .dashboard {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 10px;
        }

        .card {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            width: 220px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
            border-left: 5px solid rgb(3, 30, 59);
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 18px;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
            color: rgb(3, 30, 59);
        }
    </style>
</head>
<body>

    <h1>Lab Dashboard</h1>
    <div class="dashboard">
        <div class="card">
            <h3>Total Test Types</h3>
            <p><?php echo $total_test_types; ?></p>
        </div>
        <div class="card">
            <h3>Total Completed Tests</h3>
            <p><?php echo $completed_tests_today; ?></p>
        </div>
        <div class="card">
            <h3>Total Tests Waiting for Results</h3>
            <p><?php echo $waiting_tests_today; ?></p>
        </div>
    </div>

</body>
</html>
