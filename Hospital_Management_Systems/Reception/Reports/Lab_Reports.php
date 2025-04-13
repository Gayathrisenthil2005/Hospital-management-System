<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Corrected path to database connection file
include("../../BackEnd/dbconnection.php"); // Moves 2 levels up to reach BackEnd

// Initialize search filters
$patient_id = $dob = $test_type = $test_date = $test_week = $test_month = "";
$results = null;

// Fetch test types from `labs` table for dropdown
$test_query = "SELECT DISTINCT test_name FROM labs ORDER BY test_name ASC";
$test_result = mysqli_query($conn, $test_query);

// Base SQL Query (Only executes after search)
$query = "
    SELECT lr.test_id, lr.test_name, lr.test_date, lr.test_result, 
           p.patient_id, p.patient_name, p.age, p.date_of_birth, p.doctor_name
    FROM lab_records lr
    JOIN patients p ON lr.patient_id = p.patient_id
    WHERE 1
";

// Handle search request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['patient_id'])) {
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
        $query .= " AND p.patient_id = '$patient_id'";
    }

    if (!empty($_POST['dob'])) {
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $query .= " AND p.date_of_birth = '$dob'";
    }

    if (!empty($_POST['test_type'])) {
        $test_type = mysqli_real_escape_string($conn, $_POST['test_type']);
        $query .= " AND lr.test_name = '$test_type'";
    }

    if (!empty($_POST['test_date'])) {
        $test_date = mysqli_real_escape_string($conn, $_POST['test_date']);
        $query .= " AND lr.test_date = '$test_date'";
    }

    if (!empty($_POST['test_week'])) {
        $test_week = mysqli_real_escape_string($conn, $_POST['test_week']);
        $query .= " AND WEEK(lr.test_date) = WEEK('$test_week')";
    }

    if (!empty($_POST['test_month'])) {
        $test_month = mysqli_real_escape_string($conn, $_POST['test_month']);
        $query .= " AND MONTH(lr.test_date) = MONTH('$test_month')";
    }

    // Execute query only if filters are applied
    $results = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reports</title>
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
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .input-group {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
        }

        select, input, button {
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

        .table-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

    <h1>Search Lab Reports</h1>

    <div class="form-container">
        <form method="POST">
            <div class="input-group">
                <label>Patient ID:</label>
                <input type="text" name="patient_id">
            </div>

            <div class="input-group">
                <label>Date of Birth:</label>
                <input type="date" name="dob">
            </div>

            <div class="input-group">
                <label>Test Type:</label>
                <select name="test_type">
                    <option value="">Select Test</option>
                    <?php while ($row = mysqli_fetch_assoc($test_result)) { ?>
                        <option value="<?php echo $row['test_name']; ?>">
                            <?php echo $row['test_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <label>Test Date:</label>
                <input type="date" name="test_date">
            </div>

            <div class="input-group">
                <label>Test Week:</label>
                <input type="date" name="test_week">
            </div>

            <div class="input-group">
                <label>Test Month:</label>
                <input type="month" name="test_month">
            </div>

            <button type="submit">Search</button>
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $results && mysqli_num_rows($results) > 0): ?>
        <div class="table-container" style="display:block;">
            <h2>Lab Reports</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test ID</th>
                        <th>Test Name</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Date of Birth</th>
                        <th>Doctor Name</th>
                        <th>Test Date</th>
                        <th>Test Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($results)): ?>
                        <tr>
                            <td><?php echo $row['test_id']; ?></td>
                            <td><?php echo $row['test_name']; ?></td>
                            <td><?php echo $row['patient_id']; ?></td>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['date_of_birth']; ?></td>
                            <td><?php echo $row['doctor_name']; ?></td>
                            <td><?php echo $row['test_date']; ?></td>
                            <td><?php echo $row['test_result']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No results found for your search criteria.</p>
    <?php endif; ?>

</body>
</html>
<?php mysqli_close($conn); ?>
