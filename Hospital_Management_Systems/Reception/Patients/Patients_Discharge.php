<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Patient Discharge
if (isset($_POST['discharge'])) {
    $patient_id = $_POST['patient_id'];
    $current_date = date("Y-m-d");

    // Fetch assigned room for the patient
    $room_query = "SELECT room_id FROM patients WHERE patient_id = ? AND patient_type = 'Inpatient'";
    $stmt = $conn->prepare($room_query);
    if (!$stmt) {
        die("Error preparing room query: " . $conn->error);
    }

    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $room_result = $stmt->get_result();
    $room_data = $room_result->fetch_assoc();
    $stmt->close();

    // If the patient has a room assigned, update room status
    if ($room_data && !empty($room_data['room_id'])) {
        $update_room_sql = "UPDATE rooms SET occupancy_status = 'available' WHERE room_id = ?";
        $stmt = $conn->prepare($update_room_sql);
        if (!$stmt) {
            die("Error preparing room update: " . $conn->error);
        }

        $stmt->bind_param("s", $room_data['room_id']);
        if (!$stmt->execute()) {
            die("Error updating room status: " . $stmt->error);
        }
        $stmt->close();
    }

    // Update patient discharge date
    $update_patient_sql = "UPDATE patients SET date_of_discharge = ?, room_id = NULL WHERE patient_id = ? AND patient_type = 'Inpatient'";
    $stmt = $conn->prepare($update_patient_sql);
    if (!$stmt) {
        die("Error preparing patient discharge query: " . $conn->error);
    }

    $stmt->bind_param("ss", $current_date, $patient_id);
    if ($stmt->execute()) {
        echo "<script>alert('Patient discharged successfully! Room is now available.'); window.location.href='Patients_Discharge.php';</script>";
    } else {
        echo "<script>alert('Error discharging patient: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Handle Patient Undischarge
if (isset($_POST['undischarge'])) {
    $patient_id = $_POST['patient_id'];

    // Remove discharge date
    $undischarge_sql = "UPDATE patients SET date_of_discharge = NULL WHERE patient_id = ? AND patient_type = 'Inpatient'";
    $stmt = $conn->prepare($undischarge_sql);
    if (!$stmt) {
        die("Error preparing undischarge query: " . $conn->error);
    }

    $stmt->bind_param("s", $patient_id);
    if ($stmt->execute()) {
        echo "<script>alert('Patient undischarge successful!'); window.location.href='Patients_Discharge.php';</script>";
    } else {
        echo "<script>alert('Error undischarge patient: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch inpatients data
$sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, address, 
        mobile_number, doctor_name, date_of_appointment, date_of_discharge, room_id 
        FROM patients WHERE patient_type = 'Inpatient'";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing inpatient query: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Patients</title>
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

/* Buttons */
button {
    padding: 10px 14px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    transition: 0.3s;
}

.discharge-btn {
    background-color: #d9534f;
    color: white;
}

.discharge-btn:hover {
    background-color: #c9302c;
}

.undischarge-btn {
    background-color: #007bff;
    color: white;
}

.undischarge-btn:hover {
    background-color: #0056b3;
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

    button {
        font-size: 12px;
        padding: 8px 10px;
    }
}

    </style>
</head>
<body>

<h2>Discharge Patients</h2>

<table>
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Doctor</th>
        <th>Admission Date</th>
        <th>Discharge Date</th>
        <th>Room ID</th>
        <th>Actions</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['date_of_appointment']}</td>
                    <td>" . (!empty($row['date_of_discharge']) ? $row['date_of_discharge'] : 'N/A') . "</td>
                    <td>" . (!empty($row['room_id']) ? $row['room_id'] : 'N/A') . "</td>
                    <td>";

            if (empty($row['date_of_discharge'])) {
                // Show Discharge Button
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                        <button type='submit' name='discharge' class='discharge-btn' 
                            onclick='return confirm(\"Confirm discharge for patient ID: {$row['patient_id']}?\")'>
                            Discharge
                        </button>
                      </form>";
            } else {
                // Show Undischarge Button
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                        <button type='submit' name='undischarge' class='undischarge-btn' 
                            onclick='return confirm(\"Confirm undischarge for patient ID: {$row['patient_id']}?\")'>
                            Undischarge
                        </button>
                      </form>";
            }

            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='12' style='text-align: center;'>No inpatients found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
