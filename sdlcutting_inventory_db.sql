-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2026 at 04:25 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdlcutting_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `asset_assignments`
--

CREATE TABLE `asset_assignments` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `assigned_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_assignments`
--

INSERT INTO `asset_assignments` (`id`, `product_id`, `employee_id`, `assigned_date`, `return_date`, `remarks`, `created_at`, `updated_at`) VALUES
(20, 122, 24, '2025-07-23', NULL, 'Previous Camera Data Link Error!', '2025-07-23 05:13:10', '2025-07-23 05:13:10'),
(21, 123, 24, '2025-07-23', NULL, 'Previous Camera Data Link Error!', '2025-07-23 05:13:31', '2025-07-23 05:13:31'),
(22, 133, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:56:08', '2025-07-28 10:56:08'),
(23, 134, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:56:19', '2025-07-28 10:56:19'),
(24, 135, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:56:33', '2025-07-28 10:56:33'),
(25, 136, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:56:46', '2025-07-28 10:56:46'),
(26, 137, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:57:40', '2025-07-28 10:57:40'),
(28, 139, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:58:07', '2025-07-28 10:58:07'),
(29, 140, 8, '2025-07-28', NULL, 'New Desktop Assign', '2025-07-28 10:58:15', '2025-07-28 10:58:15'),
(30, 78, 11, '2025-07-21', NULL, 'Primary SSD is already full. Add as a secondary SSD for OneDrive', '2025-07-28 11:19:35', '2025-07-28 11:19:35'),
(31, 162, 9, '2025-08-07', NULL, 'The old router disconnects from the internet automatically.', '2025-08-09 05:43:27', '2025-08-09 05:43:27'),
(32, 163, 25, '2025-08-09', NULL, 'Update from the bullet to the dome and installation in the weight scale room inside.', '2025-08-09 05:44:58', '2025-08-09 05:44:58'),
(33, 164, 23, '2025-08-11', NULL, 'SLL- B2 Canteen Store Inside view', '2025-08-11 03:03:55', '2025-08-11 03:03:55'),
(34, 40, 23, '2025-08-11', NULL, 'SLL- B2 Canteen View', '2025-08-11 03:04:36', '2025-08-11 03:04:36'),
(35, 165, 26, '2025-08-14', NULL, 'Old CPU Full Update', '2025-08-14 09:12:02', '2025-08-14 09:12:02'),
(36, 166, 26, '2025-08-14', NULL, 'Old CPU Full Update', '2025-08-14 09:13:21', '2025-08-14 09:13:21'),
(37, 167, 26, '2025-08-14', NULL, 'Old CPU Full Update', '2025-08-14 09:13:43', '2025-08-14 09:13:43'),
(38, 168, 26, '2025-08-14', NULL, 'Old CPU Full Update', '2025-08-14 09:14:01', '2025-08-14 09:14:01'),
(39, 169, 26, '2025-08-14', NULL, 'Previous CPU Update', '2025-08-14 09:15:51', '2025-08-14 09:15:51'),
(41, 173, 30, '2025-09-14', NULL, 'Previous laptop\'s AutoCAD performance was not good. Change from the Head office.', '2025-09-14 13:13:28', '2025-09-14 13:13:28'),
(42, 172, 19, '2025-09-17', NULL, 'Previous Monitor Power Issue. Sometimes the monitor power is on, and for the maximum time no power.', '2025-09-17 05:50:59', '2025-09-17 05:50:59'),
(43, 171, 29, '2025-09-17', NULL, 'The Previous Monitor Display was damaged automatically due to a chemical effect.', '2025-09-17 05:52:17', '2025-09-17 05:52:17'),
(44, 125, 25, '2025-09-26', NULL, 'Due to Previous Camera Damage, replace this with a new camera.', '2025-10-07 03:24:49', '2025-10-07 03:24:49'),
(45, 124, 25, '2025-10-07', NULL, 'Assign for replacement due to damage D30 channel. Outside NVR ETP Blower Room', '2025-10-07 03:27:12', '2025-10-07 03:27:12'),
(46, 180, 31, '2025-10-07', NULL, 'New Laptop Assign for New Join as a Admin GM', '2025-10-07 13:05:02', '2025-10-07 13:05:02'),
(47, 179, 31, '2025-10-07', NULL, 'New VoIP Assign for New Join as a Admin GM', '2025-10-07 13:05:23', '2025-10-07 13:05:23'),
(48, 181, 31, '2025-10-11', NULL, 'New GM Sir Personal Use Purpose Assign.', '2025-10-11 11:43:09', '2025-10-11 11:43:09'),
(49, 206, 17, '2025-10-30', NULL, 'Assign for HR Department File Update Job.', '2025-10-30 11:34:55', '2025-10-30 11:34:55'),
(50, 217, 14, '2026-01-05', NULL, 'Due to a previous laptop performance issue, management changed to the new Asus ExpertBook. The previous laptop was sent to the head office. Ref: CFO, sir.', '2026-01-05 02:39:14', '2026-01-05 02:39:14'),
(51, 227, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:06:39', '2026-01-11 06:06:39'),
(52, 226, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:06:58', '2026-01-11 06:06:58'),
(53, 225, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:07:12', '2026-01-11 06:07:12'),
(54, 224, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:12:06', '2026-01-11 06:12:06'),
(55, 223, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:12:22', '2026-01-11 06:12:22'),
(56, 222, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:12:37', '2026-01-11 06:12:37'),
(57, 221, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:12:55', '2026-01-11 06:12:55'),
(58, 220, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:13:13', '2026-01-11 06:13:13'),
(59, 219, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:13:28', '2026-01-11 06:13:28'),
(60, 218, 32, '2026-01-12', NULL, 'Increase Record to 60 Days', '2026-01-11 06:13:41', '2026-01-11 06:13:41'),
(61, 244, 36, '2026-01-18', NULL, 'LEVEL-4 B2', '2026-01-18 06:35:52', '2026-01-18 06:35:52'),
(62, 243, 36, '2026-01-18', NULL, 'LEVEL-4 B2', '2026-01-18 06:37:02', '2026-01-18 06:37:02'),
(63, 242, 36, '2026-01-18', NULL, 'LEVEL-4 B2', '2026-01-18 06:38:30', '2026-01-18 06:38:30'),
(64, 245, 36, '2026-01-18', NULL, 'LEVEL-4 B2', '2026-01-18 06:38:59', '2026-01-18 06:38:59'),
(65, 240, 36, '2026-01-18', NULL, 'LEVEL-4 B2', '2026-01-18 06:39:23', '2026-01-18 06:39:23'),
(66, 305, 25, '2026-01-28', NULL, 'Due to replace for- Truck of (Jhute) Good Broken Attached CCTV Camera.', '2026-01-28 06:47:47', '2026-01-28 06:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Laptop', 'All laptop computers'),
(2, 'Desktop', 'Desktop computers and workstations'),
(3, 'Monitor', 'Computer monitors and displays'),
(4, 'Storage', 'Compute Storage Iteam'),
(5, 'Motherboard', 'Computer Motherboard'),
(6, 'Printer', 'Printers and multifunction devices'),
(7, 'Network', 'Network equipment like routers and switches'),
(8, 'Server', 'Server hardware'),
(9, 'Peripheral', 'Keyboards, mice, and other peripherals'),
(10, 'Mobile', 'Tablets and smartphones'),
(11, 'Software', 'Licensed software applications'),
(12, 'CCTV', 'All CCTV Iteam'),
(13, 'RAM', 'All RAM info');

-- --------------------------------------------------------

--
-- Table structure for table `damaged_assets`
--

CREATE TABLE `damaged_assets` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `damage_reason_text` text DEFAULT NULL,
  `concern_store` varchar(100) DEFAULT NULL,
  `damaged_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_condition` varchar(100) DEFAULT NULL,
  `requisition_no` varchar(100) DEFAULT NULL,
  `factory_name` varchar(10) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `asset_tag` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `original_created_at` timestamp NULL DEFAULT NULL,
  `original_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `job_id` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `job_id`, `email`, `department`, `designation`, `contact_number`, `location`, `status`, `created_at`, `updated_at`) VALUES
(7, 'Provat Kumar Sen', 'SLL-0007644', 'provat.it@sterlinggroup.com.bd', 'IT', 'Asst. Manager IT', '+8801751467162', 'SLL', 'active', '2025-07-15 11:51:17', '2025-07-15 11:51:17'),
(8, 'Saman Wasalthilika', 'SLL-0000001', 'saman@sterlinggroup.com.bd', 'Management', 'GM-Operations', '01711068033', 'SLL', 'active', '2025-07-19 02:20:07', '2026-01-26 10:19:47'),
(9, 'Gishan Fernando', 'SLL-0000002', 'laundry@sterlinggroup.com.bd', 'Management', 'GM-Development', '01985007331', 'SLL', 'active', '2025-07-19 02:28:18', '2026-01-26 10:19:23'),
(10, 'Mehedi Hasan', 'SLL-0000003', 'mehedi@sterlinggroup.com.bd', 'Management', 'GM-Production', '01712060633', 'SLL', 'active', '2025-07-19 02:29:23', '2026-01-26 10:19:36'),
(11, 'Shakhawhat Hossain', 'SLL-0000231', 'accoutns.laundry@sterlinggroup.com.bd', 'Accounts', 'Manager Accounts', '01728066362', 'SLL', 'active', '2025-07-19 02:30:38', '2025-07-19 02:30:38'),
(12, 'Abdullah Al Masud', 'SLL-0000212', 'masud.laundry@sterlinggroup.com.bd', 'Accounts', 'Asst. Manager Accounts', '01718573573', 'SLL', 'active', '2025-07-19 02:32:06', '2025-07-23 10:15:15'),
(13, 'Md. Sumon Reza', 'SLL-0006672', 'commercial.laundry@sterlinggroup.com.bd', 'Commercial', 'Officer-Commercial', '', 'SLL', 'active', '2025-07-19 02:32:55', '2025-07-19 02:32:55'),
(14, 'Idris Mallik', 'SLL-0000161', 'idris@sterlinggroup.com.bd', 'Compliance', 'AGM-Compliance', '01713513607', 'SLL', 'active', '2025-07-19 02:33:49', '2025-07-19 02:33:49'),
(15, 'Mujahedul Islam', 'SLL-0010700', '', 'Compliance', 'Jr. Officer', '', 'SLL', 'inactive', '2025-07-19 02:36:00', '2026-01-27 05:00:45'),
(16, 'Asadur Rahman', 'SLL-0011515', 'admin.laundry@sterlinggroup.com.bd', 'Admin', 'Manager-Admin', '01790476369', 'SLL', 'active', '2025-07-19 02:39:36', '2025-07-19 02:39:36'),
(17, 'Kamal Uddin', 'SLL-0000265', 'kamal.hr@sterlinggroup.com.bd', 'HR', 'Manager HR', '01731169996', 'SLL', 'active', '2025-07-19 02:40:52', '2025-07-19 02:40:52'),
(18, 'Md. Al-Amin', 'SLL-0000994', '', 'HR', 'Deputy Manager HR', '01731526196', 'SLL', 'active', '2025-07-19 02:42:01', '2025-07-19 02:42:01'),
(19, 'Md. Rafiqul Islam', 'SLL-0008888', '', 'HR', 'Executive-HR', '01648634282', 'SLL', 'active', '2025-07-19 02:43:17', '2025-07-19 02:43:17'),
(20, 'Md. Firoz Sha', 'SLL-0009012', '', 'HR', 'Executive-HR', '', 'SLL', 'active', '2025-07-19 02:43:48', '2025-07-19 02:43:48'),
(21, 'SLL-B1-32CNVR-01', 'SLL-NVR01', '', 'IT', 'Surveillance', '', 'SLL', 'active', '2025-07-23 05:09:22', '2025-07-23 05:09:22'),
(22, 'SLL-B2-32CNVR-02', 'SLL-NVR02', '', 'IT', 'Surveillance', '', 'SLL', 'active', '2025-07-23 05:09:54', '2025-07-23 05:09:54'),
(23, 'SLL-64CNVR-01', 'SLL-NVR03', '', 'IT', 'Surveillance', '', 'SLL', 'active', '2025-07-23 05:10:39', '2025-07-23 05:10:39'),
(24, 'SLL-ETP-16CNVR', 'SLL-NVR04', '', 'IT', 'Surveillance', '', 'SLL', 'active', '2025-07-23 05:11:16', '2025-07-23 05:11:16'),
(25, 'SLL-Outside-32C-NVR', 'SLL-NVR05', '', 'IT', 'Surveillance', '', 'SLL', 'active', '2025-07-23 05:11:59', '2025-07-23 05:11:59'),
(26, 'Samidul Hasan Sumel', 'SLL-0000319', 'quality.laundry@sterlinggroup.com.bd', 'Production', 'Manager Quality', '01751703373', 'SLL', 'active', '2025-08-14 09:10:31', '2025-08-14 09:10:31'),
(27, 'Rajakini Terminal-1', 'SLL-01', '', 'Production', 'Chemical Inventory', '', 'SLL', 'active', '2025-09-10 05:41:22', '2025-09-10 05:41:22'),
(28, 'Rajakini Terminal-2', 'SLL-02', '', 'Production', 'Chemical Inventory', '', 'SLL', 'active', '2025-09-10 05:41:50', '2025-09-10 05:41:50'),
(29, 'Rajakini Terminal-3', 'SLL-03', '', 'Production', 'Chemical Inventory', '', 'SLL', 'active', '2025-09-10 05:42:11', '2025-09-10 05:42:11'),
(30, 'Md. Humayun Kabir', 'SLL-0007744', 'humayun.kabir@sterlinggroup.com.bd', 'Compliance', 'Group Manager (Fire & Safety)', '+8801715384766', 'SLL', 'active', '2025-09-14 02:58:33', '2025-09-14 02:58:33'),
(31, 'Bodiuz Zaman', 'SLL-0012782', 'bodiuz.zaman@sterlinggroup.com.bd', 'Management', 'GM-AHRC', '01970292626', 'SLL', 'active', '2025-10-07 13:04:21', '2026-01-26 11:40:42'),
(32, 'SDL-B1-128CNVR-01', 'SDL-NVR01', '', 'IT', 'Surveillance', '', 'SDL', 'active', '2026-01-10 03:13:18', '2026-01-10 03:13:18'),
(33, 'SDL-B2-32CNVR-01', 'SDL-NVR02', '', 'IT', 'Surveillance', '', 'SDL', 'active', '2026-01-17 10:29:26', '2026-01-17 10:31:32'),
(34, 'SDL-B2-32CNVR-02', 'SDL-NVR03', '', 'IT', 'Surveillance', '', 'SDL', 'active', '2026-01-17 10:29:52', '2026-01-17 10:31:50'),
(35, 'SDL-B2-32CNVR-03', 'SDL-NVR04', '', 'IT', 'Surveillance', '', 'SDL', 'active', '2026-01-17 10:30:10', '2026-01-17 10:31:41'),
(36, 'SDL-B2-32CNVR-05', 'SDL-NVR05', '', 'IT', 'Surveillance', '', 'SDL', 'active', '2026-01-17 10:31:12', '2026-01-17 10:31:12'),
(37, 'Shofiqul Islam', 'SLL-0000092', 'bcmp.laundry@sterlinggroup.com.bd', 'Store', 'Store-Incharge', '01968560087', 'SLL', 'active', '2026-01-26 11:32:24', '2026-01-26 11:32:24'),
(38, 'Mithun Sarkar', 'SLL-0007152', '', 'Store', 'Store-Officer', '01911070806', 'SLL', 'active', '2026-01-26 11:33:24', '2026-01-26 11:33:24'),
(39, 'Nurul Islam', 'SLL-0001054', '', 'Store', 'Store-Executive', '', 'SLL', 'active', '2026-01-26 11:35:40', '2026-01-26 11:35:40'),
(40, 'Md. Zahirul Islam', 'SLL-0006512', '', 'Store', 'Store- Sr. Officer', '', 'SLL', 'active', '2026-01-26 11:36:49', '2026-01-26 11:36:49'),
(41, 'Md. Riazul Islam', 'SLL-0000040', 'store.laundry@sterlinggroup.com.bd', 'Store', 'Store- Manager', '01922484624', 'SLL', 'active', '2026-01-26 11:38:07', '2026-01-26 11:40:12'),
(42, 'Md. Billal Hossain', 'SLL-0009966', '', 'Store', 'Supervisor-Delivery', '', 'SLL', 'active', '2026-01-28 08:52:22', '2026-01-28 08:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_condition` varchar(100) DEFAULT NULL,
  `requisition_no` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `factory_name` varchar(10) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_description` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `asset_tag` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('available','assigned','damaged') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `serial_number`, `supplier`, `purchase_date`, `warranty`, `price`, `product_condition`, `requisition_no`, `created_at`, `factory_name`, `updated_at`, `product_description`, `brand`, `model`, `asset_tag`, `remarks`, `category_id`, `status`) VALUES
(18, 'Samsung 500GB SSD', 'Storage', 'S6P6NL0XC15310', 'Ryans Computers', '2025-05-18', '36 Month', 6900.00, 'New', '00000', '2025-05-24 11:10:46', 'SLL', '2026-01-24 11:30:08', 'Samsung 870 EVO 500GB 2.5 Inch SATAIII SSD', 'Samsung', '870 EVO', 'Na', 'na', 4, 'available'),
(19, 'Cisco 24P PoE+ 4SFP Managed Switch', 'Network', 'FOC2720YB6F', 'Ryans Computers', '2025-05-17', '12 Month', 48700.00, 'New', '00000', '2025-05-24 11:12:52', 'SLL', '2026-01-24 11:30:08', 'Cisco CBS350-24P-4G 28 port (24-port Gigabit PoE+ & 4-port Gigabit SFP) Rackmount Managed Switch #CBS350-24P-4G-EU', 'Cisco', 'Cisco CBS350-24P-4G ', 'N/A', 'Purchase By Head Office', 7, 'available'),
(20, 'Transcend 2.5 Inch SSD', 'Storage', 'J077461129', 'Ryans Computers', '2025-04-17', '36 Month', 2300.00, 'New', '00000', '2025-05-24 11:13:45', 'SLL', '2026-01-24 11:30:08', 'Transcend 220S 120GB 2.5 Inch SATAIII SSD', 'Transcend ', 'Transcend 220S ', 'N/A', 'N/A', 4, 'available'),
(21, 'Dell Monitor (HDMI, VGA)', 'Monitor', 'FS5CN54', 'Ryans Computers', '2025-03-04', '36 Month', 9500.00, 'New', '00000', '2025-05-24 11:15:05', 'SLL', '2026-01-24 11:30:08', 'Dell D2020H 19.5 Inch Black Monitor (HDMI, VGA)', 'Dell ', 'Dell D2020H ', 'N/A', 'N/A', 3, 'available'),
(22, 'Aula Mechanical Keyboard', 'Peripheral', 'KBF3287FB240401499', 'Ryans Computers', '2025-02-24', '12 Month', 2200.00, 'New', '00000', '2025-05-24 11:15:49', 'SLL', '2026-01-24 11:30:08', 'Aula F3287 (Blue Switch) Wired White & Grey Mechanical Gaming Keyboard', 'Aula ', 'Aula F328', 'N/A', 'HR-Alamin-PC', 9, 'available'),
(23, 'Gigabyte Intel Motherboard', 'Motherboard', 'SN24433A038461', 'Ryans Computers', '2025-02-11', '36 Month', 17500.00, 'New', '00000', '2025-05-24 11:17:54', 'SLL', '2026-01-24 11:30:08', 'Gigabyte B760M DS3H AX (Wi-Fi 6E) DDR5 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'Gigabyte ', 'Gigabyte B760M DS3H AX ', 'N/A', 'Gishan Sir GM- PC', 5, 'available'),
(24, 'Kingston 16GB DDR5  Desktop RAM', 'RAM', 'RYG1139827', 'Ryans Computers', '2025-02-11', 'Product Lifetime', 6499.00, 'New', '00000', '2025-05-24 11:18:39', 'SLL', '2026-01-24 11:30:08', 'Kingston Fury Beast 16GB DDR5 5600MHz Black Heatsink Desktop RAM', 'Kingston', 'Kingston Fury ', 'N/A', 'N/A', 13, 'available'),
(25, 'Samsung 1TB M.2 SSD', 'Storage', 'S6Z1NU0XB03084', 'Ryans Computers', '2025-02-11', '36 Month', 13500.00, 'New', '00000', '2025-05-24 11:19:14', 'SLL', '2026-01-24 11:30:08', 'Samsung 990 Pro 1TB M.2 2280 PCIe Gen 4.0 x 4 NVMe 2.0 SSD', 'Samsung', 'Samsung 990 Pro ', 'N/A', 'Gishan Sir GM', 4, 'available'),
(26, 'Logitech Keyboard & Mouse Combo', 'Peripheral', '2435SYG3ZN99', 'Ryans Computers', '2025-02-11', '36 Month', 2500.00, 'New', '00000', '2025-05-24 11:19:42', 'SLL', '2026-01-24 11:30:08', 'Logitech MK240 Black Wireless Keyboard & Mouse Combo', 'Logitech ', 'Logitech MK240', 'N/A', 'N/A', 9, 'available'),
(27, 'Gamdias Desktop Power Supply', 'Peripheral', '379923A00976', 'Ryans Computers', '2025-02-11', '60 Month', 4100.00, 'New', '00000', '2025-05-24 11:20:15', 'SLL', '2026-01-24 11:30:08', 'Gamdias HELIOS M1 450B 450W ATX Non Modular 80 Plus Bronze Certified Black Power Supply', 'Gamdias', 'Gamdias HELIOS M1 450B ', 'N/A', 'N/A', 9, 'available'),
(28, 'HP  Professional Monitor', 'Monitor', '3CM4260HXR', 'Ryans Computers', '2025-02-11', '36 Month', 19500.00, 'New', '00000', '2025-05-24 11:20:48', 'SLL', '2026-01-24 11:30:08', 'HP Series 5 524sf 23.8 Inch FHD (1920x1080) IPS 100Hz Professional Monitor', 'HP', 'HP Series 5 524sf ', 'N/A', 'N/A', 3, 'available'),
(31, 'Gigabyte Motherboard', 'Motherboard', 'SN240860033707', 'Ryans Computers', '2025-01-29', '36 Month', 9800.00, 'New', '00000', '2025-05-24 11:23:13', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H510M S2H V3 DDR4 10th/11th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte ', 'H510M S2H V3 ', 'N/A', 'N/A', 5, 'available'),
(32, 'Transcend Desktop RAM', 'RAM', 'J032230118', 'Ryans Computers', '2025-01-29', 'Product Lifetime', 1900.00, 'New', '00000', '2025-05-24 11:23:42', 'SLL', '2026-01-24 11:30:08', 'Transcend JetRAM 8GB DDR4 2666MHz U-DIMM Desktop RAM', 'Transcend', 'Transcend JetRAM ', 'N/A', 'N/A', 13, 'available'),
(33, 'HP  NVMe SSD', 'Storage', 'HBSE34450100050', 'Ryans Computers', '2025-01-29', '36 Month', 4300.00, 'New', '00000', '2025-05-24 11:25:18', 'SLL', '2026-01-24 11:30:08', 'HP EX900 Plus 512GB M.2 2280 PCIe 3.0 x4 NVMe SSD', 'HP', 'HP EX900 Plus', 'N/A', 'N/A', 4, 'available'),
(34, 'Acer  Monitor (HDMI & VGA)', 'Monitor', 'MMV0CSS0014320005C4HA1', 'Ryans Computers', '2025-01-29', '36 Month', 7800.00, 'New', '00000', '2025-05-24 11:40:02', 'SLL', '2026-01-24 11:30:08', 'Acer K202QBI 19.5 Inch HD+ (1600x900) TN 75Hz Monitor (HDMI & VGA)', 'Acer', 'Acer K202QBI', 'N/A', 'N/A', 3, 'available'),
(35, 'Apollo Offline UPS', 'Peripheral', 'E2407051582', 'Ryans Computers', '2025-01-29', '12 Month', 3200.00, 'New', '', '2025-05-24 11:40:39', 'SLL', '2026-01-24 11:30:08', 'Apollo 1065A/1065 650VA Offline UPS with Plastic Body', 'Apollo ', 'Apollo 1065A/1065', '', '', 9, 'available'),
(36, 'Asus  Wi-Fi Router', 'Network', 'N3IG32002024ML2', 'Ryans Computers', '2025-01-16', '24 Month', 11200.00, 'New', '00000', '2025-05-24 11:41:48', 'SLL', '2026-01-24 11:30:08', 'Asus RT-AX56U AX1800 Mbps Gigabit Dual-Band Wi-Fi Router', 'Asus', 'Asus RT-AX56U', 'N/A', 'N/A', 7, 'available'),
(38, 'Dahua Bullet IP Camera', 'CCTV', 'AD02E19PAG3BD14', 'Ryans Computers', '2024-12-11', '12 Month', 4100.00, 'New', '00000', '2025-05-24 11:43:39', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'available'),
(39, 'Dahua IP Camera', 'CCTV', 'AD05322PAGF3408', 'Ryans Computers', '2024-12-11', '12 Month', 4100.00, 'New', '00000', '2025-05-24 11:44:05', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'available'),
(40, 'Dahua Bullet IP Camera', 'CCTV', 'AC07064PAG7CE1C', 'Ryans Computers', '2024-12-11', '12 Month', 4100.00, 'New', '00000', '2025-05-24 11:44:38', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'assigned'),
(41, 'Dahua Bullet IP Camera', 'CCTV', 'AC07064PAGD8425', 'Ryans Computers', '2024-12-11', '12 Month', 4100.00, 'New', '00000', '2025-05-24 11:45:11', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL ', 'N/A', 'N/A', 12, 'available'),
(42, 'Dahua  Bullet IP Camera', 'CCTV', 'AD05322PAG6F753', 'Ryans Computers', '2024-11-16', '12 Month', 4200.00, 'New', '00000', '2025-05-24 11:46:18', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'available'),
(43, 'Dahua  Bullet IP Camera', 'CCTV', 'AD02E19PAGD2438', 'Ryans Computers', '2024-11-16', '12 Month', 4200.00, 'New', '00000', '2025-05-24 11:47:04', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Smart Dual Light Fixed Bullet IP Camera', 'Dahua', 'DH-IPC-HFW1439TL1-A-IL ', 'N/A', 'N/A', 12, 'available'),
(44, 'Dahua  Eyeball Dome IP Camera', 'CCTV', 'AB07C58PAG23C77', 'Ryans Computers', '2024-11-16', '12 Month', 4200.00, 'New', '00000', '2025-05-24 11:47:34', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HDW1439V-A-IL (2.8mm) (4.0MP) Smart Dual Light Fixed Eyeball Dome IP Camera', 'Dahua ', 'DH-IPC-HDW1439V-A-IL', 'N/A', 'N/A', 12, 'available'),
(45, 'Dahua  Dome IP Camera', 'CCTV', 'AB07C58PAGD9260', 'Ryans Computers', '2024-11-16', '12 Month', 4200.00, 'New', '00000', '2025-05-24 11:48:03', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HDW1439V-A-IL (2.8mm) (4.0MP) Smart Dual Light Fixed Eyeball Dome IP Camera', 'Dahua ', 'DH-IPC-HDW1439V-A-IL ', 'N/A', 'N/A', 12, 'available'),
(46, 'Dahua Dome IP Camera', 'CCTV', 'AB07C58PAG129AA', 'Ryans Computers', '2024-11-16', '12 Month', 4200.00, 'New', '00000', '2025-05-24 11:48:27', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HDW1439V-A-IL (2.8mm) (4.0MP) Smart Dual Light Fixed Eyeball Dome IP Camera', 'Dahua ', 'DH-IPC-HDW1439V-A-IL', 'N/A', 'N/A', 12, 'available'),
(48, 'Gigabyte  Motherboard', 'Motherboard', 'SN24213A014654', 'Ryans Computers', '2024-11-07', '36 Month', 8700.00, 'New', '00000', '2025-05-24 11:50:39', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H410M H V2 DDR4 H470 Chipset 10th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte ', 'Gigabyte H410M H V2 ', 'N/A', 'N/A', 5, 'available'),
(49, 'Twinmos Desktop RAM', 'RAM', 'D8J4G082220', 'Ryans Computers', '2024-11-07', 'Product Lifetime', 2350.00, 'New', '00000', '2025-05-24 11:51:09', 'SLL', '2026-01-24 11:30:08', 'Twinmos 8GB DDR4 2666MHz Blue Desktop RAM', 'Twinmos', 'Twinmos 8GB ', 'N/A', 'N/A', 13, 'available'),
(50, 'Transcend SSD', 'Storage', 'I735240944', 'Ryans Computers', '2024-11-07', '36 Month', 5500.00, 'New', '00000', '2025-05-24 11:51:39', 'SLL', '2026-01-24 11:30:08', 'Transcend SSD225S 500GB 2.5 Inch SATAIII SSD', 'Transcend ', 'Transcend SSD225S', 'N/A', 'N/A', 4, 'available'),
(51, 'Lenovo  Monitor (HDMI, VGA)', 'Monitor', 'SV90CEAET', 'Ryans Computers', '2024-11-07', '36 Month', 7300.00, 'New', '00000', '2025-05-24 11:52:12', 'SLL', '2026-01-24 11:30:08', 'Lenovo D19-10 18.5 Inch HD Monitor (HDMI, VGA)', 'Lenovo', 'Lenovo D19-10', 'N/A', 'N/A', 3, 'available'),
(52, 'Power Guard UPS ', 'Peripheral', '240801023608', 'Ryans Computers', '2024-11-07', '12 Month', 3500.00, 'New', '00000', '2025-05-24 11:52:55', 'SLL', '2026-01-24 11:30:08', 'Power Guard PG650VA-CS 650VA Offline UPS with Plastic Body', 'Power Guard ', 'Power Guard PG650VA-CS ', 'N/A', 'N/A', 9, 'available'),
(54, 'Gigabyte  Motherboard', 'Motherboard', 'SN24173A014163', 'Ryans Computers', '2024-10-24', '36 Month', 8700.00, 'New', '00000', '2025-05-24 11:54:36', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H410M H V2 DDR4 H470 Chipset 10th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte', 'H410M ', 'N/A', 'N/A', 5, 'available'),
(55, 'Transcend Desktop RAM', 'RAM', 'I793740678', 'Ryans Computers', '2024-10-24', 'Product Lifetime', 2500.00, 'New', '00000', '2025-05-24 11:55:05', 'SLL', '2026-01-24 11:30:08', 'Transcend JetRAM 8GB DDR4 2666MHz U-DIMM Desktop RAM', 'Transcend ', 'Transcend JetRAM', 'N/A', 'N/A', 13, 'available'),
(56, 'Lenovo Laptop', 'Laptop', 'SPF50ZT08', 'Ryans Computers', '2024-10-07', '12 Month', 36000.00, 'New', '00000', '2025-05-24 11:56:10', 'SLL', '2026-01-24 11:30:08', 'Lenovo IdeaPad Slim 1i 15IGL7 Intel CDC N4020 (1.10GHz (2.80GHz Brust), 8GB DDR4, 256GB SSD, No-ODD) 15.6 Inch FHD (1920x1080) TN Antiglare Display, Free Dos, Cloud Grey Laptop', 'Lenovo', 'IdeaPad Slim 1i 15IGL7', 'N/A', 'N/A', 1, 'available'),
(57, 'Lenovo Laptop', 'Laptop', 'SPF51006J', 'Ryans Computers', '2024-10-07', '12 Month', 36000.00, 'New', '00000', '2025-05-24 11:56:31', 'SLL', '2026-01-24 11:30:08', 'Lenovo IdeaPad Slim 1i 15IGL7 Intel CDC N4020 (1.10GHz (2.80GHz Brust), 8GB DDR4, 256GB SSD, No-ODD) 15.6 Inch FHD (1920x1080) TN Antiglare Display, Free Dos, Cloud Grey Laptop', 'Lenovo', 'IdeaPad Slim 1i 15IGL7', 'N/A', 'N/A', 1, 'available'),
(59, 'Gigabyte  Motherboard', 'Motherboard', 'SN24173A013476', 'Ryans Computers', '2024-09-12', '36 Month', 8700.00, 'New', '00000', '2025-05-24 11:59:30', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H410M H V2 DDR4 H470 Chipset 10th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte ', 'Gigabyte H410M H V2 ', 'N/A', 'N/A', 5, 'available'),
(60, 'Corsair Desktop RAM', 'RAM', 'A506T42312A9Y4', 'Ryans Computers', '2024-09-12', 'Product Lifetime', 2600.00, 'New', '00000', '2025-05-24 12:00:16', 'SLL', '2026-01-24 11:30:08', 'Corsair Vengeance LPX 8GB DDR4 3200MHz Black Heatsink Desktop RAM', 'Corsair', 'Corsair Vengeance LPX ', 'N/A', 'N/A', 13, 'available'),
(61, 'Power Guard UPS', 'Peripheral', '240315011499', 'Ryans Computers', '2024-09-12', '12 Month', 3500.00, 'New', '00000', '2025-05-24 12:00:44', 'SLL', '2026-01-24 11:30:08', 'Power Guard PG650VA-CS 650VA Offline UPS with Plastic Body', 'Power Guard ', 'Power Guard PG650VA-CS ', 'N/A', 'N/A', 9, 'available'),
(64, 'Dahua PoE Switch', 'Network', '8A0366FPAJ82CB9', 'Ryans Computers', '2024-08-20', '12 Month', 6400.00, 'New', '00000', '2025-05-24 12:05:05', 'SLL', '2026-01-24 11:30:08', 'Dahua PFS3009-8ET-65 9 Port (1-Port 10/100Mbps + 8-Port Ethernet PoE) Unmanaged Switch', 'Dahua', 'Dahua PFS3009', 'N/A', 'N/A', 7, 'assigned'),
(66, 'Cudy Network Switch', 'Network', 'GS1016240900231', 'Ryans Computers', '2024-08-20', '12 Month', 5250.00, 'New', '00000', '2025-05-24 12:06:47', 'SLL', '2026-01-24 11:30:08', 'Cudy GS1016 16 Port (16-Port 10/100/1000 Ethernet) Unmanaged Network Switch', 'Cudy ', 'Cudy GS1016', 'N/A', 'N/A', 7, 'available'),
(69, 'Gigabyte  Motherboard', 'Motherboard', 'SN240460075257', 'Ryans Computers', '2024-06-25', '36 Month', 9500.00, 'New', '00000', '2025-05-24 12:23:57', 'SLL', '2026-01-24 11:30:08', 'Gigabyte GA-H110M-H DDR4 6th/7th/8th/9th Gen Intel LGA1151 Socket Motherboard', 'Gigabyte ', 'Gigabyte GA-H110M-H ', 'N/A', 'N/A', 5, 'available'),
(70, 'Gigabyte Motherboard', 'Motherboard', 'SN240460073022', 'Ryans Computers', '2024-06-25', '36 Month', 10000.00, 'New', '00000', '2025-05-24 12:24:27', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H310M M.2 2.0 DDR4 8th/9th Gen LGA1151 Socket Motherboard', 'Gigabyte ', 'Gigabyte H310M M.2 2.0', 'N/A', 'N/A', 5, 'available'),
(72, 'Gigabyte Motherboard', 'Motherboard', 'SN234360000374', 'Ryans Computers', '2024-05-13', '36 Month', 14600.00, 'New', '00000', '2025-05-24 12:34:43', 'SLL', '2026-01-24 11:30:08', 'Gigabyte B760M H DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'Gigabyte ', 'Gigabyte B760M H', 'N/A', 'N/A', 5, 'available'),
(73, 'Corsair  DDR4 Desktop RAM', 'RAM', 'A506T40709I6DD', 'Ryans Computers', '2024-05-13', 'Product Lifetime', 2550.00, 'New', '00000', '2025-05-24 12:37:11', 'SLL', '2026-01-24 11:30:08', 'Corsair Vengeance LPX 8GB DDR4 3200MHz Black Heatsink Desktop RAM', 'Corsair Vengeance ', 'Corsair Vengeance LPX ', 'N/A', 'N/A', 13, 'available'),
(74, 'TP-Link PoE Switch', 'Network', '22253A0000294', 'Ryans Computers', '2024-05-06', '24 Month', 15000.00, 'New', '00000', '2025-05-24 12:39:04', 'SLL', '2026-01-24 11:30:08', 'TP-Link TL-SL1218MP 16-Port 10/100Mbps + 2-Port Gigabit Unmanaged PoE Switch', 'TP-Link', 'TP-Link TL-SL1218MP', 'N/A', 'N/A', 7, 'available'),
(75, 'AsusWi-Fi  Router', 'Network', 'N2IG4V604697WUC', 'Ryans Computers', '2024-04-23', '24 Month', 8300.00, 'New', '000', '2025-05-24 12:40:54', 'SLL', '2026-01-24 11:30:08', 'Asus RT-AX1800HP AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Router', 'Asus ', 'Asus RT-AX1800HP', 'N/A', 'N/A', 7, 'available'),
(76, 'Acer Desktop Black Monitor', 'Monitor', 'MMTKSSS0013440BB423W01', 'Ryans Computers', '2024-02-27', '36 Month', 9000.00, 'New', '0000', '2025-05-24 12:43:26', 'SLL', '2026-01-24 11:30:08', 'Acer KA222Q Hbmix/KA222Q H 21.5 Inch FHD (1920 x 1080) VA, 100Hz Black Monitor', 'Acer ', 'KA222Q', 'N/A', 'N/A', 3, 'assigned'),
(77, 'Asus WiFi Router', 'Network', 'N4IG2ZE055477DN', 'Ryans Computers', '2023-09-03', '24 Month', 8600.00, 'New', '00000', '2025-05-24 12:45:33', 'SLL', '2026-01-24 11:30:08', 'Asus RT-AX53U AX1800 Mbps Gigabit Dual-Band WiFi 6 Router', 'Asus ', 'Asus RT-AX53U', 'N/A', 'N/A', 7, 'available'),
(78, 'Seagate 2.5 Inch SSD', 'Storage', '70G00CFA', 'Ryans Computers', '2025-05-27', '36 Month', 5499.00, 'New', '00000', '2025-05-28 10:01:11', 'SLL', '2026-01-24 11:30:08', 'Seagate BarraCuda 960GB 2.5 Inch SATAIII Internal SSD', ' Seagate ', ' Seagate BarraCuda 960GB', 'N/A', 'For- Accounts PC', 4, 'assigned'),
(79, 'HyperX DDR4 8GB RAM', 'RAM', 'MC24113868', 'Fantasy Computer', '2025-05-19', '36 Month', 2000.00, 'New', '00000', '2025-05-29 10:36:47', 'SLL', '2025-07-12 06:50:02', 'Kingston HyperX FURY DDR4 2400MHz 8GB Ram', 'Kingston Hyperx', 'HyperX FURY', 'N/A', 'N/A', 13, 'available'),
(80, 'TwinMOS DDR4 8GB RAM', 'RAM', 'TBL2E113253', 'Fantasy Computer', '2024-03-06', 'Product Lifetime', 2000.00, 'New', 'N/A', '2025-05-31 10:23:26', 'SLL', '2025-07-08 12:27:02', 'TwinMOS DDR4 8GB RAM 2666Mz', 'TwinMOS ', 'TwinMos', 'N/A', 'N/A', 13, 'available'),
(81, 'HP LED Monitor (HDMI, VGA)', 'Monitor', '3CQ4420CKL', 'Ryans Computers', '2025-06-01', '36 Month', 9450.00, 'New', '53169', '2025-06-01 09:47:31', 'SLL', '2026-01-24 11:30:08', 'HP P204v 19.5 Inch HD+ LED HDMI, VGA Monitor\r\nDisplay Resolution - 1600x900\r\nRefresh Rate (Hz) - 60Hz\r\nHDMI Port - 1\r\nDisplay Size (Inch) 19.5\r\nColor: Black', ' HP', ' HP P204v', 'N/A', 'N/A', 3, 'available'),
(82, 'HP LED Monitor (HDMI, VGA)', 'Monitor', '3CQ4420CJ7', 'Ryans Computers', '2025-06-01', '36 Month', 9450.00, 'New', '53169', '2025-06-01 09:48:12', 'SLL', '2026-01-24 11:30:08', 'HP P204v 19.5 Inch HD+ LED HDMI, VGA Monitor\r\nDisplay Resolution - 1600x900\r\nRefresh Rate (Hz) - 60Hz\r\nHDMI Port - 1\r\nDisplay Size (Inch) 19.5\r\nColor: Black', ' HP', ' HP P204v', 'N/A', 'N/A', 3, 'available'),
(83, 'Huawei  PoE Switch', 'Network', '4E23C0050669', 'Ryans Computers', '2025-06-03', '12 Month', 21750.00, 'New', '000', '2025-06-04 04:15:54', 'SLL', '2026-01-24 11:30:08', 'Number of Total Ports: 18 Port Gigabit\r\nSFP Port 02\r\nPoE+ Port: 16. \r\nData Transfer Rate: 10/100/1000 Mbps. \r\nFanless, Metal, ', ' Huawei', ' Huawei S110-16LP2SR', 'N/A', 'B1, Basement', 7, 'available'),
(84, 'Huawei  PoE Switch', 'Network', '4E23C0050510', 'Ryans Computers', '2025-06-03', '12 Month', 21750.00, 'New', '00000', '2025-06-04 04:16:25', 'SLL', '2026-01-24 11:30:08', 'Number of Total Ports: 18 Port Gigabit\r\nSFP Port 02\r\nPoE+ Port: 16. \r\nData Transfer Rate: 10/100/1000 Mbps. \r\nFanless, Metal, ', ' Huawei', ' Huawei S110-16LP2SR', 'N/A', 'PP Shade GM-Room for IP Camera.', 7, 'available'),
(85, 'Gigabyte Motherboard', 'Motherboard', '24303A029658', 'Fantasy Computer', '2025-06-16', '36 Month', 9800.00, 'New', '00000', '2025-06-16 09:46:43', 'SLL', '2025-07-12 06:37:12', 'Gigabyte H510M K V2 (HDMI) DDR4 Motherboard. Supports 11th/10th Gen Intel Processors. NVMe PCIe Gen3 x4 2280 M.2 Connector. ', 'Gigabyte ', 'H510M K V2', 'N/A', 'N/A', 5, 'available'),
(86, 'Apacer 2.5 SATA SSD', 'Storage', '142447227675', 'Fantasy Computer', '2025-06-16', '36 Month', 1650.00, 'New', '00000', '2025-06-16 12:09:57', 'SLL', '2026-02-14 10:22:03', 'Apacer 2.5inch SATA 120GB SSD (Desktop)', 'Apacer', 'Apacer', 'N/A', 'N/A', 4, 'available'),
(87, 'Dahua Bullet IP Camera', 'CCTV', 'AL019BAPAG01787', 'Ryans Computers', '2025-06-18', '12 Month', 4400.00, 'New', '00000', '2025-06-18 05:44:32', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Fixed Bullet IP Camera. Working Distance - 30 Meter. Night Vision Mode - Color.', ' Dahua ', ' Dahua DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'available'),
(88, 'Dahua Bullet IP Camera', 'CCTV', 'AD05322PAGBC74E', 'Ryans Computers', '2025-06-18', '12 Month', 4400.00, 'New', '00000', '2025-06-18 05:45:13', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1-A-IL (3.6mm) (4.0MP) Fixed Bullet IP Camera. Working Distance - 30 Meter.', ' Dahua ', ' Dahua DH-IPC-HFW1439TL1-A-IL', 'N/A', 'N/A', 12, 'available'),
(89, 'Desktop UPS', 'Peripheral', '241108062601', 'Ryans Computers', '2025-06-18', '12 Month', 3400.00, 'New', '00000', '2025-06-18 05:46:16', 'SLL', '2026-01-24 11:30:08', 'Power Guard PG650VA-CS 650VA Offline UPS. Back up time\r\nUp to 15 Minutes. ', ' Power Guard ', ' Power Guard PG650VA-PS 650VA', 'N/A', 'N/A', 9, 'available'),
(90, 'Gigabyte Desktop RAM', 'RAM', 'SN245208907431', 'Ryans Computers', '2025-06-18', 'Product Lifetime', 2000.00, 'New', '', '2025-06-18 05:46:55', 'SLL', '2026-01-24 11:30:08', ' 8GB DDR4 2666MHz Black Heatsink', 'Gigabyte', ' Gigabyte 8GB', 'na', 'na', 13, 'available'),
(91, 'KingFast SATA SSD', 'Storage', 'E020021M01898', 'Fantasy Computer', '2025-06-22', '36 Month', 1200.00, 'New', '53181', '2025-06-24 04:16:23', 'SLL', '2025-07-12 06:28:16', '128GB SATA III SSD. Built-in 4K IOPS Performance Controller. SATA3.0 6Gbps transfer interface. Built-in High-speed MLC Flash NCQ/TRIM/SMART Support.', 'KingFast', 'F6', 'N/A', 'N/A', 4, 'available'),
(92, 'Kstar 650VA UPS', 'Peripheral', 'KCS2407652345', 'Fantasy Computer', '2025-06-22', '12 Month', 3200.00, 'New', '53181', '2025-06-24 04:21:26', 'SLL', '2025-07-12 04:30:08', 'Capacity 650VA. Input Voltage 230Vac, Output Voltage 230, Battery 12V Single Pcs', 'Kstar', 'Kstar 650VA', 'N/A', 'Back up time Up to 15 Minutes', 9, 'available'),
(94, 'Speed 2.5 inch SATA SSD', 'Storage', 'SPEED2024082142', 'Fantasy Computer', '2025-06-26', '36 Month', 4300.00, 'New', '53190', '2025-06-26 11:46:45', 'SLL', '2025-07-08 12:28:13', 'Speed 2.5 inch SATA SSD', 'Speed ', 'Speed ', 'N/A', 'N/A', 4, 'available'),
(95, 'Laptop', 'Laptop', '5CD4498QPS', 'Unitech-Computer', '2025-06-28', '24 Month', 75500.00, 'New', '17004', '2025-06-30 06:33:37', 'SCL', '2026-02-14 10:19:39', '', 'Acer ', '', '', 'N/A', 1, 'available'),
(96, 'Mothearboard Gigabyte', 'Motherboard', '24006636', 'Fantasy Computer', '2025-06-30', '12 Month', 5100.00, 'Refurbished', '53190', '2025-07-02 10:07:22', 'SLL', '2025-07-08 12:29:10', 'Mothearboard Gigabyte H110 (Open) Korian', 'Gigabyte ', 'H110 (Open)', 'N/A', 'N/A', 5, 'available'),
(105, 'Network Switch 8 port', 'Network', '244C656004551', 'Renesa Info Tech BD', '2025-04-22', '12 Months', 780.00, 'New', '', '2025-07-14 06:02:21', 'SAL', '2025-07-14 06:02:21', 'Network Switch 8 port  10-100 Unmanaged', 'Tp-Link', 'TL-SF1008D Desktop Switch', '', '', NULL, 'available'),
(106, 'Acer Travel Mate P214-54', 'Laptop', 'NXVVDS10024170963B7600', 'Global Brand', '2025-04-28', '36 Month', 56000.00, 'New', '00000', '2025-07-14 06:07:30', 'SAL', '2025-07-19 04:32:55', 'Acer Travel Mate P214-54 , Core i3-1215u, Ram-8GM . SSD-1TB and display FHD -14\'.', 'Acer', 'Travel Mate P214-54', 'N/A', 'N/A', 1, 'available'),
(107, 'Router TP-Link AC Series', 'Network', '224A039R00105', 'Renesa Info Tech BD', '2025-05-25', '365 Days', 4100.00, 'New', '', '2025-07-14 06:10:32', 'SAL', '2025-07-14 06:10:32', 'Router TP-Link AC Series DECO S7 AC1900', 'Tp-Link', 'DECO S7 AC1900', '', '', NULL, 'available'),
(108, 'CCTV IP Camera 4MP', 'CCTV', 'FM5610123', 'Renesa Info Tech BD', '2025-06-02', '365 Days', 4450.00, 'New', '', '2025-07-14 06:12:53', 'SAL', '2025-07-14 06:12:53', 'CCTV IP Camera 4MP Bullet - DS-2CD1043G2-LIU', 'Hikvision', 'DS-2CD1043G2-LIU', '', '', NULL, 'available'),
(109, 'Laptop RAM DDR-4', 'RAM', '8550307000301', 'Renesa Info Tech BD', '2025-06-28', '04 Month', 1900.00, 'New', '', '2025-07-14 06:15:34', 'SAL', '2026-02-14 10:13:02', 'Laptop RAM Kingspec DDR4-8G-266Mhz', 'Kingspec', 'Kingspec DDR4-8G-266Mhz', '', '', 13, 'available'),
(110, 'CCTV IP Camera 4MP', 'CCTV', 'FM5610105', 'Renesa Info Tech BD', '2025-06-29', '365 Days', 4550.00, 'New', '', '2025-07-14 06:17:05', 'SAL', '2025-07-14 06:17:05', 'CCTV IP Camera 4MP Bullet  DS-2CD1043G2-LIU', 'Hikvision', 'DS-2CD1043G2-LIU', '', '', NULL, 'available'),
(111, 'Router TP-Link AX Series', 'Network', '22433T2005621', 'Renesa Info Tech BD', '2025-06-29', '365 Days', 6100.00, 'New', '', '2025-07-14 06:18:49', 'SAL', '2025-07-14 06:18:49', 'Router TP-Link AX Series AX3000 Archer AX53', 'Tp-Link', 'AX3000 Archer AX53', '', '', NULL, 'available'),
(112, 'Battery', 'Peripheral', 'N/A', 'Fantasy Computer', '2025-07-06', '00', 60.00, 'New', '17006', '2025-07-15 05:28:25', 'SCL', '2026-01-25 03:20:35', 'CMOS Battery', 'Maxel', 'CR1220', 'N/A', '2 Pcs, 60*2=120 Tk.', 9, 'available'),
(117, 'Toner', 'Peripheral', '', '', '2025-07-08', '', 1150.00, 'New', '17006', '2025-07-16 04:17:17', 'SCL', '2025-07-16 04:17:17', 'Toner-26A', 'China', '26A', '', '', NULL, 'available'),
(118, 'Toner 78A', 'Peripheral', '', '', '2025-07-08', '', 700.00, 'New', '17006', '2025-07-16 04:18:30', 'SCL', '2025-07-16 04:18:30', 'Toner 78A', 'China', 'Toner 78A', '', '', NULL, 'available'),
(119, 'USB Hub', 'Peripheral', '', 'Fantasy Computer', '2025-07-08', NULL, 350.00, 'New', '', '2025-07-16 04:20:03', 'SCL', '2026-01-22 09:39:39', 'USB Hub 4 Port', '', '4 Port', '', '', 9, 'available'),
(120, 'Toner 05A', 'Peripheral', '', '', '2025-07-08', '', 1800.00, 'New', '17006', '2025-07-16 04:21:03', 'SCL', '2025-07-30 12:53:42', 'Toner 05A', 'China', 'Toner 05A', '', '', NULL, 'available'),
(121, 'Ribbon', 'Peripheral', '', '', '2025-07-13', '', 3300.00, 'New', '17006', '2025-07-16 04:23:20', 'SCL', '2025-07-16 04:23:20', 'Data Card Ribbon -Black', 'Data Card', 'Data Card -Black', '', '', NULL, 'available'),
(122, 'Dahua PTZ  IP Camera', 'CCTV', '9L09902PAJ8420B', 'Ryans Computers', '2025-07-22', '12 Month', 67500.00, 'New', '53962', '2025-07-23 02:37:56', 'SLL', '2026-01-24 11:30:08', 'Mega Pixel: 4.0, Form-Factor - Dome, Usage - Outdoor, Working Distance - 150 Meter, Max-Resolution: 2560 x 1440,', 'Dahua', 'DH-SD5A432GB-HNR', 'N/A', 'Purchase From Head Office', 12, 'assigned'),
(123, 'Dahua PTZ IP Camera', 'CCTV', 'AD0D109PAJ8DAEA', 'Ryans Computers', '2025-07-22', '12 Month', 67500.00, 'New', '53962', '2025-07-23 02:39:27', 'SLL', '2026-01-24 11:30:08', 'Mega Pixel: 4.0, Form-Factor - Dome, Usage - Outdoor, Working Distance - 150 Meter, Max-Resolution: 2560 x 1440,', 'Dahua', 'DH-SD5A432GB-HNR', 'N/A', 'Purchase From Head Office', NULL, 'assigned'),
(124, 'Dahua Fixed Bullet IP Camera', 'CCTV', 'AL04739PAG2FC3D', 'Ryans Computers', '2025-07-22', '12 Month', 3700.00, 'New', '53962', '2025-07-23 02:40:49', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1P-A-IL (3.6mm) (4.0MP) Entry Smart Dual Light Fixed Bullet IP Camera (Built in Audio)', 'Dahua', 'DH-IPC-HFW1439TL1P-A-IL', 'N/A', 'Purchase From Head Office', NULL, 'assigned'),
(125, 'Dahua Fixed Bullet IP Camera', 'CCTV', 'AL04739PAG7F46F', 'Ryans Computers', '2025-07-22', '12 Month', 3699.98, 'New', '53962', '2025-07-23 02:42:05', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HFW1439TL1P-A-IL (3.6mm) (4.0MP) Entry Smart Dual Light Fixed Bullet IP Camera (Built in Audio)', 'Dahua', 'DH-IPC-HFW1439TL1P-A-IL', 'N/A', 'Purchase From Head Office', NULL, 'assigned'),
(126, 'Netgear Gigabit PoE  Switch', 'Network', '76E14AD2010F1', 'Ryans Computers', '2025-07-22', 'Product LifeTime', 11500.00, 'New', '53962', '2025-07-23 02:43:55', 'SLL', '2026-01-24 11:30:08', 'Netgear GS108LP 8 Port ProSafe Gigabit PoE Unmanaged Rackmount Switch (8Port PoE+)', 'Netgear', 'GS108LP', 'N/A', 'Purchase From Head Office', NULL, 'available'),
(127, 'Netgear Gigabit PoE  Switch', 'Network', '76E14ADL0112A', 'Ryans Computers', '2025-07-22', 'Product Lifetime', 11500.00, 'New', '53962', '2025-07-23 02:45:03', 'SLL', '2026-01-24 11:30:08', 'Netgear GS108LP 8 Port ProSafe Gigabit PoE Unmanaged Rackmount Switch (8Port PoE+)', 'Netgear', 'GS108LP', 'N/A', 'Purchase From Head Office', NULL, 'available'),
(128, 'Desktop Monitor 21.5\" FHD IPS', 'Monitor', 'S22IFR100', 'Ryans Computers', '2025-01-28', '36 Month', 8800.00, 'New', '', '2025-07-27 06:37:01', 'HO', '2026-02-14 10:22:03', '21.5 Value Top Black Monitor for Saiful Sir\r\nMPN: S22IFR100\r\nModel: S22IFR100\r\nResolution: FHD (1920×1080)\r\nDisplay: IPS, 100Hz, 5ms\r\nPorts: HDMI, VGA, Audio in, DC\r\nFeatures: VESA Wall Mount, Built-in Speaker', 'Value Top', 'S22IFR100 21.5', '', 'Saiful Sir', 3, 'available'),
(129, 'Acer Travelmate', 'Laptop', 'P214-53', 'Ryans Computers', '2025-05-20', '36 Month', 60500.00, 'New', '', '2025-07-27 06:54:29', 'HO', '2026-01-24 11:30:08', 'Acer TravelMate P214-53 11th Gen Intel Core i5 1135G7 (2.40GHz-4.20GHz, 8GB\r\nDDR4, 512GB SSD, No-ODD) 14 Inch FHD for Mahmudul Hasan Masum (Import Dept)', 'Acer', 'Acer TravelMate P214-53', '', 'Mahmudul Hasan Masum', 1, 'available'),
(130, 'Canon LBP 325x', 'Printer', 'NPQA-014850', 'J.A.N Associates', '2025-06-29', '12 Month', 89000.00, 'New', '', '2025-07-27 07:02:35', 'HO', '2025-08-02 09:28:19', 'Canon LBP 325x for Cash Room (Old used to Import Team)', 'Canon', 'LBP 325x', '', '', 6, 'available'),
(132, 'Dell 19.5 Monitor', 'Monitor', '13072025', 'Tech Express', '2025-07-13', '36 Month', 9500.00, 'New', '', '2025-07-27 07:16:43', 'HO', '2025-08-04 12:23:53', 'Dell E2020H 19.5 inch Monitor', 'Dell', 'Dell E2020H', 'N/A', 'Old monitor death and replace to new monitor.', 3, 'available'),
(133, 'Processor', 'Desktop', '851D04D403717', 'Ryans Computers', '2025-07-27', '36 Month', 18500.00, 'New', '53964', '2025-07-28 10:43:37', 'SLL', '2026-01-24 11:30:08', 'Intel Core i5 14th Gen Raptor Lake 14400 Up to 4.70GHz, 10 Core, 20MB Cache LGA1700 Socket Processor.', 'Intel', 'Core i5 14th Gen Raptor Lake 14400', 'N/A', 'Purchase From Head Office', NULL, 'assigned'),
(134, 'NVMe SSD', 'Storage', 'M2NVMECYD505061537', 'Ryans Computers', '2025-07-27', '60 Month', 4700.00, 'New', '53964', '2025-07-28 10:45:27', 'SLL', '2026-01-24 11:30:08', 'Twinmos AlphaPro 512GB M.2 2280 PCIe 3.0 x4 NVMe SSD', 'Twinmos', 'AlphaPro', 'N/A', 'Purchase From Head Office', NULL, 'assigned'),
(135, 'Desktop Monitor', 'Monitor', '202503_X22IF00321', 'Ryans Computers', '2025-07-27', '36 Month', 10000.00, 'New', '53964', '2025-07-28 10:47:11', 'SLL', '2026-01-24 11:30:08', 'Value Top X22IFR100 21.5 Inch FHD (1920x1080) IPS 100Hz Professional Monitor (HDMI & VGA)', 'Value Top', 'Value Top X22IFR100', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(136, 'Desktop Case', 'Desktop', 'RY26121753', 'Ryans Computers', '2025-07-27', '24 Month', 3600.00, 'New', '53964', '2025-07-28 10:49:12', 'SLL', '2026-01-24 11:30:08', 'Golden Field HONOR 2 Mid Tower (Acrylic Side\r\nWindow) White ATX Gaming Desktop Case with\r\nStandard PSU 07.02.068.82\r\nNo Warranty (2 Year Only for PSU)', 'Golden Field', 'HONOR 2', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(137, 'Desktop UPS 1200VA', 'Peripheral', '52000058', 'Ryans Computers', '2025-07-27', '12 Month', 6500.00, 'New', '53964', '2025-07-28 10:50:12', 'SLL', '2026-01-24 11:30:08', 'Digital X 1200VA Offline UPS with Plastic\r\nBody', 'Digital X', '1200VA', 'N/A', 'Purchase By Head Office', 9, 'assigned'),
(139, 'Gigabyte DDR5 Motherboard', 'Motherboard', 'SN24523A054304', 'Ryans Computers', '2025-07-27', '36 Month', 11200.00, 'New', '53964', '2025-07-28 10:52:43', 'SLL', '2026-01-24 11:30:08', 'Gigabyte H610M H V2 DDR5 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'Gigabyte', 'H610M H V2', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(140, 'Desktop DDR5 RAM', 'RAM', 'RY26093299', 'Ryans Computers', '2025-07-27', 'Product Lifetime', 3750.00, 'New', '53964', '2025-07-28 10:55:35', 'SLL', '2026-01-24 11:30:08', 'Kingston Fury Beast 8GB DDR5 5600MHz Black Heatsink Desktop RAM', 'Kingston', 'Fury Beast', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(141, 'Intel Desktop Processor', 'Desktop', 'U4LX273300488', 'Ryans Computers', '2025-07-27', '36 Month', 11450.00, 'New', '53964', '2025-07-28 11:03:30', 'SLL', '2026-01-24 11:30:08', 'Intel 12th Gen Alder Lake Core i3 12100 3.30GHz-4.30GHz, 4 Core, 12MB Cache LGA1700 Socket Processor', 'Intel', 'Core i3 12100', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(142, 'Asus Desktop Motherboard', 'Motherboard', 'T3M0KC040379NJ7', 'Ryans Computers', '2025-07-27', '36 Month', 10100.00, 'New', '53964', '2025-07-28 11:06:45', 'SLL', '2026-01-24 11:30:08', 'Asus PRIME H610M-F D4 R2.0 DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'Asus', 'H610M-F D4 R2.0', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(143, 'Desktop DDR4 RAM', 'RAM', '1P0902198237', 'Ryans Computers', '2025-07-27', 'Product Lifetime', 2650.00, 'New', '53964', '2025-07-28 11:07:42', 'SLL', '2026-01-24 11:30:08', 'Adata XPG Gammix D30 8GB DDR4 3200MHz Red Edition Heatsink Gaming Desktop RAM', 'Adata', 'XPG Gammix D30', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(144, 'Desktop M.2  NVMe SSD', 'Storage', 'M2NVMECYD505061435', 'Ryans Computers', '2025-07-27', '60 Month', 4700.00, 'New', '53964', '2025-07-28 11:09:34', 'SLL', '2026-01-24 11:30:08', 'Twinmos AlphaPro 512GB M.2 2280 PCIe 3.0 x4 NVMe SSD #NVMe512GB2280AP-5Y (3600MB/s & 3250MB/s)', 'Twinmos', 'AlphaPro 512GB', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(145, 'Dell Monitor (HDMI, VGA)', 'Monitor', 'JW7CN54', 'Ryans Computers', '2025-07-27', '36 Month', 10500.00, 'New', '53964', '2025-07-28 11:11:25', 'SLL', '2026-01-24 11:30:08', 'Dell D2020H 20 Inch (19.5 Inch Diagonal) HD+ (1600x900) TN Black Monitor (HDMI, VGA)', 'Dell', 'D2020H', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(146, 'Desktop Case', 'Desktop', 'RYG1203023', 'Ryans Computers', '2025-07-27', '12 Month', 2750.00, 'New', '53964', '2025-07-28 11:13:18', 'SLL', '2026-01-24 11:30:08', 'Value Top TU100M Mid Tower Black Micro-ATX Desktop\r\nCase with Standard PSU 07.01.130.229\r\nNo Warranty (2 Year Only for PSU)', 'Value Top', 'TU100M', 'N/A', 'Purchase By Head Office', NULL, 'available'),
(147, 'Digital X 650VA Offline UPS', 'Peripheral', '41902278', 'Ryans Computers', '2025-07-27', '12 Month', 3400.00, 'New', '53964', '2025-07-28 11:14:13', 'SLL', '2026-01-24 11:30:08', 'Digital X 650VA Offline UPS with Plastic\r\nBody 48.06.063.01\r\n1 Year (Box Mandatory While Claiming)', 'Digital X', '650VA', 'N/A', 'Purchase By Head Office', 9, 'available'),
(149, 'Canon ImageClass', 'Printer', '866519', 'Ryans Computers', '2025-07-19', '12 Month', 15500.00, 'New', '', '2025-07-30 03:44:02', 'SDL', '2026-01-24 11:30:08', 'Printer', 'Canon', 'LBP6030', '', 'Finishing RFID', 6, 'available'),
(150, 'Canon ImageClass', 'Printer', '52951', 'Ryans Computers', '2025-07-19', '12 Month', 15500.00, 'New', '', '2025-07-30 03:51:29', 'SDL', '2026-01-24 11:30:08', 'printer', 'Canon', 'LBP6030', '', 'Fabricinspection SDL-B2', 6, 'available'),
(151, 'Apollo Offline UPS', 'Peripheral', '52015', 'Ryans Computers', '2025-04-28', '12 Month', 6500.00, 'New', '2911753', '2025-07-30 04:43:18', 'SDL', '2026-01-24 11:30:08', 'UPS', 'Apollo', 'Apollo 1120F/1120 1200VA', '', '', NULL, 'available'),
(152, 'Apollo Offline UPS', 'Peripheral', '52025', 'Ryans Computers', '2025-04-24', '12 Month', 6500.00, 'New', '2911753', '2025-07-30 04:45:08', 'SDL', '2026-01-24 11:30:08', 'UPS', 'Apollo', 'Apollo 1120F/1120 1200VA', '', '', NULL, 'available'),
(153, 'Epson EB-W52 (4000 Lumens) WXGA 3LCD Projector', 'Peripheral', '300102', 'Ryans Computers', '2025-07-19', '24 Months', 82000.00, 'New', '1272128', '2025-07-30 04:55:12', 'SDL', '2026-01-24 11:30:08', 'Projector', 'Epson', 'Epson EB-W52', '', 'Training Room SDL-B2', NULL, 'available'),
(154, 'Apollo Offline UPS', 'Peripheral', '3272', 'Ryans Computers', '2025-07-19', '12 Months', 6800.00, 'New', '3023679', '2025-07-30 05:03:26', 'SDL', '2026-01-24 11:30:08', 'UPS', 'Apollo', 'Apollo 1120F', '', 'QS', NULL, 'available'),
(158, 'Cudy Network Switch', 'Network', 'FS1010PG240100680', 'Ryans Computers', '2025-07-21', '12 Month', 4100.00, 'New', '0000', '2025-07-31 08:30:52', 'SDL', '2026-01-24 11:30:08', 'Cudy FS1010PG 10 Port (8-Port 10/100Mbps PoE+ & 2-Port 10/100/1000Mbps Uplink) Network Switch', 'Cudy', 'Cudy FS1010PG', 'N/A', 'Purchase by neamotullah', NULL, 'available'),
(159, '8GB DDR3 1600MHz Desktop RAM', 'CCTV', '25283901255', 'Ryans Computers', '2025-07-19', '00 Month', 1950.00, 'New', '', '2025-07-31 10:19:09', 'SDL', '2026-02-14 10:16:53', 'RAM', 'G.skill', '#F3-1600C11S-8GNT', '', 'Niyamutulla', 12, 'available'),
(160, '8GB DDR3 1600MHz Desktop RAM', 'RAM', '25283901245', 'Ryans Computers', '2025-07-19', '00 Month', 1950.00, 'New', '', '2025-07-31 10:20:48', 'SDL', '2026-02-14 10:18:16', 'RAM', 'G.skill', '#F3-1600C11S-8GNT', '', 'Niyamutulla', 13, 'available'),
(161, 'PowerEdge R360 Server', 'Server', 'FW3H554', 'Smart Technologies', '2025-08-03', '36 Month', 380000.00, 'New', '53977', '2025-08-04 12:20:15', 'SLL', '2025-08-06 13:06:42', 'Processor: Intel Xeon E-2434 3.4G, 4C/8T, 12M Cache\r\nChipset: C256\r\nRAM: 32GB Memory UDIMM-5600MT/s DDR5\r\nHard Drives:  2 x Dell 2TB 7.2K RPM SATA 6Gbps 3.5in Hot-plug Hard Drive\r\nHard Drive Bays: Up to 4 x 3.5 hot-plug SATA, SAS\r\nPower Supply: Dual Hot Plug Redundant Power Supplies 600W', 'Dell EMC', 'PowerEdge R360', 'Express Service Code: 34592491336', 'Server For HR-Payroll Software. Purchase by Head Office.', 8, 'available'),
(162, 'Huawei Wi-Fi Router', 'Network', '2150083928EGP7003813', 'Ryans Computers', '2025-08-06', '12 Month', 2750.00, 'New', '53979', '2025-08-09 03:16:19', 'SLL', '2026-01-24 11:30:08', 'Huawei WA8021V5 AC1200 Mbps Gigabit Dual-Band Wi-Fi Mesh Router', 'Huawei', 'Huawei WA8021V5', 'GM-Use', 'Purchase for Gishan (GM) Sir', NULL, 'assigned'),
(163, 'Dahua Dome IP Camera', 'CCTV', 'AL08AD8PAG9883D', 'Ryans Computers', '2025-08-06', '12 Month', 3500.00, 'New', '53977', '2025-08-09 03:18:15', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HDW1239T1-A-IL (2.8mm) (2.0MP) Smart Dual Light Dome IP Camera (Built in Audio)', 'Dahua', 'DH-IPC-HDW1239T1-A-IL', 'N/A', 'N/A', NULL, 'assigned'),
(164, 'Dahua Dome IP Camera', 'CCTV', 'AL08AD8PAG75B64', 'Ryans Computers', '2025-08-06', '12 Month', 3500.00, 'New', '53977', '2025-08-09 03:19:09', 'SLL', '2026-01-24 11:30:08', 'Dahua DH-IPC-HDW1239T1-A-IL (2.8mm) (2.0MP) Smart Dual Light Dome IP Camera (Built in Audio)', 'Dahua', 'DH-IPC-HDW1239T1-A-IL', 'N/A', 'N/A', NULL, 'assigned'),
(165, 'Desktop Processor', 'Desktop', 'U4EM117701202', 'Unitech-Computer', '2025-08-13', '36 Month', 10800.00, 'New', '53985', '2025-08-14 08:59:15', 'SLL', '2026-01-24 11:32:50', 'Na', 'Intel', 'Core i3 12100', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(166, 'Gigabyte Motherboard', 'Motherboard', 'SN251960002907', 'Unitech-Computer', '2025-08-13', '36 Month', 10199.99, 'New', '53985', '2025-08-14 09:01:19', 'SLL', '2026-01-24 11:32:50', 'DDR4, Dual RAM slot with M.2 Slot', 'Gigabyte', 'H610M K', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(167, 'Desktop RAM', 'RAM', '1P0902199303', 'Unitech-Computer', '2025-08-13', 'Product Lifetime', 3400.00, 'New', '53985', '2025-08-14 09:03:41', 'SLL', '2026-01-24 11:32:50', 'AData 8GB DDR4 3200MHz Desktop RAM', 'A Data', '8GBDDR4', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(168, 'M.2 SSD', 'Storage', 'WAR-512JB250669724303105', 'Unitech-Computer', '2025-08-13', '36 Month', 4500.00, 'New', '53985', '2025-08-14 09:06:30', 'SLL', '2026-01-24 11:32:50', 'Warrior 512GB NVME M.2 PCIe SSD Drive', 'Warrior', '512NVMe', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(169, 'CPU Case V200', 'Peripheral', 'N/A', 'Unitech-Computer', '2025-08-13', '24 Month', 3000.00, 'New', '53985', '2025-08-14 09:08:24', 'SLL', '2026-01-24 11:32:50', 'Only PSU warranty', 'Value Top', 'V200', 'N/A', 'Purchase By Head Office', 9, 'assigned'),
(171, 'HP LED Monitor (HDMI, VGA)', 'Monitor', '3CQ50709V5', 'Ryans Computers', '2025-09-13', '36 Month', 12000.00, 'New', '53268', '2025-09-14 03:16:39', 'SLL', '2026-01-24 11:30:08', 'HP P204v 19.5 Inch HD+ LED Monitor (HDMI, VGA)\r\n#5RD66AA 08.01.020.71\r\n3 Year (Box Mandatory While Claiming)', 'HP', 'HP P204v', 'N/A', 'For- Rajakini Terminal (B2)', NULL, 'assigned'),
(172, 'Lenovo HD Monitor (HDMI, VGA)', 'Monitor', 'SV90EHZDE', 'Ryans Computers', '2025-09-13', '36 Month', 8000.00, 'New', '53268', '2025-09-14 03:18:12', 'SLL', '2026-01-24 11:30:08', 'Lenovo D19-10 18.5 Inch HD Monitor (HDMI, VGA)\r\n#61E0KAR6WW/65F9KCC6SA/66A0KAC6IN 08.01.200.10\r\n3 Year (Box Mandatory While Claiming)', 'Lenovo', 'Lenovo D19-10', 'N/A', 'Purchase By Head Office', NULL, 'assigned'),
(173, 'Lenovo Thinkpad', 'Laptop', 'PF5G6Q3L', 'Global Brand', '2025-09-11', '36 Month', 94500.00, 'New', '53265', '2025-09-14 13:12:27', 'SLL', '2026-02-14 10:22:03', 'Processor Core: 14 Cores, CPU Cache: 18MB, RAM: 16 GB (DDR5), Storage: 512GB NvME SSD, Bluetooth:  5.3, Color: Graphite Black, MIL-STD-810H military test passed', 'Lenovo', 'ThinkPad E14', 'N/A', 'Purchase By Head Office. For Fire Manager Humayun Kabir', 1, 'assigned'),
(174, 'Cudy Access Point', 'Network', 'AP1300251001420', 'Ryans Computers', '2025-09-17', '12 Month', 4600.00, 'New', '00000', '2025-09-17 06:46:40', 'SLL', '2026-01-24 11:30:08', 'Cudy AP1300 (Indoor) Wi-Fi 5 AC1200 Mbps Wireless Dual Band Access Point', 'Cudy', 'AP1300', 'N/A', 'Purchase for HR-Admin-Compliance-Accounts Department.', NULL, 'available'),
(175, 'Asus Expertbook core i7', 'Laptop', 'DG7GMGF0L4TL', 'Globla Brand Banani', '2025-08-20', '36 Month', 124500.00, 'New', 'SDL-10-25', '2025-10-05 06:48:03', 'HO', '2026-02-14 10:22:03', 'SSD 1.5 Gb, RAM-16GB, Genuine OS 11 (Shahajahan Sir)', 'Asus', 'ASUS EXPERTBOOK P1 > P1503CVA-S71118', '', 'Genuine Windows 24000/-', 1, 'available'),
(176, 'Epson DS 530 II Document Scanner', 'Peripheral', 'B11B261202', 'Ryans Computers', '2025-10-21', '12 Month', 51000.00, 'New', 'Q-651345C', '2025-10-05 06:59:10', 'HO', '2026-02-14 10:15:32', 'Epson DS-530 II Color Duplex Document Scanner', 'Epson', 'Epson DS 530', 'N/A', 'Hadayet, Masum (Import)', 9, 'available'),
(177, 'Epson L130 Color printer', 'Printer', 'xi958y', 'Com-Culus System & Innovation', '2025-10-07', '12 Month', 13000.00, 'New', '', '2025-10-05 07:02:59', 'HO', '2026-02-14 10:15:32', 'Color Printer for (Bishkhali Dreadgers Ltd)', 'Epson', 'L-130', '', 'BDL Mahmud', NULL, 'available'),
(178, 'Epson L-130 Printer', 'Printer', 'C11CE58504', 'Ryans Computers', '2025-07-02', '12 Month', 13500.00, 'New', 'Q-637789C', '2025-10-05 07:09:22', 'HO', '2026-02-14 10:15:32', 'Espon color printer', 'Epson', 'L-130', '', 'Export Team Common', 6, 'available'),
(179, 'VoIP Telephone', 'Peripheral', 'Q4J223A011399', 'Ryans Computers', '2025-10-05', '12 Month', 4600.00, 'New', '53292', '2025-10-07 12:30:10', 'SLL', '2026-01-24 11:30:08', 'Fanvil X303P 4-SIP PoE IP Phone Without Adapter', 'Fanvil', 'Fanvil X303P', 'N/A', 'For- New GM (AHRC)', NULL, 'assigned'),
(180, 'Acer Travelmate P214-54 Laptop', 'Laptop', 'NXVVDSI0044170B8297600', 'Global Brand', '2025-10-04', '36 Month', 71000.00, 'New', '53292', '2025-10-07 13:02:43', 'SLL', '2025-10-07 13:05:02', '12th Generation Intel® Core i5-1235U, 8GB 3200MHz DDR4, 1 TB, PCIe Gen4, 16 Gb/s, NVMe SSD, Gigabit EtherneT,  14.0\" IPS Full HD Display, YES (Finger Print Reader)', 'Acer', 'TRAVELMATE P214-54', 'N/A', 'Purchase for Bodiuz Zaman (GM) AHRC', NULL, 'assigned'),
(181, 'HP Laser  Printer (WiFi)', 'Printer', 'CNB1S8FD7L', 'Ryans Computers', '2025-10-09', '12 Month', 15500.00, 'New', '53294', '2025-10-11 11:41:33', 'SLL', '2026-01-24 11:30:08', 'HP Laser 1008w Single Function Mono Laser Printer. \r\nBox Mandatory While Claiming', 'HP', 'HP 1008w', 'N/A', 'Purchase for Bodiuz Zaman (GM) AHRC', NULL, 'assigned'),
(182, 'Canon LBP 325x', 'Printer', 'NPQA-014860', 'J.A.N Associates', '2025-10-09', '12 Month', 88000.00, 'New', '53276', '2025-10-11 11:47:46', 'SLL', '2025-10-11 11:47:46', 'Canon LBP-325X Single-Function Mono Laser Printer\r\nUSB, LAN, Print Resolution: 600 x 600 dpi, Duty Cycle up to (Yield): 150,000 pages', 'Canon', 'LBP 325X', 'N/A', 'Purchase For HR Payroll', NULL, 'available'),
(183, 'RAM', 'RAM', '112443106953', 'Ryans Computers', '2025-10-14', NULL, 3500.00, 'New', '', '2025-10-15 04:41:28', 'SDL', '2026-01-24 11:30:08', 'RAM', 'Apacer', '8GB DDR3 1600MHz Desktop', 'N/A', 'Purchases for SDL', 13, 'available'),
(184, 'MONITOR', 'Monitor', 'GSVHP14', 'Ryans Computers', '2025-10-15', '36 Month', 10200.00, 'New', '', '2025-10-15 04:44:18', 'SDL', '2026-02-14 10:22:03', 'DELL MONITOR', 'DELL', 'E2020H', 'N/A', 'Purchases for SDL', 3, 'available'),
(185, 'Dell Monitor', 'Monitor', 'B74JP14', 'Ryans Computers', '2025-10-15', '36 Month', 10200.00, 'New', '', '2025-10-15 04:45:47', 'SDL', '2026-02-14 10:22:03', 'Dell E2020H 20 Inch HD+ (1600x900) LED Monitor (DP, VGA)', 'DELL', 'Dell E2020H 20 Inch HD+', 'N/A', 'Purchases for SDL', 3, 'available'),
(186, 'Motherboard', 'Motherboard', '244860058211', 'Amana International Ltd', '2025-12-10', '36 Month', 8500.00, 'New', '', '2025-10-18 11:11:21', 'SDL', '2026-02-14 10:22:03', 'Motherboard H310 M.2 9th GEN Micro ATX', 'Gigabyte', 'H310 M.2 9th GEN Micro ATX', 'N/A', 'Purchases For SDL', 5, 'available'),
(187, 'Apollo Offline UPS', 'Peripheral', '310044584F2049300999', 'Amana International Ltd', '2025-12-10', '12 Month', 3500.00, 'New', '', '2025-10-18 11:13:58', 'SDL', '2026-02-14 10:15:32', 'Apollo 1120F/1120 650VA UPS', 'Apollo', 'Apollo 1120F/1120 650VA', 'N/A', 'Purchases for SDL', 9, 'available'),
(188, 'Apollo Offline UPS', 'Peripheral', '310044584F89241504244', 'Amana International Ltd', '2025-12-10', '12 Month', 3500.00, 'New', '', '2025-10-18 11:15:36', 'SDL', '2026-02-14 10:15:32', 'Apollo 1120F/1120 650VA UPS', 'Apollo', 'Apollo 1120F/1120 650VA', 'N/A', 'Purchases For SDL', 9, 'available'),
(189, 'Processor', 'Peripheral', 'U22K70Q700953', 'Amana International Ltd', '2025-10-15', '36 Month', 11500.00, 'New', '', '2025-10-18 11:19:09', 'SDL', '2026-02-14 10:22:03', 'Intel i3 10th GEN 3.7GHZ 6MB CACHE LGA1200 Processor', 'Intel', 'Intel i3 10th GEN 3.7GHZ 6MB CACHE LGA1200', 'N/A', 'Purchases for SDL', 9, 'available'),
(190, 'Processor', 'Peripheral', '82H46F500249', 'Amana International Ltd', '2025-10-15', '36 Month', 11500.00, 'New', '', '2025-10-18 11:21:09', 'SDL', '2026-02-14 10:22:03', 'Processor', 'Intel', 'Intel i3 10th GEN 3.7GHZ 6MB CACHE LGA1200', '', 'Purchases for SDL', 9, 'available'),
(191, 'SSD', 'Storage', 'Y5VW006746T', 'Ryans Computers', '2025-10-13', '36 Month', 7400.00, 'New', '', '2025-10-19 10:34:26', 'SDL', '2026-02-14 10:22:03', 'SSD', 'Netac', 'N930E PRO  512GB M.2 NVMe', 'N/A', 'Purchase for SDL', 4, 'available'),
(192, 'Processor', 'Peripheral', '82H46F7500249', 'Ryans Computers', '2025-10-18', '36 Month', 11500.00, 'New', '', '2025-10-21 06:54:26', 'SDL', '2026-02-14 10:22:03', 'Intel i3 10th GEN 3.7GHZ 8MB CACHE LGA1200 Processor', 'Intel', 'Intel i3 10th GEN 3.7GHZ 8MB CACHE LGA1200', 'N/A', 'Purchases for SDL', 9, 'available'),
(193, 'RAM', 'RAM', 'CL-W252-CA00SW-AUR000037', 'Ryans Computers', '2025-10-18', '60 Month', 5800.00, 'New', '', '2025-10-21 06:59:23', 'SDL', '2026-02-14 10:24:40', '8GB DDR34 1600MHz Desktop RAM', 'TT Thermaltake', '8GB DDR34 1600MHz Desktop RAM', 'N/A', 'Purchases for SDL', 13, 'available'),
(194, 'RAM', 'RAM', 'CL-W252-CA005W-AUR000037', 'Ryans Computers', '2025-10-18', '60 Month', 5800.00, 'New', '', '2025-10-21 11:06:58', 'SDL', '2026-02-14 10:24:40', 'RAM', 'TT Thermaltake', 'DDR4 3200 8GB', 'N/A', 'Purchases for SDL', 13, 'available'),
(195, 'Motherboard', 'Motherboard', '25243A008906', 'Ryans Computers', '2025-10-18', '36 Month', 6900.00, 'New', '', '2025-10-22 08:15:17', 'SDL', '2026-02-14 10:22:03', 'Motherboard', 'Gigabyte', 'i3 10 gen LGA1200 M.2', 'N/A', 'Purchases for SDL', 5, 'available'),
(196, 'NVMe', 'Storage', 'Y5VW006711T', 'Ryans Computers', '2025-10-18', '36 Month', 3600.00, 'New', '', '2025-10-22 08:17:48', 'SDL', '2026-02-14 10:22:03', 'SSD', 'Netac', 'N930E PRO  512GB M.2 NVMe', 'N/A', 'Purchase for SDL', 4, 'available'),
(197, 'Motherboard', 'Motherboard', '25243A008908', 'Ryans Computers', '2025-10-18', '36 Month', 8500.00, 'New', '', '2025-10-22 08:21:01', 'SDL', '2026-02-14 10:22:03', 'Motherboard', 'Gigabyte', 'i3 10 gen LGA1200 M.2', 'N/A', 'Purchase For SDL', 5, 'available'),
(198, 'Network Switch PoE', 'Peripheral', 'FS1010PG233600133', 'Ryans Computers', '2025-10-23', '12 Month', 3900.00, 'New', '', '2025-10-25 04:26:54', 'SDL', '2026-02-14 10:15:32', 'Cudy FS1010PG 10 Port (8-Port 10/100Mbps PoE+ & 2-Port 10/100/1000Mbps Uplink) Network Switch', 'Cudy', 'Cudy FS1010PG 10PORT', 'N/A', 'Purchase For SDL-B1', 9, 'available'),
(199, 'Network Switch PoE', 'Peripheral', 'FS1010PG233600187', 'Ryans Computers', '2025-10-23', '12 Month', 3900.00, 'New', '', '2025-10-25 04:29:12', 'SDL', '2026-02-14 10:15:32', 'PoE Switch', 'Cudy', 'Cudy FS1010PG 10PORT', 'N/A', 'Purchase For SDL-B1', 9, 'available'),
(200, 'IP Camera', 'CCTV', 'FN1113078', 'Ryans Computers', '2025-10-23', '24 Month', 5600.00, 'New', '', '2025-10-25 04:33:44', 'SDL', '2026-02-14 10:19:39', 'CCTV', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchases for SDL', 12, 'available'),
(201, 'IP Camera', 'CCTV', 'FN1113082', 'Ryans Computers', '2025-10-23', '24 Month', 5600.00, 'New', '', '2025-10-25 04:34:38', 'SDL', '2026-02-14 10:19:39', 'CCTV', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchases for SDL', 12, 'available'),
(202, 'IP Camera', 'CCTV', 'FM7593662', 'Ryans Computers', '2025-10-23', '24 Month', 5600.00, 'New', '', '2025-10-25 04:35:41', 'SDL', '2026-02-14 10:19:39', 'CCTV', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchases for SDL', 12, 'available'),
(203, 'IP Camera', 'CCTV', 'FM7593688', 'Ryans Computers', '2025-10-23', '24 Month', 5600.00, 'New', '', '2025-10-25 04:36:52', 'SDL', '2026-02-14 10:19:39', 'CCTV', 'Hikvision', 'DS-2CD1047G2H-LIU', '', 'Purchases for SDL', 12, 'available'),
(204, 'Adapter', 'Peripheral', 'PHBHRY581112', 'Ryans Computers', '2025-10-23', '12 Month', 600.00, 'New', '', '2025-10-25 05:02:17', 'SDL', '2026-02-14 10:15:32', 'Adapter', 'Power House Thin', '20v 3.25A 65w Notebook Adapter', 'N/A', 'Purchases for SDL', 9, 'available'),
(205, 'Canon  LBP633Cdw Color Laser Printer', 'Printer', 'NTBA133740', 'Unitech-Computer', '2025-10-23', '12 Month', 38000.00, 'New', '53276', '2025-10-25 11:38:57', 'SLL', '2026-01-24 11:32:50', 'Canon imageCLASS LBP633Cdw Single Function Color Laser Printer. Interface: USB, LAN, WiFi, \r\nToner: \r\nCanon 067 Cyan, 067 Magenta, 067 Yellow, 067 Black. ', 'Canon', 'LBP633Cdw', 'N/A', 'Purchase for R&D Department Sample image Print Purpose', 6, 'available');
INSERT INTO `products` (`id`, `name`, `category`, `serial_number`, `supplier`, `purchase_date`, `warranty`, `price`, `product_condition`, `requisition_no`, `created_at`, `factory_name`, `updated_at`, `product_description`, `brand`, `model`, `asset_tag`, `remarks`, `category_id`, `status`) VALUES
(206, 'Canon LiDE 400 Scanner', 'Peripheral', 'KNVT17173', 'Fantasy Computer', '2025-10-30', '12 Month', 11500.00, 'New', '54370', '2025-10-30 11:33:44', 'SLL', '2025-10-30 11:34:55', 'USB Type C Flatbed Document Scanner. Resolution: 4800 x 4800dpi. Speed: 25.4msec/line. Body Color: Black.', 'Canon', 'LiDE 400', 'N/A', 'Purchase For HR Department', NULL, 'assigned'),
(207, 'Baseus USB HUB', 'Peripheral', 'AA058837', 'Ryans Computers', '2025-11-08', '6 Month', 900.00, 'New', '', '2025-11-11 11:20:45', 'SLL', '2026-01-24 11:30:08', 'Baseus CAHUB-AY01 USB Male to Tri USB 2.0 & USB 3.0 Female Black USB HUB', 'Baseus', 'CAHUB-AY01', 'N/A', 'Purchase for IT PC', NULL, 'available'),
(208, 'Grandstream GRP2601P  IP Phone', 'Peripheral', '35201M45E9', 'Ryans Computers', '2025-11-08', '12 Month', 4200.00, 'New', '', '2025-11-11 11:22:03', 'SLL', '2026-01-24 11:30:08', 'Grandstream GRP2601P 2-Line 2-SIP IP Phone With POE & without Adapter', 'Grandstream', 'GRP2601P', 'N/A', 'Purchase For Office', NULL, 'available'),
(209, 'Grandstream GRP2601P IP Phone', 'Peripheral', '35201MFB15', 'Ryans Computers', '2025-11-08', '12 Month', 4200.00, 'New', '', '2025-11-11 11:23:08', 'SLL', '2026-01-24 11:30:08', 'Grandstream GRP2601P 2-Line 2-SIP IP Phone With POE & without Adapter', 'Grandstream', 'GRP2601P', 'N/A', 'Purchase For Office', NULL, 'available'),
(211, 'Avexir DDR-4 Ram 8GB', 'RAM', 'AVD4UZ12400', 'Com-Culus System & Innovation', '2025-12-20', NULL, 4600.00, 'New', '', '2025-12-28 04:27:38', 'HO', '2026-01-24 11:31:23', 'DDR-4 Ram 8GB, 2400 BUS', 'Avexir', 'AVD', 'N/A', 'Tareqe Ali', 13, 'available'),
(212, 'Laptop- ASUS EXPERTBOOK B1', 'Laptop', 'B1503CVA-S76149', 'Global Brand', '2025-12-13', '36 Month', 85000.00, 'New', '', '2025-12-28 04:36:31', 'HO', '2026-02-14 10:22:03', 'CORE I5, 12GEN, 512+512 NVME SSD, 8+8GB RAM, 15.6 DISPLAY', 'ASUS', 'B1503CVA', 'N/A', 'ACCOUNTS (TALLY)', 1, 'available'),
(213, 'ASUS ExpertBook B1503CVA', 'Laptop', 'EITPQI2025000288', 'EASTERN IT', '2025-12-24', '36 Month', 83368.00, 'New', '', '2025-12-28 04:42:40', 'HO', '2026-02-14 10:22:03', 'ASUS ExpertBook B1503CVA Core 5-120U/8GB RAM/512GB\r\nSSD/15.6\'\'FHD DISPLAY', 'ASUS', 'B1503CVA', 'EIT-20251223779', 'COMMERCIAL IMP (CUSTOMS & BOND)', NULL, 'available'),
(214, 'TRM SATA SSD 128', 'Storage', 'Trm151025', 'Com-Culus System & Innovation', '2025-10-15', '60', 2000.00, 'New', '', '2025-12-28 04:51:26', 'HO', '2026-01-24 11:31:23', '128GB SSD', 'TRM', 'TRM S100', '', 'SAFA', 4, 'available'),
(215, 'Gigabyte H510M K V2 Motherboard', 'Motherboard', 'SN25303A015748', 'Fantasy Computer', '2026-01-03', '36 Month', 10000.00, 'New', '54037', '2026-01-03 11:42:54', 'SLL', '2026-02-14 10:22:03', 'Intel® Ultra Durable Motherboard with GbE LAN, Anti-Sulfur Resistor, Smart Fan 5, \r\nSupports 11th/10th Gen Intel® Core™ Processors', 'Gigabyte', 'H510M K V2', 'NA', 'Purchase for EMS- Department (OMI) PC', 5, 'available'),
(216, 'Digital X 650VA Offline UPS', 'Peripheral', '203933', 'Fantasy Computer', '2026-01-03', '12 Month', 3400.00, 'New', '54028', '2026-01-03 11:50:40', 'SLL', '2026-02-14 10:16:03', 'Type - Offline UPS, Output Voltage (V) - 220-230 VAC, Load Capacity - 360W\r\nBack up time - Up to 15 Minutes, Body Material - Plastic, Color - Black', 'Digital X', 'Digital X 650VA', 'Req-No: 54028', 'Purchase for Rajakini PC (B1)', 9, 'available'),
(217, 'Asus ExpertBook B1', 'Laptop', 'T8NXCV06756333C', 'Global Brand', '2026-01-04', '36 Month', 84526.00, 'New', '54035', '2026-01-05 02:34:29', 'SLL', '2026-01-05 02:39:14', 'Processor: Intel Core i5-1335U (10C, 12T, 12MB Cache)\r\nRAM: 8GB DDR5 5600MT/s, Storage: 512GB M.2 NVMe Gen4 SSD, Display: 14\" IPS FHD, Fingerprint, 720P HD Cam, Type C 65W Charger.', 'Asus', 'B1403CVA', 'N/A', 'Purchase for Idris Mallik-AGM (Compliance). Purchase From Head Office.', NULL, 'assigned'),
(218, '10TB Surveillance HDD', 'Storage', '85B2A011FVYJ', 'Richman Informatics', '2026-01-08', NULL, 43500.00, 'New', '8864', '2026-01-11 03:59:24', 'SDL', '2026-01-24 06:10:33', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines.', 'Toshiba', 'S300 (Pro Series)', '128C NVR', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', 4, 'assigned'),
(219, '10TB Surveillance HDD', 'Storage', '85B2A00WFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:00:39', 'SDL', '2026-01-11 06:13:28', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(220, '10TB Surveillance HDD', 'Storage', '85B2A00SFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:01:22', 'SDL', '2026-01-11 06:13:13', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(221, '10TB Surveillance HDD', 'Storage', '85B2A00RFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:02:17', 'SDL', '2026-01-11 06:12:55', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(222, '10TB Surveillance HDD', 'Storage', '85B2A00QFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:03:05', 'SDL', '2026-01-11 06:12:37', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(223, '10TB Surveillance HDD', 'Storage', '85B2A00NFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:03:57', 'SDL', '2026-01-11 06:12:22', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(224, '10TB Surveillance HDD', 'Storage', '85B2A00LFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:04:48', 'SDL', '2026-01-11 06:12:06', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(225, '10TB Surveillance HDD', 'Storage', '8592A03HFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:05:33', 'SDL', '2026-01-11 06:07:12', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(226, '10TB Surveillance HDD', 'Storage', '8592A03CFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:06:15', 'SDL', '2026-01-11 06:06:58', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines.', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(227, '10TB Surveillance HDD', 'Storage', '95W2A02NFVYJ', 'Richman Informatics', '2026-01-08', '60 Month', 43500.00, 'New', '8864', '2026-01-11 04:06:55', 'SDL', '2026-01-11 06:06:39', '7200RPM, Form Factor: 3.5\" , Origin: Germany, Made in: Philippines', 'Toshiba', 'S300 (Pro Series)', 'N/A', 'Purchase for SDL-B1 128 channel NVR Increase Record Duration.', NULL, 'assigned'),
(228, 'HP  M554dn  Color Laser Printer', 'Printer', 'JPBRT3M749', 'Ryans Computers', '2026-01-03', '12 Month', 104000.00, 'New', '', '2026-01-12 06:18:16', 'SDL', '2026-02-14 10:16:03', 'HP Enterprise M554dn Single Function Color Laser Printer #7ZU81A (Unofficial)', 'HP', 'M554DN', 'N/A', 'Purchase For Finishing Department- Shipping Mark Print Purpose.', NULL, 'available'),
(229, 'Sandisk Memory Card', 'Storage', 'RYG1264779', 'Ryans Computers', '2026-01-02', 'Product life time', 1700.00, 'New', '', '2026-01-12 06:22:47', 'SDL', '2026-01-24 11:30:08', 'Sandisk Ultra SDUNR 64GB SDXC UHS-I Class 10 Memory Card #SDSDUNR-064G-GN3IN', 'Sandisk', 'SDUNR 64GB', 'N/A', 'Purchase For QMS DSLR Camera', NULL, 'available'),
(230, 'Canon  LBP6030 Laser Printer', 'Printer', '918468B00892AA21PCLA992923', 'Ryans Computers', '2025-11-27', '12 Month', 14400.00, 'New', '', '2026-01-12 06:24:53', 'SDL', '2026-01-24 11:30:08', 'Canon imageCLASS LBP6030 White Single Function Mono Laser Printer', 'Canon', 'LBP6030', 'N/A', 'Purchase For Cutting Department', NULL, 'available'),
(231, 'Asus RT-AX52 Wi-Fi 6 Router', 'Network', 'SBIG6YQ08788VHX', 'Ryans Computers', '2025-11-05', '24 Month', 5900.00, 'New', '', '2026-01-17 05:24:19', 'SDL', '2026-02-14 10:19:39', 'Asus RT-AX52 AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Router', 'Asus', 'Asus RT-AX52', 'N/A', 'Purchase for SDL-B1.', 7, 'available'),
(232, 'Hikvision 4.0MP Turret IP Camera', 'CCTV', 'FF1905837', 'Ryans Computers', '2025-11-05', '24 Month', 5600.00, 'New', '', '2026-01-17 05:26:31', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera', 'Hikvision', 'Hikvision DS-2CD1347G2H-LIU', 'N/A', 'Purchase for SDL-B1.', NULL, 'available'),
(233, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FY0965763', 'Ryans Computers', '2025-11-05', '24 Month', 5600.00, 'New', '', '2026-01-17 05:28:57', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase for SDL-B1', NULL, 'available'),
(234, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FY0965820', 'Ryans Computers', '2025-11-05', '24 Month', 5600.00, 'New', '', '2026-01-17 05:29:58', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase for SDL-B1.', NULL, 'available'),
(235, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FY0965826', 'Ryans Computers', '2025-11-05', '24 Month', 5600.00, 'New', '', '2026-01-17 05:30:53', 'SDL', '2026-02-14 10:19:39', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase for SDL-B1.', 12, 'available'),
(236, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FY0965751', 'Ryans Computers', '2025-11-05', '24 Month', 5600.00, 'New', '', '2026-01-17 05:31:43', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase for SDL-B1.', NULL, 'available'),
(237, 'Gigabyte H410M Motherboard', 'Motherboard', 'SN25243A008908', 'Ryans Computers', '2025-10-18', '36 Month', 8500.00, 'New', '', '2026-01-17 05:36:41', 'SDL', '2026-02-14 10:22:03', 'Gigabyte H410M H V2 DDR4 H470 Chipset 10th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte', 'H410M H V2', 'N/A', 'Purchase for SDL', 5, 'available'),
(238, 'Gigabyte H410M Motherboard', 'Motherboard', 'SN25243A008906', 'Ryans Computers', '2025-10-18', '36 Month', 8500.00, 'New', '', '2026-01-17 05:37:38', 'SDL', '2026-01-24 11:30:08', 'Gigabyte H410M H V2 DDR4 H470 Chipset 10th Gen Intel LGA1200 Socket Motherboard', 'Gigabyte', 'H410M H V2', 'N/A', 'Purchase for SDL-B1.', NULL, 'available'),
(239, 'Hikvision Turret IPCamera', 'CCTV', 'FX4377816', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:36:42', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera', 'Hikvision', 'DS-2CD1347G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(240, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5210789', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:38:01', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'assigned'),
(241, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5211126', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:39:09', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(242, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5211133', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:40:17', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'assigned'),
(243, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5211139', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:41:15', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'assigned'),
(244, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5211141', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:41:59', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'assigned'),
(245, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FR5210790', 'Ryans Computers', '2025-12-24', '24 Month', 5600.00, 'New', '', '2026-01-17 10:42:43', 'SDL', '2026-01-24 11:30:08', 'Hikvision DS-2CD1047G2H-LIU (4mm) (4.0MP) ColorVu Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1047G2H-LIU', 'N/A', 'Purchase For SDL-B2', NULL, 'assigned'),
(246, 'Wireless Mouse', 'Peripheral', '2516APY26U59', 'Ryans Computers', '2026-01-03', '36 Month', 795.00, 'New', '', '2026-01-17 11:34:22', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(247, 'Wireless Mouse', 'Peripheral', '2515APG2AA89', 'Ryans Computers', '2026-01-03', '36 Month', 795.00, 'New', '', '2026-01-17 11:36:17', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29 3 year', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(248, 'Wireless Mouse', 'Peripheral', '2527APT33V89', 'Ryans Computers', '2025-11-05', '36 Month', 795.00, 'New', '', '2026-01-17 11:42:23', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(249, 'Wireless Mouse', 'Peripheral', '2527AP64EQS9', 'Ryans Computers', '2025-11-05', '36 Month', 795.00, 'New', '', '2026-01-17 11:43:12', 'SDL', '2026-01-24 11:30:08', '3 year ,, 2527APM31AQ9, 2511AP8G0GY9', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(250, 'Wireless Mouse', 'Peripheral', '2527APM31AQ9', 'Ryans Computers', '2025-12-05', '36 Month', 795.00, 'New', '', '2026-01-17 11:44:30', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(251, 'Wireless Mouse', 'Peripheral', '2511AP8G0GY9', 'Ryans Computers', '2025-11-05', '36 Month', 795.00, 'New', '', '2026-01-17 11:45:14', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', '', NULL, 'available'),
(252, 'Transcend SATA SSD', 'Storage', 'J454852609', 'Ryans Computers', '2025-09-18', '60', 6550.00, 'New', '', '2026-01-17 11:56:22', 'SDL', '2026-01-24 11:30:08', 'Transcend 230S 512GB 2.5 inch SATAIII SSD #TS512GSSD230S  04.02.047.19', 'Transcend', 'Transcend 230S', 'N/A', 'Purchase For NQC+QMS+Cutting', 4, 'available'),
(253, 'Transcend SATA SSD', 'Storage', 'J454852793', 'Ryans Computers', '2025-09-18', '60 Month', 6550.00, 'New', '', '2026-01-17 11:57:20', 'SDL', '2026-02-14 10:25:16', 'Transcend 230S 512GB 2.5 inch SATAIII SSD #TS512GSSD230S  04.02.047.19', 'Transcend', 'Transcend 230S', 'N/A', 'Purchase For NQC+QMS+Cutting', 4, 'available'),
(254, 'Transcend  SATAIII SSD', 'Storage', 'J454852725', 'Ryans Computers', '2025-09-18', '60 Month', 6550.00, 'New', '', '2026-01-17 11:58:21', 'SDL', '2026-02-14 10:25:16', 'Transcend 230S 512GB 2.5 inch SATAIII SSD #TS512GSSD230S  04.02.047.19', 'Transcend', 'Transcend 230S', 'N/A', 'Purchase For NQC+QMS+Cutting', 4, 'available'),
(255, 'Wireless Mouse', 'Peripheral', '2543APKAPTC9', 'Ryans Computers', '2026-01-21', '36 Month', 795.00, 'New', '', '2026-01-21 08:50:31', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(256, 'Wireless Mouse', 'Peripheral', '2515APR29XR9', 'Ryans Computers', '2026-01-21', '36 Month', 795.00, 'New', '', '2026-01-21 08:52:05', 'SDL', '2026-01-24 11:30:08', 'Logitech B175 Black Wireless Mouse #910002635  05.02.154.29', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(258, 'Canon 067 (Original) Color Laser Toner (Full Set)', 'Peripheral', '', 'Fantasy Computer', '2026-01-21', NULL, 32000.00, 'New', '', '2026-01-22 04:58:34', 'SLL', '2026-01-22 05:34:10', 'Cartridge Color: Black, Yellow, Cyan, & Magenta\r\nTotal Yield: 1350 Pages per Cartridge, Compatable with MF651Cw, MF655Cdw, MF657Cdw', 'Canon', '067', 'N/A', 'Purchase for SLL-R&D Department Color Laser Printer.', 9, 'available'),
(259, 'Zebra Barcode Printer', 'Printer', '99J200300458', 'Amana International Ltd', '2025-12-10', '12 Month', 130000.00, 'New', '', '2026-01-24 10:11:02', 'SDL', '2026-02-14 10:13:34', 'Zebra ZT-420, 300 dpi Barcode Printer', 'Zebra', 'Zebra ZT-420, 300 dpi Barcord Printer', 'N/A', 'Purchases For SDL B1', 6, 'available'),
(262, 'RAM', 'RAM', 'RY26192502', 'Ryans Computers', '2025-10-25', '36 Month', 2500.00, 'New', '', '2026-01-25 09:31:46', 'SDL', '2026-01-25 09:31:46', 'Thermaltake 8GB DDR4 3200MHz Desktop RAM', 'Thermaltake', 'Thermaltake 8GB DDR4 3200MHz', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(263, 'RAM', 'RAM', 'RY26192504', 'Ryans Computers', '2025-10-25', '36 Month', 2500.00, 'New', '', '2026-01-25 09:33:01', 'SDL', '2026-01-25 09:33:01', 'Thermaltake 8GB DDR4 3200MHz Desktop RAM', 'Thermaltake', '8GB DDR4 3200MHz Desktop RAM', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(264, 'RAM', 'RAM', '172507303147', 'Ryans Computers', '2025-09-23', '00 Month', 1900.00, 'New', '', '2026-01-25 09:37:59', 'SDL', '2026-01-25 09:37:59', 'Apacer 8GB DDR3 1600MHz Desktop RAM', 'Apacer', 'DDR3 1600MHz Desktop RAM', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(265, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FX0178803', 'Ryans Computers', '2025-09-18', '12 Month', 4900.00, 'New', '', '2026-01-25 09:47:22', 'SDL', '2026-02-14 10:15:32', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', 12, 'available'),
(266, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FW5907754', 'Ryans Computers', '2025-09-18', '12 Month', 4900.00, 'New', '', '2026-01-25 09:49:01', 'SDL', '2026-01-25 09:49:01', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(267, 'Speaker', 'Peripheral', '282214336776', 'Ryans Computers', '2025-09-18', '12 Month', 1550.00, 'New', '', '2026-01-25 09:53:13', 'SDL', '2026-01-25 09:53:13', 'Edifier R12U 2.0 USB Powered Multimedia Black Speaker', 'Edifier', 'Edifier R12U 2.0 USB', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(268, 'SSD', 'Storage', 'J550020376', 'Ryans Computers', '2025-09-18', '36 Month', 3300.00, 'New', '', '2026-01-25 09:55:46', 'SDL', '2026-01-25 09:55:46', 'Transcend SSD225S 250GB 2.5 Inch SATAIII SSD', 'Transcend', 'SSD225S 250GB 2.5 Inch SATAIII SSD', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(269, 'Offline UPS', 'Peripheral', '310044584F420493037241', 'Ryans Computers', '2025-09-18', '12 Month', 3500.00, 'New', '', '2026-01-25 09:58:47', 'SDL', '2026-01-25 09:58:47', 'Apollo 1065A/1065 650VA Offline UPS with Plastic Body', 'Apollo', '1065A/1065 650VA Offline UPS', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(270, 'Router', 'Peripheral', 'T1IG6YQ056566YK', 'Ryans Computers', '2025-09-18', '36 Month', 6400.00, 'New', '', '2026-01-25 10:00:48', 'SDL', '2026-01-25 10:00:48', 'Asus RT-AX52 AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Router', 'Asus', 'RT-AX52 AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Route', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(271, 'Printer', 'Peripheral', 'X8SJ040914', 'Ryans Computers', '2025-09-03', '12 Month', 37600.00, 'New', '', '2026-01-25 10:04:38', 'SDL', '2026-01-25 10:04:38', 'Epson EcoTank L4260 Wi-Fi Multifunction Color Ink Tank Printer', 'Epson', 'EcoTank L4260 Wi-Fi Multifunction Color Ink Tank Printer', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(272, 'HP Monitor', 'Monitor', '3CQ427074D', 'Ryans Computers', '2025-02-01', '36 Month', 9100.00, 'New', '', '2026-01-26 03:43:36', 'SDL', '2026-01-26 03:43:36', 'HP V20 19.5 Inch HD+ (1600x900) Black Monitor (VGA, HDMI) #1H849AA', 'HP', 'HP V20 19.5 Inch HD+ (1600x900) Black Monitor', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(273, 'HP Monitor', 'Monitor', '3CQ426092N', 'Ryans Computers', '2025-02-01', '36 Month', 9100.00, 'New', '', '2026-01-26 03:44:51', 'SDL', '2026-01-26 03:44:51', 'HP V20 19.5 Inch HD+ (1600x900) Black Monitor (VGA, HDMI) #1H849AA', 'HP', 'HP V20 19.5 Inch HD+ (1600x900) Black Monitor', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(274, 'Gigabyte Motherboard', 'Motherboard', 'SN244360038385', 'Ryans Computers', '2025-02-28', '36 Month', 13500.00, 'New', '', '2026-01-26 03:49:15', 'SDL', '2026-01-26 03:49:15', 'Gigabyte B760M K V2 DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'Gigabyte', 'Gigabyte B760M K V2 DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(275, 'RAM', 'RAM', 'I956440659', 'Ryans Computers', '2025-02-28', '00 Month', 1900.00, 'New', '', '2026-01-26 03:51:13', 'SDL', '2026-01-26 03:51:13', 'Transcend JetRAM 8GB DDR4 3200MHz U-DIMM Desktop RAM #JM3200HLG-8G / JM3200HLB-8G  03.01.047.56 Product LifeTime', 'Transcend', '8GB DDR4 3200MHz U-DIMM Desktop RAM', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(276, 'SSD', 'Storage', 'M2NVMEBHO411071513', 'Ryans Computers', '2025-02-28', '60 Month', 2650.00, 'New', '', '2026-01-26 03:53:29', 'SDL', '2026-01-26 03:53:29', '(Bundle with PC) Twinmos Alpha Pro 256GB M.2 2280 PCIe NVMe Gen.3 SSD #NVMEEGBM2280-5Y / NVMe256GB2280AP-5Y (3600MB/s & 3250MB/s)  04.02.048.41 5 Year', 'Twinmos', 'Twinmos Alpha Pro 256GB M.2 2280 PCIe NVMe Gen.3 SSD', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(277, 'Processor', 'Peripheral', 'U4XX526603060', 'Ryans Computers', '2025-02-28', '36 Month', 14800.00, 'New', '', '2026-01-26 03:55:27', 'SDL', '2026-01-26 03:55:27', 'Intel 12th Gen Alder Lake Core i5 12400 2.50GHz-4.40GHz, 6 Core, 18MB Cache LGA1700 Socket Processor (OEM/Tray)  01.01.024.322', 'Intel', 'Intel 12th Gen Alder Lake Core i5 12400 2.50GHz-4.40GHz, 6 Core, 18MB Cache LGA1700 Socket Processor', '', 'Purchase For SDL-B1', NULL, 'available'),
(278, 'Router', 'Peripheral', 'S9IG2ZE00357ZSL', 'Ryans Computers', '2025-03-10', '24 Month', 8650.00, 'New', '', '2026-01-26 03:57:35', 'SDL', '2026-01-26 03:57:35', 'Asus RT-AX53U AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Router  29.01.006.51 2 Year (Without Adapter)', 'Asus', 'RT-AX53U AX1800 Mbps Gigabit Dual-Band Wi-Fi 6 Router', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(279, 'Gigabyte  Motherboard', 'Motherboard', 'SN244360038384', 'Ryans Computers', '2025-04-12', '36 Month', 13500.00, 'New', '', '2026-01-26 04:00:40', 'SDL', '2026-01-26 04:00:40', 'Gigabyte B760M K V2 DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard  02.01.018.556 3 Year', 'Gigabyte', 'Gigabyte B760M K V2 DDR4 12th/13th/14th Gen Intel LGA1700 Socket Motherboard', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(280, 'RAM', 'RAM', 'J021754557', 'Ryans Computers', '2025-04-12', '00 Month', 1850.00, 'New', '', '2026-01-26 04:02:51', 'SDL', '2026-01-26 04:02:51', 'Transcend JetRAM 8GB DDR4 3200MHz U-DIMM Desktop RAM #JM3200HLG-8G / JM3200HLB-8G  03.01.047.56 Product LifeTime', 'Transcend', 'JetRAM 8GB DDR4 3200MHz U-DIMM Desktop RAM', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(281, 'Processor', 'Peripheral', 'U4BM578301723', 'Ryans Computers', '2025-04-12', '36 Month', 15500.00, 'New', '', '2026-01-26 04:04:45', 'SDL', '2026-01-26 04:04:45', 'Intel 12th Gen Alder Lake Core i5 12400 2.50GHz-4.40GHz, 6 Core, 18MB Cache LGA1700 Socket Processor (OEM/Tray)  01.01.024.322 3 Year (No Warranty for Fan or Cooler)', 'Intel', 'Core i5 12400 2.50GHz-4.40GHz, 6 Core, 18MB Cache LGA1700 Socket Processor', '', 'Purchase For SDL-B1', NULL, 'available'),
(282, 'NVMe', 'Storage', 'M2NVMEBHO412242806', 'Ryans Computers', '2025-04-12', '60 Month', 2650.00, 'New', '', '2026-01-26 04:07:25', 'SDL', '2026-01-26 04:07:25', '(Bundle with PC) Twinmos Alpha Pro 256GB M.2 2280 PCIe NVMe Gen.3 SSD #NVMEEGBM2280-5Y / NVMe256GB2280AP-5Y (3600MB/s & 3250MB/s)  04.02.048.41 5 Year', 'Twinmos', 'Twinmos Alpha Pro 256GB M.2 2280 PCIe NVMe Gen.3 SSD', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(283, 'Router', 'Peripheral', 'R8IG3F602238JNM', 'Ryans Computers', '2025-04-12', '24 Month', 2300.00, 'New', '', '2026-01-26 04:11:47', 'SDL', '2026-01-26 04:11:47', 'Asus RT-N12+ 300 Mbps Ethernet Single-Band Wi-Fi Router  29.01.006.19 2 Year (Without Adapter) SERIAL NO. QTY PRICE', 'Asus', 'RT-N12+ 300 Mbps Ethernet Single-Band Wi-Fi Router', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(284, 'Hikvision Turret IPCamera', 'CCTV', 'K57909080', 'Ryans Computers', '2025-05-19', '12 Month', 5600.00, 'New', '', '2026-01-27 05:15:47', 'SDL', '2026-02-14 10:15:32', 'Hikvision DS-2CD1347G0-L (2.8mm) (4.0MP) ColorVu Fixed Turret IP Camera  127.03.485.177 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1347G0-L (2.8mm) (4.0MP) ColorVu Fixed Turret IP Camera', 'N/A', 'Purchase For SDL-B1', 12, 'available'),
(285, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FM5611879', 'Ryans Computers', '2025-05-19', '12 Month', 4600.00, 'New', '', '2026-01-27 05:21:12', 'SDL', '2026-01-27 05:21:12', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(286, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FN2370364', 'Ryans Computers', '2025-05-19', '12 Month', 4600.00, 'New', '', '2026-01-27 05:22:18', 'SDL', '2026-01-27 05:22:18', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter) ,', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(287, 'Pen Drive', 'Storage', 'RY26105577', 'Ryans Computers', '2025-05-19', '60 Month', 1200.00, 'New', '', '2026-01-27 05:30:59', 'SDL', '2026-01-27 05:30:59', 'Hiksemi Dual Slim HS-USB-E307C 128GB USB 3.2 & Type-C Grey Pen Drive #HS-USB-E307C-128G-U3', 'Hiksemi', 'Dual Slim HS-USB-E307C 128GB USB 3.2 & Type-C Grey Pen Drive', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(288, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FM5611878', 'Ryans Computers', '2025-05-27', '12 Month', 4600.00, 'New', '', '2026-01-27 05:34:08', 'SDL', '2026-01-27 05:34:08', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(289, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FM5610850', 'Ryans Computers', '2025-05-27', '12 Month', 4600.00, 'New', '', '2026-01-27 05:35:47', 'SDL', '2026-01-27 05:35:47', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter) ,', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(290, 'Hikvision Turret IPCamera', 'CCTV', 'FF3653275', 'Ryans Computers', '2025-05-27', '12 Month', 5600.00, 'New', '', '2026-01-27 05:36:49', 'SDL', '2026-01-27 05:36:49', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera  127.03.485.399 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1347G2H-LIU', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(291, 'Wireless Mouse', 'Peripheral', '2504APE8PG79', 'Ryans Computers', '2025-05-27', '36 Month', 795.00, 'New', '', '2026-01-27 05:40:00', 'SDL', '2026-01-27 05:40:00', 'Logitech B175 Black Wireless Mouse #910-002635  05.02.154.29 3 year SERIAL NO. QTY PRICE 1', 'Logitech', 'B175 Black Wireless Mouse #910-002635', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(292, 'Motherboard', 'Motherboard', 'SN244860058946', 'Ryans Computers', '2025-06-02', '36 Month', 9400.00, 'New', '', '2026-01-27 05:45:18', 'SDL', '2026-01-27 05:45:18', 'Gigabyte GA-H110M-H DDR4 6th/7th Gen Only Intel LGA1151 Socket Motherboard  02.01.018.532 3 Year', 'Gigabyte', 'GA-H110M-H DDR4 6th/7th Gen Only Intel LGA1151 Socket Motherboard', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(293, 'Motherboard', 'Motherboard', 'SN235260001075', 'Ryans Computers', '2025-06-02', '36 Month', 9300.00, 'New', '', '2026-01-27 05:47:40', 'SDL', '2026-01-27 05:47:40', 'Gigabyte GA-H81M-H DDR3 4th Gen Intel LGA1150 Socket Motherboard  02.01.018.490 3 Year', 'Gigabyte', 'GA-H81M-H DDR3 4th Gen Intel LGA1150 Socket Motherboard', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(294, 'Hikvision Turret IPCamera', 'CCTV', 'FT8467182', 'Ryans Computers', '2025-06-02', '12 Month', 5600.00, 'New', '', '2026-01-27 05:49:11', 'SDL', '2026-01-27 05:49:11', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera  127.03.485.399 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(295, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FM5610167', 'Ryans Computers', '2025-06-21', '12 Month', 4600.00, 'New', '', '2026-01-27 05:52:28', 'SDL', '2026-01-27 05:52:28', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(296, 'Hikvision Fixed Bullet IP Camera', 'CCTV', 'FM5614260', 'Ryans Computers', '2025-06-21', '12 Month', 4600.00, 'New', '', '2026-01-27 05:53:25', 'SDL', '2026-01-27 05:53:25', 'Hikvision DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera  127.03.485.410 1 Year (Without Adapter) ,', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(297, 'Hikvision Turret IPCamera', 'CCTV', 'FT8466945', 'Ryans Computers', '2025-06-21', '12 Month', 5600.00, 'New', '', '2026-01-27 05:54:54', 'SDL', '2026-01-27 05:54:54', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera  127.03.485.399 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(298, 'Hikvision Turret IPCamera', 'CCTV', 'FT8466944', 'Ryans Computers', '2025-06-21', '00 Month', 5600.00, 'New', '', '2026-01-27 05:56:25', 'SDL', '2026-01-27 05:56:25', 'Hikvision DS-2CD1347G2H-LIU (2.8mm) (4.0MP) ColorVu Smart Hybrid Light Turret Camera  127.03.485.399 1 Year (Without Adapter)', 'Hikvision', 'DS-2CD1043G2-LIU (4mm) (4.0MP) Smart Hybrid Light Fixed Bullet IP Camera', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(299, 'Laser Printer', 'Printer', '918468B00892AA21PCLA852951', 'Ryans Computers', '2025-07-19', '12 Month', 15500.00, 'New', '', '2026-01-27 05:59:04', 'SDL', '2026-01-27 05:59:04', 'Canon imageCLASS LBP6030 White Single Function Mono Laser Printer  12.01.010.65 1 Year (Without Adapter) (Box Mandatory While Claiming)', 'Canon', 'Canon imageCLASS LBP6030 White Single Function Mono Laser Printer', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(300, 'Epson EB-W52 Projector', 'Monitor', 'X8BX2300102', 'Ryans Computers', '2025-07-19', '24 Month', 82000.00, 'New', '', '2026-01-27 06:02:37', 'SDL', '2026-02-14 10:19:39', 'Epson EB-W52 (4000 Lumens) WXGA 3LCD Projector  59.01.015.97 2 year (1st Year Parts, 2nd Year Service) (Lamp 12 Month/1000 Hours Which One Comes First)', 'Epson', 'EB-W52 (4000 Lumens) WXGA 3LCD Projector', 'N/A', 'Purchase For SDL-B2', 3, 'available'),
(301, 'Offline UPS', 'Peripheral', 'E2503023272', 'Ryans Computers', '2025-07-19', '12 Month', 6800.00, 'New', '', '2026-01-27 06:05:25', 'SDL', '2026-01-27 06:05:25', 'Apollo 1120F/1120 1200VA Offline UPS with Plastic Body (4 Port)  48.06.057.39 1 Year (Box Mandatory While Claiming)', 'Apollo', '1120F/1120 1200VA Offline UPS', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(302, 'Laser Printer', 'Printer', '918468B00892AA21PCLA8665191', 'Ryans Computers', '2025-07-19', '12 Month', 15500.00, 'New', '', '2026-01-27 06:07:18', 'SDL', '2026-01-27 06:07:18', 'Canon imageCLASS LBP6030 White Single Function Mono Laser Printer  12.01.010.65 1 Year (Without Adapter) (Box Mandatory While Claiming)', 'Canon', 'Canon imageCLASS LBP6030 White Single Function Mono Laser Printer', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(303, 'Portable SSD', 'Storage', '2512B5401533', 'Ryans Computers', '2025-08-13', '36 Month', 17300.00, 'New', '', '2026-01-27 06:12:35', 'SDL', '2026-01-27 06:12:35', 'Sandisk Extreme PRO 1TB USB 3.2 Gen 2 Type-C Portable SSD #SDSSDE81-1T00-G25 (2000MB/s) (3 Year)  04.04.041.142 3 Year', 'Sandisk', 'Sandisk Extreme PRO 1TB USB 3.2 Gen 2 Type-C Portable SSD', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(304, 'USB HUB', 'Peripheral', '22494E6001099', 'Ryans Computers', '2025-08-13', '12 Month', 4250.00, 'New', '', '2026-01-27 06:15:59', 'SDL', '2026-01-27 06:15:59', 'Tp-link UH9120C V1 Type-C Male to Tri USB, Dual USB Type-C, HDMI, SD, TF & Lan Female Gray Converter #UH9120C  152.05.091.102 1 Year', 'Tp-link', 'UH9120C V1 Type-C Male to Tri USB, Dual USB Type-C, HDMI, SD, TF & Lan Female Gray Converter', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(305, 'Dahua Smart 4.0MP Bullet IP Camera', 'CCTV', 'BD00BDEPAG2C0F0', 'Ryans Computers', '2026-01-28', '24 Month', 6000.00, 'New', '61255', '2026-01-28 06:45:07', 'SLL', '2026-01-28 06:47:47', 'Dahua DH-IPC-HFW1439TL1-PV (3.6mm) Smart Dual Light Bullet IP Camera.\r\nWorking Distance - 78 Meter, Mega Pixels (MP) - 4.0, Night Vision Mode - Color', 'Dahua', 'DH-IPC-HFW1439TL1-PV', 'N/A', 'Purchase For Outside NVR, Due to Track Accident to Broken Previous Camera.', NULL, 'assigned'),
(306, 'Dell Monitor', 'Monitor', '5PVRS04', 'Ryans Computers', '2024-10-16', '36 Month', 9100.00, 'New', '', '2026-02-03 05:10:15', 'SDL', '2026-02-03 05:10:15', 'Dell D2020H 20 Inch (19.5 Inch Diagonal) HD+ (1600x900) TN Black Monitor (HDMI, VGA)  08.01.013.132 3 Year (Box Mandatory While Claiming)', 'DELL', 'Dell D2020H 20 Inch (19.5 Inch Diagonal) HD+ (1600x900) TN Black Monitor', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(307, 'Dell Monitor', 'Monitor', 'BYS1B14', 'Ryans Computers', '2024-10-16', '36 Month', 9100.00, 'New', '', '2026-02-03 05:11:26', 'SDL', '2026-02-03 05:11:26', 'Dell D2020H 20 Inch (19.5 Inch Diagonal) HD+ (1600x900) TN Black Monitor (HDMI, VGA)  08.01.013.132 3 Year (Box Mandatory While Claiming)', 'DELL', 'Dell D2020H 20 Inch (19.5 Inch Diagonal) HD+ (1600x900) TN Black Monitor', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(308, 'Acer Laptop', 'CCTV', 'UNVTGSI47E404052760700', 'Ryans Computers', '2024-11-07', '36 Month', 54500.00, 'New', '', '2026-02-03 05:21:06', 'SDL', '2026-02-14 10:22:03', 'Acer TravelMate P214-53 11th Gen Intel Core i3 1115G4 (Up to 4.10GHz, 8GB DDR4, 512GB SSD, No-ODD) 14 Inch FHD (1920x1080) Display, Free Dos, Shale Black Laptop', 'Acer', 'Acer TravelMate P214-53 11th Gen Intel Core i3 1115G4', 'N/A', 'Purchase For SDL-B2', 12, 'available'),
(309, '256GB SSD', 'Storage', 'H598180149', 'Ryans Computers', '2024-11-07', '60 Month', 4200.00, 'New', '', '2026-02-03 05:23:59', 'SDL', '2026-02-03 05:23:59', '(Bundle with PC) Transcend 230S 256GB 2.5 Inch SATAIII SSD #TS256GSSD230S  04.02.047.17 5 Year', 'Transcend', '256GB 2.5 Inch SATAIII SSD', 'N/A', 'Purchase For SDL-B1', NULL, 'available'),
(310, 'Wireless Mouse', 'Peripheral', '2426LZX8VGF9', 'Ryans Computers', '2025-02-17', '36 Month', 750.00, 'New', '', '2026-02-03 05:27:26', 'SDL', '2026-02-03 05:27:26', 'Logitech B175 Black Wireless Mouse #910002635  05.02.154.29 3 year', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(311, 'Wireless Mouse', 'Peripheral', '2417LZXGE2U9', 'Ryans Computers', '2025-02-17', '36 Month', 750.00, 'New', '', '2026-02-03 05:28:22', 'SDL', '2026-02-03 05:28:22', 'Logitech B175 Black Wireless Mouse #910002635  05.02.154.29 3 year SERIAL NO. QTY PRICE ,', 'Logitech', 'Logitech B175 Black Wireless Mouse', 'N/A', 'Purchase For SDL-B2', NULL, 'available'),
(312, 'Canon Printer', 'Printer', '913515C00592AA21NPQA011948', 'Ryans Computers', '2025-02-25', '12 Month', 84000.00, 'New', '', '2026-02-03 05:32:18', 'SDL', '2026-02-03 05:32:18', 'Canon imageCLASS LBP325x Single Function Mono Laser Printer  12.01.010.97 1 Year (Without Adapter) (Box Mandatory While Claiming)', 'Canon', 'Canon imageCLASS LBP325x Single Function Mono Laser Printer', 'N/A', 'Purchase For SDL-B1', NULL, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `priority` enum('Low','Medium','High','Urgent') DEFAULT 'Medium',
  `reason_for_purchase` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Purchased') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `handled_by` int(11) DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `requisition_no` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_items`
--

CREATE TABLE `purchase_requisition_items` (
  `id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `created_at`) VALUES
(1, 'Fantasy Computer', '2026-01-24 10:37:02'),
(2, 'Ryans Computers', '2026-01-24 10:37:16'),
(3, 'Com-Culus System & Innovation', '2026-01-24 10:57:21'),
(4, 'Eastern IT', '2026-01-24 11:00:12'),
(5, 'Global Brand', '2026-01-24 11:01:34'),
(6, 'J.A.N Associates', '2026-01-24 11:01:59'),
(7, 'Richman Informatics', '2026-01-24 11:02:33'),
(8, 'Smart Technologies', '2026-01-24 11:03:07'),
(9, 'Unitech-Computer', '2026-01-24 11:04:11'),
(10, 'Star Tech Ltd', '2026-01-24 11:23:47'),
(11, 'Walton Hi-Tech Industries PLC', '2026-01-24 11:26:20'),
(12, 'Renesa Info Tech BD', '2026-01-24 11:33:55'),
(13, 'Amana International Ltd', '2026-01-24 12:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(4, 'Raju', '$2y$10$4YgKdZO2WawiL1HXfrdv..pwW0AX7N0tN8L9VW8EwEwN3ljQcZyRe'),
(5, 'Rabbani', '$2y$10$ZW6mCFN4Ak5BX9bMsOQuIe8GyWVKNTz44eDM3E83yw5yaQ.GFUc1.'),
(6, 'Kamrul', '$2y$10$hVuEBcjdwwq/V.6EgU6BOOmk9MJGzIRczfuwDHDskmPnrOU0cDel.'),
(7, 'Riad', '$2y$10$FNrwYpA/hvGYBvw7pfLgWuDaM1HmUwwyy6vVC5olOLe2A3kC2bqzy'),
(8, 'Sohanur', '$2y$10$w.98ArVbiuxUzA/hiv4AtO8cP/UW.v8WATEtKZzuymhjE9OBI2Sf.'),
(9, 'Provat', '$2y$10$sLFQ6Y2cCZmqgMEXQ9Uixu3yZQxtCrBc0lKlU7sNi9u20S1EG0yJS'),
(11, 'Mehedi', '$2y$10$HFacOkmE0If2offMA3BzQeBfF03/Lh/LA8d6W4O8aOok0NLfRuW4G');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product` (`product_id`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `damaged_assets`
--
ALTER TABLE `damaged_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `damaged_at` (`damaged_at`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requisition_no` (`requisition_no`),
  ADD KEY `requester_id` (`requester_id`);

--
-- Indexes for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_id` (`requisition_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `damaged_assets`
--
ALTER TABLE `damaged_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD CONSTRAINT `purchase_requisition_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
