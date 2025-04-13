<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch patients with an appointment date and time
$sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, 
               address, mobile_number, patient_type, doctor_name AS doctor_id, 
               date_of_appointment, appointment_time
        FROM patients
        WHERE date_of_appointment IS NOT NULL"; 

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Appointments</title>
    <style>
        /* General Page Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #eef2f7;
    margin: 0;
    padding: 20px;
}

/* Title */
h2 {
    text-align: center;
    color: #03303b;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 14px;
    text-align: left;
    font-size: 16px;
}

th {
    background-color: #03303b;
    color: white;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f8f9fc;
}

tr:hover {
    background-color: #e9eef5;
}

/* Appointment Time Styling */
.past-appointment {
    color: #999;
    font-style: italic;
}

/* No records message */
td[colspan] {
    text-align: center;
    font-weight: bold;
    padding: 15px;
    font-size: 18px;
    color: #555;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    th, td {
        font-size: 14px;
        padding: 10px;
    }
}

    </style>
</head>
<body>

<h2>Patient Appointments</h2>

<table>
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Patient Type</th>
        <th>Doctor ID</th>
        <th>Appointment Date</th>
        <th>Appointment Time</th>
    </tr>

    <?php
    date_default_timezone_set('Asia/Kolkata'); // Set your timezone
    $current_datetime = date('Y-m-d H:i:s'); // Get current date and time

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointment_datetime = $row['date_of_appointment'] . ' ' . $row['appointment_time'];

            // Check if the appointment date and time have passed
            if (strtotime($appointment_datetime) < strtotime($current_datetime)) {
                $status = "Ended";
            } else {
                $status = $row['appointment_time'];
            }

            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['patient_type']}</td>
                    <td>{$row['doctor_id']}</td>
                    <td>{$row['date_of_appointment']}</td>
                    <td>{$status}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='11' style='text-align: center;'>No appointments found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
