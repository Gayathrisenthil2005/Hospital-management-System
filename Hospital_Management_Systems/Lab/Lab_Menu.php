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

        .toggle-menu {
            background-color: #03203B;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            text-align: center;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .toggle-menu:hover {
            background-color: #0056b3;
        }

        .active {
            background-color: #ffcc00 !important;
            color: black !important;
        }

    </style>
</head>
<body>

<div class="menu-container">


    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Lab/Lab_Dashboard.php', this)">
        <i class="fa fa-home"></i> Dashboard
    </button>
    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Lab/New_Test.php', this)">
    <i class="fas fa-vial"></i> New Test
    </button>
    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Lab/Result_Updation.php', this)">
    <i class="fas fa-flask"></i> Result Updation
    </button>
    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Lab/Add_Test.php', this)">
    <i class="fas fa-file-medical"></i>Add New Test
    </button>
    <button class="menu-btn" onclick="loadPage('http://localhost/Hospital_Management_Systems/Lab/Lab_Reports.php', this)">
    <i class="fa fa-chart-bar"></i> Reporting
    </button>
    
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

    function toggleSidebar() {
        var menu = document.querySelector('.menu-container');
        if (menu.style.display === "none") {
            menu.style.display = "flex";
        } else {
            menu.style.display = "none";
        }
    }
</script>

</body>
</html>
