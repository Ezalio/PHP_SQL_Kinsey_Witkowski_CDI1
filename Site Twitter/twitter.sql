-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2024 at 12:09 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `twitter`
--

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tweets`
--

INSERT INTO `tweets` (`id`, `user_id`, `content`, `created_at`) VALUES
(11, 2, 'Sometimes Moon look like giant cookie. Must look into joining space program.', '2018-08-13 18:49:00'),
(12, 1, 'Has anybody ever seen a rock eat a cookie? Elmo is just curious.', '2022-01-05 21:27:13'),
(13, 3, 'Three thousand three hundred seventy! Ah ah ah!', '2022-03-30 20:19:15'),
(19, 4, 'I am in search of this super bowl everyone is talking about today. It must be a pretty large bowl…', '2021-02-07 18:00:06'),
(20, 4, 'It is so cold out that my toes are blue! Oh wait, that is just the color I am. Sorry for the false alarm, folks.', '2024-01-11 17:00:15'),
(21, 3, 'Two thousand nine hundred ninety-nine!', '2021-05-21 16:33:32'),
(23, 5, 'Can one bird make a funny pun? \r\nNo, but toucan!', '2022-08-23 16:25:44'),
(24, 5, 'Bert gives such great advice. Sometimes I wish he could do it without yelling, but nobody is perfect!', '2023-10-17 19:35:58'),
(25, 2, 'Me love cookies. Me Cookie Monster.', '2018-09-17 12:23:21'),
(26, 2, 'Three things me thankful for: cookies, cookies and more cookies. Me monster of simple tastes.', '2018-11-20 20:22:37'),
(27, 1, 'You can be anything you wanna be! But Elmo hopes you pick being kind. ❤️', '2022-01-31 14:03:21'),
(28, 1, 'Elmo loves to play checkers. When he does, Elmo is always red. Hey! Elmo is ALWAYS red all the time anyway!', '2014-12-22 17:02:05'),
(29, 5, 'Counting sheep is a great way to fall asleep, but Bert always tends to wake me up and ask why there are so many sheep in our room! 🐑🐑🐑🐑', '2023-06-24 23:35:22'),
(30, 6, 'Ernie adds lots of excitement to my life. I just wish he wouldn’t do it while I’m trying to read.', '2023-08-23 18:04:51'),
(31, 6, 'You might be surprised, but sometimes I like to do wild things. Like flip through a dictionary until I learn a new word.', '2022-09-06 16:12:36'),
(32, 7, "I'm going to the library today. Let me know if you need anything from the top shelf!", '2022-03-22 11:42:20'),
(33, 7, 'They say dinosaurs were just big birds. Does that mean I am a dinosaur? 🦖RAWR', '2024-02-27 16:10:29'),
(34, 7, 'Went bird watching today! I looked in the mirror!', '2018-05-08 19:48:11'),
(35, 8, 'The weekend is here. Time to stay in my trashcan and do nothing.', '2023-05-26 17:40:36'),
(36, 8, 'Wanna hear something scary?  \r\nYou have to go to work tomorrow.', '2023-10-29 18:58:01'),
(37, 8, 'Rainy days are my favorite. I like to watch everyone who forgot their umbrellas at home. Heh Heh Heh.', '2018-09-25 16:30:47'),
(38, 2, 'Me just took a DNA test turns out me 100% cookies...', '2019-09-10 15:27:18'),
(39, 4, 'I do not want to say it is freezing cold outside, but my fingers are blue.', '2022-01-31 14:03:40'),
(43, 4, 'Does anyone know how to get pizza stains out of a super cape? Asking for a friend.', '2023-07-30 19:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'Elmo', 'elmo@sesame.street', 'elmo'),
(2, 'Cookie Monster', 'cookie@sesame.street', 'cookie'),
(3, 'Count von Count', 'count@sesame.street', 'countvoncount'),
(4, 'Grover', 'grover@sesame.street', 'grover'),
(5, 'Ernie', 'ernie@sesame.street', 'ernie'),
(6, 'Bert', 'bert@sesame.street', 'bert'),
(7, 'Big Bird', 'bigbird@sesame.street', 'bigbird'),
(8, 'Oscar the Grouch', 'oscar@sesame.street', 'oscar'),
(17, 'Slimey', 'slimey@sesame.street', 'slimey');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tweets`
--
ALTER TABLE `tweets`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
