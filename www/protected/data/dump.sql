
--
-- foreign key checks, autocomit and start a transaction
--

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Structure for table `file`
--

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_file___path` (`path`),
  KEY `idx_file___file` (`file`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;


--
-- Data for table `file`
--

INSERT INTO `file` (`id`, `file`, `path`, `mime_type`, `size`, `ext`, `created_at`, `updated_at`) VALUES
  ('1', 'dsp_and_mirrow.png', '/upload/files/L85Q7Y14U5HU/dsp_and_mirrow.png', 'image/png', '16961', 'png', '2014-09-25 17:15:27', '2014-09-25 17:15:27'),
  ('2', 'mangal.jpg', '/upload/files/Y84U606JTT9U/mangal.jpg', 'image/jpeg', '70210', 'jpg', '2014-09-25 17:24:34', '2014-09-25 17:24:34'),
  ('3', '73062758_idea2.jpg', '/upload/files/L85Q7Y14U5HU/73062758_idea2.jpg', 'image/jpeg', '66730', 'jpg', '2014-09-26 10:23:51', '2014-09-26 10:23:51'),
  ('4', 'resetki.jpg', '/upload/files/L85Q7Y14U5HU/resetki.jpg', 'image/jpeg', '11948', 'jpg', '2014-09-26 10:26:05', '2014-09-26 10:26:05'),
  ('5', 'thumb_938_product_pictures_medium.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_938_product_pictures_medium.jpeg', 'image/jpeg', '56465', 'jpeg', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('6', '4.jpg', '/upload/files/L85Q7Y14U5HU/4.jpg', 'image/jpeg', '239161', 'jpg', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('7', '4.jpg', '/upload/files/Y84U606JTT9U/4.jpg', 'image/jpeg', '239161', 'jpg', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('8', '1.jpg', '/upload/files/L85Q7Y14U5HU/1.jpg', 'image/jpeg', '23761', 'jpg', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('9', 'thumb_848_product_pictures_medium.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_848_product_pictures_medium.jpeg', 'image/jpeg', '77202', 'jpeg', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('10', 'koshki_140.jpg', '/upload/files/L85Q7Y14U5HU/koshki_140.jpg', 'image/jpeg', '74745', 'jpg', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('11', 'red-cat.jpg', '/upload/files/L85Q7Y14U5HU/red-cat.jpg', 'image/jpeg', '50954', 'jpg', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('12', 'IMG_5861_Belkyn_6_mes__500.jpg', '/upload/files/L85Q7Y14U5HU/IMG_5861_Belkyn_6_mes__500.jpg', 'image/jpeg', '39339', 'jpg', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('13', 'thumb_1028_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1028_product_pictures_big.jpeg', 'image/jpeg', '189442', 'jpeg', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('14', '1.jpg', '/upload/files/Y84U606JTT9U/1.jpg', 'image/jpeg', '23761', 'jpg', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('15', '2.jpeg', '/upload/files/L85Q7Y14U5HU/2.jpeg', 'image/jpeg', '509423', 'jpeg', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('16', 'русский.png', '/upload/files/L85Q7Y14U5HU/русский.png', 'image/png', '218251', 'png', '2014-09-29 18:52:13', '2014-09-29 18:52:13'),
  ('17', '4.jpg', '/upload/files/3YT3TY1L4D71/4.jpg', 'image/jpeg', '239161', 'jpg', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('18', '3568437.jpg', '/upload/files/3YT3TY1L4D71/3568437.jpg', 'image/jpeg', '153398', 'jpg', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('19', 'koshki_140.jpg', '/upload/files/3YT3TY1L4D71/koshki_140.jpg', 'image/jpeg', '74745', 'jpg', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('20', 'ser.jpg', '/upload/files/3YT3TY1L4D71/ser.jpg', 'image/jpeg', '103777', 'jpg', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('21', '4.jpg', '/upload/files/58RZNU2O48I5/4.jpg', 'image/jpeg', '239161', 'jpg', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('22', '210653.jpg', '/upload/files/3YT3TY1L4D71/210653.jpg', 'image/jpeg', '168148', 'jpg', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('23', 'Красивый-белый-кот..jpg', '/upload/files/3YT3TY1L4D71/Красивый-белый-кот..jpg', 'image/jpeg', '63078', 'jpg', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('24', 'white cat 002.jpg', '/upload/files/3YT3TY1L4D71/white cat 002.jpg', 'image/jpeg', '176235', 'jpg', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('25', 'IMG_5861_Belkyn_6_mes__500.jpg', '/upload/files/3YT3TY1L4D71/IMG_5861_Belkyn_6_mes__500.jpg', 'image/jpeg', '39339', 'jpg', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('26', '1.jpg', '/upload/files/3YT3TY1L4D71/1.jpg', 'image/jpeg', '23761', 'jpg', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('27', 'red-cat.jpg', '/upload/files/3YT3TY1L4D71/red-cat.jpg', 'image/jpeg', '50954', 'jpg', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('28', 'images.jpg', '/upload/files/3YT3TY1L4D71/images.jpg', 'image/jpeg', '7126', 'jpg', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('29', 'парам.jpg', '/upload/files/3YT3TY1L4D71/парам.jpg', 'image/jpeg', '47878', 'jpg', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('30', '3.jpg', '/upload/files/3YT3TY1L4D71/3.jpg', 'image/jpeg', '128934', 'jpg', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('31', 'cat2.jpg', '/upload/files/3YT3TY1L4D71/cat2.jpg', 'image/jpeg', '62672', 'jpg', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('32', '3.jpg', '/upload/files/58RZNU2O48I5/3.jpg', 'image/jpeg', '128934', 'jpg', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('33', '6.jpg', '/upload/files/3YT3TY1L4D71/6.jpg', 'image/jpeg', '111585', 'jpg', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('34', 'cat1.jpg', '/upload/files/3YT3TY1L4D71/cat1.jpg', 'image/jpeg', '76590', 'jpg', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('35', 'шкаф.jpg', '/upload/files/3YT3TY1L4D71/шкаф.jpg', 'image/jpeg', '8784', 'jpg', '2014-10-01 14:16:01', '2014-10-01 14:16:01'),
  ('36', 'thumb_938_product_pictures_medium.jpeg', '/upload/files/58RZNU2O48I5/thumb_938_product_pictures_medium.jpeg', 'image/jpeg', '56465', 'jpeg', '2014-11-18 10:40:39', '2014-11-18 10:40:39'),
  ('37', 'thumb_848_product_pictures_medium.jpeg', '/upload/files/58RZNU2O48I5/thumb_848_product_pictures_medium.jpeg', 'image/jpeg', '77202', 'jpeg', '2014-11-18 10:41:08', '2014-11-18 10:41:08'),
  ('38', 'thumb_1028_product_pictures_big.jpeg', '/upload/files/58RZNU2O48I5/thumb_1028_product_pictures_big.jpeg', 'image/jpeg', '189442', 'jpeg', '2014-11-18 10:41:30', '2014-11-18 10:41:30'),
  ('39', 'thumb_1048_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1048_product_pictures_big.jpeg', 'image/jpeg', '114431', 'jpeg', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('40', 'thumb_1048_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_1048_product_pictures_big.jpeg', 'image/jpeg', '114431', 'jpeg', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('41', 'picture_1048.jpeg', '/upload/files/58RZNU2O48I5/picture_1048.jpeg', 'image/jpeg', '211289', 'jpeg', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('42', 'thumb_868_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_868_product_pictures_big.jpeg', 'image/jpeg', '118884', 'jpeg', '2014-11-18 10:45:48', '2014-11-18 10:45:48'),
  ('43', 'thumb_868_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_868_product_pictures_big.jpeg', 'image/jpeg', '118884', 'jpeg', '2014-11-18 10:45:48', '2014-11-18 10:45:48'),
  ('44', 'thumb_878_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_878_product_pictures_big.jpeg', 'image/jpeg', '119423', 'jpeg', '2014-11-18 10:47:06', '2014-11-18 10:47:06'),
  ('45', 'thumb_878_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_878_product_pictures_big.jpeg', 'image/jpeg', '119423', 'jpeg', '2014-11-18 10:47:06', '2014-11-18 10:47:06'),
  ('46', 'thumb_1038_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_1038_product_pictures_big.jpeg', 'image/jpeg', '129828', 'jpeg', '2014-11-18 10:48:12', '2014-11-18 10:48:12'),
  ('47', 'thumb_1038_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1038_product_pictures_big.jpeg', 'image/jpeg', '129828', 'jpeg', '2014-11-18 10:48:12', '2014-11-18 10:48:12'),
  ('48', 'thumb_1088_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_1088_product_pictures_big.jpeg', 'image/jpeg', '204989', 'jpeg', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('49', 'picture_1088.jpeg', '/upload/files/58RZNU2O48I5/picture_1088.jpeg', 'image/jpeg', '119070', 'jpeg', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('50', 'thumb_1088_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1088_product_pictures_big.jpeg', 'image/jpeg', '204989', 'jpeg', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('51', 'thumb_1108_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1108_product_pictures_big.jpeg', 'image/jpeg', '144724', 'jpeg', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('52', 'picture_1108.jpeg', '/upload/files/58RZNU2O48I5/picture_1108.jpeg', 'image/jpeg', '140437', 'jpeg', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('53', 'thumb_1108_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_1108_product_pictures_big.jpeg', 'image/jpeg', '144724', 'jpeg', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('54', 'thumb_1118_product_pictures_big.jpeg', '/upload/files/L85Q7Y14U5HU/thumb_1118_product_pictures_big.jpeg', 'image/jpeg', '160463', 'jpeg', '2014-11-18 10:54:02', '2014-11-18 10:54:02'),
  ('55', 'thumb_1118_product_pictures_big.jpeg', '/upload/files/Y84U606JTT9U/thumb_1118_product_pictures_big.jpeg', 'image/jpeg', '160463', 'jpeg', '2014-11-18 10:54:02', '2014-11-18 10:54:02');



--
-- Structure for table `file_pack_file`
--

DROP TABLE IF EXISTS `file_pack_file`;

CREATE TABLE `file_pack_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `pack_file_id` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_file_pack_file___file_id__pack_file_id` (`file_id`,
  `pack_file_id`),
  KEY `idx_file_pack_file___position` (`position`),
  KEY `idx_file_pack_file___pack_file_id__position` (`pack_file_id`,
  `position`),
  KEY `fkidx_file_pack_file___file_id` (`file_id`),
  KEY `fkidx_file_pack_file___pack_file_id` (`pack_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;


--
-- Data for table `file_pack_file`
--

INSERT INTO `file_pack_file` (`id`, `file_id`, `pack_file_id`, `position`, `created_at`, `updated_at`) VALUES
  ('1', '6', '1', '1', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('2', '7', '1', '2', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('3', '8', '1', '3', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('4', '6', '2', '1', '2014-09-26 11:51:56', '2014-09-26 11:51:56'),
  ('5', '7', '2', '2', '2014-09-26 11:51:56', '2014-09-26 11:51:56'),
  ('6', '8', '2', '3', '2014-09-26 11:51:56', '2014-09-26 11:51:56'),
  ('7', '10', '3', '1', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('8', '11', '3', '2', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('9', '12', '3', '3', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('10', '14', '4', '1', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('11', '15', '4', '2', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('12', '18', '5', '1', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('13', '19', '5', '2', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('14', '20', '5', '3', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('15', '21', '5', '4', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('16', '23', '6', '1', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('17', '24', '6', '2', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('18', '25', '6', '3', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('19', '27', '7', '1', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('20', '28', '7', '2', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('21', '29', '7', '3', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('22', '30', '7', '4', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('23', '32', '8', '1', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('24', '33', '8', '2', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('25', '34', '8', '3', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('26', '6', '9', '1', '2014-10-16 11:21:29', '2014-10-16 11:21:29'),
  ('27', '7', '9', '2', '2014-10-16 11:21:29', '2014-10-16 11:21:29'),
  ('28', '8', '9', '3', '2014-10-16 11:21:29', '2014-10-16 11:21:29'),
  ('29', '6', '10', '1', '2014-10-16 11:59:37', '2014-10-16 11:59:37'),
  ('30', '7', '10', '2', '2014-10-16 11:59:37', '2014-10-16 11:59:37'),
  ('31', '8', '10', '3', '2014-10-16 11:59:37', '2014-10-16 11:59:37'),
  ('32', '6', '11', '1', '2014-10-16 12:02:05', '2014-10-16 12:02:05'),
  ('33', '7', '11', '2', '2014-10-16 12:02:05', '2014-10-16 12:02:05'),
  ('34', '8', '11', '3', '2014-10-16 12:02:05', '2014-10-16 12:02:05'),
  ('35', '10', '12', '1', '2014-10-16 12:02:47', '2014-10-16 12:02:47'),
  ('36', '11', '12', '2', '2014-10-16 12:02:47', '2014-10-16 12:02:47'),
  ('37', '12', '12', '3', '2014-10-16 12:02:47', '2014-10-16 12:02:47'),
  ('38', '14', '13', '1', '2014-10-16 12:03:16', '2014-10-16 12:03:16'),
  ('39', '15', '13', '2', '2014-10-16 12:03:16', '2014-10-16 12:03:16'),
  ('40', '36', '14', '1', '2014-11-18 10:40:39', '2014-11-18 10:40:39'),
  ('41', '37', '15', '1', '2014-11-18 10:41:08', '2014-11-18 10:41:08'),
  ('42', '38', '16', '1', '2014-11-18 10:41:30', '2014-11-18 10:41:30'),
  ('43', '40', '17', '1', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('44', '41', '17', '2', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('45', '43', '18', '1', '2014-11-18 10:45:48', '2014-11-18 10:45:48'),
  ('46', '45', '19', '1', '2014-11-18 10:47:06', '2014-11-18 10:47:06'),
  ('47', '47', '20', '1', '2014-11-18 10:48:13', '2014-11-18 10:48:13'),
  ('48', '49', '21', '1', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('49', '50', '21', '2', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('50', '49', '22', '1', '2014-11-18 10:51:57', '2014-11-18 10:51:57'),
  ('51', '50', '22', '2', '2014-11-18 10:51:57', '2014-11-18 10:51:57'),
  ('52', '52', '23', '1', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('53', '53', '23', '2', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('54', '55', '24', '1', '2014-11-18 10:54:02', '2014-11-18 10:54:02');



--
-- Structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fixed_name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_menu_label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


--
-- Data for table `menu`
--

INSERT INTO `menu` (`id`, `fixed_name`, `label`) VALUES
  ('2', 'main_menu', 'Основное верхнее меню'),
  ('4', 'left_menu', 'Левое меню категорий');



--
-- Structure for table `menu_item`
--

DROP TABLE IF EXISTS `menu_item`;

CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_item_id` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `link_options` text,
  `item_options` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_item_name` (`item_name`),
  KEY `idx_menu_position` (`menu_id`,
  `position`),
  KEY `idx_menu_id` (`menu_id`),
  KEY `idx_parent_item_id` (`parent_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


--
-- Data for table `menu_item`
--

INSERT INTO `menu_item` (`id`, `parent_item_id`, `menu_id`, `item_name`, `item_type`, `value`, `position`, `link_options`, `item_options`, `created_at`, `updated_at`) VALUES
  ('1', NULL, '2', 'Главная', 'page', '5', '1', '', '', '2014-09-25 17:41:40', '2014-09-25 17:41:40'),
  ('2', NULL, '2', 'О компании', 'page', '1', '2', '', '', '2014-09-25 17:41:58', '2014-09-25 17:41:58'),
  ('3', NULL, '2', 'Оплата и доставка', 'page', '2', '3', '', '', '2014-09-25 17:42:12', '2014-09-25 17:42:12'),
  ('4', NULL, '2', 'Контакты', 'page', '3', '4', '', '', '2014-09-25 17:42:34', '2014-09-25 17:42:34'),
  ('5', NULL, '4', 'Мангалы', 'productCategory', '7', '1', '', '', '2014-09-26 10:21:06', '2014-09-26 10:21:06'),
  ('6', NULL, '4', 'Для сада', 'productCategory', '9', '2', '', '', '2014-09-26 10:26:38', '2014-09-26 10:26:38'),
  ('7', NULL, '4', 'Решетки на окна', 'productCategory', '10', '3', '', '', '2014-09-26 10:26:50', '2014-09-26 10:26:50');



--
-- Structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
  ('m000000_000000_base', '1411651808'),
  ('m131224_103217_create_user_table', '1411651811'),
  ('m140327_122842_create_page_table', '1411651813'),
  ('m140331_165649_setting_table', '1411651813'),
  ('m140401_120457_create_menu_table', '1411651813'),
  ('m140401_135540_create_menu_item_table', '1411651815'),
  ('m140411_090058_create_file_table', '1411651815'),
  ('m140416_115030_create_pack_file_table', '1411651815'),
  ('m140416_115032_create_file_pack_file_table', '1411651816'),
  ('m140522_153821_create_product_category_table', '1411651818'),
  ('m140522_154617_create_product_table', '1411651820'),
  ('m140801_113739_add_more_setting_types', '1411651821'),
  ('m140925_143924_add_default_page', '1411656046'),
  ('m140925_143938_fill_menu_main', '1411656046'),
  ('m140925_145153_create_head_setting', '1411657050'),
  ('m140926_070642_create_admin_email_setting', '1411715748'),
  ('m140926_070711_translates_setting_in_russian', '1411715749'),
  ('m140926_071725_fill_menu_left', '1411716018'),
  ('m140926_072913_translates_menu_in_russian', '1411716733'),
  ('m140926_081435_add_collumns_in_product', '1411720085'),
  ('m141016_080649_create_text_main_setting', '1413446877'),
  ('m141016_080847_translate_text_mein_in_russian', '1413447061'),
  ('m141016_084448_add_collumn_in_product', '1413449166'),
  ('m141016_102148_create_price_setting', '1413455052'),
  ('m141016_102220_translate_price_setting_in_russian', '1413455052');



--
-- Structure for table `pack_file`
--

DROP TABLE IF EXISTS `pack_file`;

CREATE TABLE `pack_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;


--
-- Data for table `pack_file`
--

INSERT INTO `pack_file` (`id`, `created_at`, `updated_at`) VALUES
  ('1', '2014-09-26 11:51:21', '2014-09-26 11:51:21'),
  ('2', '2014-09-26 11:51:56', '2014-09-26 11:51:56'),
  ('3', '2014-09-26 12:27:49', '2014-09-26 12:27:49'),
  ('4', '2014-09-26 12:30:20', '2014-09-26 12:30:20'),
  ('5', '2014-09-29 18:54:12', '2014-09-29 18:54:12'),
  ('6', '2014-09-29 18:54:53', '2014-09-29 18:54:53'),
  ('7', '2014-09-29 18:55:37', '2014-09-29 18:55:37'),
  ('8', '2014-10-01 14:03:34', '2014-10-01 14:03:34'),
  ('9', '2014-10-16 11:21:29', '2014-10-16 11:21:29'),
  ('10', '2014-10-16 11:59:37', '2014-10-16 11:59:37'),
  ('11', '2014-10-16 12:02:05', '2014-10-16 12:02:05'),
  ('12', '2014-10-16 12:02:47', '2014-10-16 12:02:47'),
  ('13', '2014-10-16 12:03:16', '2014-10-16 12:03:16'),
  ('14', '2014-11-18 10:40:39', '2014-11-18 10:40:39'),
  ('15', '2014-11-18 10:41:08', '2014-11-18 10:41:08'),
  ('16', '2014-11-18 10:41:30', '2014-11-18 10:41:30'),
  ('17', '2014-11-18 10:42:57', '2014-11-18 10:42:57'),
  ('18', '2014-11-18 10:45:48', '2014-11-18 10:45:48'),
  ('19', '2014-11-18 10:47:06', '2014-11-18 10:47:06'),
  ('20', '2014-11-18 10:48:12', '2014-11-18 10:48:12'),
  ('21', '2014-11-18 10:51:38', '2014-11-18 10:51:38'),
  ('22', '2014-11-18 10:51:57', '2014-11-18 10:51:57'),
  ('23', '2014-11-18 10:53:12', '2014-11-18 10:53:12'),
  ('24', '2014-11-18 10:54:02', '2014-11-18 10:54:02');



--
-- Structure for table `page`
--

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_page_id` int(11) DEFAULT NULL,
  `page_name` varchar(255) NOT NULL,
  `fixed_name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `content` text,
  `short_content` text,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `seo_keywords` text,
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fixed_name` (`fixed_name`),
  KEY `idx_page_name` (`page_name`),
  KEY `idx_parent_page_id` (`parent_page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


--
-- Data for table `page`
--

INSERT INTO `page` (`id`, `parent_page_id`, `page_name`, `fixed_name`, `alias`, `content`, `short_content`, `seo_title`, `seo_description`, `seo_keywords`, `position`, `created_at`, `updated_at`) VALUES
  ('1', NULL, 'О компании', NULL, 'info-about', '<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n', '', '', '', '', '2', '2014-09-25 17:34:46', '2014-11-21 18:03:48'),
  ('2', NULL, 'Оплата и доставка', NULL, 'info-oplata-i-dostavka', '<span class=\"col-title\">Оплата и доставка </span>\r\n\r\n<p>► Оплата наличным или безналичным расчетом.</p>\r\n\r\n<p>► Предоплата - 70%</p>\r\n\r\n<p>► Доставка и установка расчитываются индивидуально.</p>\r\n', '', '', '', '', '3', '2014-09-25 17:35:26', '2014-10-16 12:43:20'),
  ('3', NULL, 'Контакты', NULL, 'info-contacts', '<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;</p>', '', '', '', '', '4', '2014-09-25 17:36:03', '2014-11-21 18:05:06'),
  ('5', NULL, 'Главная страница', 'default', '', NULL, NULL, NULL, NULL, NULL, '0', '2014-09-25 18:40:46', '2014-09-25 18:40:46');



--
-- Structure for table `product`
--

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `content` text,
  `short_content` text,
  `price` float NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `seo_keywords` text,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `pack_file_id` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `article` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_product_category_alias` (`category_id`,
  `alias`),
  KEY `idx_product_name` (`product_name`),
  KEY `idx_product_position` (`position`),
  KEY `idx_product_category_position` (`category_id`,
  `position`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_file_id` (`file_id`),
  KEY `idx_product_pack_file_id` (`pack_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;


--
-- Data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `file_id`, `product_name`, `alias`, `content`, `short_content`, `price`, `seo_title`, `seo_description`, `seo_keywords`, `position`, `created_at`, `updated_at`, `pack_file_id`, `visible`, `article`) VALUES
  ('1', '7', '5', 'мб-10', 'mb-10', 'Мангал с дровницей и двумя полками.', '', '13000', '', '', '', '2', '2014-09-26 11:51:21', '2014-11-18 10:40:39', '14', '1', 'мб-10'),
  ('2', '7', '9', 'Мангал без крыши', 'mangal_bez_kryshi', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Мангал с дровницей и полкой.</span>', '', '13000', '', '', '', '1', '2014-09-26 12:27:49', '2014-11-18 10:41:08', '15', '0', 'мб-11'),
  ('3', '9', '13', 'Ящик для цветов', 'jaschik_dlja_cvetov', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Традиционное, праздничное и практичное украшение дома.</span>', '', '4000', '', '', '', '1', '2014-09-26 12:30:20', '2014-11-18 10:41:30', '16', '1', ' дс-1'),
  ('8', '9', '39', 'Почтовый ящик', 'pochtovyj_jaschik', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Функциональное украшение для дома. Отличный подарок.</span>', '', '4500', '', '', '', '2', '2014-11-18 10:42:57', '2014-11-18 10:42:57', '17', '1', 'дс-3'),
  ('9', '7', '42', ' мб-9', 'mb-9', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Мангал с дровницей и двумя полками.</span>', '', '17000', '', '', '', '3', '2014-11-18 10:45:48', '2014-11-18 10:45:48', '18', '0', ' мб-9'),
  ('10', '7', '44', 'мб-7', 'mb-7', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Мангал с богатым узором и дровницей.</span>', '', '13000', '', '', '', '4', '2014-11-18 10:47:06', '2014-11-18 10:47:06', '19', '0', 'мб-7'),
  ('11', '9', '46', 'дс-2', 'ds-2', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Крыша для дымохода с флюгером.</span>', '', '16000', '', '', '', '3', '2014-11-18 10:48:13', '2014-11-18 10:49:18', '20', '0', 'дс-2'),
  ('12', '10', '48', 'Оконная решетка', 'okonnaja_reshetka', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Изящная решетка для окна с изогнутым профилем.</span>', '', '5000', '', '', '', '1', '2014-11-18 10:51:38', '2014-11-18 10:51:57', '22', '1', 'ор-1'),
  ('13', '10', '51', 'ор-2', 'or-2', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Решетка на окна с начальной буквой имени владельца.</span>', '', '10000', '', '', '', '2', '2014-11-18 10:53:12', '2014-11-18 10:53:12', '23', '0', 'ор-2'),
  ('14', '10', '54', 'ор-3', 'or-3', '<span style=\"color: rgb(37, 28, 19); font-family: sans-serif; font-size: 12px; line-height: 16.7999992370605px;\">Стеклянная дверь в кованым узором &quot;виноград&quot;.</span>', '', '10000', '', '', '', '3', '2014-11-18 10:54:02', '2014-11-18 10:54:02', '24', '0', 'ор-3');



--
-- Structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_category_id` int(11) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `fixed_name` varchar(255) DEFAULT NULL,
  `content` text,
  `short_content` text,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `seo_keywords` text,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fixed_name` (`fixed_name`),
  UNIQUE KEY `idx_parent_alias` (`parent_category_id`,
  `alias`),
  KEY `idx_category_name` (`category_name`),
  KEY `idx_category_position` (`position`),
  KEY `idx_parent_category_position` (`parent_category_id`,
  `position`),
  KEY `idx_parent_category_id` (`parent_category_id`),
  KEY `idx_file_id` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;


--
-- Data for table `product_category`
--

INSERT INTO `product_category` (`id`, `parent_category_id`, `file_id`, `category_name`, `alias`, `fixed_name`, `content`, `short_content`, `seo_title`, `seo_description`, `seo_keywords`, `position`, `created_at`, `updated_at`) VALUES
  ('7', NULL, '2', 'Мангалы', 'kovanye-mangaly', NULL, '<p><strong>МАНГАЛЫ</strong> для дачи или профессиональные с крышей и дымоотводом. Некоторые модели, представленные на сайте есть в наличии.<br />\r\nЛюбой мангал можно изготовить под заказ в течении 5-7 дней.</p>\r\n', '', '', '', '', '1', '2014-09-25 17:15:27', '2014-10-16 13:08:59'),
  ('9', NULL, '3', 'Для сада', 'dlja-sada', NULL, '', '', '', '', '', '2', '2014-09-26 10:23:51', '2014-09-26 10:23:51'),
  ('10', NULL, '4', 'Решетки на окна', 'reshetki-na-okna', NULL, '', '', '', '', '', '3', '2014-09-26 10:26:05', '2014-09-26 10:26:05');



--
-- Structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fixed_name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `setting_type` enum('string',
  'text',
  'ckeditor',
  'email',
  'url',
  'number',
  'integer') NOT NULL DEFAULT 'string',
  `can_be_empty` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_setting_label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;


--
-- Data for table `setting`
--

INSERT INTO `setting` (`id`, `fixed_name`, `label`, `value`, `setting_type`, `can_be_empty`, `created_at`, `updated_at`) VALUES
  ('6', 'contacts', 'Контакты', '<div class=\"b-contacts\"><span class=\"col-title\">Контактные телефоны:</span>\r\n<ul>\r\n	<li class=\"phone i1\">+375 (29) 661-32-87</li>\r\n</ul>\r\n</div>\r\n', 'ckeditor', '1', '2014-09-25 18:57:30', '2014-11-20 16:15:34'),
  ('7', 'contacts_footer_right', 'Контакты (правый нижний угол)', '<p><span>&copy;2013 &ldquo;Кованые изделия&rdquo;</span><br />\r\n<span>Телефоны:+375 (29) 661-32-87</span></p>\r\n', 'ckeditor', '1', '2014-09-25 18:57:30', '2014-11-20 16:16:34'),
  ('8', 'contacts_footer_left', 'Контакты (левый нижний угол)', '<p><span>Адрес:&nbsp;</span>Беларусь, г. Узда<br />\r\n&nbsp;</p>\r\n', 'ckeditor', '1', '2014-09-25 18:57:30', '2014-11-21 18:07:04'),
  ('10', 'adminEmail', 'Email администратора', 'admin@this-mail-must-be-changed.us', 'ckeditor', '1', '2014-09-26 11:15:48', '2014-09-26 11:15:48'),
  ('12', 'text_main', 'Текст на главной странице', '<p>Каждое изделие изготавливается индивидуально, под заказ.&nbsp; Срок от 7-10 дней.</p>\r\n\r\n<p><strong>КАЧЕСТВО ГАРАНТИРУЕТСЯ</strong></p>\r\n\r\n<p>Вы можете выбрать понравившуюся модель на сайте, которая может быть изменена с учетом Ваших пожеланий. Возможна работа по вашим эскизам.<br />\r\nИли создание эскиза специально для Вас профессиональным художником по ковке.</p>\r\n\r\n<p><strong>Звоните, чтобы обсудить ВЕЛИКОЛЕПНУЮ ВЕЩЬ, которой Вы вскоре сможете обзавестись!</strong></p>\r\n\r\n<p>Качественная художественная ковка - это всегда красота и практичность!</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'ckeditor', '1', '2014-10-16 12:07:57', '2014-10-16 11:12:02'),
  ('14', 'price', 'Цена', 'руб.', 'ckeditor', '1', '2014-10-16 14:24:11', '2014-10-16 13:30:39');



--
-- Structure for table `user`
--

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_username` (`username`),
  UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
  ('1', 'admin', 'admin@mail.com', '$2a$13$7AcdlUIsRkPGERwzmS1v5eVMYmow8VXIFZIjKfePgTtLQ66ODYX.u', '1', '2014-09-25 16:34:31', '2014-09-25 16:34:31');


--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_pack_file`
--

ALTER TABLE file_pack_file
	ADD CONSTRAINT `fk_file_pack_file___file_id___file___id` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
	ADD CONSTRAINT `fk_file_pack_file___pack_file_id___pack_file___id` FOREIGN KEY (`pack_file_id`) REFERENCES `pack_file` (`id`);
--
-- Constraints for table `menu_item`
--

ALTER TABLE menu_item
	ADD CONSTRAINT `fk_menu_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
	ADD CONSTRAINT `fk_menu_item_menu_item` FOREIGN KEY (`parent_item_id`) REFERENCES `menu_item` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `page`
--

ALTER TABLE page
	ADD CONSTRAINT `fk_page_page` FOREIGN KEY (`parent_page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `product`
--

ALTER TABLE product
	ADD CONSTRAINT `fk_product_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
	ADD CONSTRAINT `fk_product_pack_file` FOREIGN KEY (`pack_file_id`) REFERENCES `pack_file` (`id`),
	ADD CONSTRAINT `fk_product_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `product_category`
--

ALTER TABLE product_category
	ADD CONSTRAINT `fk_category_category` FOREIGN KEY (`parent_category_id`) REFERENCES `product_category` (`id`) ON DELETE CASCADE,
	ADD CONSTRAINT `fk_category_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
