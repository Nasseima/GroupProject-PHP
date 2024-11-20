<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['doctor_id'])) {
    header("Location: doctor_list.php");
    exit();
}

$doctor_id = $_GET['doctor_id'];
$patient_id = $_SESSION['patient_id'];
$message = '';

$stmt = $pdo->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: doctor_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    try {
        $pdo->beginTransaction();

    
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date]);
        $appointment_count = $stmt->fetchColumn();

    
        $max_appointments = 10;

        if ($appointment_count >= $max_appointments) {
            throw new Exception("This doctor is fully booked for the selected date. Please choose another date.");
        }

        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date, $appointment_time]);
        $time_slot_count = $stmt->fetchColumn();

        if ($time_slot_count > 0) {
            throw new Exception("This time slot is already booked. Please choose another time.");
        }

        // Book the appointment
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time]);

        $pdo->commit();
        $message = "Your appointment with Dr. {$doctor['last_name']} is booked successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Booking failed: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
$stmt->execute([$patient_id]);
$user = $stmt->fetch();
$full_name = $user['first_name'] . ' ' . $user['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment with Dr. <?php echo htmlspecialchars($doctor['last_name']); ?> - MediCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            color: #000000;
        }
        h1 {
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
        }
        input[type="submit"], .nav-btn {
            background-color: #ff0000;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        input[type="submit"]:hover, .nav-btn:hover {
            background-color: #cc0000;
        }
        input[type="date"], input[type="time"] {
            border: 1px solid #000000;
            padding: 5px;
            margin-bottom: 10px;
        }
        .message {
            background-color: #f0f0f0;
            border: 1px solid #000000;
            padding: 10px;
            margin-bottom: 20px;
        }
        .doctor-info {
            border: 1px solid #000000;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Book Appointment with Dr. <?php echo htmlspecialchars($doctor['last_name']); ?></h1>
    
    <div class="doctor-info">
        <p><strong>Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></strong></p>
        <p><?php echo htmlspecialchars($doctor['specialization']); ?></p>
        <p><?php echo htmlspecialchars($doctor['email']); ?></p>
        <p><?php echo htmlspecialchars($doctor['phone']); ?></p>
    </div>

    <?php if ($message): ?>
        <div class="message">
            <strong><?php echo htmlspecialchars($message); ?></strong>
        </div>
    <?php endif; ?>

    <form method="post">
        <p>
            <label for="appointment_date">Appointment Date:</label><br>
            <input type="date" id="appointment_date" name="appointment_date" required>
        </p>
        <p>
            <label for="appointment_time">Appointment Time:</label><br>
            <input type="time" id="appointment_time" name="appointment_time" required>
        </p>
        <p>
            <input type="submit" value="Book Appointment">
        </p>
    </form>

    <p>
        <a href="doctor_list.php" class="nav-btn">Back to Doctor List</a>
        <a href="view_appointments.php" class="nav-btn">View My Appointments</a>
    </p>
</body>
</html>