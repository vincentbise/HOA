-- ============================================
-- Clinic Online Reservation System
-- Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS clinic_db;
USE clinic_db;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('patient', 'admin') NOT NULL DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Doctors Table
-- ============================================
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Doctor Schedules Table
-- ============================================
CREATE TABLE IF NOT EXISTS doctor_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    day_of_week ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    max_patients INT NOT NULL DEFAULT 10,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    INDEX idx_doctor_day (doctor_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Reservations Table
-- ============================================
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doctor_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending','Approved','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_doctor (doctor_id),
    INDEX idx_date (appointment_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Seed Data
-- ============================================

-- Default admin account (password: admin123)
INSERT INTO users (full_name, email, password, phone, role) VALUES
('System Administrator', 'admin@clinic.com', '$2y$10$8WxYRHMRXy0lXETMKRNBku0MBGvGvQxDLtMiKjG8mFPVbHCfOxIHa', '09171234567', 'admin');

-- Sample doctors
INSERT INTO doctors (full_name, specialization, email, phone, status) VALUES
('Dr. Maria Santos', 'General Medicine', 'maria.santos@clinic.com', '09171111111', 'active'),
('Dr. Jose Reyes', 'Pediatrics', 'jose.reyes@clinic.com', '09172222222', 'active'),
('Dr. Ana Cruz', 'Dermatology', 'ana.cruz@clinic.com', '09173333333', 'active'),
('Dr. Miguel Garcia', 'Cardiology', 'miguel.garcia@clinic.com', '09174444444', 'active'),
('Dr. Patricia Lim', 'OB-Gynecology', 'patricia.lim@clinic.com', '09175555555', 'active');

-- Sample schedules for doctors
INSERT INTO doctor_schedules (doctor_id, day_of_week, start_time, end_time, max_patients) VALUES
(1, 'Monday', '08:00:00', '12:00:00', 10),
(1, 'Monday', '13:00:00', '17:00:00', 10),
(1, 'Wednesday', '08:00:00', '12:00:00', 10),
(1, 'Friday', '08:00:00', '12:00:00', 10),
(2, 'Tuesday', '09:00:00', '12:00:00', 8),
(2, 'Thursday', '09:00:00', '12:00:00', 8),
(2, 'Saturday', '08:00:00', '12:00:00', 8),
(3, 'Monday', '13:00:00', '17:00:00', 6),
(3, 'Wednesday', '13:00:00', '17:00:00', 6),
(3, 'Friday', '13:00:00', '17:00:00', 6),
(4, 'Tuesday', '08:00:00', '12:00:00', 5),
(4, 'Thursday', '13:00:00', '17:00:00', 5),
(5, 'Monday', '08:00:00', '12:00:00', 8),
(5, 'Wednesday', '08:00:00', '12:00:00', 8),
(5, 'Friday', '08:00:00', '17:00:00', 12);
