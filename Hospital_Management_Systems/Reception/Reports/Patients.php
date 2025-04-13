<?php
// Include Database Connection
include("C:/wamp/www/Hospital_Management_Systems/BackEnd/dbconnection.php");

// Initialize variables
$search_patient_id = "";
$search_date = "";
$search_week = "";
$search_month = "";
$search_year = "";
$search_doctor_id = "";
$search_patient_type = "";
$result = null;
$showTable = false; // To control table display

// Apply Filters Only When Search is Clicked
if (isset($_POST['search'])) {
    $showTable = true; // Show table after searching

    // Base SQL Query (fetch all patients)
    $sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, address, 
                   mobile_number, doctor_name, date_of_appointment, patient_type 
            FROM patients"; 

    // Apply Filters
    $conditions = array();

    if (!empty($_POST['patient_id'])) {
        $search_patient_id = $_POST['patient_id'];
        $conditions[] = "patient_id = '$search_patient_id'";
    }
    if (!empty($_POST['date'])) {
        $search_date = $_POST['date'];
        $conditions[] = "date_of_appointment = '$search_date'";
    }
    if (!empty($_POST['week'])) {
        $search_week = $_POST['week'];
        $conditions[] = "WEEK(date_of_appointment) = WEEK('$search_week')";
    }
    if (!empty($_POST['month'])) {
        $search_month = $_POST['month'];
        $conditions[] = "MONTH(date_of_appointment) = MONTH('$search_month')";
    }
    if (!empty($_POST['year'])) {
        $search_year = $_POST['year'];
        $conditions[] = "YEAR(date_of_appointment) = '$search_year'";
    }
    if (!empty($_POST['doctor_id'])) {
        $search_doctor_id = $_POST['doctor_id'];
        $conditions[] = "doctor_name = '$search_doctor_id'";
    }
    if (!empty($_POST['patient_type'])) {
        $search_patient_type = $_POST['patient_type'];
        $conditions[] = "patient_type = '$search_patient_type'";
    }

    // Append conditions to SQL Query
    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Patients Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .search-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            margin: 5px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .report-btn, .full-report-btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .full-report-btn {
            display: block;
            width: 200px;
            margin: 10px auto;
            text-align: center;
        }
        .report-btn:hover, .full-report-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>All Patients Report</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <label for="patient_id">Search by Patient ID:</label>
        <input type="text" name="patient_id" id="patient_id" placeholder="Enter Patient ID" value="<?php echo $search_patient_id; ?>"><br>

        <label for="date">Search by Date:</label>
        <input type="date" name="date" id="date" value="<?php echo $search_date; ?>"><br>

        <label for="week">Search by Week:</label>
        <input type="date" name="week" id="week" value="<?php echo $search_week; ?>"><br>

        <label for="month">Search by Month:</label>
        <input type="month" name="month" id="month" value="<?php echo $search_month; ?>"><br>

        <label for="year">Search by Year:</label>
        <input type="number" name="year" id="year" placeholder="Enter Year" value="<?php echo $search_year; ?>"><br>

        <label for="doctor_id">Search by Doctor ID:</label>
        <input type="text" name="doctor_id" id="doctor_id" placeholder="Enter Doctor ID" value="<?php echo $search_doctor_id; ?>"><br>

        <label for="patient_type">Search by Patient Type:</label>
        <select name="patient_type" id="patient_type">
            <option value="">-- Select Type --</option>
            <option value="Inpatient" <?php if ($search_patient_type == "Inpatient") echo "selected"; ?>>Inpatient</option>
            <option value="Outpatient" <?php if ($search_patient_type == "Outpatient") echo "selected"; ?>>Outpatient</option>
        </select><br>

        <button type="submit" name="search">Search</button>
    </form>
</div>

<?php if ($showTable && $result && $result->num_rows > 0): ?>
    <form method="POST" action="Generate_AllPatients_Report.php" target="_blank">
        <button type="submit" name="generate_report" class="full-report-btn">Generate Full Report</button>
    </form>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Age</th>
            <th>Doctor</th>
            <th>Appointment Date</th>
            <th>Patient Type</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['patient_id']; ?></td>
                <td><?php echo $row['patient_name']; ?></td>
                <td><?php echo $row['date_of_birth']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['doctor_name']; ?></td>
                <td><?php echo $row['date_of_appointment']; ?></td>
                <td><?php echo $row['patient_type']; ?></td>
                <td>
                    <a href="Generate_AllPatients_Report.php?patient_id=<?php echo $row['patient_id']; ?>" target="_blank" class="report-btn">Generate Report</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
