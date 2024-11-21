<!-- Programmer: Nasseima L., Sean Derrick S., Juan D.
About: MediCare is a small fictional firm we used to portray our Medical Appointment System.
Purpose: Displays the registry page.
Date Created: 11/17/2024
-->
<?php
require_once 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO patients (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $phone, $password]);
        $message = "<div class='message success'>Registration successful! Please log in with your new account.</div>";
        header("refresh:2;url=login.php");  // Redirect to login page after 2 seconds
    } catch(PDOException $e) {
        if ($e->getCode() == '23000') {
            $message = "<div class='message error'>This email already exists. Please use a different email.</div>";
        } else {
            $message = "<div class='message error'>Registration failed: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - MediCare</title>
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
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: #ffffff;
            border: 1px solid #000000;
        }

        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #000000;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #cc0000;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000000;
        }

        .message.success {
            background-color: #ffffff;
            color: #000000;
        }

        .message.error {
            background-color: #ff0000;
            color: #ffffff;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #ff0000;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .nav-btn {
            background-color: #000000;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
        }

        .nav-btn:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Patient Registration</h1>
        <?php echo $message; ?>
        <form method="post">
            <div class="input-group">
                <input type="text" name="first_name" placeholder="First Name" required>
            </div>
            <div class="input-group">
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="tel" name="phone" placeholder="Phone" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
        </div>
    </div>
</body>
</html>