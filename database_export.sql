-- Database: club_db
-- Exported via PHP script

DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years` (
  `year_id` int(11) NOT NULL AUTO_INCREMENT,
  `year_name` varchar(10) NOT NULL COMMENT 'เช่น 2567',
  `is_current` tinyint(1) DEFAULT 0 COMMENT '1=ปีปัจจุบัน',
  PRIMARY KEY (`year_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `academic_years` (`year_id`, `year_name`, `is_current`) VALUES ('1', '2566', '0');
INSERT INTO `academic_years` (`year_id`, `year_name`, `is_current`) VALUES ('2', '2567', '0');
INSERT INTO `academic_years` (`year_id`, `year_name`, `is_current`) VALUES ('9', '2569', '1');

DROP TABLE IF EXISTS `activities`;
CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(200) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `academic_year_id` int(11) NOT NULL,
  `created_by_committee` int(11) DEFAULT NULL,
  `advisors_id` int(11) NOT NULL,
  `status` enum('planning','approved','completed','cancelled','rejected') DEFAULT 'planning',
  `created_at` datetime DEFAULT current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `qr_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `academic_year_id` (`academic_year_id`),
  CONSTRAINT `fk_act_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`year_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activities` (`activity_id`, `activity_name`, `cover_image`, `description`, `start_date`, `end_date`, `location`, `academic_year_id`, `created_by_committee`, `advisors_id`, `status`, `created_at`, `latitude`, `longitude`, `qr_token`) VALUES ('2', 'Workshop CodeIgniter 4', NULL, 'อบรมการเขียนโปรแกรมเว็บเบื้องต้น', '2026-07-10 13:00:00', '2026-07-10 17:00:00', 'ห้องคอมพิวเตอร์ 402', '2', NULL, '0', 'approved', '2026-02-12 00:37:40', NULL, NULL, NULL);
INSERT INTO `activities` (`activity_id`, `activity_name`, `cover_image`, `description`, `start_date`, `end_date`, `location`, `academic_year_id`, `created_by_committee`, `advisors_id`, `status`, `created_at`, `latitude`, `longitude`, `qr_token`) VALUES ('3', 'งานเฟรชชี่ไนท์ 2026', NULL, 'กิจกรรมต้อนรับน้องใหม่', '2026-06-20 17:00:00', '2026-06-20 22:00:00', 'หอประชุมใหญ่', '1', NULL, '0', 'completed', '2026-02-12 09:57:42', NULL, NULL, NULL);
INSERT INTO `activities` (`activity_id`, `activity_name`, `cover_image`, `description`, `start_date`, `end_date`, `location`, `academic_year_id`, `created_by_committee`, `advisors_id`, `status`, `created_at`, `latitude`, `longitude`, `qr_token`) VALUES ('4', 'อบรมเขียนเว็บเบื้องต้น', '1772610021_42b1214745f45e21a919.png', 'สอนพื้นฐาน HTML CSS JS', '2026-07-15 09:00:00', '2026-07-16 16:00:00', 'ห้องคอม 1', '1', NULL, '0', 'approved', '2026-02-12 09:57:42', NULL, NULL, 'ad464bb0ca36519a3430cffc149d37ed');
INSERT INTO `activities` (`activity_id`, `activity_name`, `cover_image`, `description`, `start_date`, `end_date`, `location`, `academic_year_id`, `created_by_committee`, `advisors_id`, `status`, `created_at`, `latitude`, `longitude`, `qr_token`) VALUES ('6', 'Workshop เขียนโปรแกรม', NULL, 'อบรมพื้นฐานการสร้างเว็บด้วย CodeIgniter 4', '2026-07-15 09:00:00', '2026-07-16 16:00:00', 'ห้องคอมพิวเตอร์ 1', '1', NULL, '0', 'approved', '2026-02-12 10:11:29', NULL, NULL, NULL);

DROP TABLE IF EXISTS `activity_documents`;
CREATE TABLE `activity_documents` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`document_id`),
  KEY `registration_id` (`registration_id`),
  CONSTRAINT `fk_doc_reg` FOREIGN KEY (`registration_id`) REFERENCES `activity_registrations` (`registration_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `activity_registrations`;
CREATE TABLE `activity_registrations` (
  `registration_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `register_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `checkin_time` datetime DEFAULT NULL,
  PRIMARY KEY (`registration_id`),
  KEY `student_id` (`student_id`),
  KEY `activity_id` (`activity_id`),
  CONSTRAINT `fk_reg_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`),
  CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_registrations` (`registration_id`, `student_id`, `activity_id`, `register_date`, `status`, `checkin_time`) VALUES ('2', '661102064116', '3', '2026-02-12 04:03:34', 'approved', NULL);
INSERT INTO `activity_registrations` (`registration_id`, `student_id`, `activity_id`, `register_date`, `status`, `checkin_time`) VALUES ('4', '661102064118', '3', '2026-02-12 04:33:06', 'approved', NULL);

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`admin_id`, `username`, `password`, `full_name`) VALUES ('4', 'admin', '$2y$10$qiW4lTRycIT4Tq2t3Vl7heHM5.Gr4pok2uSr22FY6eax0ni4IOAc2', 'ผู้ดูแลระบบ (System Admin)');

DROP TABLE IF EXISTS `advisors`;
CREATE TABLE `advisors` (
  `advisor_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`advisor_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `advisors` (`advisor_id`, `username`, `password`, `full_name`, `email`, `phone`) VALUES ('9', 'advisor01', '$2y$10$gG6TJBjfw/ySbzWsHFl2MeeN6z4DnEk2eoJIk2e00IEhd1LWo8ZMG', 'อาจารย์ สมชาย ใจดี', 'smosan@pcru.ac.th', '088888888');

DROP TABLE IF EXISTS `club_positions`;
CREATE TABLE `club_positions` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(50) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `club_positions` (`position_id`, `position_name`) VALUES ('1', 'นายกสโมสร');
INSERT INTO `club_positions` (`position_id`, `position_name`) VALUES ('2', 'รองนายกสโมสร');
INSERT INTO `club_positions` (`position_id`, `position_name`) VALUES ('3', 'เลขานุการ');
INSERT INTO `club_positions` (`position_id`, `position_name`) VALUES ('7', 'คณะกรรมการ');

DROP TABLE IF EXISTS `committee_members`;
CREATE TABLE `committee_members` (
  `committee_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  PRIMARY KEY (`committee_id`),
  KEY `student_id` (`student_id`),
  KEY `academic_year_id` (`academic_year_id`),
  KEY `position_id` (`position_id`),
  CONSTRAINT `fk_comm_pos` FOREIGN KEY (`position_id`) REFERENCES `club_positions` (`position_id`),
  CONSTRAINT `fk_comm_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  CONSTRAINT `fk_comm_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`year_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `committee_members` (`committee_id`, `student_id`, `academic_year_id`, `position_id`) VALUES ('1', '661102064116', '1', '7');

DROP TABLE IF EXISTS `majors`;
CREATE TABLE `majors` (
  `major_id` int(11) NOT NULL AUTO_INCREMENT,
  `major_name` varchar(100) NOT NULL,
  PRIMARY KEY (`major_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `majors` (`major_id`, `major_name`) VALUES ('1', 'วิทยาการคอม');
INSERT INTO `majors` (`major_id`, `major_name`) VALUES ('2', 'เทคโนโลยีสารสนเทศ');
INSERT INTO `majors` (`major_id`, `major_name`) VALUES ('3', 'วิทยาศาสตร์สิ่งแวดล้อม');
INSERT INTO `majors` (`major_id`, `major_name`) VALUES ('4', 'คณิตศาสตร์และสถิติประยุกต์');
INSERT INTO `majors` (`major_id`, `major_name`) VALUES ('5', 'เคมี');

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` varchar(20) NOT NULL COMMENT 'รหัสนักศึกษา',
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `major_id` int(11) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`student_id`),
  KEY `major_id` (`major_id`),
  CONSTRAINT `fk_student_major` FOREIGN KEY (`major_id`) REFERENCES `majors` (`major_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `students` (`student_id`, `password`, `full_name`, `email`, `avatar`, `major_id`, `phone_number`, `created_at`) VALUES ('661102064116', '1234', 'นายภิรวัส พิกุลทอง', '', '1772606928_39ace9344740cc374c4a.png', '1', '0995587981', '2026-02-12 09:49:26');
INSERT INTO `students` (`student_id`, `password`, `full_name`, `email`, `avatar`, `major_id`, `phone_number`, `created_at`) VALUES ('661102064118', '$2y$10$9H1gp1XrvYkG..yBAYLHhe3P/eJzikKssPsryM83Fi8SPT0f03RQy', 'นายธนกร เขียวสไว', NULL, NULL, '1', '0000000', '2026-02-12 11:32:51');

