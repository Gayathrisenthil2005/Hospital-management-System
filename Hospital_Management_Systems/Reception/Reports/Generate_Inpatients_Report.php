<?php
// Include Database Connection
include("C:/wamp/www/Hospital_Management_Systems/BackEnd/dbconnection.php");

// Check if patient_id is passed
if (!isset($_GET['patient_id'])) {
    die("Patient ID is required.");
}

$patient_id = $_GET['patient_id'];

// Fetch patient details
$sql = "SELECT p.patient_id, p.patient_name, p.date_of_birth, p.age, p.gender, p.address, 
               p.mobile_number, p.doctor_name, p.transaction_id, p.date_of_appointment,
               r.room_id, r.room_type, r.room_number, r.status_id, r.booking_date, r.occupancy_status
        FROM patients p
        LEFT JOIN rooms r ON p.room_id = r.room_id 
        WHERE p.patient_id = '$patient_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("No details found for this patient.");
}

$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inpatient Report - <?php echo $patient['patient_name']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        .report-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            text-align: left;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgb(3, 30, 59);
            color: white;
            text-align: left;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
        }
        .pdf-btn {
            background-color: #28a745;
            color: white;
        }
        .word-btn {
            background-color: #ffc107;
            color: black;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Inpatient Report</h2>
    <table>
        <tr><th>Patient ID</th><td><?php echo $patient['patient_id']; ?></td></tr>
        <tr><th>Name</th><td><?php echo $patient['patient_name']; ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo $patient['date_of_birth']; ?></td></tr>
        <tr><th>Age</th><td><?php echo $patient['age']; ?></td></tr>
        <tr><th>Gender</th><td><?php echo $patient['gender']; ?></td></tr>
        <tr><th>Address</th><td><?php echo $patient['address']; ?></td></tr>
        <tr><th>Contact No</th><td><?php echo $patient['mobile_number']; ?></td></tr>
        <tr><th>Doctor</th><td><?php echo $patient['doctor_name']; ?></td></tr>
        <tr><th>Transaction ID</th><td><?php echo $patient['transaction_id']; ?></td></tr>
        <tr><th>Admission Date</th><td><?php echo $patient['date_of_appointment']; ?></td></tr>
        <tr><th>Room ID</th><td><?php echo $patient['room_id']; ?></td></tr>
        <tr><th>Room Type</th><td><?php echo $patient['room_type']; ?></td></tr>
        <tr><th>Room Number</th><td><?php echo $patient['room_number']; ?></td></tr>
        <tr><th>Booking Date</th><td><?php echo $patient['booking_date']; ?></td></tr>
        <tr><th>Occupancy Status</th><td><?php echo $patient['occupancy_status']; ?></td></tr>
    </table>

    <div class="button-container">
        <button class="print-btn" onclick="window.print()">Print</button>
        <form method="POST" action="Generate_Inpatients_Word.php" style="display: inline;">
            <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">
            <button type="submit" class="word-btn">Download as Word</button>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
