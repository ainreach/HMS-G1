-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 05:03 PM
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
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) UNSIGNED NOT NULL,
  `appointment_number` varchar(20) NOT NULL,
  `patient_id` int(11) UNSIGNED NOT NULL,
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `duration` int(3) NOT NULL DEFAULT 30 COMMENT 'Duration in minutes',
  `type` enum('consultation','follow_up','emergency','surgery','checkup') NOT NULL DEFAULT 'consultation',
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') NOT NULL DEFAULT 'scheduled',
  `reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `patient_id` int(11) UNSIGNED NOT NULL,
  `appointment_id` int(11) UNSIGNED DEFAULT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','card','insurance','bank_transfer','check') DEFAULT NULL,
  `insurance_claim_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_items`
--

CREATE TABLE `billing_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `billing_id` int(11) UNSIGNED NOT NULL,
  `item_type` enum('consultation','procedure','medication','lab_test','room_charge','other') NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(5) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_claims`
--

CREATE TABLE `insurance_claims` (
  `id` int(11) UNSIGNED NOT NULL,
  `claim_no` varchar(50) NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `patient_name` varchar(120) NOT NULL,
  `provider` varchar(120) DEFAULT NULL,
  `policy_no` varchar(80) DEFAULT NULL,
  `amount_claimed` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_approved` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'submitted',
  `submitted_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance_claims`
--

INSERT INTO `insurance_claims` (`id`, `claim_no`, `invoice_no`, `patient_name`, `provider`, `policy_no`, `amount_claimed`, `amount_approved`, `status`, `submitted_at`, `created_at`, `updated_at`) VALUES
(1, '11222', '2000', 'ain reach nabale', 'jose', '1001', 1000.00, 700.00, 'approved', '2025-08-31 17:31:00', '2025-09-01 00:31:08', '2025-09-01 00:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) UNSIGNED NOT NULL,
  `medicine_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity_in_stock` int(8) NOT NULL DEFAULT 0,
  `minimum_stock_level` int(8) NOT NULL DEFAULT 10,
  `maximum_stock_level` int(8) NOT NULL DEFAULT 1000,
  `reorder_level` int(8) NOT NULL DEFAULT 20,
  `location` varchar(100) DEFAULT NULL COMMENT 'Storage location like shelf, room, etc.',
  `last_updated_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `patient_name` varchar(120) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `issued_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `id` int(11) UNSIGNED NOT NULL,
  `test_number` varchar(20) NOT NULL,
  `patient_id` int(11) UNSIGNED NOT NULL,
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `test_type` varchar(100) NOT NULL,
  `test_name` varchar(200) NOT NULL,
  `test_category` enum('blood','urine','imaging','pathology','microbiology','other') NOT NULL,
  `requested_date` datetime NOT NULL,
  `sample_collected_date` datetime DEFAULT NULL,
  `result_date` datetime DEFAULT NULL,
  `status` enum('requested','sample_collected','in_progress','completed','cancelled') NOT NULL DEFAULT 'requested',
  `results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`results`)),
  `normal_range` text DEFAULT NULL,
  `interpretation` text DEFAULT NULL,
  `lab_technician_id` int(11) UNSIGNED DEFAULT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `priority` enum('routine','urgent','stat') NOT NULL DEFAULT 'routine',
  `cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) UNSIGNED NOT NULL,
  `record_number` varchar(20) NOT NULL,
  `patient_id` int(11) UNSIGNED NOT NULL,
  `appointment_id` int(11) UNSIGNED DEFAULT NULL,
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `visit_date` datetime NOT NULL,
  `chief_complaint` text DEFAULT NULL,
  `history_present_illness` text DEFAULT NULL,
  `physical_examination` text DEFAULT NULL,
  `vital_signs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Blood pressure, temperature, pulse, etc.' CHECK (json_valid(`vital_signs`)),
  `diagnosis` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `medications_prescribed` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medications_prescribed`)),
  `follow_up_instructions` text DEFAULT NULL,
  `next_visit_date` date DEFAULT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) UNSIGNED NOT NULL,
  `medicine_code` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `generic_name` varchar(200) DEFAULT NULL,
  `brand_name` varchar(200) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `dosage_form` enum('tablet','capsule','syrup','injection','cream','drops','inhaler','other') NOT NULL,
  `strength` varchar(50) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `purchase_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `requires_prescription` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `side_effects` text DEFAULT NULL,
  `contraindications` text DEFAULT NULL,
  `storage_instructions` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1756442412, 1),
(2, '2024-01-01-000002', 'App\\Database\\Migrations\\CreateBranchesTable', 'default', 'App', 1756442412, 1),
(3, '2024-01-01-000003', 'App\\Database\\Migrations\\CreatePatientsTable', 'default', 'App', 1756442412, 1),
(4, '2024-01-01-000004', 'App\\Database\\Migrations\\CreateAppointmentsTable', 'default', 'App', 1756442412, 1),
(5, '2024-01-01-000005', 'App\\Database\\Migrations\\CreateMedicalRecordsTable', 'default', 'App', 1756442412, 1),
(6, '2024-01-01-000006', 'App\\Database\\Migrations\\CreateBillingTable', 'default', 'App', 1756442412, 1),
(7, '2024-01-01-000007', 'App\\Database\\Migrations\\CreateBillingItemsTable', 'default', 'App', 1756442412, 1),
(8, '2024-01-01-000008', 'App\\Database\\Migrations\\CreateLabTestsTable', 'default', 'App', 1756442412, 1),
(9, '2024-01-01-000009', 'App\\Database\\Migrations\\CreateMedicinesTable', 'default', 'App', 1756442413, 1),
(10, '2024-01-01-000010', 'App\\Database\\Migrations\\CreateInventoryTable', 'default', 'App', 1756442413, 1),
(11, '2025-08-29-000000', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1756460218, 2),
(12, '2025-08-29-022430', 'App\\Database\\Migrations\\CreateInvoicesTable', 'default', 'App', 1756460218, 2),
(13, '2025-08-29-022520', 'App\\Database\\Migrations\\CreatePaymentsTable', 'default', 'App', 1756460218, 2),
(14, '2025-08-29-024000', 'App\\Database\\Migrations\\CreateInsuranceClaimsTable', 'default', 'App', 1756461080, 3),
(15, '2025-09-16-003700', 'App\\Database\\Migrations\\CreatePrescriptionsTable', 'default', 'App', 1757954288, 4),
(16, '2025-09-16-003701', 'App\\Database\\Migrations\\CreatePrescriptionsTable', 'default', 'App', 1757954382, 5);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relation` varchar(50) DEFAULT NULL,
  `insurance_provider` varchar(100) DEFAULT NULL,
  `insurance_number` varchar(50) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `patient_id`, `first_name`, `last_name`, `middle_name`, `date_of_birth`, `gender`, `blood_type`, `phone`, `email`, `address`, `city`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `insurance_provider`, `insurance_number`, `allergies`, `medical_history`, `branch_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'P-20250917145907', 'Nicole', 'Bayani', NULL, '2004-11-10', 'female', NULL, '9534921530', 'nicolebayani110@gmail.com', 'Purok 4, Conel, General Santos City', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2025-09-17 14:59:07', '2025-09-17 14:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_name` varchar(120) NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `patient_name`, `invoice_no`, `amount`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 'ain reach nabale', NULL, 10000.00, '2025-08-31 17:31:00', '2025-09-01 00:31:41', '2025-09-01 00:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_id` int(11) UNSIGNED NOT NULL,
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `medication` varchar(255) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `frequency` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','doctor','nurse','receptionist','lab_staff','pharmacist','accountant','it_staff') NOT NULL DEFAULT 'receptionist',
  `branch_id` int(11) UNSIGNED DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `branch_id`, `specialization`, `license_number`, `is_active`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 'E-0001', 'admin_jane', 'admin_jane@example.com', '$2y$10$Zdly8MYsaOJ5LO2puOGRae7Z9N8bWeztdrypPG47x3SoJkyIR88Ym', 'Jane', 'Admin', NULL, 'admin', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(2, 'E-0002', 'it_mike', 'it_mike@example.com', '$2y$10$9LSc4krgC4ld5EFXCXY0w.Yf9l5xdkmWaMSIoWoUwuuDDJi/0eUl6', 'Mike', 'IT', NULL, 'it_staff', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(3, 'E-0003', 'doc_sara', 'doc_sara@example.com', '$2y$10$Ai09vunk.bM4D60zTTTOT.07Cskux9PzpgIZ/boqKuaKc7mf.KTEi', 'Sara', 'Doctor', NULL, 'doctor', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(4, 'E-0004', 'nurse_lee', 'nurse_lee@example.com', '$2y$10$eNIRsdU1hZ.3pGyP0kg.M.OszIZDTo8rB6HgkrbybZ5NpCgGK6n8K', 'Lee', 'Nurse', NULL, 'nurse', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(5, 'E-0005', 'recep_anna', 'recep_anna@example.com', '$2y$10$zHM9cfWTBf3l9KQcuj311ueAKDSO.JbnSD1SMVpbwIl5loMT1m5Xm', 'Anna', 'Reception', NULL, 'receptionist', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(6, 'E-0006', 'lab_ben', 'lab_ben@example.com', '$2y$10$5wXnSkGiKIRkBG1hPsEpMOrVb.zQgZhTPwv/.eZm1ZHpFRw9MTk.6', 'Ben', 'Lab', NULL, 'lab_staff', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(7, 'E-0007', 'pharm_kate', 'pharm_kate@example.com', '$2y$10$OnJD7bnbbHMj.WXC0JobV.42XjYZ9.0YDP4Kr0D6AO9tnPlujUoLu', 'Kate', 'Pharm', NULL, 'pharmacist', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(8, 'E-0008', 'acct_noel', 'acct_noel@example.com', '$2y$10$vJOx/9yOwaejzDEmcMc9.ufC2FvmxOclGOXBUvqu80hhPfBlSXGum', 'Noel', 'Acct', NULL, 'accountant', NULL, NULL, NULL, 1, NULL, '2025-08-29 06:20:15', '2025-08-29 06:20:15'),
(9, '2311600074', 'ayenreach', 'ayenreach123@gmail.com', '$2y$10$hEVN/o.o279DbrMJ8ZFWje.FnUqvoB.YDLKoAp7uDA6S3.YC5Yfs.', 'ain reach', 'nabale', NULL, 'admin', NULL, NULL, NULL, 1, NULL, '2025-08-31 23:44:38', '2025-08-31 23:44:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_number` (`appointment_number`),
  ADD KEY `patient_id_doctor_id_appointment_date` (`patient_id`,`doctor_id`,`appointment_date`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `patient_id_bill_date` (`patient_id`,`bill_date`);

--
-- Indexes for table `billing_items`
--
ALTER TABLE `billing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `billing_id` (`billing_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `claim_no` (`claim_no`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_id_branch_id` (`medicine_id`,`branch_id`),
  ADD KEY `expiry_date` (`expiry_date`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_number` (`test_number`),
  ADD KEY `patient_id_requested_date` (`patient_id`,`requested_date`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `record_number` (`record_number`),
  ADD KEY `patient_id_visit_date` (`patient_id`,`visit_date`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicine_code` (`medicine_code`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_items`
--
ALTER TABLE `billing_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
