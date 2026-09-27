-- ========================================================
-- CampusSwap Database Setup Script
-- Course: ICT 2209 - Web Technologies Mini Project
-- Database Name: campusswap_db
-- Description: Peer-to-peer campus thrift and item exchange platform
-- ========================================================

CREATE DATABASE IF NOT EXISTS `campusswap_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `campusswap_db`;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) DEFAULT NULL,
  `faculty` VARCHAR(100) DEFAULT 'Faculty of Technology',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `messages` (Contact Form Inquiries)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `products` (Marketplace Items)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `item_condition` VARCHAR(30) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `seller_name` VARCHAR(50) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'Available',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seed Initial Sample Data
-- --------------------------------------------------------

-- Default Demo User (Password: "Student123!")
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `faculty`) VALUES
('kavindu_ict', 'student@rusl.ac.lk', '$2y$10$iI8G6R31sN3G1m8w1yWzbeNnE5i7V0oR1P2Q3S4T5U6V7W8X9Y0Za', 'Kavindu Perera', 'Faculty of Technology'),
('nethmi_bio', 'nethmi@rusl.ac.lk', '$2y$10$wE4R5T6Y7U8I9O0P1A2S3dF4G5H6J7K8L9Z0X1C2V3B4N5M6Q7W8E', 'Nethmi Fernando', 'Faculty of Applied Sciences');

-- Initial Student Listings
INSERT INTO `products` (`title`, `category`, `price`, `item_condition`, `description`, `image_url`, `seller_name`, `user_id`, `status`) VALUES
('Casio FX-991EX Scientific ClassWiz Calculator', 'Electronics', 25.00, 'Like New', 'Original natural textbook display calculator. Approved for university engineering and math examinations. Includes protective slide-on case.', 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=600&q=80', 'Kavindu P.', 1, 'Available'),
('Data Structures and Algorithm Analysis in C++', 'Books', 28.00, 'Good', 'Comprehensive reference book for 2nd year IT algorithms course. Clean pages, no torn edges, highlighted key sections for quick revision.', 'https://images.unsplash.com/photo-1532012164546-f432f2e3777a?auto=format&fit=crop&w=600&q=80', 'Dilshan R.', 1, 'Available'),
('A2 Engineering Drawing Board & T-Square Ruler', 'Stationery', 38.00, 'Excellent', 'Standard drafting board for technical drawing and engineering graphics. Comes with parallel motion ruler and carrying clips.', 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=600&q=80', 'Nethmi F.', 2, 'Available'),
('Adjustable LED Dorm Desk Lamp (USB Rechargeable)', 'Dorm Gear', 18.00, 'Like New', 'Touch-control 3-level brightness study lamp with flexible neck. Saves hostel electricity and ideal for night study without glare.', 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=600&q=80', 'Sahan D.', 1, 'Available'),
('Stainless Steel Cordless Electric Kettle 1.8L', 'Appliances', 16.00, 'Good', 'Rapid boil electric kettle with automatic shut-off and boil-dry protection. Great for quick hostel tea, coffee, or noodles.', 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?auto=format&fit=crop&w=600&q=80', 'Amaya K.', 2, 'Available'),
('Ergonomic Laptop Stand & RGB Mechanical Keyboard', 'Electronics', 42.00, 'Like New', 'Aluminium foldable laptop cooling stand plus compact mechanical keyboard. Boosts posture and typing speed during programming labs.', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80', 'Kavindu P.', 1, 'Available'),
('Waterproof Heavy-Duty Campus Backpack 30L', 'Accessories', 32.00, 'Good', 'Spacious laptop compartment fits up to 15.6 inch laptops, padded straps, and breathable mesh back. Rain cover included.', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80', 'Charith W.', 1, 'Available'),
('Foldable Wooden Hostel Study Bed Table', 'Furniture', 20.00, 'Fair', 'Multi-purpose bed tray desk with cup holder and tablet slot. Folds flat for compact hostel room storage.', 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=600&q=80', 'Nethmi F.', 2, 'Available');
