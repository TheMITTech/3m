-- MySQL dump 10.13  Distrib 5.5.42, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 3m
-- ------------------------------------------------------
-- Server version	5.5.42-1-log

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
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `deptid` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `shortname` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `order` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `isActive` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`deptid`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emailrules`
--

DROP TABLE IF EXISTS `emailrules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emailrules` (
  `ruleid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dept` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `addlist` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `notificationlist` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ruleid`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maillist_members_not_in_use`
--

DROP TABLE IF EXISTS `maillist_members_not_in_use`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maillist_members_not_in_use` (
  `maillistmemberid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `maillistid` smallint(3) unsigned NOT NULL DEFAULT '0',
  `staffid` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `isActive` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`maillistmemberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maillists_not_in_use`
--

DROP TABLE IF EXISTS `maillists_not_in_use`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maillists_not_in_use` (
  `maillistid` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `isActive` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`maillistid`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `positions_not_in_use`
--

DROP TABLE IF EXISTS `positions_not_in_use`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions_not_in_use` (
  `posid` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `staffid` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `deptid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `deptposid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`posid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `previousemailupdate`
--

DROP TABLE IF EXISTS `previousemailupdate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `previousemailupdate` (
  `updateid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addlist` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `athena_username` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`updateid`)
) ENGINE=MyISAM AUTO_INCREMENT=1479 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `staffid` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `first` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `middle` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `last` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `year` smallint(4) unsigned NOT NULL DEFAULT '9999' COMMENT 'year zero is Grad Student',
  `gender` enum('male','female') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'male',
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dept` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `athena_username` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `phone` char(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000000' COMMENT 'US 10 digit only. No dashes',
  `previd` mediumint(5) unsigned DEFAULT NULL,
  `enable_mail` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `active` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `gchat` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`staffid`)
) ENGINE=MyISAM AUTO_INCREMENT=1615 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `titles`
--

DROP TABLE IF EXISTS `titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `titles` (
  `deptposid` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isManboard` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `isActive` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `inDepartment` set('adv','biz','cl','etc','exec','fto','nuz','opn','prod','rtz','spo','ten','cpy','med') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`deptposid`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-08 17:23:35
