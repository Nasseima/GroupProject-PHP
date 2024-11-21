<?php
session_start(); // Start the session to maintain user state
require_once 'db_connect.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch all doctors from the database, ordered by last name and first name
$stmt = $pdo->query("SELECT * FROM doctors ORDER BY last_name, first_name");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC); // Retrieve all doctors as an associative array

// Fetch the logged-in user's full name
$stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
$stmt->execute([$_SESSION['patient_id']]); // Execute query with the patient's ID from the session
$user = $stmt->fetch(); // Fetch the patient's details
$full_name = $user['first_name'] . ' ' . $user['last_name']; // Concatenate first and last name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Doctors - MediCare</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Center container and add padding */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Title styling */
        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        /* Welcome message styling */
        .welcome-message {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        /* Doctor grid layout */
        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Flexible grid */
            gap: 20px; /* Space between grid items */
        }

        /* Individual doctor card styling */
        .doctor-card {
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 20px;
        }

        /* Doctor's name styling */
        .doctor-name {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* General doctor information styling */
        .doctor-info {
            margin-bottom: 10px;
        }

        /* Book appointment button styling */
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

        /* Hover effect for book appointment button */
        .book-btn:hover {
            background-color: #cc0000;
        }

        /* Navigation bar styling */
        .navigation {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        /* Navigation buttons styling */
        .nav-btn {
            background-color: #000000;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        /* Hover effect for navigation buttons */
        .nav-btn:hover {
            background-color: #333333;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .doctor-grid {
                grid-template-columns: 1fr; /* Stack items on smaller screens */
            }

            .navigation {
                flex-direction: column; /* Stack navigation buttons vertically */
                align-items: center;
            }

            .nav-btn {
                margin: 10px 0; /* Add spacing for vertical buttons */
                width: 200px; /* Set fixed width for better alignment */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page header -->
        <h1>Our Doctors</h1>
        
        <!-- Welcome message for the logged-in user -->
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($full_name); ?>! Here's our team of expert doctors:</p>
        
        <!-- Grid of doctors -->
        <div class="doctor-grid">
            <?php foreach ($doctors as $doctor): ?>
                <div class="doctor-card">
                    <!-- Display doctor details -->
                    <div class="doctor-name">Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></div>
                    <div class="doctor-info"><?php echo htmlspecialchars($doctor['specialization']); ?></div>
                    <div class="doctor-info"><?php echo htmlspecialchars($doctor['email']); ?></div>
                    <div class="doctor-info"><?php echo htmlspecialchars($doctor['phone']); ?></div>
                    
                    <!-- Link to book an appointment -->
                    <a href="book_doctor_appointment.php?doctor_id=<?php echo $doctor['doctor_id']; ?>" class="book-btn">
                        Book Appointment
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Navigation buttons -->
        <div class="navigation">
            <a href="book_appointment.php" class="nav-btn">Book New Appointment</a>
            <a href="view_appointments.php" class="nav-btn">View My Appointments</a>
            <a href="index.php" class="nav-btn">Home</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</body>
</html>
