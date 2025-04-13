<?php
session_start();
include("../../BackEnd/dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $doctor_name = $_POST['doctor_name'];
    $date_of_appointment = $_POST['date_of_appointment'];
    $appointment_time = $_POST['appointment_time'];
    $patient_type = "Outpatient"; // Default patient type

    // Calculate age from DOB
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    // Generate Patient ID
    $query = "SELECT patient_id FROM patients ORDER BY patient_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $last_id = intval(substr($row['patient_id'], 3));
        $patient_id = "PAT" . str_pad($last_id + 1, 3, "0", STR_PAD_LEFT);
    } else {
        $patient_id = "PAT001";
    }

    // Insert into Database
    $sql = "INSERT INTO patients (patient_id, patient_name, date_of_birth, age, gender, address, mobile_number, doctor_name, date_of_appointment, appointment_time, patient_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $patient_id, $patient_name, $date_of_birth, $age, $gender, $address, $mobile_number, $doctor_name, $date_of_appointment, $appointment_time, $patient_type);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Patient Added Successfully!'); window.location.href='Patients_Add.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: rgb(3, 30, 59);
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-left: 5px solid rgb(3, 30, 59);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: rgb(3, 30, 59);
            outline: none;
            box-shadow: 0px 0px 5px rgba(3, 30, 59, 0.5);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: rgb(5, 50, 90);
        }
    </style>
    <script>
        function updateTimeSlots() {
            const doctor = document.getElementById("doctor_name").value;
            const timeSlotSelect = document.getElementById("appointment_time");
            timeSlotSelect.innerHTML = "<option value=''>Select Time Slot</option>";

            let startTime, endTime, lunchStart, lunchEnd;

            if (doctor === "EM0001") { // General Doctor
                startTime = 10 * 60; // 10:00 AM
                endTime = 15 * 60; // 3:00 PM
                lunchStart = 12 * 60 + 30; // 12:30 PM
                lunchEnd = 13 * 60 + 30; // 1:30 PM
            } else if (doctor === "EM0002") { // Child Doctor
                startTime = 10 * 60; // 10:00 AM
                endTime = 12 * 60 + 30; // 12:30 PM
                lunchStart = null;
                lunchEnd = null;
            } else {
                return;
            }

            for (let minutes = startTime; minutes < endTime; minutes += 15) {
                if (lunchStart && minutes >= lunchStart && minutes < lunchEnd) continue;
                let hours = Math.floor(minutes / 60);
                let mins = minutes % 60;
                let period = hours >= 12 ? "PM" : "AM";
                hours = hours > 12 ? hours - 12 : hours;
                let formattedTime = `${hours}:${mins === 0 ? "00" : mins} ${period}`;
                let option = document.createElement("option");
                option.value = formattedTime;
                option.textContent = formattedTime;
                timeSlotSelect.appendChild(option);
            }
        }
    </script>
</head>
<body>
    <h1>Add New Patient</h1>

    <div class="form-container">
        <form method="post">
            <div class="form-group">
                <label>Full Name(Father's or Guadian's name):</label>
                <input type="text" name="patient_name" required>
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" required>
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <input type="radio" name="gender" value="Male" required> Male
                <input type="radio" name="gender" value="Female" required> Female
                <input type="radio" name="gender" value="Other" required> Other
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile_number" pattern="\d{10}" title="Enter a 10-digit mobile number" required>
            </div>

            <div class="form-group">
                <label>Doctor Type:</label>
                <select name="doctor_name" id="doctor_name" required onchange="updateTimeSlots()">
                    <option value="">Select Doctor</option>
                    <option value="EM0001">General</option>
                    <option value="EM0002">Child</option>
                </select>
            </div>
            <div class="form-group">
                    <label>Appointment Date:</label>
                    <input type="date" name="date_of_appointment" value="<?php echo date('Y-m-d'); ?>" readonly required>
            </div>
            <div class="form-group">
                <label>Appointment Time:</label>
                <select name="appointment_time" id="appointment_time" required></select>
            </div>

            <button type="submit" class="btn-submit">Add Patient</button>
        </form>
    </div>
</body>
</html>
