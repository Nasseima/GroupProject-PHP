-- Create a database named "medical_appointment_system"
CREATE DATABASE medical_appointment_system;

-- Use the created database
USE medical_appointment_system;

-- Create a table for storing patient information
CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each patient
    first_name VARCHAR(50) NOT NULL,           -- Patient's first name
    last_name VARCHAR(50) NOT NULL,            -- Patient's last name
    email VARCHAR(100) UNIQUE NOT NULL,        -- Patient's email 
    phone VARCHAR(20) NOT NULL,                -- Patient's phone number
    password VARCHAR(255) NOT NULL,            -- Patient's password 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of patient record creation
);

-- Create a table for storing doctor information
CREATE TABLE doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each doctor
    first_name VARCHAR(50) NOT NULL,           -- Doctor's first name
    last_name VARCHAR(50) NOT NULL,            -- Doctor's last name
    specialization VARCHAR(100) NOT NULL,      -- Doctor's field of specialization
    email VARCHAR(100) UNIQUE NOT NULL,        -- Doctor's email 
    phone VARCHAR(20) NOT NULL                 -- Doctor's phone number
);

-- Create a table for managing appointments
CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each appointment
    patient_id INT,                                 -- ID of the patient (foreign key)
    doctor_id INT,                                  -- ID of the doctor (foreign key)
    appointment_date DATE NOT NULL,                 -- Date of the appointment
    appointment_time TIME NOT NULL,                 -- Time of the appointment
    status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled', -- Status of the appointment
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of appointment record creation
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id), -- Link to the patients table
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)     -- Link to the doctors table
);

-- Create an index to optimize queries filtering by appointment date
CREATE INDEX idx_appointment_date ON appointments(appointment_date);

-- Insert sample data into the doctors table
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

-- Select all data from the patients table (likely for verification or debugging)
SELECT * FROM patients;
