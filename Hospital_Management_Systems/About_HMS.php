<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About HMS</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Background with White Overlay */
        body {
            background: url('Images/img6.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
            position: relative;
        }

        /* White shadow overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3); /* Semi-transparent white */
            z-index: 1;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: black;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links a {
            color: black;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            padding: 5px 10px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            border-radius: 5px;
            color: blue;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* About Section */
        .about-section {
            max-width: 900px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .about-section h1 {
            color: white;
            margin-bottom: 20px;
        }

        .about-section p, .about-section ul {
            font-size: 18px;
            line-height: 1.6;
            text-align: justify;
        }

        .about-section ul {
            margin: 20px 0;
            padding-left: 20px;
        }

        .about-section ul li {
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.9);
            color: black;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            position: relative;
            z-index: 2;
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
    </style>
</head>
<body>

    <!-- Navbar -->
    
    <div class="navbar">
    <div class="logo-container">
            <img src="Images/img5.jpg" alt="Hospital Logo"> 
            <span class="logo-text">HOSPITAL MANAGEMENT SYSTEMS</span>
        </div>
        <div class="nav-links">
            <a href="Index_Page.php">Home</a>
            <a href="About_HMS.php">About</a>
        </div>
    </div>

    <!-- About Section -->
    <div class="about-section">
        <h1>About Hospital Management System</h1>
        <p>
            The <strong>Hospital Management System (HMS)</strong> is a web-based application designed to 
            streamline hospital operations by integrating various modules such as patient management, employee records, 
            billing, inventory, and laboratory management.
        </p>

        <h2>Key Features:</h2>
        <ul>
            <li><strong>Patient Management:</strong> Organizes patient details, appointments, and medical history.</li>
            <li><strong>Doctor & Employee Management:</strong> Stores records of doctors, receptionists, and lab staff.</li>
            <li><strong>Lab Management:</strong> Tracks patient tests and results efficiently.</li>
            <li><strong>Inventory Management:</strong> Keeps records of hospital's room availability.</li>
            <li><strong>Secure Access:</strong> Different logins for Admin, Doctors, Receptionists, and Lab Staff.</li>
        </ul>

        <p>
            Our system ensures a smooth and efficient workflow, allowing hospitals to provide better care while maintaining 
            accurate and secure data records. 
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Hospital Management System. Done by Us.</p>
    </div>
    
</body>
</html>
