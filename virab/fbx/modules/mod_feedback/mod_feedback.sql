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
-- Definition of table `mod_feedback_group`
--

DROP TABLE IF EXISTS `mod_feedback_group`;
CREATE TABLE  `mod_feedback_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `id_te_value` int(11) NOT NULL,
  `count_per_page` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mod_feedback_group`
--

/*!40000 ALTER TABLE `mod_feedback_group` DISABLE KEYS */;
LOCK TABLES `mod_feedback_group` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `mod_feedback_group` ENABLE KEYS */;


--
-- Definition of table `mod_faq_qw`
--

DROP TABLE IF EXISTS `mod_feedback_txt`;
CREATE TABLE  `mod_feedback_txt` (
  `id` int(11) NOT NULL auto_increment,
  `text` varchar(32) NOT NULL,
  `idate` datetime NOT NULL,
  `author_name` varchar(32) NOT NULL,
  `author_mail` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `priz_active` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mod_feedback_txt`
--

/*!40000 ALTER TABLE `mod_feedback_txt` DISABLE KEYS */;
LOCK TABLES `mod_feedback_txt` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `mod_feedback_txt` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
