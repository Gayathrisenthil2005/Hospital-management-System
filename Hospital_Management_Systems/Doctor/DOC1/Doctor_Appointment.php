<?php
session_start();
include("../../BackEnd/dbconnection.php"); // Ensure correct database connection

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$doctor_id = "EM001"; // Hardcoded doctor ID (change as needed)

// Fetch patients who have an appointment with EM001
$query = "SELECT patient_id, patient_name, age, date_of_birth, gender, mobile_number, address, 
                 patient_type, date_of_appointment, appointment_time
          FROM patients 
          WHERE doctor_name = '$doctor_id'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle appointment status updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $patient_id = $_POST['patient_id'];
    $new_status = $_POST['status'];

    // Update status in database
    $update_query = "UPDATE patients SET appointment_status = '$new_status' WHERE patient_id = '$patient_id'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Appointment status updated successfully!'); window.location.href='Doctor_Appointment.php';</script>";
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
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

        .status-btn {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        .accepted { background-color: green; color: white; }
        .cancelled { background-color: red; color: white; }
        .ended { background-color: orange; color: white; }
    </style>
</head>
<body>

    <h1>Doctor Appointments - Dr. EM001</h1>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Age</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Patient Type</th>
            <th>Date of Appointment</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['patient_id']; ?></td>
                <td><?php echo $row['patient_name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['date_of_birth']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['mobile_number']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo ucfirst($row['patient_type']); ?></td>
                <td><?php echo $row['date_of_appointment']; ?></td>
                <td><?php echo $row['appointment_time']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="patient_id" value="<?php echo $row['patient_id']; ?>">
                        <select name="status">
                            <option value="Accepted">Accepted</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Ended">Ended</option>
                        </select>
                        <button type="submit" name="update_status" class="status-btn">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>

    </table>

</body>
</html>
