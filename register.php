<!-- Programmer: Nasseima L., Sean Derrick S., Juan D.
About: MediCare is a small fictional firm we used to portray our Medical Appointment System.
Purpose: Displays the registry page.
Date Created: 11/17/2024
-->
<?php
require_once 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieves form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashes the password using a one-way hashing algorithm

    try {
        // Try to insert the data into the database
        // Uses ? placeholders for the values and inserts the user data into patients.
        $stmt = $pdo->prepare("INSERT INTO patients (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $phone, $password]);
        $message = "<div class='message success'>Registration successful! Please log in with your new account.</div>";
        header("refresh:2;url=login.php");  // Redirect to login page after 2 seconds
    } catch(PDOException $e) {
        if ($e->getCode() == '23000') {
            // Tells the user that the username is already being used and asks to retry.
            $message = "<div class='message error'>This email already exists. Please use a different email.</div>";
        } else {
            // Grabs the error message and shows that the registration was unsuccessful
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
        /* Body Styling */
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

        /* Container Styling */
        .container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: #ffffff;
            border: 1px solid #000000;
        }

        /* Header 1 Styling */
        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        
        /* Styling for input name and button group */
        .input-group {
            margin-bottom: 20px;
        }

        /* Styling for input inside input-group */
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #000000;
            font-size: 16px;
        }

        /* Style for the submit button */
        input[type="submit"] {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        /* Changes the background color of the submit
        button when hovering over it with cursor */
        input[type="submit"]:hover {
            background-color: #cc0000;
        }

        /* Styling for message */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000000;
        }

        /* Changes color for the success message */
        .message.success {
            background-color: #ffffff;
            color: #000000;
        }

        /* Changes color for the error message */
        .message.error {
            background-color: #ff0000;
            color: #ffffff;
        }

        /* Paragraph styling */
        p {
            text-align: center;
            margin-top: 20px;
        }

        /* Changes link color and removes the text decoration */
        a {
            color: #ff0000;
            text-decoration: none;
        }

        /* When hovering over link, underline it */
        a:hover {
            text-decoration: underline;
        }

        /* Navigation styling */
        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        /* Navigation button styling */
        .nav-btn {
            background-color: #000000;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
        }
        
        /* Changes color of nav button when hovered over */
        .nav-btn:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Patient Registration</h1>
        <?php echo $message; ?>
        <!-- Create form for user registration -->
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
        <!-- Sends user back to login page -->
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <!-- Navigation button of home page -->
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
        </div>
    </div>
</body>
</html>