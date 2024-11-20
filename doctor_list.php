<?php
session_start();
require_once 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all doctors from the database
$stmt = $pdo->query("SELECT * FROM doctors ORDER BY last_name, first_name");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's name
$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
$stmt->execute([$_SESSION['patient_id']]);
$user = $stmt->fetch();
$full_name = $user['first_name'] . ' ' . $user['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Doctors - MediCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .welcome-message {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .doctor-card {
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 20px;
        }

        .doctor-name {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .doctor-info {
            margin-bottom: 10px;
        }

        .book-btn {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .book-btn:hover {
            background-color: #cc0000;
        }

        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .nav-btn {
            background-color: #000000;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        .nav-btn:hover {
            background-color: #333333;
        }

        @media (max-width: 768px) {
            .doctor-grid {
                grid-template-columns: 1fr;
            }

            .navigation {
                flex-direction: column;
                align-items: center;
            }

            .nav-btn {
                margin: 10px 0;
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Doctors</h1>
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($full_name); ?>! Here's our team of expert doctors:</p>
        
        <div class="doctor-grid">
            <?php foreach ($doctors as $doctor): ?>
                <div class="doctor-card">
                    <div class="doctor-name">Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></div>
                    <div class="doctor-info">
                        <?php echo htmlspecialchars($doctor['specialization']); ?>
                    </div>
                    <div class="doctor-info">
                        <?php echo htmlspecialchars($doctor['email']); ?>
                    </div>
                    <div class="doctor-info">
                        <?php echo htmlspecialchars($doctor['phone']); ?>
                    </div>
                    <a href="book_doctor_appointment.php?doctor_id=<?php echo $doctor['doctor_id']; ?>" class="book-btn">
                        Book Appointment
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="navigation">
            <a href="book_appointment.php" class="nav-btn">Book New Appointment</a>
            <a href="view_appointments.php" class="nav-btn">View My Appointments</a>
            <a href="index.php" class="nav-btn">Home</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</body>
</html>