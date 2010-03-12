-- MySQL dump 10.11
--
-- Host: localhost    Database: 3m
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny3-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `staff` (
  `staffid` mediumint(5) unsigned NOT NULL auto_increment,
  `first` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `middle` varchar(20) collate utf8_unicode_ci default '',
  `last` varchar(30) collate utf8_unicode_ci NOT NULL default '',
  `display_name` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `year` smallint(4) unsigned NOT NULL default '9999' COMMENT 'year zero is Grad Student',
  `gender` enum('male','female') collate utf8_unicode_ci NOT NULL default 'male',
  `email` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `dept` varchar(20) collate utf8_unicode_ci NOT NULL,
  `position` varchar(30) collate utf8_unicode_ci NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `athena_username` varchar(8) collate utf8_unicode_ci NOT NULL default '',
  `birthday` date NOT NULL default '0000-00-00',
  `phone` char(10) collate utf8_unicode_ci NOT NULL default '000000000' COMMENT 'US 10 digit only. No dashes',
  `previd` mediumint(5) unsigned default NULL,
  `enable_mail` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  `active` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  PRIMARY KEY  (`staffid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `departments` (
  `deptid` tinyint(2) unsigned NOT NULL auto_increment,
  `shortname` varchar(4) collate utf8_unicode_ci NOT NULL default '',
  `name` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `order` tinyint(2) unsigned NOT NULL default '0',
  `isActive` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  PRIMARY KEY  (`deptid`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'exec','Executive Board',0,'yes'),(2,'nuz','News',1,'yes'),(3,'prod','Production',2,'yes'),(4,'opn','Opinion',3,'yes'),(5,'spo','Sports',4,'yes'),(6,'rtz','Arts',5,'yes'),(7,'fto','Photography',6,'yes'),(8,'cl','Campus Life',7,'yes'),(9,'biz','Business',8,'yes'),(10,'ten','Technology',9,'yes'),(11,'etc','Editors at Large',10,'yes'),(12,'adv','Advisory Board',11,'yes');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `titles`
--

DROP TABLE IF EXISTS `titles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `titles` (
  `deptposid` tinyint(2) unsigned NOT NULL auto_increment,
  `name` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `order` tinyint(1) unsigned NOT NULL default '0',
  `isManboard` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  `isActive` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  `inDepartment` set('adv','biz','cl','etc','exec','fto','nuz','opn','prod','rtz','spo','ten') collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`deptposid`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `titles`
--

LOCK TABLES `titles` WRITE;
/*!40000 ALTER TABLE `titles` DISABLE KEYS */;
INSERT INTO `titles` VALUES (1,'Chairman',1,'yes','yes','exec'),(2,'Editor in Chief',2,'yes','yes','exec'),(3,'Executive Editor',5,'yes','yes','exec'),(4,'Business Manager',3,'yes','yes','exec'),(5,'Managing Editor',4,'yes','yes','exec'),(6,'Editor',1,'yes','yes','cl,fto,nuz,opn,prod,rtz,spo'),(7,'Associate Editor',2,'no','yes','cl,fto,nuz,opn,prod,rtz,spo'),(8,'Staff',3,'no','yes','cl,fto,nuz,opn,prod,rtz,spo,ten'),(9,'Director',1,'yes','yes','ten'),(10,'Meteorologist',4,'no','yes','nuz'),(11,'Police Log Compiler',5,'no','yes','nuz'),(12,'Cartoonist',4,'no','yes','cl'),(13,'Advertising Manager',1,'yes','yes','biz'),(14,'Operations Manager',2,'yes','yes','biz'),(15,'Contributing Editor',1,'yes','yes','etc'),(16,'Senior Editor',2,'yes','yes','etc'),(17,'Illustrator',4,'yes','yes','prod'),(18,'',1,'yes','yes','adv');
/*!40000 ALTER TABLE `titles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-03-12  2:50:08
