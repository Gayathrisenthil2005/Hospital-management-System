<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            background: linear-gradient(to right, #ffffff, #f8f9fa);
            margin: 0;
            padding: 10px;
        }

        .menu-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .menu-btn {
            background-color: #03203B;
            color: white;
            border: none;
            padding: 12px;
            margin: 5px 0;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-size: 16px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .menu-btn i {
            margin-right: 10px;
        }

        .menu-btn:hover {
            background-color: #0056b3;
        }

        .dropdown {
            display: none;
            flex-direction: column;
            margin-left: 20px;
            transition: all 0.3s ease-in-out;
        }

        .dropdown button {
            background-color: #6c757d;
            font-size: 14px;
            padding: 10px;
            border-left: 3px solid #ffffff;
            transition: 0.3s;
        }

        .dropdown button:hover {
            background-color: #5a6268;
            border-left: 3px solid #ffcc00;
        }

        .active {
            background-color: #ffcc00 !important;
            color: black !important;
        }
    </style>
</head>
<body>

<div class="menu-container">

    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Reception_Dashboard.php', this)">
        <i class="fa fa-home"></i> Dashboard
    </button>

    <button class="menu-btn" onclick="toggleDropdown('patientsDropdown')">
        <i class="fa fa-user-injured"></i> Patients
    </button>
    <div class="dropdown" id="patientsDropdown">
        <button class="menu-btn" onclick="toggleDropdown('patientsAddDropdown')">Add</button>
        <div class="dropdown" id="patientsAddDropdown">
            <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Patients/Patients_Add.php', this)">New</button>
        </div>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Patients/Patients_Manage.php', this)">View & Manage</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Patients/Patients_Transfer.php', this)">Transfer</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Patients/Patients_Discharge.php', this)">Discharge</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Patients/Patients_Appointments.php', this)">Appointment Details</button>
    </div>

    <button class="menu-btn" onclick="toggleDropdown('employeesDropdown')">
        <i class="fa fa-user-md"></i> Employees
    </button>
    <div class="dropdown" id="employeesDropdown">
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Employees/Employees_Add.php', this)">Add</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Employees/Employees_Password_setup.php', this)">Password Setup</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Employees/Employees_View.php', this)">View</button>
    </div>

    <button class="menu-btn" onclick="toggleDropdown('roomsDropdown')">
        <i class="fa fa-bed"></i> Rooms
    </button>
    <div class="dropdown" id="roomsDropdown">
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Rooms/Rooms_Inpatients.php', this)">Book Rooms</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Rooms/Rooms_View.php', this)">View Rooms</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Rooms/Manage_Rooms.php', this)">Manage</button>
    </div>

    <button class="menu-btn" onclick="toggleDropdown('reportingDropdown')">
        <i class="fa fa-chart-bar"></i> Reporting
    </button>
    <div class="dropdown" id="reportingDropdown">
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Reports/Patients.php', this)">Patients</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Reports/Rooms.php', this)">Rooms</button>
        <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Reception/Reports/Lab_Reports.php', this)">Lab Tests</button> <!-- Added Lab Reports -->
    </div>

</div>

<script>
    function loadPage(page, btn) {
        window.parent.frames['contentFrame'].location.href = page;

        // Remove active class from all buttons
        document.querySelectorAll('.menu-btn').forEach(button => {
            button.classList.remove('active');
        });

        // Add active class to clicked button
        btn.classList.add('active');
    }

    function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        if (dropdown.style.display === "flex") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "flex";
        }
    }
</script>

</body>
</html>
