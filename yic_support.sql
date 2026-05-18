-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.45 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `yic_support`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;

USE `yic_support`;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'open',
  PRIMARY KEY (`ticket_id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Password for all sample users: 123456
INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `student_id`, `role`) VALUES
(1, 'Leen Essam Al-booq', 'leen1@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500252', 'student'),
(2, 'Sara Ali', 'sara@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500253', 'student'),
(3, 'Noura Ahmed', 'noura@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500254', 'student'),
(4, 'Reem Khalid', 'reem@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500255', 'student'),
(5, 'Lama Hassan', 'lama@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500256', 'student'),
(6, 'Huda Saleh', 'huda@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500257', 'student'),
(7, 'Raghad Omar', 'raghad@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500258', 'student'),
(8, 'Abeer Mohammed', 'abeer@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500259', 'student'),
(9, 'Dana Faisal', 'dana@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500260', 'student'),
(10, 'Yara Saad', 'yara@uni.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq', '441500261', 'student'),
(11, 'leen albooq', 'Y4F441500252@university.edu', '$2y$10$HUw7aOuw1Gwz9OwN/UJPc.pvvaw8.Im3xoFZzaYLq4DvsMlmN9M6e', '441500252', 'student');

-- Admin password: 123456
INSERT INTO `admins` (`admin_id`, `full_name`, `email`, `password`) VALUES
(2, 'Admin', 'admin@yic.edu', '$2y$10$SNhL9AyyJ4oUr7hTBUZt6eYWoCqhkIBsQNG4Vp.2pz4s1H0ISMfTq');

INSERT INTO `tickets` (`ticket_id`, `user_id`, `admin_id`, `title`, `description`, `status`) VALUES
(1, 1, NULL, 'Internet Issue', 'Cannot connect to WiFi', 'open'),
(2, 2, NULL, 'Login Problem', 'Cannot login to system', 'open'),
(3, 3, NULL, 'Printer Error', 'Printer not working', 'in-progress'),
(4, 4, NULL, 'Software Crash', 'App crashes frequently', 'resolved'),
(5, 5, NULL, 'Email Issue', 'Email not sending', 'open'),
(6, 6, NULL, 'Slow Computer', 'System is very slow', 'closed'),
(7, 7, NULL, 'Keyboard Problem', 'Keys not working', 'open'),
(8, 8, NULL, 'Monitor Issue', 'Screen flickering', 'resolved'),
(9, 9, NULL, 'Network Down', 'No internet access', 'in-progress'),
(10, 10, NULL, 'Update Error', 'System update failed', 'open'),
(12, 11, NULL, 'network', 'network dose not working', 'in-progress');

SET FOREIGN_KEY_CHECKS=1;