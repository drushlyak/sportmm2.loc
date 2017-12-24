-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.45-Debian_1ubuntu3.1-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Definition of table `mod_foto`
--

DROP TABLE IF EXISTS `mod_foto`;
CREATE TABLE  `mod_foto` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_te_value` int(10) unsigned default NULL,
  `id_fotogr` int(10) unsigned default NULL,
  `name` varchar(32) default NULL,
  `title` varchar(32) default NULL,
  `idate` datetime default NULL,
  `orig` varchar(255) default NULL,
  `img` varchar(255) default NULL,
  `tmb` varchar(255) default NULL,
  `ord` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mod_foto`
--

/*!40000 ALTER TABLE `mod_foto` DISABLE KEYS */;
LOCK TABLES `mod_foto` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `mod_foto` ENABLE KEYS */;


--
-- Definition of table `mod_fotogr_data`
--

DROP TABLE IF EXISTS `mod_fotogr_data`;
CREATE TABLE  `mod_fotogr_data` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_te_value` int(10) unsigned default NULL,
  `name` varchar(32) default NULL,
  `description` varchar(32) default NULL,
  `code` int(10) unsigned NOT NULL,
  `count_per_page` int(10) unsigned NOT NULL,
  `resize` tinyint unsigned NOT NULL,
  `crop_img` tinyint unsigned NOT NULL DEFAULT 0,
  `width_img` int(10) unsigned NOT NULL DEFAULT 400,
  `height_img` int(10) unsigned NOT NULL DEFAULT 300,
  `quality_img` int(10) unsigned NOT NULL DEFAULT 75,
  `auto_tmb` tinyint unsigned NOT NULL,
  `crop_tmb` tinyint unsigned NOT NULL DEFAULT 0,
  `width_tmb` int(10) unsigned NOT NULL DEFAULT 40,
  `height_tmb` int(10) unsigned NOT NULL DEFAULT 30,
  `quality_tmb` int(10) unsigned NOT NULL DEFAULT 75,
  `res_id` int(10) NOT NULL, 
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mod_fotogr_data`
--

/*!40000 ALTER TABLE `mod_fotogr_data` DISABLE KEYS */;
LOCK TABLES `mod_fotogr_data` WRITE;
INSERT INTO `mod_fotogr_data` VALUES  (1,0,NULL,NULL,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fotogr_data` ENABLE KEYS */;


--
-- Definition of table `mod_fotogr_struct`
--

DROP TABLE IF EXISTS `mod_fotogr_struct`;
CREATE TABLE  `mod_fotogr_struct` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `data_id` int(10) unsigned NOT NULL default '0',
  `lft` int(10) unsigned NOT NULL default '0',
  `rgt` int(10) unsigned NOT NULL default '0',
  `level` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `lft` (`lft`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fotogr_struct`
--

/*!40000 ALTER TABLE `mod_fotogr_struct` DISABLE KEYS */;
LOCK TABLES `mod_fotogr_struct` WRITE;
INSERT INTO `mod_fotogr_struct` VALUES  (1,1,1,2,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `mod_fotogr_struct` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
