<?php
session_start();
include("../BackEnd/dbconnection.php"); // Database connection

// Initialize arrays using `array()` instead of `[]`
$patient_data = array();
$tests = array();

// Handle search request
if (isset($_POST['search'])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    $query = "SELECT patient_id, patient_name, age, doctor_name FROM patients WHERE 1";

    if (!empty($patient_id)) {
        $query .= " AND patient_id = '$patient_id'";
    } elseif (!empty($dob)) {
        $query .= " AND date_of_birth = '$dob'";
    }

    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $patient_data[] = $row;
        }
    }
}

// Fetch available tests from `labs` table
$test_query = "SELECT test_id, test_name FROM labs";
$test_result = mysqli_query($conn, $test_query);

if ($test_result) {
    while ($row = mysqli_fetch_assoc($test_result)) {
        $tests[] = $row;
    }
}

// Function to generate the next `lab_pat_id` (LT001, LT002, ...)
function generateLabPatID($conn) {
    $query = "SELECT lab_pat_id FROM lab_records ORDER BY lab_pat_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $last_id = mysqli_fetch_assoc($result);
    
    $last_lab_pat_id = isset($last_id['lab_pat_id']) ? $last_id['lab_pat_id'] : 'LT000';
    $num = intval(substr($last_lab_pat_id, 2)) + 1;
    
    return "LT" . str_pad($num, 3, '0', STR_PAD_LEFT);
}

// Handle new test submission
if (isset($_POST['submit_test'])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $test_id = mysqli_real_escape_string($conn, $_POST['test_id']);
    $test_date = date("Y-m-d"); // Current date
    $test_result = "Pending"; // Default test result

    // Fetch test name from `labs` table
    $test_name_query = "SELECT test_name FROM labs WHERE test_id = '$test_id' LIMIT 1";
    $test_name_result = mysqli_query($conn, $test_name_query);
    $test_row = mysqli_fetch_assoc($test_name_result);
    $test_name = $test_row['test_name'];

    // Generate unique `lab_pat_id`
    $lab_pat_id = generateLabPatID($conn);

    // Insert test details into `lab_records` table
    $insert_lab_query = "
        INSERT INTO lab_records (lab_pat_id, test_id, test_name, test_date, test_result, patient_id) 
        VALUES ('$lab_pat_id', '$test_id', '$test_name', '$test_date', '$test_result', '$patient_id')
    ";

    // Update `patients` table with test info
    $update_patient_query = "
        UPDATE patients 
        SET test_id = '$test_id', test_date = '$test_date', test_result = '$test_result', lab_pat_id = '$lab_pat_id'
        WHERE patient_id = '$patient_id'
    ";

    if (mysqli_query($conn, $insert_lab_query) && mysqli_query($conn, $update_patient_query)) {
        echo "<script>alert('Test Assigned Successfully!'); window.location.href='New_Test.php';</script>";
    } else {
        echo "<script>alert('Error Assigning Test');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: rgb(3, 30, 59);
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: rgb(3, 30, 59);
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: rgb(2, 20, 40);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: rgb(3, 30, 59);
            color: white;
        }
    </style>
</head>
<body>

<h2>New Lab Test</h2>

<!-- Search Form -->
<div class="form-container">
    <form method="POST">
        <div class="input-group">
            <label>Enter Patient ID:</label>
            <input type="text" name="patient_id">
        </div>
        <div class="input-group">
            <label>OR Enter Date of Birth:</label>
            <input type="date" name="dob">
        </div>
        <button type="submit" name="search">Search</button>
    </form>
</div>

<!-- Display Patient Details -->
<?php if (!empty($patient_data)): ?>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Consulting Doctor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patient_data as $patient): ?>
                <tr>
                    <td><?php echo $patient['patient_id']; ?></td>
                    <td><?php echo $patient['patient_name']; ?></td>
                    <td><?php echo $patient['age']; ?></td>
                    <td><?php echo $patient['doctor_name']; ?></td>
                    <td>
                        <button onclick="openTestForm('<?php echo $patient['patient_id']; ?>')">New Test</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Hidden Test Form -->
    <div id="test-form" class="form-container" style="display:none;">
        <form method="POST">
            <input type="hidden" id="patient_id" name="patient_id">

            <div class="input-group">
                <label>Select Test:</label>
                <select name="test_id" required>
                    <option value="">-- Select Test --</option>
                    <?php foreach ($tests as $test): ?>
                        <option value="<?php echo $test['test_id']; ?>">
                            <?php echo $test['test_id'] . " - " . $test['test_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="submit_test">Submit</button>
        </form>
    </div>
<?php endif; ?>

<script>
    function openTestForm(patientId) {
        document.getElementById("patient_id").value = patientId;
        document.getElementById("test-form").style.display = "block";
    }
</script>

</body>
</html>
