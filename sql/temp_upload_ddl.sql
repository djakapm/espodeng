/*
SQLyog Community v9.01 
MySQL - 5.5.15 : Database - app
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`app` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `app`;

/*Table structure for table `ongkir_temp_upload_csv` */

DROP TABLE IF EXISTS `ongkir_temp_upload_csv`;

CREATE TABLE `ongkir_temp_upload_csv` (
  `id` int(11) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `price_per_kg` int(11) DEFAULT NULL,
  `price_next_kg` int(11) DEFAULT NULL,
  `delivery_time` int(11) DEFAULT NULL,
  `lookup_status` int(11) DEFAULT '0' COMMENT 'location lookup status: 0:no match, 1:match, 2:select',
  `guessed_location_id` int(11) DEFAULT NULL,
  `guessed_location_name` varchar(50) DEFAULT NULL,
  `guessed_options` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `ongkir_temp_upload_csv_info` */

DROP TABLE IF EXISTS `ongkir_temp_upload_csv_info`;

CREATE TABLE `ongkir_temp_upload_csv_info` (
  `origin_id` int(11) NOT NULL,
  `logistic_company` int(11) NOT NULL,
  `logistic_service_type` int(11) NOT NULL,
  `logistic_table_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
