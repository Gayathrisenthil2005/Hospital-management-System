<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('Images/img3.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
        }

        /* Header Styling */
        .header {
            background: rgb(250, 250, 250);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Align items closer */
            width: 100%;
            z-index: 1000;
        }

        /* Logo and Title */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px; /* Adjust gap to bring items closer */
        }

        .logo-container img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            background-color: black;
            padding: 5px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: black;
            white-space: nowrap;
        }

        /* Navigation */
        .nav {
            display: flex;
            gap: 15px; /* Space between menu items */
            margin-left: 20px; /* Reduce space between text and menu */
        }

        .nav a {
            color: black;
            text-decoration: none;
            font-size: 18px;
            padding: 5px 10px;
        }

        .nav a:hover, .dropdown:hover .dropbtn {
            border-radius: 5px;
            color:blue;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            }

        /* Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background:rgb(251, 255, 252);
            color:blue;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="logo-container">
            <img src="Images/img5.jpg" alt="Hospital Logo"> 
            <span class="logo-text">HOSPITAL MANAGEMENT SYSTEMS</span>
        </div><pre>                                                                       </pre>
        <div class="nav">
            <a href="Index_Page.php">Home</a>
            <div class="dropdown">
                <a class="dropbtn" class="dropdown">Logins ▼</a>
                <div class="dropdown-content">
                    <a href="Doctor\Doctor_Login.php">Doctor Login</a>
                    <a href="Reception\Receptionist_Login.php">Reception Login</a>
                    <a href="Lab\Lab_Login.php">Lab Login</a>
                    <a href="Admin\Admin_Login.php">Admin Login</a>
                </div>
            </div>
            <a href="About_HMS.php">About</a>
        </div>
    </div>
    <script>
        function showAlert() {
            alert("Welcome to the Hospital Management System!");
        }
    </script>

</body>
</html>
