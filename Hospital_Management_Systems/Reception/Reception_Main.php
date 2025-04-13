<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Main</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d3cce3, #e9e4f0);
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
            transition: 0.3s;
        }

        /* Left Frame (Menu) */
        .left-frame {
            width: 25%;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(15px);
            border-right: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: width 0.3s ease-in-out;
        }

        /* Right Frame (Content Display) */
        .right-frame {
            width: 75%;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            transition: width 0.3s ease-in-out;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 50, 0.5);
            border-radius: 10px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .left-frame {
                width: 100%;
                height: 30vh;
                border-right: none;
                border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            }
            .right-frame {
                width: 100%;
                height: 70vh;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left-frame">
        <iframe src="Reception_Menu.php" name="menuFrame"></iframe>
    </div>
    <div class="right-frame">
        <iframe src="" name="contentFrame"></iframe>
    </div>
</div>

</body>
</html>
