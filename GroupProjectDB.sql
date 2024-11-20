CREATE DATABASE medical_appointment_system;
USE medical_appointment_system;

CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL
);

CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);


CREATE INDEX idx_appointment_date ON appointments(appointment_date);

INSERT INTO doctors (first_name, last_name, specialization, email, phone) VALUES
('John', 'Smith', 'General Practice', 'john.smith@medicare.com', '(555) 123-4567'),
('Emily', 'Johnson', 'Pediatrics', 'emily.johnson@medicare.com', '(555) 234-5678'),
('Michael', 'Williams', 'Cardiology', 'michael.williams@medicare.com', '(555) 345-6789'),
('Sarah', 'Brown', 'Dermatology', 'sarah.brown@medicare.com', '(555) 456-7890'),
('David', 'Jones', 'Orthopedics', 'david.jones@medicare.com', '(555) 567-8901'),
('Jennifer', 'Garcia', 'Obstetrics and Gynecology', 'jennifer.garcia@medicare.com', '(555) 678-9012'),
('Robert', 'Miller', 'Neurology', 'robert.miller@medicare.com', '(555) 789-0123'),
('Lisa', 'Davis', 'Psychiatry', 'lisa.davis@medicare.com', '(555) 890-1234'),
('William', 'Rodriguez', 'Urology', 'william.rodriguez@medicare.com', '(555) 901-2345'),
('Maria', 'Martinez', 'Endocrinology', 'maria.martinez@medicare.com', '(555) 012-3456'),
('James', 'Anderson', 'Ophthalmology', 'james.anderson@medicare.com', '(555) 123-4567'),
('Patricia', 'Taylor', 'Allergy and Immunology', 'patricia.taylor@medicare.com', '(555) 234-5678'),
('Richard', 'Thomas', 'Gastroenterology', 'richard.thomas@medicare.com', '(555) 345-6789'),
('Elizabeth', 'Hernandez', 'Pulmonology', 'elizabeth.hernandez@medicare.com', '(555) 456-7890'),
('Charles', 'Moore', 'Rheumatology', 'charles.moore@medicare.com', '(555) 567-8901');

Select * From patients