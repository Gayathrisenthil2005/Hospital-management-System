<?php
session_start();
include("../BackEnd/dbconnection.php"); // Ensure correct database connection

// Function to generate the next `test_id` (TST001, TST002, ...)
function generateTestID($conn) {
    $query = "SELECT test_id FROM labs ORDER BY test_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $last_id = mysqli_fetch_assoc($result);

    $last_test_id = isset($last_id['test_id']) ? $last_id['test_id'] : 'TST000';
    $num = intval(substr($last_test_id, 3)) + 1;
    
    return "TST" . str_pad($num, 3, '0', STR_PAD_LEFT);
}

// Handle form submission for adding a new test
if (isset($_POST['add_test'])) {
    $test_name = mysqli_real_escape_string($conn, $_POST['test_name']);
    
    if (!empty($test_name)) {
        $test_id = generateTestID($conn);

        // Insert the new test into the `labs` table
        $insert_query = "INSERT INTO labs (test_id, test_name) VALUES ('$test_id', '$test_name')";
        
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('New Test Added Successfully!'); window.location.href='Add_Test.php';</script>";
        } else {
            echo "<script>alert('Error Adding Test');</script>";
        }
    } else {
        echo "<script>alert('Please enter a test name');</script>";
    }
}

// Fetch all existing tests from `labs` table
$fetch_tests_query = "SELECT * FROM labs ORDER BY test_id ASC";
$tests_result = mysqli_query($conn, $fetch_tests_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Test</title>
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
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input, button {
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
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
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

        .btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <h1>Add New Test</h1>

    <div class="form-container">
        <form method="POST">
            <div class="input-group">
                <label>Enter Test Name:</label>
                <input type="text" name="test_name" required>
            </div>
            <button type="submit" name="add_test">Add Test</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Existing Tests</h2>
        <table>
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Test Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($tests_result)): ?>
                    <tr>
                        <td><?php echo $row['test_id']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td>
                            <a href="Delete_Test.php?test_id=<?php echo $row['test_id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this test?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
