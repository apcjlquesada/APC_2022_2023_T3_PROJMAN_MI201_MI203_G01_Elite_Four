-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2023 at 11:00 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `complete_name` varchar(50) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `verification` varchar(255) NOT NULL,
  `account_created` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `profile_picture`, `complete_name`, `email_address`, `password`, `phone_number`, `verification`, `account_created`) VALUES
(18, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Michael Alfaras', 'alfaras.dmc78@gmail.com', '4c8c72011b93d4ff7fbd9dcdd5ae010c7529d632', '09154601667', '', '2023-02-17');

-- --------------------------------------------------------

--
-- Table structure for table `car_models`
--

CREATE TABLE `car_models` (
  `car_model_id` int(11) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'AVAILABLE',
  `date_uploaded` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_models`
--

INSERT INTO `car_models` (`car_model_id`, `car_model`, `status`, `date_uploaded`) VALUES
(31, 'Toyota Rush', 'AVAILABLE', '2023-02-28'),
(32, 'Toyota Wigo', 'AVAILABLE', '2023-02-28'),
(33, 'Ford Everest', 'AVAILABLE', '2023-02-28'),
(34, 'Toyota Hilux', 'AVAILABLE', '2023-02-28'),
(35, 'Mitsubishi Xpander Cross', 'AVAILABLE', '2023-02-28'),
(36, 'Nissan Terra', 'AVAILABLE', '2023-02-28'),
(37, 'Mitsubishi L300', 'AVAILABLE', '2023-02-28'),
(38, 'Geely Coolray', 'AVAILABLE', '2023-02-28'),
(39, 'Honda Brio', 'AVAILABLE', '2023-02-28'),
(40, 'Kia Seltos', 'AVAILABLE', '2023-02-28'),
(41, 'Mitsubishi Strada', 'AVAILABLE', '2023-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `complete_name` varchar(50) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `verification` varchar(255) NOT NULL,
  `account_created` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `profile_picture`, `complete_name`, `email_address`, `password`, `phone_number`, `verification`, `account_created`) VALUES
(150, '1.jpg', 'Andre Viernes', 'aoblina17@gmail.com', '6389202fb925a92be282a1594daeee223f9d484b', '09991109385', '', '2023-02-28'),
(151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', 'erinchaz23@gmail.com', '0ddbf0afc16109d0c8d178864460fb92c5c34932', '09567663825', '', '2023-02-28'),
(153, '22.jpg', 'Aly Garcia', 'garciaalyssa6822@gmail.com', '4634d76843fc4db096e96901f1b98a2e46d7b1aa', '09353385479', '', '2023-02-28'),
(155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', 'alfaras.dmc78@gmail.com', 'fbde2cdb2fab1098299a527c2832a8e604673778', '09155555555', '', '2023-03-02');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `faq_title` varchar(100) NOT NULL,
  `faq_description` text NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'SHOW',
  `date_uploaded` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `faq_title`, `faq_description`, `status`, `date_uploaded`) VALUES
(84, 'How frequently should I get an oil change?', 'The popular thought is that an oil change should be performed every three months or \r\n               every 3,000 to 7,000 miles for drivers. You can always consult your owner\'s manual,\r\n               which includes the suggested time between oil changes for the make and model of your\r\n               vehicle. ', 'SHOW', '2023-03-10'),
(85, 'How frequently should I check the fluid levels in my car?', 'In addition to engine oil, your car also needs a variety of other fluids to function properly.\r\n               The transmission fluid, brake fluid, engine coolant, and power steering fluid are a few examples.\r\n               Extreme temperatures have an impact on them as well as how frequently you drive.\r\n               As a result, experts advise inspecting them every month or two or at the same time\r\n               as your oil change.', 'SHOW', '2023-03-10'),
(86, 'How often should I change my air filter?', 'In a car, there are often two filters. The engine uses one, while the cabin uses the other.\r\n                A clogged filter can obstruct airflow, reducing the engine\'s capacity to expel hot air\r\n               and obstructing the passage of clean air. The simplest way to tell when it needs to\r\n               be replaced is to check it periodically. Though the typical duration between changes\r\n               is around 30,000 miles, the frequency varies. \r\n          ', 'SHOW', '2023-03-10'),
(87, 'When should I replace my brake pads?', 'Brake pads typically start to wear out between 20,000 and 30,000 miles, however a\r\n               variety of factors can affect this estimate. Therefore, recognizing the warning\r\n               signs can help you determine when it\'s time to change your brake pads.\r\n               Scheduling a checkup is essential if you sense your car taking longer to stop\r\n               or hear a high-pitched screaming or grinding noise when braking.\r\n   ', 'SHOW', '2023-03-10'),
(88, 'When should I replace my car battery?', 'The average battery is expected to last three to five years,\r\n               but since environmental factors like heat and cold can affect its\r\n               longevity, it is wise to watch for warning signals that your\r\n               battery power is dwindling. It may be time to replace your vehicle\'s\r\n               battery if your dashboard and headlights are beginning to fade\r\n               or if your car is having trouble starting.', 'SHOW', '2023-03-09'),
(89, 'When should I get a tire rotation?', 'Tires sustain the most damage because they are the primary point\r\n                of contact with the pavement. This is especially true if you \r\n                use your car for off-roading or it has a two-wheel drive system\r\n                 that distributes the load among a few wheels. Therefore,\r\n                  it is a good rule to follow to have your tires rotated every six months\r\n                   or around every 6,000 to 8,000 miles. Your tires will wear evenly if you do this.', 'SHOW', '2023-03-09'),
(90, 'How much air pressure should I put into my tires?', 'Depending on the tire\'s type and the vehicle, different pressure levels are needed.\r\n               The recommended air pressure is listed on the door jamb, the tire sidewall,\r\n               or in your owner\'s handbook, among other places. A blow-out might happen\r\n               if there is insufficient pressure, which can also make it challenging\r\n               for you to maneuver your car. The best pressure levels may always be\r\n               determined by speaking with a product expert.', 'SHOW', '2023-03-09'),
(91, 'What should I do when my check engine lights comes on?', 'There could be a lot of problems when your check engine light illuminates.\r\n                It can be something simple, like a gas cap that is loose, or it might be\r\n                 something more catastrophic. Bringing your car in for a multi-point check \r\n                 is the greatest approach to make sure you are safe to drive. We can\r\n                  identify the problem and let you know about it so you may decide whether \r\n                  to take further action to restore your car to top', 'SHOW', '2023-03-09'),
(92, 'What should I do if my automobile seems to be overheating?', 'If your vehicle overheats, major and costly engine damage may occur.\r\n                Pull over to the side of the road and shut off the engine when it is\r\n                 secure to do so. The car will start to cool down as a result of this.\r\n                  DO NOT attempt to check the radiator\'s fluid level because it is \r\n                  extremely hot and could result in severe burns. We suggest that \r\n                  drivers have their overheated car towed to the closest, reliable auto shop', 'SHOW', '2023-03-09'),
(93, 'Should the color of my oil be milky brown?', 'No! It\'s a common sign that radiator coolant has gotten into the \r\n               engine oil if the oil in your engine has a milky brown tint. \r\n               The cause is frequently a blown head gasket (or other similar gasket).\r\n                It can also be a sign of a broken case or a failing transmission cooler.\r\n                 A skilled mechanic should be consulted as soon as possible to\r\n                  analyze milky brown oil, which is a highly hazardous condition.', 'SHOW', '2023-03-09');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `gallery_picture` varchar(100) NOT NULL,
  `gallery_picture_name` varchar(100) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'SHOW',
  `date_uploaded` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_picture`, `gallery_picture_name`, `status`, `date_uploaded`) VALUES
(31, 'photo1676984848 (6).jpeg', 'image ', 'SHOW', '2023-03-05'),
(35, '3.png', 'Image 3', 'SHOW', '2023-03-10'),
(36, '4.png', 'Image 4', 'SHOW', '2023-03-10'),
(37, '5.png', 'Image 5', 'SHOW', '2023-03-10'),
(38, '1.png', 'Image 6', 'SHOW', '2023-03-10'),
(39, '2.png', 'Image 1', 'SHOW', '2023-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `promos`
--

CREATE TABLE `promos` (
  `promo_id` int(11) NOT NULL,
  `promo_poster` varchar(100) NOT NULL,
  `promo_name` varchar(100) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'SHOW',
  `date_uploaded` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promos`
--

INSERT INTO `promos` (`promo_id`, `promo_poster`, `promo_name`, `status`, `date_uploaded`) VALUES
(22, 'promo1.jpg', 'promo', 'SHOW', '2023-03-12'),
(23, 'promo3.jpg', 'promo2', 'HIDE', '2023-03-10'),
(24, 'promo3.jpg', 'promo3', 'HIDE', '2023-03-10'),
(25, 'promo1.jpg', 'promo4', 'HIDE', '2023-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `customer_email` varchar(32) NOT NULL,
  `schedule` datetime NOT NULL,
  `customer_number` varchar(11) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'PENDING',
  `date_placed` date NOT NULL DEFAULT CURDATE() ,
  `date_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `customer_name`, `service_type`, `car_model`, `customer_email`, `schedule`, `customer_number`, `status`, `date_placed`, `date_updated`) VALUES
(237, 155, 'Dan Alfaras', 'Wheel Alignment', 'Mitsubishi Strada', 'alfaras.dmc78@gmail.com', '2023-03-04 08:00:00', '09154601667', 'COMPLETED', '2023-03-03', '2023-02-28'),
(242, 155, 'Dan Alfaras', 'Wheel Alignment', 'A3 Audi', 'alfaras.dmc78@gmail.com', '2023-03-05 08:00:00', '09154601667', 'COMPLETED', '2023-03-03', '2023-02-28'),
(246, 155, 'Dan Alfaras', 'Wheel Alignment', 'Mitsubishi Strada', 'alfaras.dmc78@gmail.com', '2023-03-04 09:00:00', '09154601667', 'COMPLETED', '2023-03-03', '2023-02-28'),
(248, 155, 'Dan Alfaras', 'Engine Overhaul', 'Kia Seltos', 'alfaras.dmc78@gmail.com', '2023-03-06 09:00:00', '09154601667', 'CANCELLED', '2023-03-03', '2023-02-28'),
(250, 155, 'Dan Alfaras', 'Oil Change', 'Toyota Altis 2021', 'alfaras.dmc78@gmail.com', '2023-03-05 12:00:00', '09154601667', 'COMPLETED', '2023-03-04', '2023-03-04'),
(251, 155, 'Dan Alfaras', 'Engine Overhaul', 'Mitsubishi Strada', 'alfaras.dmc78@gmail.com', '2023-03-08 12:00:00', '09154601667', 'COMPLETED', '2023-03-04', '2023-02-28'),
(252, 155, 'Dan Alfaras', 'Engine Overhaul', 'Mitsubishi Strada', 'alfaras.dmc78@gmail.com', '2023-03-06 12:00:00', '09154601667', 'COMPLETED', '2023-03-04', '2023-02-28'),
(253, 155, 'Dan Alfaras', 'Engine Overhaul', 'Toyota Altis 2021', 'alfaras.dmc78@gmail.com', '2023-03-06 13:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-02-28'),
(254, 155, 'Dan Alfaras', 'Engine Overhaul', 'Toyota Fortuner', 'alfaras.dmc78@gmail.com', '2023-03-15 13:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-03-04'),
(255, 155, 'Dan Alfaras', 'Oil Change', 'Kia Seltos', 'alfaras.dmc78@gmail.com', '2023-03-10 13:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-03-04'),
(256, 155, 'Dan Alfaras', 'Engine Overhaul', 'Toyota Fortuner', 'alfaras.dmc78@gmail.com', '2023-03-09 15:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-03-04'),
(257, 156, 'Dan Michael C. Alfaras', 'Engine Overhaul', 'Toyota Fortuner', 'danskie.alfaras78@gmail.com', '2023-03-07 08:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-03-04'),
(258, 156, 'Dan Michael C. Alfaras', 'Engine Overhaul', 'Toyota Fortuner', 'danskie.alfaras78@gmail.com', '2023-03-17 09:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-02-08'),
(259, 156, 'Dan Michael C. Alfaras', 'Engine Overhaul', 'Toyota Fortuner', 'danskie.alfaras78@gmail.com', '2023-03-24 09:00:00', '09154601667', 'CANCELLED', '2023-03-04', '2023-02-28'),
(261, 151, 'Erin Camino', 'Oil Change', 'Mitsubishi Xpander Cross', 'erinchaz23@gmail.com', '2023-03-23 14:00:00', '09567663825', 'CANCELLED', '2023-03-09', '2023-03-09'),
(262, 151, 'Erin Camino', 'Engine Overhaul', 'Toyota Hilux', 'erinchaz23@gmail.com', '2023-03-28 15:00:00', '09567663825', 'CANCELLED', '2023-03-09', '2023-03-09'),
(263, 151, 'Erin Camino', 'Tires and Batteries', 'Kia Seltos', 'erinchaz23@gmail.com', '2023-03-27 14:00:00', '09567663825', 'COMPLETED', '2023-03-09', '2023-03-09'),
(264, 150, 'Andre Viernes', 'Brakes', 'Ford Everest', 'aoblina17@gmail.com', '2023-03-14 09:00:00', '09991109385', 'COMPLETED', '2023-03-09', '2023-03-10'),
(265, 150, 'Andre Viernes', 'Oil Change', 'Toyota Hilux', 'aoblina17@gmail.com', '2023-03-24 11:00:00', '09991109385', 'COMPLETED', '2023-03-09', '2023-03-10'),
(267, 155, 'Dan Alfaras', 'Underchassis Repair', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-03-25 14:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-02-12'),
(268, 155, 'Dan Alfaras', 'Wheel Alignment', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-03-30 14:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-03-10'),
(269, 155, 'Dan Alfaras', 'Underchassis Repair', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-04-06 14:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-03-10'),
(271, 155, 'Dan Alfaras', 'Underchassis Repair', 'Toyota Rush', 'alfaras.dmc78@gmail.com', '2023-03-18 15:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-03-10'),
(272, 155, 'Dan Alfaras', 'Computerized Diagnostic', 'Toyota Hilux', 'alfaras.dmc78@gmail.com', '2023-04-05 09:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-03-10'),
(273, 155, 'Dan Alfaras', 'Tires and Batteries', 'Ford Everest', 'alfaras.dmc78@gmail.com', '2023-04-04 09:00:00', '09154601667', 'COMPLETED', '2023-03-09', '2023-03-10'),
(274, 155, 'Dan Alfaras', 'Tires and Batteries', 'Ford Everest', 'alfaras.dmc78@gmail.com', '2023-03-24 12:00:00', '09154601667', 'CANCELLED', '2023-03-10', '2023-03-10'),
(275, 155, 'Dan Alfaras', 'Oil Change', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-04-06 15:00:00', '09154601667', 'COMPLETED', '2023-03-10', '2023-03-10'),
(276, 151, 'Erin Camino', 'Underchassis Repair', 'Toyota Hilux', 'erinchaz23@gmail.com', '2023-03-22 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(277, 151, 'Erin Camino', 'Oil Change', 'Mitsubishi Xpander Cross', 'erinchaz23@gmail.com', '2023-03-30 12:00:00', '09567663825', 'CANCELLED', '2023-03-10', '2023-03-10'),
(278, 151, 'Erin Camino', 'Computerized Diagnostic', 'Nissan Terra', 'erinchaz23@gmail.com', '2023-05-01 12:00:00', '09567663825', 'PENDING', '2023-03-10', NULL),
(279, 151, 'Erin Camino', 'Engine Overhaul', 'Toyota Wigo', 'erinchaz23@gmail.com', '2023-05-01 15:00:00', '09567663825', 'CANCELLED', '2023-03-10', '2023-03-10'),
(280, 151, 'Erin Camino', 'Tires and Batteries', 'Ford Everest', 'erinchaz23@gmail.com', '2023-03-26 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(281, 151, 'Erin Camino', 'Brakes', 'Ford Everest', 'erinchaz23@gmail.com', '2023-03-25 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(282, 151, 'Erin Camino', 'Computerized Diagnostic', 'Ford Everest', 'erinchaz23@gmail.com', '2023-03-17 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(283, 151, 'Erin Camino', 'Balancing', 'Kia Seltos', 'erinchaz23@gmail.com', '2023-03-11 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(284, 151, 'Erin Camino', 'Wheel Alignment', 'Toyota Wigo', 'erinchaz23@gmail.com', '2023-03-12 12:00:00', '09567663825', 'COMPLETED', '2023-03-10', '2023-03-10'),
(285, 155, 'Dan Alfaras', 'Tires and Batteries', 'Mitsubishi Xpander Cross', 'alfaras.dmc78@gmail.com', '2023-03-12 16:00:00', '09155555555', 'COMPLETED', '2023-03-10', '2023-03-10'),
(286, 155, 'Dan Alfaras', 'Tires and Batteries', 'Toyota Rush', 'alfaras.dmc78@gmail.com', '2023-03-18 12:00:00', '09155555555', 'PENDING', '2023-03-10', NULL),
(287, 155, 'Dan Alfaras', 'Wheel Alignment', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-05-01 13:00:00', '09155555555', 'COMPLETED', '2023-03-10', '2023-03-10'),
(288, 155, 'Dan Alfaras', 'Wheel Alignment', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-03-25 13:00:00', '09155555555', 'COMPLETED', '2023-03-10', '2023-03-10'),
(289, 168, 'Dan Alfaras', 'Wheel Alignment', 'Toyota Hilux', 'danskie.alfaras78@gmail.com', '2023-04-08 13:00:00', '09154601667', 'COMPLETED', '2023-03-10', '2023-03-10'),
(290, 155, 'Dan Alfaras', 'Balancing', 'Toyota Hilux', 'alfaras.dmc78@gmail.com', '2023-04-04 14:00:00', '09155555555', 'CANCELLED', '2023-03-10', '2023-03-10'),
(291, 155, 'Dan Alfaras', 'Underchassis Repair', 'Kia Seltos', 'alfaras.dmc78@gmail.com', '2023-03-13 14:00:00', '09155555555', 'PENDING', '2023-03-10', NULL),
(292, 155, 'Dan Alfaras', 'Wheel Alignment', 'Mitsubishi Xpander Cross', 'alfaras.dmc78@gmail.com', '2023-04-07 15:00:00', '09155555555', 'PENDING', '2023-03-10', NULL),
(293, 155, 'Dan Alfaras', 'Brakes', 'Toyota Wigo', 'alfaras.dmc78@gmail.com', '2023-04-05 11:00:00', '09155555555', 'PENDING', '2023-03-10', NULL),
(294, 150, 'Andre Viernes', 'Engine Overhaul', 'Geely Coolray', 'aoblina17@gmail.com', '2023-03-15 10:00:00', '09991109385', 'COMPLETED', '2023-03-12', '2023-03-12'),
(295, 150, 'Andre Viernes', 'Underchassis Repair', 'Ford Everest', 'aoblina17@gmail.com', '2023-03-14 10:00:00', '09991109385', 'CANCELLED', '2023-03-12', '2023-03-12'),
(296, 150, 'Andre Viernes', 'Oil Change', 'Nissan Terra', 'aoblina17@gmail.com', '2023-03-27 11:00:00', '09991109385', 'CANCELLED', '2023-03-12', '2023-03-12');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_profile` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `rating` varchar(1) NOT NULL,
  `description` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `customer_id`, `customer_profile`, `customer_name`, `rating`, `description`, `date_posted`) VALUES
(54, 151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', '5', 'got my car diagnosed from the shop, they will tell everything  from what\'s wrong with your car and offer the right service', '2023-03-09 01:38:49'),
(55, 151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', '5', 'Got my suspension upgraded from them they really know what they\'re doing 5/5 stars service and quality ', '2023-03-09 01:39:07'),
(56, 151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', '5', 'I just picked up my car today. they definitely work fast but they make sure that the vehicle is in great and running condition.', '2023-03-09 01:39:34'),
(57, 150, '1.jpg', 'Andre Viernes', '5', 'They do everything related to vehicles. If you have problems with your car,bikes or even trucks they\'d be the one you should call.', '2023-03-09 01:42:12'),
(58, 150, '1.jpg', 'Andre Viernes', '5', 'They were referred to me by a friend, they definitely don\'t disappoint the shop staff are kind and accommodating most of all they offer discounts.\r\n', '2023-02-28 01:42:31'),
(59, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', '5', 'Very nice and friendly mechanics', '2023-03-09 09:37:30'),
(60, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', '4', 'There is a bug in your web-app', '2023-03-09 09:40:18'),
(65, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', '5', 'The staffs were very accommodating and very friendly', '2023-03-09 23:29:12'),
(67, 168, 'dan.jpg', 'Dan Michael Alfaras', '4', 'The service is very nice. ', '2023-03-10 13:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_picture` varchar(100) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_description` text NOT NULL,
  `status` varchar(32) DEFAULT 'AVAILABLE',
  `date_uploaded` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_picture`, `service_name`, `service_description`, `status`, `date_uploaded`) VALUES
(97, 'Balancing.png', 'Balancing', 'Tire balancing corrects the weight imbalance on the tire and wheel of the \r\nvehicle. Proper balancing can enable a smooth ride, less tire wearing, and lesser \r\nstrain on the drivetrain of the device.', 'AVAILABLE', '2023-03-10'),
(98, 'Underchassis Repair.png', 'Underchassis Repair', 'Repairs the under chassis, which is a vital part of the vehicle comprised of the \r\nclutch, steering, and suspension to enable a safe drive. This ensures the safety \r\nfunctionality of the under chassis. ', 'AVAILABLE', '2023-03-05'),
(99, 'Tires and Batteries.png', 'Tires and Batteries', 'This service ensures the quality of the tires and batteries are of good health, to \r\nfunction properly for the vehicles, making sure that there are no possible \r\nfailures that might interrupt the smoothness of driving.', 'AVAILABLE', '2023-03-08'),
(100, 'Brakes.png', 'Brakes', 'This service enables checking and diagnosis of brakes of the vehicle, which is \r\nvery crucial for safety in driving. This service offers repair, replacement, and \r\ndiagnosis of the brakes. ', 'AVAILABLE', '2023-03-05'),
(101, 'Oil Change and Batteries.png', 'Oil Change', 'This service is a process of replacing fresh oil and removing unwanted oils that \r\nruns through in the internal combustion engine.', 'AVAILABLE', '2023-03-05'),
(102, 'Computerized Diagnostic.png', 'Computerized Diagnostic', 'This is a test that digital analysis of the car’s various computer system and \r\ncomponents. This test can detect problems before they cause a unexpected \r\nbreakdown', 'AVAILABLE', '2023-03-05'),
(103, 'Engine Overhaul.png', 'Engine Overhaul', 'This service is an adjustment or modifications of the internal combustion \r\nengine or to yield optimal performance and increase the engine’s power, \r\noutput, economy or durabilit', 'AVAILABLE', '2023-03-05'),
(104, 'Transmission.png', 'Transmission', 'This service that transfer the power from the engine to the drive shaft and the \r\ndifferential to let the wheels turn.', 'AVAILABLE', '2023-03-05'),
(110, 'Wheel Alignment.png', 'Wheel Alignment', 'The wheel alignment service is for the adjustment of wheel angles, in accordance to a car\'s specifications, so this varies. It is done to reduce wearing of tires, and that there is more comfortability in driving as the wheels are straight.', 'AVAILABLE', '2023-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `support_reply`
--

CREATE TABLE `support_reply` (
  `reply_id` int(11) NOT NULL,
  `support_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `complete_name` varchar(100) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `thread_response` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_reply`
--

INSERT INTO `support_reply` (`reply_id`, `support_id`, `customer_id`, `admin_id`, `complete_name`, `profile_picture`, `thread_response`, `date_posted`) VALUES
(32, 25, 150, 0, 'Andre Viernes', '1.jpg', '2', '2023-03-03 01:13:45'),
(33, 25, 0, 18, 'Dan Michael Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', '4', '2023-03-03 01:15:34'),
(34, 26, 0, 18, 'Dan Michael Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', 'sup', '2023-03-04 16:31:55'),
(35, 26, 150, 0, 'Andre Viernes', '1.jpg', 'Sup', '2023-03-04 16:33:48'),
(36, 28, 155, 0, 'Dan Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', '123412', '2023-03-04 21:59:53'),
(37, 26, 151, 0, 'Erin Camino', '277462357_2551316591668317_1182869813594210227_n.jpg', 'reply to hello what\'s up', '2023-03-07 22:25:03'),
(38, 26, 151, 0, 'Erin Camino', '277462357_2551316591668317_1182869813594210227_n.jpg', 'reply to whats', '2023-03-07 22:29:09'),
(39, 26, 151, 0, 'Erin Camino', '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin reply to what\'s up\r\n', '2023-03-07 23:15:56'),
(40, 44, 151, 0, 'Erin Camino', '277462357_2551316591668317_1182869813594210227_n.jpg', 'i can\'t afford to travel to Cavite with my car needing care that\'s why I am asking hehe', '2023-03-09 01:15:25'),
(41, 44, 150, 0, 'Andre Viernes', '1.jpg', 'Same question! I really hope they have other branches too!', '2023-03-09 01:42:53'),
(42, 44, 0, 18, 'Dan Michael Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', 'Hi! Erin, this is Dan from D7. Regarding the extension of the company. We have not yet planned to extend to other areas. But please, stay posted with our updates! ', '2023-03-09 01:45:39'),
(43, 45, 150, 0, 'Andre Viernes', '1.jpg', 'Err I don\'t think that\'s safe missy.', '2023-03-09 01:50:35'),
(44, 51, 0, 18, 'Dan Michael Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', 'Maybe it is stuck?', '2023-03-10 00:52:33'),
(45, 46, 0, 18, 'Dan Michael Alfaras', '254351157_589300142288374_2888679371265441279_n.jpg', 'As a specialist from D7 this usual indicate that your oil has been unchanged beyond its recommended period of oil change. You can have your Oil Change/Tune Up when you book a reservation with D7 Auto Service Center today.', '2023-03-10 00:56:43'),
(46, 45, 150, 0, 'Andre Viernes', '1.jpg', 'Hi! have u got an updated? ', '2023-03-12 17:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `support_tab`
--

CREATE TABLE `support_tab` (
  `support_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `complete_name` varchar(100) NOT NULL,
  `thread_title` varchar(100) NOT NULL,
  `thread_description` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_tab`
--

INSERT INTO `support_tab` (`support_id`, `customer_id`, `profile_picture`, `complete_name`, `thread_title`, `thread_description`, `date_posted`) VALUES
(44, 151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', 'When will D7 have other branches?', 'I am currently at Quezon City right now and I would love to have their service but they\'re too far :(', '2023-03-09 01:13:26'),
(45, 151, '277462357_2551316591668317_1182869813594210227_n.jpg', 'Erin Camino', 'Is installing fairy lights ok?', 'I would love to have fairy lights inside my car but not sure if it\'s safe #aesthetic\r\n', '2023-03-09 01:14:21'),
(46, 150, '1.jpg', 'Andre Viernes', 'My Oil is milky brown. Is that normal?', 'This is my first time having a car, and I am so sorry if this might be a silly question. But is having an oil that is milky brown normal? Or do I need to bring my car to d7?', '2023-03-09 01:48:48'),
(47, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', 'What is the possible problem?', 'The aircon and speaker of my van is not working.', '2023-03-09 09:39:48'),
(49, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', 'How often should I get my engine checked?', 'I only do this every 3 years is that ok?', '2023-03-09 23:32:08'),
(50, 155, '254351157_589300142288374_2888679371265441279_n.jpg', 'Dan Alfaras', ' Is a car insurance worth it for my 5 yr old car?', ' I have had this car for more than 5 years but recently, I was offered car insurance is it worth value of my money?', '2023-03-09 23:36:03'),
(51, 150, '1.jpg', 'Andre Viernes', 'Wiper moving on its own', 'I don\'t want this to be a scary story but my wiper apparently moves on its own. What should I do?', '2023-03-09 23:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `website_visits`
--

CREATE TABLE `website_visits` (
  `cookie_id` bigint(11) NOT NULL,
  `visit_time` date NOT NULL DEFAULT CURDATE() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_visits`
--

INSERT INTO `website_visits` (`cookie_id`, `visit_time`) VALUES
(2147483754, '2023-02-28'),
(2147483755, '2023-03-08'),
(2147483756, '2023-02-28'),
(2147483757, '2023-03-08'),
(2147483758, '2023-03-08'),
(2147483759, '2023-03-08'),
(2147483760, '2023-02-28'),
(2147483761, '2023-02-28'),
(2147483762, '2023-03-08'),
(2147483763, '2023-03-08'),
(2147483764, '2023-03-09'),
(2147483765, '2023-03-09'),
(2147483766, '2023-03-09'),
(2147483767, '2023-03-09'),
(2147483768, '2023-03-09'),
(2147483769, '2023-03-09'),
(2147483770, '2023-03-09'),
(2147483771, '2023-03-09'),
(2147483772, '2023-03-09'),
(2147483773, '2023-03-09'),
(2147483774, '2023-03-09'),
(2147483775, '2023-03-09'),
(2147483776, '2023-03-09'),
(2147483777, '2023-03-09'),
(2147483778, '2023-03-09'),
(2147483779, '2023-03-09'),
(2147483780, '2023-03-09'),
(2147483781, '2023-03-09'),
(2147483782, '2023-03-09'),
(2147483783, '2023-03-09'),
(2147483784, '2023-03-09'),
(2147483785, '2023-03-09'),
(2147483786, '2023-03-09'),
(2147483787, '2023-03-09'),
(2147483788, '2023-03-09'),
(2147483789, '2023-03-09'),
(2147483790, '2023-03-09'),
(2147483791, '2023-03-09'),
(2147483792, '2023-03-09'),
(2147483793, '2023-03-09'),
(2147483794, '2023-03-09'),
(2147483795, '2023-03-09'),
(2147483796, '2023-03-09'),
(2147483797, '2023-03-09'),
(2147483798, '2023-03-09'),
(2147483799, '2023-03-09'),
(2147483800, '2023-03-09'),
(2147483801, '2023-03-09'),
(2147483802, '2023-03-09'),
(2147483803, '2023-03-09'),
(2147483804, '2023-03-09'),
(2147483805, '2023-03-09'),
(2147483806, '2023-03-09'),
(2147483807, '2023-03-09'),
(2147483808, '2023-03-09'),
(2147483809, '2023-03-09'),
(2147483810, '2023-03-09'),
(2147483811, '2023-03-09'),
(2147483812, '2023-03-09'),
(2147483813, '2023-03-09'),
(2147483814, '2023-03-09'),
(2147483815, '2023-03-09'),
(2147483816, '2023-03-09'),
(2147483817, '2023-03-10'),
(2147483818, '2023-03-10'),
(2147483819, '2023-03-10'),
(2147483820, '2023-03-10'),
(2147483821, '2023-03-10'),
(2147483822, '2023-03-10'),
(2147483823, '2023-03-10'),
(2147483824, '2023-03-10'),
(2147483825, '2023-03-10'),
(2147483826, '2023-03-10'),
(2147483827, '2023-03-10'),
(2147483828, '2023-03-10'),
(2147483829, '2023-03-10'),
(2147483830, '2023-03-10'),
(2147483831, '2023-03-10'),
(2147483832, '2023-03-10'),
(2147483833, '2023-03-10'),
(2147483834, '2023-03-10'),
(2147483835, '2023-03-10'),
(2147483836, '2023-03-10'),
(2147483837, '2023-03-10'),
(2147483838, '2023-03-10'),
(2147483839, '2023-03-10'),
(2147483840, '2023-03-10'),
(2147483841, '2023-03-10'),
(2147483842, '2023-03-10'),
(2147483843, '2023-03-10'),
(2147483844, '2023-03-10'),
(2147483845, '2023-03-10'),
(2147483846, '2023-03-10'),
(2147483847, '2023-03-10'),
(2147483848, '2023-03-10'),
(2147483849, '2023-03-10'),
(2147483850, '2023-03-10'),
(2147483851, '2023-03-10'),
(2147483852, '2023-03-10'),
(2147483853, '2023-03-10'),
(2147483854, '2023-03-10'),
(2147483855, '2023-03-10'),
(2147483856, '2023-03-10'),
(2147483857, '2023-03-10'),
(2147483858, '2023-03-10'),
(2147483859, '2023-03-10'),
(2147483860, '2023-03-10'),
(2147483861, '2023-03-10'),
(2147483862, '2023-03-10'),
(2147483863, '2023-03-10'),
(2147483864, '2023-03-10'),
(2147483865, '2023-03-10'),
(2147483866, '2023-03-10'),
(2147483867, '2023-03-10'),
(2147483868, '2023-03-10'),
(2147483869, '2023-03-10'),
(2147483870, '2023-03-12'),
(2147483871, '2023-03-12'),
(2147483872, '2023-03-12'),
(2147483873, '2023-03-12'),
(2147483874, '2023-03-12'),
(2147483875, '2023-03-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `car_models`
--
ALTER TABLE `car_models`
  ADD PRIMARY KEY (`car_model_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `support_reply`
--
ALTER TABLE `support_reply`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `support_tab`
--
ALTER TABLE `support_tab`
  ADD PRIMARY KEY (`support_id`);

--
-- Indexes for table `website_visits`
--
ALTER TABLE `website_visits`
  ADD PRIMARY KEY (`cookie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `car_models`
--
ALTER TABLE `car_models`
  MODIFY `car_model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `promos`
--
ALTER TABLE `promos`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `support_reply`
--
ALTER TABLE `support_reply`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `support_tab`
--
ALTER TABLE `support_tab`
  MODIFY `support_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `website_visits`
--
ALTER TABLE `website_visits`
  MODIFY `cookie_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483876;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
