<?php
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
        .logout-message {
            text-align: center;
            border: 1px solid #000000;
            padding: 20px;
            max-width: 300px;
        }
        .logout-message h1 {
            color: #ff0000;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="logout-message">
        <h1>Logging Out</h1>
        <p>You have been successfully logged out.</p>
        <p>Redirecting to home page...</p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>
</body>
</html>