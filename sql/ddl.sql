-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2011 at 10:37 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `k2684956_palingoke`
--

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_logistic_service`
--

CREATE TABLE IF NOT EXISTS `ongkir_logistic_service` (
  `id` int(11) NOT NULL auto_increment,
  `service_type_id` int(2) NOT NULL,
  `company_id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `unit_price` decimal(10,0) NOT NULL,
  `next_unit_price` int(11) NOT NULL,
  `delivery_time` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Logistic service';

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_city`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_city` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='countries that supported by the application' ;

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_country`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_country` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='countries that supported by the application' ;

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_district`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_district` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='districts that supported by the application' ;

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_location`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_location` (
  `id` int(11) NOT NULL auto_increment,
  `district_id` int(11) default NULL,
  `district_name` varchar(200) default NULL,
  `city_id` int(11) NOT NULL,
  `city_name` varchar(200) NOT NULL,
  `state_id` int(11) NOT NULL,
  `state_name` varchar(50) NOT NULL,
  `last_rebuilt_date` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Holds district,city and state id' ;

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_logistic_company`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_logistic_company` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `site_url` varchar(50) NOT NULL,
  `customer_care_email` varchar(50) NOT NULL,
  `phone` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='logistic company detail data' ;


INSERT INTO `ongkir_ref_logistic_company` (`id`, `name`, `address`, `site_url`, `customer_care_email`, `phone`) VALUES
(1, 'jne', 'Jl. Tomang Raya No. 45 Jakarta 11440 Indonesia', 'http://www.jne.co.id', 'customercare@jne.co.id', '(021) 2927 8888, 566 5262, 563 3232'),
(2, 'tiki', 'Jl. Veteran Yogyakarta, Indonesia ', 'http://www.tiki-online.com/home', '', '');
-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_service_type`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_service_type` (
  `id` int(11) NOT NULL auto_increment,
  `company_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='logistic company service types' ;


INSERT INTO `ongkir_ref_service_type` (`id`, `company_id`, `name`) VALUES
(1, 1, 'reguler'),
(2, 2, 'reguler'),
(3, 2, 'ons ( over night service )'),
(4, 1, 'oke'),
(5, 1, 'yes (yakin esok sampai)'),
(6, 1, 'ss ( super speed)');

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_ref_state`
--

CREATE TABLE IF NOT EXISTS `ongkir_ref_state` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='countries that supported by the application' ;

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_registry`
--

CREATE TABLE IF NOT EXISTS `ongkir_registry` (
  `registry_name` varchar(50) NOT NULL,
  `numeric_value` int(11) NOT NULL default '0',
  `string_value` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Key value registry';


INSERT INTO `ongkir_registry` (`registry_name`, `numeric_value`, `string_value`) VALUES
('ongkir_logistic_service', 0, 'ongkir_logistic_service_20112011'),
('column_separator', 0, '#');

-- --------------------------------------------------------

--
-- Table structure for table `ongkir_user_session`
--

CREATE TABLE IF NOT EXISTS `ongkir_user_session` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELIMITER $$
--
-- Functions
--
CREATE FUNCTION `LEVENSHTEIN`( s1 VARCHAR(255), s2 VARCHAR(255) ) RETURNS int(11)
    DETERMINISTIC
BEGIN 
    DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; 
    DECLARE s1_char CHAR; 
    
    DECLARE cv0, cv1 VARBINARY(256); 
    SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; 
    IF s1 = s2 THEN 
      RETURN 0; 
    ELSEIF s1_len = 0 THEN 
      RETURN s2_len; 
    ELSEIF s2_len = 0 THEN 
      RETURN s1_len; 
    ELSE 
      WHILE j <= s2_len DO 
        SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1; 
      END WHILE; 
      WHILE i <= s1_len DO 
        SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; 
        WHILE j <= s2_len DO 
          SET c = c + 1; 
          IF s1_char = SUBSTRING(s2, j, 1) THEN  
            SET cost = 0; ELSE SET cost = 1; 
          END IF; 
          SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost; 
          IF c > c_temp THEN SET c = c_temp; END IF; 
            SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1; 
            IF c > c_temp THEN  
              SET c = c_temp;  
            END IF; 
            SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1; 
        END WHILE; 
        SET cv1 = cv0, i = i + 1; 
      END WHILE; 
    END IF; 
    RETURN c; 
  END$$

CREATE FUNCTION `LEVENSHTEIN_RATIO`( s1 VARCHAR(255), s2 VARCHAR(255) ) RETURNS int(11)
    DETERMINISTIC
BEGIN 
    DECLARE s1_len, s2_len, max_len INT; 
    SET s1_len = LENGTH(s1), s2_len = LENGTH(s2); 
    IF s1_len > s2_len THEN  
      SET max_len = s1_len;  
    ELSE  
      SET max_len = s2_len;  
    END IF; 
    RETURN ROUND((1 - LEVENSHTEIN(s1, s2) / max_len) * 100); 
  END$$

DELIMITER ;
