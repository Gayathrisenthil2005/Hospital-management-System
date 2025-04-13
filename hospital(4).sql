-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2025 at 01:12 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` varchar(15) NOT NULL,
  `employee_name` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_name`, `date_of_birth`, `age`, `gender`, `mobile_number`, `date_of_joining`, `department`, `email`, `password`) VALUES
('EM0001', 'Haresh', '1990-06-14', 35, 'Male', '9870456321', '2019-03-05', 'Doctor', 'Haresh@gmail.com', 'Haresh1100'),
('EM0002', 'Maya', '1993-01-12', 32, 'Female', '9012365478', '2021-03-03', 'Doctor', 'Maya@gmail.com', 'Maya1100'),
('EM003', 'Zoya', '2000-01-15', 25, 'Female', '9236541781', '2022-03-09', 'Reception', 'Zoya@gmail.com', 'Zoya@1100'),
('EM004', 'Vimala', '1980-03-13', 44, 'Female', '9874563212', '2025-03-10', 'Admin', 'Vimala@gmail.com', 'Vimala@1100'),
('EM005', 'Ramasamy', '1971-05-26', 53, 'Male', '9874512632', '2025-03-10', 'Admin', 'Ramasamy@gmail.com', 'Ramasamy@1100'),
('EM006', 'Rethanyaa', '1993-04-22', 31, 'Female', '9874456321', '2025-03-06', 'Lab', 'rethanyaa@gmail.com', 'rethanyaa@1100');

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE IF NOT EXISTS `labs` (
  `test_id` varchar(20) NOT NULL,
  `test_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`test_id`, `test_name`) VALUES
('TST001', 'Complete Blood Count (CBC)'),
('TST002', 'Blood Glucose Test'),
('TST003', 'Liver Function Test (LFT)'),
('TST004', 'Hemoglobin Test'),
('TST005', 'Allergy Test (IgE Test)'),
('TST006', 'Thyroid Function Test (TFT)');

-- --------------------------------------------------------

--
-- Table structure for table `lab_records`
--

CREATE TABLE IF NOT EXISTS `lab_records` (
  `lab_pat_id` varchar(20) NOT NULL,
  `test_id` varchar(20) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `test_date` date NOT NULL,
  `test_result` text,
  `patient_id` varchar(20) NOT NULL,
  PRIMARY KEY (`lab_pat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_records`
--

INSERT INTO `lab_records` (`lab_pat_id`, `test_id`, `test_name`, `test_date`, `test_result`, `patient_id`) VALUES
('LT001', 'TST001', 'Complete Blood Count (CBC)', '2025-03-17', 'Normal', 'PAT001'),
('LT002', 'TST002', 'Blood Glucose Test', '2025-03-17', 'Normal', 'PAT002'),
('LT003', 'TST001', 'Complete Blood Count (CBC)', '2025-03-25', 'Pending', 'PAT001');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `patient_id` varchar(15) NOT NULL,
  `lab_pat_id` varchar(20) NOT NULL,
  `patient_name` varchar(50) NOT NULL,
  `age` int(3) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `patient_type` enum('inpatient','outpatient') DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `date_of_appointment` date DEFAULT NULL,
  `date_of_discharge` date DEFAULT NULL,
  `patients_condition` varchar(255) DEFAULT NULL,
  `test_id` varchar(20) DEFAULT NULL,
  `test_date` date DEFAULT NULL,
  `test_result` text,
  `room_id` varchar(15) DEFAULT NULL,
  `room_no` varchar(10) DEFAULT NULL,
  `room_type` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `lab_pat_id`, `patient_name`, `age`, `date_of_birth`, `gender`, `mobile_number`, `address`, `patient_type`, `doctor_name`, `date_of_appointment`, `date_of_discharge`, `patients_condition`, `test_id`, `test_date`, `test_result`, `room_id`, `room_no`, `room_type`, `transaction_id`, `appointment_time`) VALUES
('PAT001', 'LT003', 'James', 20, '2005-03-04', 'Male', '9876543210', 'asdfadsfasdafsdfz', 'outpatient', 'EM0001', '2025-03-29', '2025-03-08', NULL, 'TST001', '2025-03-25', 'Pending', 'F1-EW', '1', 'Emergency Ward', 'TRAN005', '11:30:00'),
('PAT002', 'LT002', 'waroior', 4, '2020-06-10', 'Male', '9876654097', 'dasfsdfasdfafa', 'outpatient', 'EM0002', '2025-03-29', '2025-03-10', NULL, 'TST002', '2025-03-17', 'Normal', NULL, '101', 'Room', 'TRAN002', '02:00:00'),
('PAT003', '', 'Sharan', 10, '2015-02-07', 'Male', '9876654312', 'lllkjhgfdssssssa', 'outpatient', 'EM0002', '2025-04-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '03:00:00'),
('PAT004', '', 'abi', 3, '2021-07-15', 'Female', '9500814208', '25A,KUPPANKADU,KALLUKADAIMED\r\nU,THENMUGAM\r\nVELLODE,VADAMUGAM\r\nVELLODE,PERUNDURAI TK,ERODE', 'inpatient', 'EM0002', '2025-03-10', NULL, NULL, NULL, NULL, NULL, 'F1-R2', '102', 'Room', 'TRAN004', '12:00:00'),
('PAT005', '', 'Pooja Kannan', 15, '2009-12-03', 'Female', '9123456782', '23,adds,ihadshf', 'inpatient', 'EM0001', '2025-03-28', NULL, NULL, NULL, NULL, NULL, 'F1-R4', '104', 'Room', 'TRAN006', '10:00:00'),
('PAT006', '', 'Iswarya ', 6, '2019-02-13', 'Female', '9876543421', '87,jkgjk,jkhjkhdssssss', 'inpatient', 'EM0002', '2025-03-28', NULL, NULL, NULL, NULL, NULL, 'F2-R2', '202', 'Room', 'TRAN007', '10:00:00'),
('PAT007', '', 'Harivarshan', 18, '2006-05-17', 'Male', '9876754321', '45,were,dsfgsdfg', 'inpatient', 'EM0001', '2025-03-28', NULL, NULL, NULL, NULL, NULL, 'F2-EW', '2', 'Emergency Ward', 'TRAN008', '10:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` varchar(15) NOT NULL,
  `room_type` varchar(30) DEFAULT NULL,
  `room_number` int(5) NOT NULL DEFAULT '0',
  `status_id` varchar(10) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `occupancy_status` enum('occupied','available') DEFAULT 'available',
  PRIMARY KEY (`room_id`,`room_number`),
  UNIQUE KEY `status_id` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_type`, `room_number`, `status_id`, `booking_date`, `occupancy_status`) VALUES
('F1-EW', 'Emergency Ward', 1, 'STR003', '2025-03-10 00:00:00', 'occupied'),
('F1-R1', 'Room', 101, 'STR002', '2025-03-08 00:00:00', 'occupied'),
('F1-R2', 'Room', 102, 'STR007', '2025-03-28 00:00:00', 'occupied'),
('F1-R3', 'Room', 103, NULL, '0000-00-00 00:00:00', 'available'),
('F1-R4', 'Room', 104, 'STR008', '2025-03-28 00:00:00', 'occupied'),
('F1-R5', 'Room', 105, NULL, '0000-00-00 00:00:00', 'available'),
('F2-EW', 'Emergency Ward', 2, 'STR010', '2025-03-28 00:00:00', 'occupied'),
('F2-R1', 'Room', 201, NULL, '0000-00-00 00:00:00', 'available'),
('F2-R2', 'Room', 202, 'STR009', '2025-03-28 00:00:00', 'occupied'),
('F2-R3', 'Room', 203, NULL, '0000-00-00 00:00:00', 'available'),
('F2-R4', 'Room', 204, NULL, '0000-00-00 00:00:00', 'available'),
('F2-R5', 'Room', 205, 'STR004', '2025-03-28 00:00:00', 'occupied'),
('F3-PR1', 'Private Room', 301, NULL, '0000-00-00 00:00:00', 'available'),
('F3-PR2', 'Private Room', 302, NULL, '0000-00-00 00:00:00', 'available'),
('F3-PR3', 'Private Room', 303, NULL, '0000-00-00 00:00:00', 'available'),
('F3-PR4', 'Private Room', 304, NULL, '0000-00-00 00:00:00', 'available'),
('F3-PR5', 'Private Room', 305, NULL, '0000-00-00 00:00:00', 'available'),
('F4-PR1', 'Private Room', 401, 'STR006', '2025-03-28 00:00:00', 'occupied'),
('F4-PR2', 'Private Room', 402, NULL, '0000-00-00 00:00:00', 'available'),
('F4-PR3', 'Private Room', 403, NULL, '0000-00-00 00:00:00', 'available'),
('F4-PR4', 'Private Room', 404, NULL, '0000-00-00 00:00:00', 'available'),
('F4-PR5', 'Private Room', 405, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 1, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 2, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 3, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 4, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 5, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 6, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 7, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 8, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 9, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 10, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 11, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 12, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 13, 'STR005', '2025-03-28 00:00:00', 'occupied'),
('G01', 'General Ward', 14, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 15, NULL, '0000-00-00 00:00:00', 'available'),
('G01', 'General Ward', 16, NULL, '2025-03-18 14:57:44', 'available'),
('G01', 'General Ward', 17, NULL, '2025-03-18 18:55:16', 'available');
