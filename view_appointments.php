<!-- Programmer: Nasseima L., Sean Derrick S., Juan D.
About: MediCare is a small fictional firm we used to portray our Medical Appointment System.
Purpose: Shows list of the appointments that the patient has made.
Date Created: 11/17/2024
-->
<?php
require_once 'db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$message = '';

// Handle appointment cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE appointment_id = ? AND patient_id = ?");
        $stmt->execute([$appointment_id, $patient_id]);

        $pdo->commit();
        $message = "<div class='message success'>Your appointment was cancelled successfully.</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='message error'>Cancellation failed: " . $e->getMessage() . "</div>";
    }
}

// Fetch patient's appointments
$stmt = $pdo->prepare("
    SELECT a.*, d.first_name AS doctor_first_name, d.last_name AS doctor_last_name, d.specialization
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.patient_id = ? AND a.status != 'cancelled'
    ORDER BY a.appointment_date, a.appointment_time
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's name
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
    <title>My Appointments - MediCare</title>
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

        .message {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #000000;
        }

        .message.success {
            border-color: #ff0000;
            color: #ff0000;
        }

        .message.error {
            background-color: #ff0000;
            color: #ffffff;
        }

        .appointment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .appointment-card {
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 20px;
        }

        .appointment-date {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .appointment-time {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .doctor-info {
            margin-bottom: 10px;
        }

        .cancel-btn {
            background-color: #ff0000;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #cc0000;
        }

        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .nav-btn {
            background-color: #000000;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        .nav-btn:hover {
            background-color: #333333;
        }

        .no-appointments {
            text-align: center;
            font-size: 1.2em;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .appointment-grid {
                grid-template-columns: 1fr;
            }

            .navigation {
                flex-direction: column;
                align-items: center;
            }

            .nav-btn {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Appointments</h1>
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($full_name); ?>! Here are your upcoming appointments:</p>
        <?php echo $message; ?>
        <?php if (empty($appointments)): ?>
            <p class="no-appointments">You have no upcoming appointments. Time for a check-up?</p>
        <?php else: ?>
            <div class="appointment-grid">
                <?php foreach ($appointments as $appointment): ?>
                    <div class="appointment-card">
                        <div class="appointment-date">
                            <?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?>
                        </div>
                        <div class="appointment-time">
                            <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                        </div>
                        <div class="doctor-info">
                            Dr. <?php echo htmlspecialchars($appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']); ?>
                        </div>
                        <div class="doctor-info">
                            <?php echo htmlspecialchars($appointment['specialization']); ?>
                        </div>
                        <form method="post" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                            <button type="submit" name="cancel_appointment" class="cancel-btn">
                                Cancel
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="navigation">
            <a href="book_appointment.php" class="nav-btn">Book New Appointment</a>
            <a href="doctor_list.php" class="nav-btn">View Doctors</a>
            <a href="index.php" class="nav-btn">Home</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</body>
</html>