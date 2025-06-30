-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 06:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`) VALUES
('A01'),
('A02'),
('A03'),
('A06');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_to`
--

CREATE TABLE `assigned_to` (
  `Student_ID` varchar(10) NOT NULL,
  `Room_No` varchar(10) NOT NULL,
  `Assignment_date` date DEFAULT NULL,
  `Checkout_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_to`
--

INSERT INTO `assigned_to` (`Student_ID`, `Room_No`, `Assignment_date`, `Checkout_date`) VALUES
('S01', 'R101', '2024-09-01', NULL),
('S02', 'R102', '2024-09-05', NULL),
('S03', 'R102', '2024-09-05', NULL),
('S04', 'R103', '2024-10-01', NULL),
('S05', 'R104', '2024-11-15', NULL),
('S06', 'R101', '2025-06-29', NULL),
('S07', 'R103', '2025-06-30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `Complaint_ID` int(11) NOT NULL,
  `Student_ID` varchar(10) DEFAULT NULL,
  `Complaint_type` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Pending',
  `Warden_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`Complaint_ID`, `Student_ID`, `Complaint_type`, `Description`, `Status`, `Warden_ID`) VALUES
(1, 'S01', 'Water Leakage', 'There is a leakage in the bathroom tap.', 'Resolved', NULL),
(2, 'S02', 'Electric Issue', 'Light bulb flickering frequently.', 'Resolved', NULL),
(3, 'S03', 'Noise Disturbance', 'Roommates playing loud music at night.', 'Pending', NULL),
(4, 'S04', 'Furniture Damage', 'Broken chair in the room.', 'Resolved', NULL),
(5, 'S05', 'Cleanliness', 'Room not cleaned for weeks.', 'Resolved', NULL),
(7, 'S03', 'Security', 'there is no safe in front', 'Resolved', NULL),
(14, 'S06', 'Cleanliness', 'dsdsdsdss', 'Pending', NULL),
(15, 'S06', 'Security', 'security are not present alsways at the gate', 'Pending', NULL),
(16, 'S06', 'Security', 'security are not present alsways at the gate', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `Student_ID` varchar(10) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `Payment_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `penalty` decimal(10,2) DEFAULT 0.00,
  `month_of_payment` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Student_ID`, `amount`, `Payment_date`, `due_date`, `penalty`, `month_of_payment`) VALUES
(1, 'S01', 12000.00, '2025-06-01', '2026-06-01', 1000.00, 'June 2025'),
(2, 'S02', 9000.00, '2025-06-03', '2026-04-03', 600.00, 'April 2025'),
(3, 'S03', 9000.00, '2025-06-04', '2026-02-04', 550.00, 'May 2025'),
(4, 'S04', 11000.00, '2025-06-01', '2025-12-01', 900.00, 'June 2025'),
(5, 'S05', 8000.00, '2025-06-06', '2026-03-06', 2000.00, 'January 2025');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `Room_No` varchar(10) NOT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `Room_type` enum('Single','Double') DEFAULT NULL,
  `ac_type` enum('ac','non-ac') DEFAULT NULL,
  `Monthly_rent` decimal(10,2) DEFAULT NULL,
  `Occupied_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`Room_No`, `Capacity`, `Room_type`, `ac_type`, `Monthly_rent`, `Occupied_count`) VALUES
('R101', 2, 'Single', 'ac', 12000.00, 2),
('R102', 2, 'Double', 'non-ac', 9000.00, 2),
('R103', 2, 'Double', 'ac', 11000.00, 2),
('R104', 1, 'Single', 'non-ac', 8000.00, 1),
('R105', 2, 'Double', 'ac', 11500.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `ID` varchar(10) NOT NULL,
  `Duration_of_stay` int(11) DEFAULT NULL,
  `Warden_ID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`ID`, `Duration_of_stay`, `Warden_ID`) VALUES
('S01', 12, 'W02'),
('S02', 10, 'W01'),
('S03', 8, 'W03'),
('S04', 6, 'W01'),
('S05', 9, 'W02'),
('S06', 24, 'W01'),
('S07', 24, 'W01');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phonenumber` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `name`, `email`, `phonenumber`, `password`, `role`) VALUES
('A01', 'Admin 1', 'admin1@mail.com', '0712345001', 'passA01', 'admin'),
('A02', 'Admin 2', 'admin2@mail.com', '0723456000', 'passA02', 'admin'),
('A03', 'Admin 3', 'admin3@mail.com', '0734567000', 'passA03', 'admin'),
('A04', 'Admin4', 'admin4@mail.com', '0723456789', '$2y$10$eFzLmF.p5XDsZ/lFp0aQauLFhhnm80Y5iGN8ocj4eUQsuaB34AFuu', 'admin'),
('A05', 'Admin5', 'admin5@mail.com', '0716644098', 'passA05', 'admin'),
('A06', 'Malhara', '2022e126@eng.jfn.ac.lk', '0703517377', 'Malhara@123', 'admin'),
('S01', 'Kosala Nayanajith', 'kosala01@mail.com', '0711234567', 'passS01', 'student'),
('S02', 'Imesh kavinda', 'imesh02@mail.com', '0722345679', 'passS02', 'student'),
('S03', 'Dilshan Silva', 'dilshan03@mail.com', '0733456789', 'passS03', 'student'),
('S04', 'kavindu kavishka', 'kavindu04@mail.com', '0744567890', 'passS04', 'student'),
('S05', 'Tharindu Jayasooriya', 'tharindu05@mail.com', '0755678901', 'passS05', 'student'),
('S06', 'Kosala Nayanajith Deshapriya', '2022e27@eng.jfn.ac.lk', '0703517377', 'passS06', 'student'),
('S07', 'Jeewantha rathnayaka', '2022e124@eng.jfn.ac.lk', '0703517378', '$2y$10$eSKUvPGt/mshqUOWtl8TMOTsncssPEgMllcUAF8T1yUNItKu7e0Oq', 'student'),
('W01', 'Kosala nayanjith', 'warden1@mail.com', '0766789012', 'passW01', 'warden'),
('W02', 'Ruwan Gunasekara', 'warden2@mail.com', '0777890123', 'passW02', 'warden'),
('W03', 'Kalum Rathnayake', 'warden3@mail.com', '0788901234', 'passW03', 'warden'),
('W04', 'Nadeesha Weerasinghe', 'warden4@mail.com', '0799012345', 'passW04', 'warden'),
('W05', 'Thilina Jayawardena', 'warden5@mail.com', '0700123456', 'passW05', 'warden'),
('W06', 'Sujatha Kumari', 'suja94@gmail.com', '0713798935', 'passW07', 'warden'),
('W07', 'Sujatha Kumari', 'suja84@gmail.com', '0713798935', 'passW06', 'warden'),
('W08', 'Fatima Al-Farsi', 'fatima.alfarsi@hostel.com', '0765566778', 'passW08', 'warden');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_log`
--

CREATE TABLE `visitor_log` (
  `Visitor_ID` int(11) NOT NULL,
  `Student_ID` varchar(10) DEFAULT NULL,
  `Visitor_name` varchar(100) DEFAULT NULL,
  `Phonenumber` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_log`
--

INSERT INTO `visitor_log` (`Visitor_ID`, `Student_ID`, `Visitor_name`, `Phonenumber`) VALUES
(1, 'S01', 'Sameera Fernando', '0719911223'),
(2, 'S02', 'Nilusha Perera', '0728833445'),
(3, 'S03', 'Harsha Silva', '0737744556'),
(4, 'S04', 'Amali Nuwanthi', '0746655778'),
(5, 'S05', 'Kasun Jayalath', '0755566889'),
(10, 'S02', 'jeewantha rathnayaka', '0705113030');

-- --------------------------------------------------------

--
-- Table structure for table `warden`
--

CREATE TABLE `warden` (
  `ID` varchar(10) NOT NULL,
  `Yrs_of_experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warden`
--

INSERT INTO `warden` (`ID`, `Yrs_of_experience`) VALUES
('W01', 7),
('W02', 10),
('W03', 6),
('W04', 5),
('W05', 8),
('W06', NULL),
('W07', NULL),
('W08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `assigned_to`
--
ALTER TABLE `assigned_to`
  ADD PRIMARY KEY (`Student_ID`,`Room_No`),
  ADD KEY `Room_No` (`Room_No`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`Complaint_ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`Room_No`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitor_log`
--
ALTER TABLE `visitor_log`
  ADD PRIMARY KEY (`Visitor_ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `warden`
--
ALTER TABLE `warden`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `Complaint_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `visitor_log`
--
ALTER TABLE `visitor_log`
  MODIFY `Visitor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `assigned_to`
--
ALTER TABLE `assigned_to`
  ADD CONSTRAINT `assigned_to_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `student` (`ID`),
  ADD CONSTRAINT `assigned_to_ibfk_2` FOREIGN KEY (`Room_No`) REFERENCES `room` (`Room_No`);

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `complaint_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `student` (`ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `student` (`ID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `visitor_log`
--
ALTER TABLE `visitor_log`
  ADD CONSTRAINT `visitor_log_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `student` (`ID`);

--
-- Constraints for table `warden`
--
ALTER TABLE `warden`
  ADD CONSTRAINT `warden_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
