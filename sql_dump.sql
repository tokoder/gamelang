-- Server version	5.5.5-10.6.5-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `package` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities`
--

DROP TABLE IF EXISTS `entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `owner_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` enum('user','group','object') NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `privacy` tinyint(1) NOT NULL DEFAULT '2',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0',
  `deleted_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` VALUES (1,0,0,'user','administrator','admin','indonesia',0,1,0,0,1526871009,0,0);
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metadata`
--

DROP TABLE IF EXISTS `metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_values` (`guid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metadata`
--

LOCK TABLES `metadata` WRITE;
/*!40000 ALTER TABLE `metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objects` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  `content` longtext,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `options` (
  `name` varchar(100) NOT NULL,
  `value` longtext NOT NULL,
  `tab` varchar(50) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT 'text',
  `options` varchar(255) NOT NULL DEFAULT '',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES ('active_packages','a:0:{}','packages','text','',1),('allowed_types','gif|png|jpeg|jpg|pdf|doc|txt|docx|xls|zip|rar|xls|mp4','upload','text','',1),('allow_multi_session','true','users','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('allow_registration','true','users','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('email_activation','true','users','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('google_analytics_id','UA-XXXXX-Y','general','text','',0),('google_site_verification','','general','text','',0),('language','indonesia','language','dropdown','a:2:{s:7:\"english\";s:7:\"english\";}',1),('languages','a:2:{i:0;s:7:\"english\";i:1;s:9:\"indonesia\";}','language','dropdown','',1),('login_type','both','users','dropdown','a:3:{s:4:\"both\";s:14:\"lang:lang_both\";s:8:\"username\";s:18:\"lang:lang_username\";s:5:\"email\";s:15:\"lang:lang_email\";}',1),('mail_protocol','mail','email','dropdown','a:3:{s:4:\"mail\";s:4:\"Mail\";s:4:\"smtp\";s:4:\"SMTP\";s:8:\"sendmail\";s:8:\"Sendmail\";}',1),('mail_library','phpmailer','email','dropdown','a:1:{s:9:\"phpmailer\";s:10:\"PHP Mailer\";}',1),('manual_activation','false','users','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('max_height','0','upload','number','',1),('max_size','0','upload','number','',1),('max_width','0','upload','number','',1),('min_height','0','upload','number','',1),('min_width','0','upload','number','',1),('per_page','10','general','dropdown','a:3:{i:10;i:10;i:20;i:20;i:30;i:30;}',1),('recaptcha_private_key','','captcha','text','',0),('recaptcha_site_key','','captcha','text','',0),('sendmail_path','/usr/sbin/sendmail','email','text','',0),('mail_address','noreply@localhost','email','text','',1),('site_author','Buddy Winangun','general','text','',0),('site_description','An open source codeigniter management system.','general','text','',0),('site_keywords','gamelang, codeigniter3, application, open source','general','text','',0),('site_name','Gamelang','general','text','',1),('site_title','','general','text','',0),('smtp_crypto','none','email','dropdown','a:3:{s:4:\"none\";s:14:\"lang:lang_none\";s:3:\"ssl\";s:3:\"SSL\";s:3:\"tls\";s:3:\"TLS\";}',1),('smtp_host','','email','text','',0),('smtp_pass','','email','password','',0),('smtp_port','','email','text','',0),('smtp_user','','email','text','',0),('theme','default','theme','text','',1),('upload_path','uploads','upload','text','',0),('use_captcha','true','captcha','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('use_gravatar','false','users','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1),('use_recaptcha','true','captcha','dropdown','a:2:{s:4:\"true\";s:13:\"lang:lang_yes\";s:5:\"false\";s:12:\"lang:lang_no\";}',1);
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relations`
--

DROP TABLE IF EXISTS `relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid_from` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid_to` bigint(20) unsigned NOT NULL DEFAULT '0',
  `relation` varchar(100) NOT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relations`
--

LOCK TABLES `relations` WRITE;
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `guid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `gender` enum('unspecified','male','female') NOT NULL DEFAULT 'unspecified',
  `online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `last_seen` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`guid`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@localhost','$2a$08$MnCsgx3mmz3khvfFfDaEi.BaVS.gXVY/aQ1TmYEJVSAxm3cvfgtQO','Admin','Gamelang','male',0,1526871009);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `value` longtext,
  `params` longtext,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_values` (`guid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variables`
--

LOCK TABLES `variables` WRITE;
/*!40000 ALTER TABLE `variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `variables` ENABLE KEYS */;
UNLOCK TABLES;

--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
