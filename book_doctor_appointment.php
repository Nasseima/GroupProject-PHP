<?php
require_once 'db_connect.php'; // Include the database connection file
session_start(); // Start the session to manage user login state

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect to doctor list page if no doctor_id is provided in the URL
if (!isset($_GET['doctor_id'])) {
    header("Location: doctor_list.php");
    exit();
}

$doctor_id = $_GET['doctor_id']; // Get the doctor's ID from the URL
$patient_id = $_SESSION['patient_id']; // Get the patient's ID from the session
$message = ''; // Initialize the feedback message variable

// Fetch the selected doctor's details
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
$stmt->execute([$doctor_id]); // Execute the query with the doctor's ID
$doctor = $stmt->fetch(); // Fetch the doctor's details

// If the doctor doesn't exist, redirect to the doctor list page
if (!$doctor) {
    header("Location: doctor_list.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_date = $_POST['appointment_date']; // Get the appointment date from the form
    $appointment_time = $_POST['appointment_time']; // Get the appointment time from the form

    try {
        $pdo->beginTransaction(); // Start a database transaction

        // Check if the doctor has reached the maximum appointments for the day
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date]); // Execute query
        $appointment_count = $stmt->fetchColumn(); // Get the count of appointments

        $max_appointments = 10; // Define the maximum number of appointments allowed per day

        if ($appointment_count >= $max_appointments) { // If limit reached, throw an exception
            throw new Exception("This doctor is fully booked for the selected date. Please choose another date.");
        }

        // Check if the selected time slot is already booked
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date, $appointment_time]); // Execute query
        $time_slot_count = $stmt->fetchColumn(); // Get the count of appointments for the specific time slot

        if ($time_slot_count > 0) { // If the time slot is taken, throw an exception
            throw new Exception("This time slot is already booked. Please choose another time.");
        }

        // Insert a new appointment into the database
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time]); // Execute the query

        $pdo->commit(); // Commit the transaction
        $message = "Your appointment with Dr. {$doctor['last_name']} is booked successfully!"; // Success message
    } catch (Exception $e) { // Handle errors
        $pdo->rollBack(); // Rollback the transaction on failure
        $message = "Booking failed: " . $e->getMessage(); // Error message
    }
}

// Fetch the patient's full name for the welcome message
$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
$stmt->execute([$patient_id]); // Execute the query with the patient's ID
$user = $stmt->fetch(); // Fetch the patient's details
$full_name = $user['first_name'] . ' ' . $user['last_name']; // Concatenate first and last name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment with Dr. <?php echo htmlspecialchars($doctor['last_name']); ?> - MediCare</title>
    <style>
        /* General body styling */
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

        /* Page header styling */
        h1 {
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
        }

        /* Button and navigation styling */
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

        /* Button hover effects */
        input[type="submit"]:hover, .nav-btn:hover {
            background-color: #cc0000;
        }

        /* Date and time input styling */
        input[type="date"], input[type="time"] {
            border: 1px solid #000000;
            padding: 5px;
            margin-bottom: 10px;
        }

        /* Message box styling */
        .message {
            background-color: #f0f0f0;
            border: 1px solid #000000;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Doctor information box styling */
        .doctor-info {
            border: 1px solid #000000;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Page header with doctor's name -->
    <h1>Book Appointment with Dr. <?php echo htmlspecialchars($doctor['last_name']); ?></h1>
    
    <!-- Doctor information section -->
    <div class="doctor-info">
        <p><strong>Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></strong></p>
        <p><?php echo htmlspecialchars($doctor['specialization']); ?></p>
        <p><?php echo htmlspecialchars($doctor['email']); ?></p>
        <p><?php echo htmlspecialchars($doctor['phone']); ?></p>
    </div>

    <!-- Feedback message display -->
    <?php if ($message): ?>
        <div class="message">
            <strong><?php echo htmlspecialchars($message); ?></strong>
        </div>
    <?php endif; ?>

    <!-- Appointment booking form -->
    <form method="post">
        <p>
            <label for="appointment_date">Appointment Date:</label><br>
            <input type="date" id="appointment_date" name="appointment_date" required> <!-- Date input -->
        </p>
        <p>
            <label for="appointment_time">Appointment Time:</label><br>
            <input type="time" id="appointment_time" name="appointment_time" required> <!-- Time input -->
        </p>
        <p>
            <input type="submit" value="Book Appointment"> <!-- Submit button -->
        </p>
    </form>

    <!-- Navigation links -->
    <p>
        <a href="doctor_list.php" class="nav-btn">Back to Doctor List</a>
        <a href="view_appointments.php" class="nav-btn">View My Appointments</a>
    </p>
</body>
</html>
