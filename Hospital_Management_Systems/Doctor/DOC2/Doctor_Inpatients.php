<?php
session_start();
include("../../BackEnd/dbconnection.php"); // Ensure correct database connection

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$doctor_id = "EM0002"; // Doctor ID is set to EM0002 (the doctor you are filtering for)

// Fetch inpatients who are consulting with doctor EM0002
$query = "SELECT patient_id, patient_name, age, date_of_birth, gender, mobile_number, address, 
                 patient_type, doctor_name, date_of_appointment, room_id, room_no, room_type, 
                 appointment_time
          FROM patients
          WHERE doctor_name = '$doctor_id' AND patient_type = 'inpatient'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inpatients Consulting Dr. EM0002</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <h1>Inpatients Consulting Dr. EM0002</h1>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Age</th>
            <th>Date of Birth</th>
            <th>Room ID</th>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Date of Appointment</th>
            <th>Appointment Time</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['patient_id']; ?></td>
                <td><?php echo $row['patient_name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['date_of_birth']; ?></td>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['room_no']; ?></td>
                <td><?php echo $row['room_type']; ?></td>
                <td><?php echo $row['date_of_appointment']; ?></td>
                <td><?php echo $row['appointment_time']; ?></td>
            </tr>
        <?php } ?>

    </table>

</body>
</html>
