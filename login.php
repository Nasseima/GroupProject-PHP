<!-- Programmer: Nasseima L., Sean Derrick S., Juan D.
About: MediCare is a small fictional firm we used to portray our Medical Appointment System.
Purpose: Displays the Login Page. Asks for email and password. If the user doesn't have a login
then has a link to create one.
Date Created: 11/16/2024
-->
<?php
require_once 'db_connect.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT patient_id, password FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['patient_id'] = $user['patient_id'];
        header("Location: book_appointment.php");
        exit();
    } else {
        $message = "<div class='message error'>Invalid email or password. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to MediCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #000000;
            font-size: 1rem;
        }

        button[type="submit"] {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #cc0000;
        }

        .message {
            padding: 0.75rem;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }

        .message.error {
            background-color: #ff0000;
            color: white;
        }

        .links {
            text-align: center;
            margin-top: 1rem;
        }

        .links a {
            color: #ff0000;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .nav-btn {
            background-color: #000000;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
        }

        .nav-btn:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login to MediCare</h1>
        <?php echo $message; ?>
        <form method="post">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
        </div>
    </div>
</body>
</html>