<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Inpatient_Report.doc");

// Include Database Connection
include("C:/wamp/www/Hospital_Management_Systems/BackEnd/dbconnection.php");

// Get patient ID from the request
$patient_id = $_POST['patient_id'];

// Fetch patient details
$sql = "SELECT * FROM patients WHERE patient_id = '$patient_id'";
$result = $conn->query($sql);

// Check if patient exists
if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
} else {
    echo "<p>No patient found with the given ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inpatient Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Inpatient Report</h2>

<table>
    <tr>
        <th>Field</th>
        <th>Details</th>
    </tr>
    <?php foreach ($patient as $key => $value) { ?>
        <tr>
            <td><strong><?php echo ucfirst(str_replace("_", " ", $key)); ?></strong></td>
            <td><?php echo $value; ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
