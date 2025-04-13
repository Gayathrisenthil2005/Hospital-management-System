<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("../../BackEnd/dbconnection.php"); // Adjust path if needed

// Doctor ID (EM0001) - Fetch only outpatients under this doctor
$doctor_id = "EM0002";

// Query to fetch outpatient details
$query = "
    SELECT patient_id, patient_name, age, date_of_birth, gender, mobile_number, address, 
           doctor_name, date_of_appointment, patients_condition, appointment_time
    FROM patients
    WHERE patient_type = 'outpatient' AND doctor_name = '$doctor_id'
";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Reports - Outpatients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: rgb(3, 30, 59);
            font-size: 24px;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1000px;
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
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .no-data {
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Doctor Reports - Outpatients (Dr. Maya)</h1>

    <div class="container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Doctor Name</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_appointment']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No outpatients found for Dr. Maya.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
