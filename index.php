<?php
session_start();

// Include the database connection file
require_once 'db_connect.php';

// Check if the connection was successful
if (!$pdo) {
    die("Database connection failed. Please check your configuration.");
}

$logged_in = isset($_SESSION['patient_id']);

if ($logged_in) {
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
    $stmt->execute([$_SESSION['patient_id']]);
    $user = $stmt->fetch();
    $full_name = $user['first_name'] . ' ' . $user['last_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MediCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            border: 1px solid #000000;
            padding: 20px;
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background-color: #000000;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            margin-top: 10px;
        }

        .logout-btn {
            display: block;
            width: 100px;
            margin: 20px auto 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($logged_in): ?>
            <h1>Welcome back, <?php echo htmlspecialchars($full_name); ?>!</h1>
            <p class="welcome-message">What would you like to do today?</p>
            <div class="card-container">
                <div class="card">
                    <div class="card-title">Book an Appointment</div>
                    <p>Schedule a new appointment with one of our doctors.</p>
                    <a href="book_appointment.php" class="btn">Book Now</a>
                </div>
                <div class="card">
                    <div class="card-title">View My Appointments</div>
                    <p>Check your upcoming and past appointments.</p>
                    <a href="view_appointments.php" class="btn">View Appointments</a>
                </div>
                <div class="card">
                    <div class="card-title">Our Doctors</div>
                    <p>Learn more about our team of healthcare professionals.</p>
                    <a href="doctor_list.php" class="btn">Meet Our Doctors</a>
                </div>
            </div>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        <?php else: ?>
            <h1>Welcome to MediCare</h1>
            <p class="welcome-message">Your trusted healthcare partner. Join us for quality medical care.</p>
            <div class="card-container">
                <div class="card">
                    <div class="card-title">Login</div>
                    <p>Access your account to manage appointments and more.</p>
                    <a href="login.php" class="btn">Login</a>
                </div>
                <div class="card">
                    <div class="card-title">Register</div>
                    <p>New to MediCare? Create an account to get started.</p>
                    <a href="register.php" class="btn">Register</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>