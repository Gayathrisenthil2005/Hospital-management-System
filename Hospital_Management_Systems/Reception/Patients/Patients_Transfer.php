<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

// Connect to database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a unique transaction ID
function generateTransactionID($conn) {
    $sql = "SELECT transaction_id FROM patients WHERE transaction_id IS NOT NULL ORDER BY transaction_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['transaction_id'];
        $num = intval(substr($last_id, 4)) + 1;
        return "TRAN" . str_pad($num, 3, "0", STR_PAD_LEFT);
    } else {
        return "TRAN001";
    }
}

// Handle patient transfer
if (isset($_POST['transfer'])) {
    $patient_id = $_POST['patient_id'];

    $check_sql = "SELECT patient_type, transaction_id FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($check_sql);

    if ($stmt) {
        $stmt->bind_param("s", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $current_type = $row['patient_type'];
            $existing_transaction_id = $row['transaction_id'];

            if (strcasecmp($current_type, "Outpatient") == 0) {
                // Change to Inpatient and generate a new transaction ID
                $new_type = "Inpatient";
                $transaction_id = generateTransactionID($conn);
            } else {
                // Change to Outpatient but KEEP the existing transaction ID
                $new_type = "Outpatient";
                $transaction_id = $existing_transaction_id;
            }

            // Update patient type and transaction ID
            $update_sql = "UPDATE patients SET patient_type = ?, transaction_id = ? WHERE patient_id = ?";
            $stmt = $conn->prepare($update_sql);

            if ($stmt) {
                $stmt->bind_param("sss", $new_type, $transaction_id, $patient_id);
                if ($stmt->execute()) {
                    echo "<script>alert('Patient transferred successfully! Now marked as: $new_type'); 
                    window.location.href='Patients_Transfer.php';</script>";
                } else {
                    echo "<script>alert('Error transferring patient!');</script>";
                }
                $stmt->close();
            }
        }
    }
}

// Search filter by Date of Birth
$search_dob = "";
$sql = "SELECT patient_id, patient_name, date_of_birth, age, gender, address, 
        mobile_number, patient_type, doctor_name, transaction_id FROM patients";

if (isset($_POST['search'])) {
    $search_dob = $_POST['dob'];

    if (!empty($search_dob)) {
        $sql .= " WHERE date_of_birth = ?";
    }
}

// Execute query properly
if (!empty($search_dob)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_dob);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query($sql);
}

// Ensure $result is a valid object
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Patients</title>
    <style>
       /* General Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #eef2f3;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #03303b;
    font-size: 28px;
    margin-bottom: 20px;
}

/* Container for better alignment */
.container {
    max-width: 95%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Search Form Styling */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
}

input[type="date"] {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.search-btn {
    background-color: #03303b;
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.search-btn:hover {
    background-color: #055087;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: #03303b;
    color: white;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Transfer Button Styling */
.transfer-btn {
    background-color: #28a745;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.transfer-btn:hover {
    background-color: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    th, td {
        font-size: 13px;
        padding: 10px;
    }

    .search-container {
        flex-direction: column;
        gap: 5px;
    }

    .search-btn {
        width: 100%;
        padding: 10px;
    }
}

</style>

    </style>
</head>
<body>

<h2>Transfer Patients</h2>

<!-- Search Form -->
<div class="search-container">
    <form method="POST">
        <label for="dob">Search by Date of Birth: </label>
        <input type="date" name="dob" value="<?php echo $search_dob; ?>">
        <button type="submit" name="search" class="search-btn">Search</button>
    </form>
</div>

<table>
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Patient Type</th>
        <th>Doctor</th>
        <th>Transaction ID</th>
        <th>Actions</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['patient_type']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>" . (!empty($row['transaction_id']) ? $row['transaction_id'] : 'N/A') . "</td>
                    <td>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                            <button type='submit' name='transfer' class='transfer-btn' 
                                onclick='return confirm(\"Confirm transfer to " . ($row['patient_type'] == "Outpatient" ? "Inpatient" : "Outpatient") . "?\")'>
                                Transfer
                            </button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='11' style='text-align: center;'>No patients found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
