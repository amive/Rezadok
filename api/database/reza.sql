-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 11:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reza`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('pending','confirmed','completed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `appointment_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `status`, `created_at`, `appointment_time`) VALUES
(14, 1, 9, '2025-04-22 16:40:00', 'canceled', '2025-04-22 12:12:32', NULL),
(15, 1, 9, '2025-04-22 17:47:00', 'confirmed', '2025-04-22 14:47:21', NULL),
(16, 1, 9, '2025-04-25 17:52:00', 'confirmed', '2025-04-22 14:50:19', NULL),
(17, 1, 9, '2025-04-22 21:01:00', 'canceled', '2025-04-22 18:59:33', NULL),
(18, 1, 9, '2025-04-25 00:46:00', 'pending', '2025-04-22 20:44:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `file_path`, `file_type`) VALUES
(18, 9, 2, 'ااااااا', '2025-04-22 15:41:58', NULL, NULL),
(19, 9, 1, 'اىىىى', '2025-04-22 15:42:33', NULL, NULL),
(20, 1, 9, 'وي سلعة', '2025-04-22 15:42:50', NULL, NULL),
(21, 9, 1, '', '2025-04-22 15:43:21', 'uploads/images/6807ab096f1dd_دعم المرضى.jpg', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `doctor_id`, `title`, `content`, `created_at`, `image`) VALUES
(8, 9, 'علم المناعة الورمي: نقلة نوعية في علاج السرطان  ', '\r\n\r\n???? **علم المناعة الورمي: نقلة نوعية في علاج السرطان** ????\r\n\r\nهل تصدق أن جهازك المناعي قد يكون أقوى سلاح في مواجهة السرطان؟  \r\nنعم! علم المناعة الورمي غيّر قواعد اللعبة ????\r\n\r\nزمان، كان علاج السرطان يعتمد بشكل أساسي على الجراحة، والعلاج الكيميائي، والإشعاعي.  \r\nلكن اليوم، دخلنا عصر جديد… **عصر العلاج المناعي** ????\r\n\r\n???? **ما هو علم المناعة الورمي؟**  \r\nهو العلم الذي يدرس العلاقة بين **الجهاز المناعي** والخلايا السرطانية، وكيف يمكن \"تحفيز\" المناعة لمهاجمة الورم وكأنه عدو يجب القضاء عليه.\r\n\r\n???? **ما الفرق؟**  \r\nبدل ما نقتل الخلايا السرطانية بأي وسيلة، زي العلاج الكيميائي، اللي يأثر على الخلايا السليمة كمان…  \r\nالعلاج المناعي يعلّم جهاز المناعة كيف يهاجم الخلايا الخبيثة **بدقة**، ويخلي الجسم هو اللي يقاوم المرض ????\r\n\r\n???? من أبرز العلاجات:\r\n- ✅ مثبطات نقاط التفتيش (Checkpoint Inhibitors): تعيد تنشيط الخلايا المناعية لمهاجمة الورم.\r\n- ✅ العلاج بالخلايا التائية المعدلة (CAR-T): تعديل خلايا مناعية لتصبح قاتلة للسرطان.\r\n\r\n✨ والنتيجة؟  \r\n- حالات شُفيت من سرطانات كانت تصنّف \"مستعصية\"\r\n- آثار جانبية أقل من العلاج الكيميائي\r\n- أمل جديد لمرضى فقدوا الأمل\r\n\r\n???? الخلاصة:  \r\n**علم المناعة الورمي مش بس تقدم طبي، بل ثورة حقيقية في علاج السرطان.**  \r\nومع تطور الأبحاث، المستقبل مبشّر أكتر وأكتر ????????\r\n\r\n#السرطان #المناعة_الورمية #علم_المناعة #علاج_السرطان #أمل_جديد\r\n\r\n', '2025-04-22 11:13:07', '680779c30f07a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('doctor','patient') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `specialty` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `specialty`, `bio`, `profile_picture`) VALUES
(1, 'patient_1', 'patient_1@gmail.com', '$2y$10$IkKlcOGw4hdo60MEgIs3z.b9/c1ajg3588mvp5YGZ4y3q4gAB1nB2', '', 'patient', '2025-04-22 08:23:14', NULL, NULL, NULL),
(2, 'patient_1', 'garahbilal02@gmail.com', '$2y$10$BLofbpklmmNf6Tqmp8jvMOWHzE7IEV4tbIK6Olc91/BsAr11y58ai', '0772622596', 'patient', '2025-04-22 10:08:28', NULL, NULL, NULL),
(3, 'patient_2', 'patient_2@gmail.com', '$2y$10$vQdnvKr6rOZ6gDN/G/Lqg.idY9SUmrO9BkhMluGiDTppNJCgLLn.2', '0558611066', 'patient', '2025-04-22 10:10:46', NULL, NULL, NULL),
(4, 'patient_3', 'patient_3@gmail.com', '$2y$10$LukLbxlzXLv8Lizi8nI1g.odzR8DNQbRPZF/opjWoN44Kye/ep22.', '0558611066', 'patient', '2025-04-22 10:13:27', NULL, NULL, NULL),
(5, 'patient_4', 'patient_4@gmail.com', '$2y$10$MJ5GFSvWhHdnhe5fzos/AeoKsnDVRm3HpqoELiiCa5LEHfeUsQEX2', '0558611066', 'patient', '2025-04-22 10:21:35', NULL, NULL, NULL),
(6, 'patient_5', 'patient_5@gmail.com', '$2y$10$27bdo6snLOxXbU/8iTnC3OpfzEzkT3K4nzT4SbKd30w7oIFJpXX0m', '0558611066', 'patient', '2025-04-22 10:23:55', NULL, NULL, 'uploads/1745317435_نونو_معدلة3.jpg'),
(7, 'patient_6', 'patient_6@gmail.com', '$2y$10$4yTTJF04cwzWItX.Kp8e0eaNvVkEH5mQsquHpBoFbiqCpNUA4xrk2', '0772622596', 'patient', '2025-04-22 10:38:19', NULL, NULL, 'uploads/default_avatar.jpg'),
(8, 'patient_7', 'patient_7@gmail.com', '$2y$10$KDCVU6qxjr7RyIp/65a5lOM0Et3pqDJ9WaFT5Jqf.Si18md5fQCEO', '0558611066', 'patient', '2025-04-22 10:39:23', NULL, NULL, 'uploads/default_avatar.jpg'),
(9, 'doctor_1', 'doctor_1@gmail.com', '$2y$10$T2Amzld2eotZhlkUmrA/he3Rs7p5F6sxXv5dSeY41gpCtNL4q8AZu', 'patient_2', 'doctor', '2025-04-22 10:40:59', 'طب النساء والتوليد', 'لالالالالالالالالالالا', 'uploads/default_avatar.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
