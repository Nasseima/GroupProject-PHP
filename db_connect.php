<?php
// Database connection details
$host = 'localhost'; // Database host (usually localhost)
$port = 3306; // MySQL default port
$dbname = 'medical_appointment_system'; // The name of the database
$username = 'root'; // Database username
$password = 'password'; // Database password

try {
    // Create a new PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set the PDO error mode to exception so any errors will throw an exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // If an error occurs during connection, output the error message and stop execution
    die("Connection failed: " . $e->getMessage());
}
?>
