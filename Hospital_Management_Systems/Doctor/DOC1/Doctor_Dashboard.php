<?php
session_start();
include("../../BackEnd/dbconnection.php"); // Ensure correct database connection

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to get total counts safely
function getTotalCount($conn, $table, $condition = "") {
    $query = "SELECT COUNT(*) as total FROM $table";
    if (!empty($condition)) {
        $query .= " WHERE $condition";
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        return 0; // Return 0 if query fails
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

// Fetch total counts where doctor_name = 'EM0001'
$inpatient_count = getTotalCount($conn, "patients", "patient_type = 'inpatient' AND doctor_name = 'EM0001'");
$outpatient_count = getTotalCount($conn, "patients", "patient_type = 'outpatient' AND doctor_name = 'EM0001'");
$total_patients = getTotalCount($conn, "patients", "doctor_name = 'EM0001'"); // Count all patients under EM0001

// Get today's appointment count for doctor EM0001
$today_date = date("Y-m-d");
$todays_appointments = getTotalCount($conn, "patients", "date_of_appointment = '$today_date' AND doctor_name = 'EM0001'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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

    <h1>Doctor Dashboard - EM0001</h1>
    <div class="dashboard">
        <div class="card">
            <h3>Inpatients</h3>
            <p><?php echo $inpatient_count; ?></p>
        </div>
        <div class="card">
            <h3>Outpatients</h3>
            <p><?php echo $outpatient_count; ?></p>
        </div>
        <div class="card">
            <h3>Today's Appointments</h3>
            <p><?php echo $todays_appointments; ?></p>
        </div>
        <div class="card">
            <h3>Total Patients</h3>
            <p><?php echo $total_patients; ?></p>
        </div>
    </div>

</body>
</html>
