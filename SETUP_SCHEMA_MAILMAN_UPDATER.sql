SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8;
DROP TABLE IF EXISTS emailrules;
CREATE TABLE `emailrules` (
       `ruleid` int unsigned NOT NULL auto_increment,
       `dept` varchar(20) collate utf8_unicode_ci NOT NULL default '',
       `position` varchar(30) collate utf8_unicode_ci NOT NULL,
       `addlist` varchar(150) collate utf8_unicode_ci NOT NULL,
       `notificationlist` varchar(35) collate utf8_unicode_ci NOT NULL,
       PRIMARY KEY (`ruleid`)
       ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS previousemailupdate;
CREATE TABLE `previousemailupdate` (
       `updateid` int unsigned NOT NULL auto_increment,
       `addlist` varchar(40) collate utf8_unicode_ci NOT NULL,
       `athena_username` varchar(8) collate utf8_unicode_ci NOT NULL,
       PRIMARY KEY (`updateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
