<!-- Programmer: Nasseima L., Sean Derrick S., Juan D.
About: MediCare is a small fictional firm we used to portray our Medical Appointment System.
Purpose: Displays the Logout Page. Resets the session data and tells the user that
they have safely logged out.
Date Created: 11/17/2024
-->

<?php
// Basically resets the session data.
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - MediCare</title>
    <style>
        /* Body Style for Logout Page */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        /* Logout Message Style */
        .logout-message {
            text-align: center;
            border: 1px solid #000000;
            padding: 20px;
            max-width: 300px;
        }

        /* Logout Message Header Style */
        .logout-message h1 {
            color: #ff0000;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Successful Logout Message -->
    <div class="logout-message">
        <h1>Logging Out</h1>
        <p>You have been successfully logged out.</p>
        <p>Redirecting to home page...</p>
    </div>
    <!-- After logout message is said, sends the user to the home page -->
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>
</body>
</html>