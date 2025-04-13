<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1, h3 {
            color: #333;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-container div {
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-container input, .search-container select {
            width: 200px;
            margin: 5px;
            padding: 5px;
        }

        .search-container button {
            width: auto;
        }
    </style>
</head>
<body>

    <h1>Add New Patient</h1>

    <!-- SEARCH FORM -->
    <div class="search-container">
        <h3>Search Patient</h3>
        <input type="text" id="search_id" placeholder="Patient ID">
        <input type="text" id="search_name" placeholder="Patient Name">
        <input type="date" id="search_dob">
        <select id="search_doctor">
            <option value="">Select Doctor Type</option>
            <option value="EM0001">General</option>
            <option value="EM0002">Child</option>
        </select>
        <button onclick="searchPatient()">Search</button>
    </div>

    <div class="form-container">
        <form method="post">
            <input type="hidden" name="patient_id" id="patient_id">

            <div>
                <label>Full Name:</label>
                <input type="text" name="patient_name" id="patient_name" required>
            </div>

            <div>
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" id="date_of_birth" required>
            </div>

            <div>
                <label>Gender:</label>
                <input type="radio" name="gender" value="Male" required> Male
                <input type="radio" name="gender" value="Female" required> Female
                <input type="radio" name="gender" value="Other" required> Other
            </div>

            <div>
                <label>Address:</label>
                <textarea name="address" id="address" required></textarea>
            </div>

            <div>
                <label>Mobile Number:</label>
                <input type="text" name="mobile_number" id="mobile_number" pattern="\d{10}" required>
            </div>

            <div>
                <label>Doctor Type:</label>
                <select name="doctor_name" id="doctor_name" required>
                    <option value="">Select Doctor</option>
                    <option value="EM0001">General</option>
                    <option value="EM0002">Child</option>
                </select>
            </div>

            <div>
                <label>Appointment Date:</label>
                <input type="date" name="date_of_appointment" id="date_of_appointment" required>
            </div>

            <div>
                <label>Appointment Time:</label>
                <select name="appointment_time" id="appointment_time" required></select>
            </div>

            <button type="submit" name="submit_patient">Add Patient</button>
        </form>
    </div>

    <script>
        function searchPatient() {
            var id = $("#search_id").val();
            var name = $("#search_name").val();
            var dob = $("#search_dob").val();
            var doctor = $("#search_doctor").val();

            $.ajax({
                url: "search_patient.php",
                type: "POST",
                data: { patient_id: id, patient_name: name, date_of_birth: dob, doctor_name: doctor },
                success: function(response) {
                    var patient = JSON.parse(response);
                    if (patient) {
                        $("#patient_id").val(patient.patient_id);
                        $("#patient_name").val(patient.patient_name);
                        $("#date_of_birth").val(patient.date_of_birth);
                        $("input[name=gender][value='" + patient.gender + "']").prop("checked", true);
                        $("#address").val(patient.address);
                        $("#mobile_number").val(patient.mobile_number);
                        $("#doctor_name").val(patient.doctor_name);
                        $("#date_of_appointment").val(patient.date_of_appointment);
                        $("#appointment_time").html("<option value='" + patient.appointment_time + "' selected>" + patient.appointment_time + "</option>");
                    } else {
                        alert("Patient not found!");
                    }
                }
            });
        }
    </script>

</body>
</html>
