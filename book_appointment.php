<?php
require_once 'db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_SESSION['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    try {
        $pdo->beginTransaction();

        // Check if the doctor has reached the appointment limit for the day (assuming 10 appointments per day)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date]);
        $appointment_count = $stmt->fetchColumn();

        if ($appointment_count >= 10) {
            throw new Exception("This doctor is fully booked for the selected date.");
        }

        // Book the appointment
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time]);

        $pdo->commit();
        $message = "<div class='message success'>🎉 Yay! Your appointment is booked successfully!</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='message error'>😢 Oh no! Booking failed: " . $e->getMessage() . "</div>";
    }
}

// Fetch available doctors
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
    <title>Book an Appointment - MediCare</title>
    <link rel="stylesheet" href="friendly_style.css">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ff0000;
            --background-color: #ffffff;
            --text-color: #000000;
            --card-background: #f0f0f0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        form {
            background-color: var(--card-background);
            border-radius: 0;
            box-shadow: none;
            border: 1px solid #000000;
            padding: 20px;
            margin-bottom: 20px;
        }

        select, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #000000;
            border-radius: 0;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 0;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #cc0000;
        }

        .nav-btn {
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 0;
            transition: background-color 0.3s ease;
        }

        .nav-btn:hover {
            background-color: #333333;
        }

        .message.success {
            background-color: #f0f0f0;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            padding: 10px;
            margin-bottom: 10px;
        }

        .message.error {
            background-color: var(--secondary-color);
            color: white;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book an Appointment</h1>
        <p>Welcome, <?php echo htmlspecialchars($full_name); ?>! Let's schedule your appointment.</p>
        <?php echo $message; ?>
        <form method="post">
            <select name="doctor_id" required>
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo $doctor['doctor_id']; ?>">
                        Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name'] . ' (' . $doctor['specialization'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="appointment_date" required>
            <input type="time" name="appointment_time" required>
            <input type="submit" value="Book Appointment">
        </form>
        <p><a href="view_appointments.php" class="nav-btn">View My Appointments</a> | <a href="doctor_list.php" class="nav-btn">View Doctors</a> | <a href="index.php" class="nav-btn">Home</a> | <a href="logout.php" class="nav-btn">Logout</a></p>
    </div>
</body>
</html>