SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emailrules` (
       `ruleid` int unsigned NOT NULL auto_increment,
       `dept` varchar(20) collate utf8_unicode_ci NOT NULL default '',
       `position` varchar(30) collate utf8_unicode_ci NOT NULL,
       `addlist` varchar(30) collate utf8_unicode_ci NOT NULL,
       `notificationlist` varchar(30) collate utf8_unicode_ci NOT NULL
       )
