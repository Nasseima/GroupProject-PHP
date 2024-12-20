<?php
require_once 'db_connect.php'; // Include database connection file
session_start(); // Start the session to manage user login state

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit(); // Stop further execution
}

$message = ''; // Initialize message variable for feedback to the user

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if the form is submitted
    $patient_id = $_SESSION['patient_id']; // Get the logged-in patient's ID from the session
    $doctor_id = $_POST['doctor_id']; // Get selected doctor's ID from the form
    $appointment_date = $_POST['appointment_date']; // Get appointment date from the form
    $appointment_time = $_POST['appointment_time']; // Get appointment time from the form

    try {
        $pdo->beginTransaction(); // Start a transaction

        // Check if the doctor has reached their appointment limit for the day
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $appointment_date]); // Execute query with parameters
        $appointment_count = $stmt->fetchColumn(); // Get the count of appointments

        if ($appointment_count >= 10) { // If the limit is reached, throw an exception
            throw new Exception("This doctor is fully booked for the selected date.");
        }

        // Insert a new appointment into the database
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time]); // Execute query with parameters

        $pdo->commit(); // Commit the transaction
        $message = "<div class='message success'>🎉 Yay! Your appointment is booked successfully!</div>"; // Success message
    } catch (Exception $e) { // Handle any exceptions
        $pdo->rollBack(); // Rollback the transaction in case of an error
        $message = "<div class='message error'>😢 Oh no! Booking failed: " . $e->getMessage() . "</div>"; // Error message
    }
}

// Fetch all available doctors for the dropdown menu
$stmt = $pdo->query("SELECT * FROM doctors ORDER BY last_name, first_name");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all doctors as an associative array

// Fetch the logged-in user's full name
$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
$stmt->execute([$_SESSION['patient_id']]); // Execute query with the patient's ID
$user = $stmt->fetch(); // Fetch the user's details
$full_name = $user['first_name'] . ' ' . $user['last_name']; // Concatenate first and last name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - MediCare</title>
    <link rel="stylesheet" href="friendly_style.css"> <!-- Link to external stylesheet -->
    <style>
        /* Define CSS variables for theme colors */
        :root {
            --primary-color: #000000;
            --secondary-color: #ff0000;
            --background-color: #ffffff;
            --text-color: #000000;
            --card-background: #f0f0f0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        /* Style for the form */
        form {
            background-color: var(--card-background);
            border-radius: 0;
            box-shadow: none;
            border: 1px solid #000000;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Style for input fields and select dropdown */
        select, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #000000;
            border-radius: 0;
            font-size: 16px;
        }

        /* Style for the submit button */
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

        /* Change submit button color on hover */
        input[type="submit"]:hover {
            background-color: #cc0000;
        }

        /* Style for navigation buttons */
        .nav-btn {
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 0;
            transition: background-color 0.3s ease;
        }

        /* Change navigation button color on hover */
        .nav-btn:hover {
            background-color: #333333;
        }

        /* Success message styling */
        .message.success {
            background-color: #f0f0f0;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            padding: 10px;
            margin-bottom: 10px;
        }

        /* Error message styling */
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
        <!-- Display welcome message -->
        <p>Welcome, <?php echo htmlspecialchars($full_name); ?>! Let's schedule your appointment.</p>
        <!-- Display feedback message -->
        <?php echo $message; ?>
        <!-- Appointment booking form -->
        <form method="post">
            <select name="doctor_id" required>
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor): ?> <!-- Loop through doctors to populate dropdown -->
                    <option value="<?php echo $doctor['doctor_id']; ?>">
                        Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name'] . ' (' . $doctor['specialization'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="appointment_date" required> <!-- Date input -->
            <input type="time" name="appointment_time" required> <!-- Time input -->
            <input type="submit" value="Book Appointment"> <!-- Submit button -->
        </form>
        <!-- Navigation links -->
        <p><a href="view_appointments.php" class="nav-btn">View My Appointments</a> | <a href="doctor_list.php" class="nav-btn">View Doctors</a> | <a href="index.php" class="nav-btn">Home</a> | <a href="logout.php" class="nav-btn">Logout</a></p>
    </div>
</body>
</html>
