<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient = null;
if (isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    $sql = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $patient_type = $_POST['patient_type'];
    $doctor = $_POST['doctor'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time']; // Dropdown value

    $update_sql = "UPDATE patients SET patient_name=?, date_of_birth=?, age=?, gender=?, address=?, mobile_number=?, patient_type=?, doctor_name=?, date_of_appointment=?, appointment_time=? WHERE patient_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssissssssss", $name, $dob, $age, $gender, $address, $mobile, $patient_type, $doctor, $appointment_date, $appointment_time, $patient_id);

    if ($stmt->execute()) {
        echo "<script>alert('Patient record updated successfully!'); window.location.href='Patients_Manage.php';</script>";
    } else {
        echo "<script>alert('Error updating record!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            max-width: 5000px;
            margin: auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Update Patient Details</h2>

<?php if ($patient): ?>
<form method="POST">
    <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">

    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $patient['patient_name']; ?>" required>

    <label>Date of Birth:</label>
    <input type="date" name="dob" value="<?php echo $patient['date_of_birth']; ?>" required>

    <label>Age:</label>
    <input type="number" name="age" value="<?php echo $patient['age']; ?>" required>

    <label>Gender:</label>
    <input type="text" name="gender" value="<?php echo $patient['gender']; ?>" required>

    <label>Address:</label>
    <input type="text" name="address" value="<?php echo $patient['address']; ?>" required>

    <label>Contact No:</label>
    <input type="text" name="mobile" value="<?php echo $patient['mobile_number']; ?>" required>

    <label>Patient Type:</label>
    <select name="patient_type" required>
        <option value="Outpatient" <?php echo ($patient['patient_type'] == 'Outpatient') ? 'selected' : ''; ?>>Outpatient</option>
        <option value="Inpatient" <?php echo ($patient['patient_type'] == 'Inpatient') ? 'selected' : ''; ?>>Inpatient</option>
    </select>

    <label>Doctor:</label>
    <input type="text" name="doctor" value="<?php echo $patient['doctor_name']; ?>" required>

    <label>Date of Appointment:</label>
    <input type="date" name="appointment_date" value="<?php echo $patient['date_of_appointment']; ?>" required>

    <label>Appointment Time:</label>
    <select name="appointment_time" required>
        <option value="">Select Time Slot</option>
        <option value="09:00 AM" <?php echo ($patient['appointment_time'] == '09:00 AM') ? 'selected' : ''; ?>>09:00 AM</option>
        <option value="09:30 AM" <?php echo ($patient['appointment_time'] == '09:30 AM') ? 'selected' : ''; ?>>09:30 AM</option>
        <option value="10:00 AM" <?php echo ($patient['appointment_time'] == '10:00 AM') ? 'selected' : ''; ?>>10:00 AM</option>
        <option value="10:30 AM" <?php echo ($patient['appointment_time'] == '10:30 AM') ? 'selected' : ''; ?>>10:30 AM</option>
        <option value="11:00 AM" <?php echo ($patient['appointment_time'] == '11:00 AM') ? 'selected' : ''; ?>>11:00 AM</option>
        <option value="11:30 AM" <?php echo ($patient['appointment_time'] == '11:30 AM') ? 'selected' : ''; ?>>11:30 AM</option>
        <option value="12:00 PM" <?php echo ($patient['appointment_time'] == '12:00 PM') ? 'selected' : ''; ?>>12:00 PM</option>
        <option value="12:30 PM" <?php echo ($patient['appointment_time'] == '12:30 PM') ? 'selected' : ''; ?>>12:30 PM</option>
        <option value="01:00 PM" <?php echo ($patient['appointment_time'] == '01:00 PM') ? 'selected' : ''; ?>>01:00 PM</option>
        <option value="02:00 PM" <?php echo ($patient['appointment_time'] == '02:00 PM') ? 'selected' : ''; ?>>02:00 PM</option>
        <option value="02:30 PM" <?php echo ($patient['appointment_time'] == '02:30 PM') ? 'selected' : ''; ?>>02:30 PM</option>
        <option value="03:00 PM" <?php echo ($patient['appointment_time'] == '03:00 PM') ? 'selected' : ''; ?>>03:00 PM</option>
        <option value="03:30 PM" <?php echo ($patient['appointment_time'] == '03:30 PM') ? 'selected' : ''; ?>>03:30 PM</option>
        <option value="04:00 PM" <?php echo ($patient['appointment_time'] == '04:00 PM') ? 'selected' : ''; ?>>04:00 PM</option>
        <option value="04:30 PM" <?php echo ($patient['appointment_time'] == '04:30 PM') ? 'selected' : ''; ?>>04:30 PM</option>
        <option value="05:00 PM" <?php echo ($patient['appointment_time'] == '05:00 PM') ? 'selected' : ''; ?>>05:00 PM</option>
        <option value="05:30 PM" <?php echo ($patient['appointment_time'] == '05:30 PM') ? 'selected' : ''; ?>>05:30 PM</option>
    </select>

    <button type="submit" name="update">Update</button>
</form>
<?php else: ?>
<p style="text-align: center; color: red;">Patient not found. Please try again.</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
