-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: banshee_dev
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.1

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
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agenda` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(100) NOT NULL,
  `value` mediumtext NOT NULL,
  `timeout` datetime NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `collection_album`
--

DROP TABLE IF EXISTS `collection_album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_album` (
  `collection_id` int(10) unsigned NOT NULL,
  `album_id` int(10) unsigned NOT NULL,
  KEY `collection_id` (`collection_id`),
  KEY `album_id` (`album_id`),
  CONSTRAINT `collection_album_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`),
  CONSTRAINT `collection_album_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `photo_albums` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection_album`
--

LOCK TABLES `collection_album` WRITE;
/*!40000 ALTER TABLE `collection_album` DISABLE KEYS */;
INSERT INTO `collection_album` VALUES (1,1);
/*!40000 ALTER TABLE `collection_album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES (1,'Project images');
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary`
--

DROP TABLE IF EXISTS `dictionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `long_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary`
--

LOCK TABLES `dictionary` WRITE;
/*!40000 ALTER TABLE `dictionary` DISABLE KEYS */;
INSERT INTO `dictionary` VALUES (1,'Banshee','Secure PHP framework','<p>Banshee is a PHP website framework, which aims at being secure, fast and easy to use. It has a Model-View-Controller architecture (XSLT for the views). Although it was designed to use MySQL as the database, other database applications can be used as well with only little effort.</p>\r\n\r\n<p>Ready-to-use modules like a forum, F.A.Q. page, weblog, poll and a guestbook will save web developers a lot of work when creating a new website. Easy to use libraries for e-mail, pagination, HTTP requests, database management, polls, POP3, newsletters, images, cryptography and more are also included.</p>'),(2,'Hiawatha','Advanced and secure webserver','<p>Hiawatha is an open source webserver for Unix.</p>\r\n\r\n<p>Hiawatha has been written with security in mind. This resulted in a highly secure webserver in both code and features. Hiawatha can stop SQL injections, XSS and CSRF attacks and exploit attempts. Via a specially crafted monitoring tool, you can keep track of all your webservers.</p>\r\n\r\n<p>You don\'t need to be a HTTP or CGI expert to get Hiawatha up and running. Its configuration syntax is easy to learn. The documentation and examples you can find on this website will give you all the information you need to configure your webserver within minutes.</p>\r\n\r\n<p>Although Hiawatha has everything a modern webserver needs, it\'s nevertheless a small and lightweight webserver. This makes Hiawatha ideal for older hardware or embedded systems. Special techniques are being used to keep the usage of resources as low as possible.</p>');
/*!40000 ALTER TABLE `dictionary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dummy`
--

DROP TABLE IF EXISTS `dummy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dummy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `line` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `boolean` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `enum` enum('value1','value2','value3') NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dummy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dummy`
--

LOCK TABLES `dummy` WRITE;
/*!40000 ALTER TABLE `dummy` DISABLE KEYS */;
INSERT INTO `dummy` VALUES (1,72,'hello world','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus erat urna, accumsan at, mattis eu, euismod nec, justo. Integer consectetur. Aliquam erat volutpat. Sed ac ipsum. Maecenas pretium, felis non blandit pellentesque, arcu nulla adipiscing dui, ac sollicitudin ipsum nisl a dolor. Praesent in dolor consequat massa molestie mollis. In viverra eleifend purus. Nunc vel sapien. Etiam risus. Morbi auctor commodo nunc. In hac habitasse platea dictumst. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse posuere lectus non sapien. Mauris congue dolor a magna.\r\n\r\nMauris tristique justo ac sem. Vivamus pharetra quam et nunc. Proin quis erat. Proin pharetra mattis enim. Sed diam. Aliquam tempor eros sed odio aliquam fringilla. Nulla posuere. Phasellus eleifend sem a odio feugiat vehicula. Integer dignissim, est sed consectetur vestibulum, massa arcu ultrices nulla, ac consequat ligula justo at tellus. Etiam interdum est quis felis. Mauris lacinia.',0,'2009-01-06 14:19:00','value2',1),(2,23,'Lorum ipsum','ouifhilduvnxaifs driaurfc iweurnfcisaeurnbc iseruvsieurbviaceurbnfc iscdbn ilzdbv sraerf ase rgc sr cae rgv sfgb vaergcfh seirfc togvcn eufnseirgubc sertcgse riguncs eriuneizrung caieunrfgc iaeurb vsiubre viseurb viauerf ciaseur vciauwe nrisuviaeruniapwuenfc awijf wrtunh gviasuebr vciaubervn isubeviauebrf isbv iauebrf iauebnrv iaunerv iaubf visuubenrv iaeubnrfv aiebviAHWBE FIWY4BTGV9QUHB3 FIAUUBFPIUbi suuebrfiauuwbef istrbv isdbfv aidfvb',0,'2009-01-23 19:38:00','value3',NULL);
/*!40000 ALTER TABLE `dummy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_sections`
--

DROP TABLE IF EXISTS `faq_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_sections`
--

LOCK TABLES `faq_sections` WRITE;
/*!40000 ALTER TABLE `faq_sections` DISABLE KEYS */;
INSERT INTO `faq_sections` VALUES (1,'Banshee');
/*!40000 ALTER TABLE `faq_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int(10) unsigned NOT NULL,
  `question` tinytext NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `faq_sections` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,1,'What is Banshee?','Banshee is a secure PHP framework.'),(2,1,'Who wrote Banshee?','Banshee was written by Hugo Leisink.');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_last_view`
--

DROP TABLE IF EXISTS `forum_last_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_last_view` (
  `user_id` int(10) unsigned NOT NULL,
  `last_view` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `forum_last_view_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forum_messages`
--

DROP TABLE IF EXISTS `forum_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `forum_messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`),
  CONSTRAINT `forum_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_messages`
--

LOCK TABLES `forum_messages` WRITE;
/*!40000 ALTER TABLE `forum_messages` DISABLE KEYS */;
INSERT INTO `forum_messages` VALUES (1,1,1,NULL,'2013-04-30 08:54:44','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac elit quam. Nullam aliquam justo et nisi dictum pretium interdum tellus hendrerit. Aenean tristique posuere dictum. Maecenas nec sapien ut magna suscipit euismod quis ut metus. Aenean sit amet metus a turpis iaculis mollis. Nam faucibus mauris vel ligula ultricies dapibus. Nullam quis orci ac sem convallis malesuada nec id nisi. Praesent quis tellus nec sapien viverra blandit at ut erat. Curabitur bibendum malesuada erat, in suscipit leo porta et. Cras quis arcu sit amet nibh molestie mollis eu eget nulla. Vivamus sed enim fringilla elit pretium feugiat. Nullam elementum fermentum nunc in sodales.</p>\r\n\r\n<p>Mauris nec nunc quis enim porttitor consectetur at et lorem. Vivamus ac rutrum sapien. Nullam metus lectus, lobortis sit amet vulputate sit amet, fermentum sed velit. Phasellus ac libero urna. Maecenas tellus massa, ultrices sed pretium non, faucibus ut lorem. Donec aliquam vehicula ante, eu sodales felis ullamcorper at. Sed sed odio ipsum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam laoreet tristique est in molestie. Sed lacinia euismod porttitor. Praesent ullamcorper fringilla arcu sit amet viverra. Aliquam erat volutpat.</p>\r\n\r\n<p>Nulla vel eros quam. Nam nec turpis ac turpis pulvinar facilisis non non nunc. Nam bibendum nunc in velit cursus rutrum. Integer at ultricies orci. Suspendisse vitae sodales dui. Integer malesuada hendrerit dui, a ullamcorper mauris aliquam sit amet. Nulla dignissim tortor accumsan velit laoreet non eleifend massa aliquet. Quisque luctus dapibus viverra. Aliquam sed lorem diam. Phasellus condimentum lectus vitae ipsum molestie a vestibulum risus malesuada. Duis posuere urna a arcu facilisis sit amet blandit lacus tempus. Vestibulum vel arcu nunc, ut imperdiet massa. Donec congue risus nec urna laoreet et euismod magna semper. Fusce pharetra porttitor ultrices.</p>','84.29.202.23');
/*!40000 ALTER TABLE `forum_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(10) unsigned NOT NULL,
  `subject` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_topics`
--

LOCK TABLES `forum_topics` WRITE;
/*!40000 ALTER TABLE `forum_topics` DISABLE KEYS */;
INSERT INTO `forum_topics` VALUES (1,1,'Lorum ipsum');
/*!40000 ALTER TABLE `forum_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forums`
--

LOCK TABLES `forums` WRITE;
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
INSERT INTO `forums` VALUES (1,'Lorum ipsum','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer a purus velit, et porttitor diam.',1);
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guestbook`
--

DROP TABLE IF EXISTS `guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page` (`page`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'admin','test','This is a test.');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` VALUES (1,'Hiawatha webserver','http://www.hiawatha-webserver.org/'),(2,'Banshee PHP framework','http://www.banshee-php.org/');
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_page_views`
--

DROP TABLE IF EXISTS `log_page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_page_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page` tinytext NOT NULL,
  `date` date NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_page_views`
--

LOCK TABLES `log_page_views` WRITE;
/*!40000 ALTER TABLE `log_page_views` DISABLE KEYS */;
INSERT INTO `log_page_views` VALUES (1,'homepage','2012-11-13',3),(2,'crud','2012-11-13',3),(3,'system/error','2012-11-13',2),(4,'login','2012-11-13',5),(5,'homepage','2012-11-14',2),(6,'login','2012-11-14',4),(7,'offline','2012-11-14',2),(8,'homepage','2012-11-16',16),(9,'modules','2012-11-16',13),(10,'demos','2012-11-16',21),(11,'demos/ajax','2012-11-16',35),(12,'demos/calendar','2012-11-16',7),(13,'demos/captcha','2012-11-16',2),(14,'captcha','2012-11-16',2),(15,'agenda','2012-11-16',16),(16,'login','2012-11-16',116),(17,'guestbook','2012-11-16',1),(18,'forum','2012-11-16',16),(19,'dictionary','2012-11-16',2),(20,'faq','2012-11-16',1),(21,'logout','2012-11-16',1),(22,'homepage','2012-11-17',24),(23,'modules','2012-11-17',6),(24,'system/error','2012-11-17',8),(25,'demos','2012-11-17',3),(26,'login','2012-11-17',25),(27,'homepage','2012-11-18',278),(28,'login','2012-11-18',3),(29,'logout','2012-11-18',1),(30,'homepage','2012-11-20',19),(31,'system/error','2012-11-20',3),(32,'login','2012-11-20',5),(33,'demos','2012-11-20',27),(34,'demos/posting','2012-11-20',7),(35,'demos/ajax','2012-11-20',1),(36,'demos/calendar','2012-11-20',2),(37,'demos/captcha','2012-11-20',1),(38,'captcha','2012-11-20',1),(39,'demos/errors','2012-11-20',1),(40,'demos/ckeditor','2012-11-20',3),(41,'demos/googlemaps','2012-11-20',2),(42,'demos/openstreetmap','2012-11-20',2),(43,'demos/layout','2012-11-20',3),(44,'demos/pagination','2012-11-20',1),(45,'demos/alphabetize','2012-11-20',1),(46,'demos/pdf','2012-11-20',1),(47,'demos/poll','2012-11-20',2),(48,'demos/splitform','2012-11-20',42),(49,'demos/system_message','2012-11-20',1),(50,'demos/banshee_website','2012-11-20',1),(51,'demos/validation','2012-11-20',1),(52,'demos/utf8','2012-11-20',1),(53,'demos/parameter','2012-11-20',1),(54,'homepage','2012-11-21',5),(55,'modules','2012-11-21',5),(56,'photo','2012-11-21',3),(57,'demos','2012-11-21',2),(58,'login','2012-11-21',2),(59,'demos/openstreetmap','2012-11-21',2),(60,'search','2012-11-21',3),(61,'forum','2012-11-21',1),(62,'homepage','2012-11-22',3),(63,'login','2012-11-22',1),(64,'demos','2012-11-22',2),(65,'demos/splitform','2012-11-22',1),(66,'login','2012-11-23',24),(67,'homepage','2012-11-23',21),(68,'demos','2012-11-23',14),(69,'system/error','2012-11-23',12),(70,'logout','2012-11-23',2),(71,'demos/readonly','2012-11-23',1),(72,'modules','2012-11-23',5),(73,'demos/poll','2012-11-23',10),(74,'poll','2012-11-23',7),(75,'demos/system_message','2012-11-23',1),(76,'demos/splitform','2012-11-23',2),(77,'homepage','2012-11-24',2),(78,'login','2012-11-24',2),(79,'login','2012-11-26',3),(80,'homepage','2012-11-27',2),(81,'demos','2012-11-27',2),(82,'login','2012-11-27',1),(83,'demos/splitform','2012-11-27',6),(84,'homepage','2012-11-28',3),(85,'modules','2012-11-28',1),(86,'agenda','2012-11-28',12),(87,'homepage','2012-12-03',1),(88,'homepage','2012-12-05',1),(89,'modules','2012-12-05',2),(90,'forum','2012-12-05',3),(91,'demos','2012-12-05',3),(92,'demos/ckeditor','2012-12-05',83),(93,'login','2012-12-05',2),(94,'homepage','2012-12-06',1),(95,'modules','2012-12-06',1),(96,'news','2012-12-06',1),(97,'demos','2012-12-06',1),(98,'homepage','2012-12-07',1),(99,'login','2012-12-07',7),(100,'demos','2012-12-07',20),(101,'homepage','2012-12-08',1),(102,'login','2012-12-08',2),(103,'homepage','2012-12-10',1),(104,'modules','2012-12-10',2),(105,'photo','2012-12-10',5),(106,'collection','2012-12-10',2),(107,'collection','2012-12-13',3),(108,'photo','2012-12-13',7),(109,'homepage','2012-12-14',8),(110,'demos','2012-12-14',8),(111,'demos/ajax','2012-12-14',10),(112,'modules','2012-12-14',4),(113,'demos/ckeditor','2012-12-14',1),(114,'login','2012-12-14',4),(115,'login','2012-12-17',2),(116,'homepage','2012-12-19',2),(117,'image','2012-12-19',160),(118,'forum','2012-12-19',3),(119,'forum','2012-12-20',6),(120,'homepage','2012-12-20',1),(121,'modules','2012-12-20',9),(122,'photo','2012-12-20',5),(123,'collection','2012-12-20',3),(124,'search','2012-12-20',32),(125,'agenda','2012-12-20',3),(126,'demos','2012-12-20',1),(127,'demos/googlemaps','2012-12-20',2),(128,'modules','2012-12-21',1),(129,'login','2012-12-21',1),(130,'contact','2012-12-23',1),(131,'homepage','2012-12-24',49),(132,'modules','2012-12-24',4),(133,'login','2012-12-24',6),(134,'password','2012-12-24',4),(135,'system/error','2012-12-24',2),(136,'demos','2012-12-24',5),(137,'logout','2012-12-24',1),(138,'guestbook','2012-12-27',1),(139,'system/error','2012-12-27',1),(140,'homepage','2012-12-27',1),(141,'guestbook','2012-12-29',1),(142,'forum','2012-12-29',1),(143,'guestbook','2012-12-30',1),(144,'homepage','2013-01-03',1),(145,'login','2013-01-03',5),(146,'password','2013-01-03',2),(147,'homepage','2013-01-04',1),(148,'login','2013-01-04',6),(149,'modules','2013-01-07',1),(150,'homepage','2013-01-08',1),(151,'login','2013-01-08',4),(152,'demos','2013-01-09',4),(153,'homepage','2013-01-09',5),(154,'modules','2013-01-09',2),(155,'login','2013-01-09',10),(156,'demos/googlemaps','2013-01-09',2),(157,'demos/ajax','2013-01-09',3),(158,'photo','2013-01-09',8),(159,'weblog','2013-01-09',1),(160,'demos','2013-01-10',1),(161,'modules','2013-01-10',2),(162,'homepage','2013-01-10',13),(163,'login','2013-01-10',3),(164,'homepage','2013-01-12',1),(165,'homepage','2013-01-15',1),(166,'login','2013-01-15',2),(167,'search','2013-01-16',14),(168,'homepage','2013-01-16',3),(169,'homepage','2013-01-18',1),(170,'modules','2013-01-22',1),(171,'system/error','2013-01-22',1),(172,'login','2013-01-22',1),(173,'homepage','2013-01-22',36),(174,'homepage','2013-01-23',64),(175,'agenda','2013-01-23',6),(176,'news','2013-01-23',34),(177,'demos','2013-01-23',2),(178,'demos/banshee_website','2013-01-23',3),(179,'demos/ajax','2013-01-23',47),(180,'login','2013-01-23',8),(181,'system/error','2013-01-23',9),(182,'demos/ajax','2013-01-24',1),(183,'homepage','2013-01-24',7),(184,'modules','2013-01-24',1),(185,'demos','2013-01-24',3),(186,'login','2013-01-24',1),(187,'system/error','2013-01-24',8),(188,'faq','2013-01-25',1),(189,'demos/googlemaps','2013-01-25',1),(190,'private','2013-01-25',1),(191,'homepage','2013-01-25',1),(192,'contact','2013-01-25',1),(193,'demos/splitform','2013-01-25',1),(194,'homepage','2013-01-26',1),(195,'login','2013-01-26',3),(196,'demos/ckeditor','2013-01-26',1),(197,'homepage','2013-01-27',24),(198,'demos','2013-01-27',4),(199,'demos/system_message','2013-01-27',7),(200,'modules','2013-01-27',3),(201,'guestbook','2013-01-27',1),(202,'system/error','2013-01-27',1),(203,'guestbook','2013-01-28',1),(204,'homepage','2013-01-28',2),(205,'collection','2013-01-28',1),(206,'forum','2013-01-28',1),(207,'demos/pdf','2013-01-29',1),(208,'demos/*/parameter','2013-01-29',1),(209,'homepage','2013-01-29',15),(210,'modules','2013-01-29',2),(211,'login','2013-01-29',5),(212,'links','2013-01-29',1),(213,'system/error','2013-01-29',2),(214,'demos','2013-01-29',1),(215,'demos/posting','2013-01-29',2),(216,'newsletter','2013-01-29',1),(217,'demos/validation','2013-01-30',1),(218,'homepage','2013-01-30',11),(219,'demos','2013-01-30',8),(220,'demos/posting','2013-01-30',225),(221,'system/error','2013-01-30',11),(222,'modules','2013-01-30',7),(223,'login','2013-01-30',7),(224,'weblog','2013-01-30',6),(225,'forum','2013-01-30',27),(226,'demos/ckeditor','2013-01-30',3),(227,'search','2013-01-30',1),(228,'poll','2013-01-31',1),(229,'login','2013-01-31',1),(230,'demos/utf8','2013-02-01',1),(231,'demos/alphabetize','2013-02-01',1),(232,'demos/layout','2013-02-01',1),(233,'demos/errors','2013-02-01',1),(234,'login','2013-02-01',1),(235,'demos/posting','2013-02-02',1),(236,'homepage','2013-02-02',2),(237,'demos/calendar','2013-02-02',1),(238,'demos/banshee_website','2013-02-03',1),(239,'homepage','2013-02-03',1),(240,'login','2013-02-03',1),(241,'demos/openstreetmap','2013-02-04',1),(242,'news','2013-02-04',1),(243,'homepage','2013-02-04',3),(244,'dictionary','2013-02-04',1),(245,'demos/poll','2013-02-04',1),(246,'login','2013-02-04',3),(247,'demos/system_message','2013-02-05',1),(248,'homepage','2013-02-05',1),(249,'modules','2013-02-06',1),(250,'system/error','2013-02-06',1),(251,'homepage','2013-02-06',1),(252,'login','2013-02-06',3),(253,'password','2013-02-06',1),(254,'photo','2013-02-07',1),(255,'agenda','2013-02-07',1),(256,'weblog','2013-02-07',1),(257,'demos/captcha','2013-02-07',1),(258,'poll','2013-02-07',1),(259,'demos/ajax','2013-02-08',1),(260,'demos/pagination','2013-02-08',1),(261,'login','2013-02-08',1),(262,'demos','2013-02-08',1),(263,'faq','2013-02-09',1),(264,'private','2013-02-09',1),(265,'demos/googlemaps','2013-02-09',1),(266,'contact','2013-02-09',1),(267,'demos/splitform','2013-02-09',1),(268,'homepage','2013-02-10',1),(269,'demos/ckeditor','2013-02-10',1),(270,'homepage','2013-02-11',2),(271,'login','2013-02-11',2),(272,'system/error','2013-02-12',1),(273,'guestbook','2013-02-13',1),(274,'collection','2013-02-13',1),(275,'forum','2013-02-13',1),(276,'homepage','2013-02-13',13),(277,'modules','2013-02-13',11),(278,'demos','2013-02-13',6),(279,'demos/googlemaps','2013-02-13',6),(280,'demos/openstreetmap','2013-02-13',54),(281,'demos/posting','2013-02-13',3),(282,'login','2013-02-13',19),(283,'mailbox','2013-02-13',25),(284,'system/error','2013-02-13',4),(285,'logout','2013-02-13',3),(286,'weblog','2013-02-13',1),(287,'demos/pdf','2013-02-13',1),(288,'demos/*/parameter','2013-02-14',1),(289,'links','2013-02-14',1),(290,'newsletter','2013-02-14',1),(291,'demos/validation','2013-02-14',1),(292,'homepage','2013-02-15',1),(293,'search','2013-02-15',1),(294,'poll','2013-02-16',1),(295,'login','2013-02-16',1),(296,'demos/alphabetize','2013-02-16',1),(297,'demos/utf8','2013-02-16',1),(298,'homepage','2013-02-16',2),(299,'demos/layout','2013-02-17',1),(300,'homepage','2013-02-17',2),(301,'demos','2013-02-17',3),(302,'demos/googlemaps','2013-02-17',4),(303,'demos/openstreetmap','2013-02-17',2),(304,'demos/posting','2013-02-17',1),(305,'demos/errors','2013-02-17',1),(306,'login','2013-02-17',1),(307,'demos/calendar','2013-02-17',1),(308,'demos/banshee_website','2013-02-18',1),(309,'guestbook','2013-02-18',1),(310,'photo','2013-02-18',1),(311,'login','2013-02-18',1),(312,'homepage','2013-02-19',1),(313,'demos','2013-02-19',2),(314,'demos/posting','2013-02-19',24),(315,'news','2013-02-19',1),(316,'dictionary','2013-02-19',1),(317,'demos/openstreetmap','2013-02-20',1),(318,'demos/poll','2013-02-20',1),(319,'agenda','2013-02-20',1),(320,'demos/system_message','2013-02-20',1),(321,'demos/pagination','2013-02-21',1),(322,'modules','2013-02-21',1),(323,'system/error','2013-02-21',1),(324,'weblog','2013-02-22',2),(325,'photo','2013-02-22',1),(326,'login','2013-02-22',1),(327,'password','2013-02-22',1),(328,'agenda','2013-02-22',1),(329,'homepage','2013-02-23',54),(330,'demos/captcha','2013-02-23',1),(331,'demos','2013-02-23',2),(332,'modules','2013-02-23',3),(333,'login','2013-02-23',48),(334,'demos/pagination','2013-02-23',1),(335,'demos/ajax','2013-02-24',1),(336,'homepage','2013-02-24',81),(337,'faq','2013-02-24',1),(338,'private','2013-02-24',1),(339,'demos/googlemaps','2013-02-24',1),(340,'login','2013-02-24',9),(341,'demos','2013-02-24',1),(342,'contact','2013-02-25',1),(343,'demos/splitform','2013-02-25',1),(344,'homepage','2013-02-25',56),(345,'login','2013-02-25',14),(346,'modules','2013-02-25',6),(347,'mailbox','2013-02-25',1),(348,'system/error','2013-02-25',3),(349,'logout','2013-02-25',4),(350,'demos','2013-02-25',1),(351,'profile','2013-02-25',1),(352,'homepage','2013-02-26',9),(353,'demos/ckeditor','2013-02-26',1),(354,'login','2013-02-26',2),(355,'homepage','2013-02-27',1),(356,'agenda','2013-02-27',1),(357,'system/error','2013-02-27',1),(358,'guestbook','2013-02-28',1),(359,'homepage','2013-02-28',1),(360,'collection','2013-02-28',1),(361,'forum','2013-02-28',1),(362,'demos/pdf','2013-02-28',1),(363,'demos/*/parameter','2013-03-01',1),(364,'links','2013-03-01',1),(365,'newsletter','2013-03-01',1),(366,'demos/validation','2013-03-01',1),(367,'homepage','2013-03-02',15),(368,'search','2013-03-02',1),(369,'poll','2013-03-03',1),(370,'login','2013-03-03',3),(371,'homepage','2013-03-03',3),(372,'demos','2013-03-03',4),(373,'demos/openstreetmap','2013-03-03',4),(374,'demos/googlemaps','2013-03-03',2),(375,'modules','2013-03-03',1),(376,'mailbox','2013-03-03',8),(377,'logout','2013-03-03',1),(378,'demos/alphabetize','2013-03-03',1),(379,'demos/utf8','2013-03-03',1),(380,'demos/layout','2013-03-04',1),(381,'demos/errors','2013-03-04',1),(382,'login','2013-03-04',1),(383,'demos/posting','2013-03-04',1),(384,'demos/calendar','2013-03-04',1),(385,'homepage','2013-03-05',2),(386,'weblog','2013-03-05',20),(387,'demos/banshee_website','2013-03-05',1),(388,'login','2013-03-05',1),(389,'homepage','2013-03-06',9),(390,'news','2013-03-06',1),(391,'demos/openstreetmap','2013-03-07',1),(392,'dictionary','2013-03-07',1),(393,'demos/poll','2013-03-07',1),(394,'demos','2013-03-07',2),(395,'demos/validation','2013-03-07',7),(396,'demos/system_message','2013-03-08',1),(397,'demos/pagination','2013-03-08',1),(398,'modules','2013-03-08',1),(399,'system/error','2013-03-09',1),(400,'login','2013-03-09',1),(401,'password','2013-03-09',1),(402,'photo','2013-03-09',1),(403,'weblog','2013-03-09',1),(404,'agenda','2013-03-09',1),(405,'news','2013-03-09',1),(406,'demos/captcha','2013-03-10',1),(407,'demos/pagination','2013-03-10',1),(408,'login','2013-03-10',1),(409,'demos/ajax','2013-03-11',1),(410,'demos','2013-03-11',2),(411,'faq','2013-03-11',1),(412,'homepage','2013-03-11',21),(413,'modules','2013-03-11',2),(414,'demos/googlemaps','2013-03-11',1),(415,'weblog','2013-03-11',1),(416,'private','2013-03-11',1),(417,'contact','2013-03-12',1),(418,'homepage','2013-03-12',1),(419,'demos/splitform','2013-03-12',1),(420,'weblog','2013-03-13',1),(421,'demos/ckeditor','2013-03-13',1),(422,'homepage','2013-03-13',1),(423,'agenda','2013-03-14',1),(424,'homepage','2013-03-14',3),(425,'system/error','2013-03-14',2),(426,'login','2013-03-14',10),(427,'guestbook','2013-03-15',1),(428,'homepage','2013-03-15',56),(429,'collection','2013-03-15',1),(430,'forum','2013-03-15',1),(431,'demos/pdf','2013-03-15',1),(432,'demos/*/parameter','2013-03-16',1),(433,'homepage','2013-03-16',1),(434,'demos','2013-03-16',1),(435,'demos/captcha','2013-03-16',5),(436,'captcha','2013-03-16',5),(437,'links','2013-03-16',1),(438,'newsletter','2013-03-16',1),(439,'demos/validation','2013-03-16',1),(440,'weblog','2013-03-16',1),(441,'dictionary','2013-03-17',1),(442,'search','2013-03-17',1),(443,'photo','2013-03-17',1),(444,'homepage','2013-03-18',1),(445,'poll','2013-03-18',1),(446,'login','2013-03-18',1),(447,'forum','2013-03-18',1),(448,'collection','2013-03-18',1),(449,'demos/utf8','2013-03-18',1),(450,'demos/alphabetize','2013-03-18',1),(451,'weblog','2013-03-19',2),(452,'agenda','2013-03-19',1),(453,'demos/layout','2013-03-19',1),(454,'demos/errors','2013-03-19',1),(455,'login','2013-03-19',1),(456,'demos/posting','2013-03-19',1),(457,'demos/calendar','2013-03-19',1),(458,'dictionary','2013-03-19',1),(459,'demos/pagination','2013-03-20',3),(460,'homepage','2013-03-20',1),(461,'demos/banshee_website','2013-03-20',1),(462,'login','2013-03-20',1),(463,'photo','2013-03-20',1),(464,'poll','2013-03-21',1),(465,'forum','2013-03-21',1),(466,'demos/pagination','2013-03-21',2),(467,'homepage','2013-03-21',20),(468,'login','2013-03-21',14),(469,'logout','2013-03-21',7),(470,'system/error','2013-03-21',4),(471,'demos/pagination','2013-03-22',1),(472,'agenda','2013-03-22',1),(473,'news','2013-03-22',1),(474,'demos/openstreetmap','2013-03-22',1),(475,'dictionary','2013-03-22',1),(476,'demos/poll','2013-03-22',1),(477,'poll','2013-03-22',1),(478,'agenda','2013-03-23',1),(479,'demos/pagination','2013-03-23',4),(480,'demos/system_message','2013-03-23',1),(481,'demos/alphabetize','2013-03-23',1),(482,'modules','2013-03-24',2),(483,'homepage','2013-03-24',8),(484,'demos','2013-03-24',1),(485,'agenda','2013-03-24',1),(486,'demos/pagination','2013-03-24',2),(487,'demos/alphabetize','2013-03-24',4),(488,'system/error','2013-03-24',1),(489,'weblog','2013-03-24',2),(490,'login','2013-03-24',1),(491,'password','2013-03-24',1),(492,'photo','2013-03-24',1),(493,'agenda','2013-03-25',2),(494,'demos/alphabetize','2013-03-25',5),(495,'news','2013-03-25',1),(496,'demos/pagination','2013-03-25',1),(497,'poll','2013-03-25',1),(498,'demos/captcha','2013-03-25',1),(499,'weblog','2013-03-25',1),(500,'demos/alphabetize','2013-03-26',5),(501,'agenda','2013-03-26',1),(502,'demos/pagination','2013-03-26',1),(503,'dictionary','2013-03-26',2),(504,'login','2013-03-26',2),(505,'demos/ajax','2013-03-26',1),(506,'demos','2013-03-26',1),(507,'faq','2013-03-26',1),(508,'demos/googlemaps','2013-03-27',1),(509,'weblog','2013-03-27',1),(510,'private','2013-03-27',1),(511,'contact','2013-03-27',1),(512,'demos/alphabetize','2013-03-27',4),(513,'demos/splitform','2013-03-27',1),(514,'demos/alphabetize','2013-03-28',3),(515,'weblog','2013-03-28',1),(516,'dictionary','2013-03-28',1),(517,'demos/ckeditor','2013-03-28',1),(518,'homepage','2013-03-28',1),(519,'demos/pagination','2013-03-29',1),(520,'demos/alphabetize','2013-03-29',2),(521,'agenda','2013-03-29',2),(522,'weblog','2013-03-29',3),(523,'homepage','2013-03-29',1),(524,'system/error','2013-03-29',1),(525,'demos/alphabetize','2013-03-30',2),(526,'guestbook','2013-03-30',1),(527,'collection','2013-03-30',1),(528,'forum','2013-03-30',1),(529,'demos/pdf','2013-03-30',1),(530,'weblog','2013-03-31',2),(531,'system/error','2013-03-31',2),(532,'login','2013-03-31',5),(533,'search','2013-03-31',1),(534,'private','2013-03-31',1),(535,'poll','2013-03-31',1),(536,'photo','2013-03-31',1),(537,'newsletter','2013-03-31',2),(538,'news','2013-03-31',1),(539,'links','2013-03-31',2),(540,'guestbook','2013-03-31',1),(541,'forum','2013-03-31',1),(542,'faq','2013-03-31',1),(543,'dictionary','2013-03-31',1),(544,'demos/validation','2013-03-31',1),(545,'demos/utf8','2013-03-31',1),(546,'demos/system_message','2013-03-31',1),(547,'demos/splitform','2013-03-31',1),(548,'demos/posting','2013-03-31',1),(549,'demos/poll','2013-03-31',1),(550,'demos/pdf','2013-03-31',1),(551,'demos/pagination','2013-03-31',1),(552,'demos/openstreetmap','2013-03-31',1),(553,'demos/layout','2013-03-31',1),(554,'demos/googlemaps','2013-03-31',1),(555,'demos/errors','2013-03-31',1),(556,'demos/ckeditor','2013-03-31',1),(557,'demos/captcha','2013-03-31',1),(558,'demos/calendar','2013-03-31',1),(559,'demos/banshee_website','2013-03-31',1),(560,'demos/alphabetize','2013-03-31',1),(561,'demos/ajax','2013-03-31',1),(562,'demos/*/parameter','2013-03-31',2),(563,'contact','2013-03-31',1),(564,'collection','2013-03-31',1),(565,'agenda','2013-03-31',2),(566,'demos/validation','2013-04-01',1),(567,'weblog','2013-04-01',1),(568,'dictionary','2013-04-01',1),(569,'search','2013-04-01',1),(570,'photo','2013-04-01',1),(571,'homepage','2013-04-02',2),(572,'login','2013-04-02',1),(573,'forum','2013-04-02',1),(574,'poll','2013-04-02',1),(575,'demos/utf8','2013-04-03',1),(576,'collection','2013-04-03',1),(577,'forum','2013-04-03',1),(578,'demos/alphabetize','2013-04-03',1),(579,'weblog','2013-04-03',1),(580,'demos/errors','2013-04-03',1),(581,'login','2013-04-03',1),(582,'agenda','2013-04-03',1),(583,'demos/layout','2013-04-03',1),(584,'demos/calendar','2013-04-04',1),(585,'demos/posting','2013-04-04',1),(586,'weblog','2013-04-04',1),(587,'demos/pagination','2013-04-04',3),(588,'dictionary','2013-04-04',1),(589,'homepage','2013-04-04',1),(590,'weblog','2013-04-05',8),(591,'poll','2013-04-05',4),(592,'photo','2013-04-05',3),(593,'password','2013-04-05',1),(594,'forum','2013-04-05',3),(595,'dictionary','2013-04-05',4),(596,'demos/pagination','2013-04-05',9),(597,'demos/alphabetize','2013-04-05',26),(598,'collection','2013-04-05',1),(599,'agenda','2013-04-05',4),(600,'homepage','2013-04-05',1),(601,'login','2013-04-05',1),(602,'demos/banshee_website','2013-04-05',1),(603,'demos/pagination','2013-04-06',1),(604,'demos/openstreetmap','2013-04-06',1),(605,'agenda','2013-04-06',1),(606,'news','2013-04-06',1),(607,'dictionary','2013-04-06',1),(608,'demos/poll','2013-04-06',1),(609,'poll','2013-04-06',1),(610,'agenda','2013-04-07',1),(611,'demos/pagination','2013-04-07',1),(612,'demos/alphabetize','2013-04-07',1),(613,'demos/system_message','2013-04-07',1),(614,'system/error','2013-04-08',3),(615,'demos/alphabetize','2013-04-08',6),(616,'demos/pagination','2013-04-08',5),(617,'agenda','2013-04-08',1),(618,'dictionary','2013-04-08',1),(619,'modules','2013-04-08',3),(620,'homepage','2013-04-08',15),(621,'news','2013-04-08',2),(622,'login','2013-04-08',6),(623,'weblog','2013-04-08',1),(624,'demos','2013-04-08',3),(625,'demos/poll','2013-04-08',2),(626,'poll','2013-04-08',2),(627,'banshee/error','2013-04-08',5),(628,'banshee/login','2013-04-08',16),(629,'logout','2013-04-08',1),(630,'password','2013-04-08',1),(631,'agenda','2013-04-09',2),(632,'demos/alphabetize','2013-04-09',5),(633,'news','2013-04-09',1),(634,'photo','2013-04-09',1),(635,'weblog','2013-04-09',1),(636,'demos/captcha','2013-04-09',2),(637,'homepage','2013-04-09',3),(638,'demos','2013-04-09',6),(639,'demos/calendar','2013-04-09',6),(640,'banshee/login','2013-04-09',4),(641,'modules','2013-04-09',1),(642,'demos/ajax','2013-04-09',1),(643,'captcha','2013-04-09',1),(644,'demos/ckeditor','2013-04-09',1),(645,'private','2013-04-09',2),(646,'demos/pagination','2013-04-09',1),(647,'poll','2013-04-09',1),(648,'guestbook','2013-04-10',1),(649,'demos/alphabetize','2013-04-10',7),(650,'weblog','2013-04-10',1),(651,'demos/ajax','2013-04-10',1),(652,'agenda','2013-04-10',1),(653,'demos/pagination','2013-04-10',1),(654,'banshee/login','2013-04-10',1),(655,'demos','2013-04-10',1),(656,'forum','2013-04-10',1),(657,'banshee/error','2013-04-10',1),(658,'dictionary','2013-04-11',2),(659,'demos/googlemaps','2013-04-11',1),(660,'weblog','2013-04-11',1),(661,'contact','2013-04-11',1),(662,'demos/alphabetize','2013-04-11',2),(663,'faq','2013-04-11',1),(664,'private','2013-04-11',1),(665,'agenda','2013-04-12',1),(666,'demos/alphabetize','2013-04-12',6),(667,'demos/splitform','2013-04-12',1),(668,'guestbook','2013-04-12',1),(669,'dictionary','2013-04-12',1),(670,'weblog','2013-04-12',1),(671,'demos/ckeditor','2013-04-12',1),(672,'demos/pagination','2013-04-13',1),(673,'agenda','2013-04-13',2),(674,'weblog','2013-04-13',3),(675,'homepage','2013-04-13',3),(676,'demos/alphabetize','2013-04-13',1),(677,'demos/alphabetize','2013-04-14',3),(678,'banshee/error','2013-04-14',1),(679,'guestbook','2013-04-14',1),(680,'demos/pdf','2013-04-14',1),(681,'collection','2013-04-15',1),(682,'forum','2013-04-15',2),(683,'demos/*/parameter','2013-04-15',1),(684,'weblog','2013-04-15',1),(685,'links','2013-04-15',1),(686,'agenda','2013-04-15',1),(687,'newsletter','2013-04-15',1),(688,'demos/validation','2013-04-16',1),(689,'weblog','2013-04-16',1),(690,'search','2013-04-16',1),(691,'dictionary','2013-04-16',1),(692,'photo','2013-04-16',1),(693,'homepage','2013-04-17',2),(694,'banshee/login','2013-04-17',6),(695,'forum','2013-04-17',1),(696,'poll','2013-04-17',1),(697,'guestbook','2013-04-17',1),(698,'collection','2013-04-18',1),(699,'demos/utf8','2013-04-18',1),(700,'homepage','2013-04-18',6),(701,'banshee/login','2013-04-18',22),(702,'demos','2013-04-18',1),(703,'modules','2013-04-18',2),(704,'demos/alphabetize','2013-04-18',1),(705,'photo','2013-04-18',192),(706,'banshee/error','2013-04-18',1),(707,'weblog','2013-04-18',1),(708,'demos/errors','2013-04-18',1),(709,'agenda','2013-04-19',1),(710,'demos/layout','2013-04-19',1),(711,'demos/calendar','2013-04-19',1),(712,'demos/pagination','2013-04-19',3),(713,'banshee/login','2013-04-19',3),(714,'demos/posting','2013-04-19',1),(715,'weblog','2013-04-19',2),(716,'dictionary','2013-04-19',1),(717,'homepage','2013-04-19',1),(718,'banshee/login','2013-04-20',1),(719,'poll','2013-04-20',1),(720,'demos/banshee_website','2013-04-20',1),(721,'photo','2013-04-20',1),(722,'demos/pagination','2013-04-20',1),(723,'forum','2013-04-20',1),(724,'demos/pagination','2013-04-21',1),(725,'demos/openstreetmap','2013-04-21',1),(726,'homepage','2013-04-21',6),(727,'banshee/login','2013-04-21',3),(728,'banshee/error','2013-04-21',2),(729,'logout','2013-04-21',1),(730,'agenda','2013-04-21',1),(731,'news','2013-04-21',1),(732,'dictionary','2013-04-21',1),(733,'poll','2013-04-21',1),(734,'demos/poll','2013-04-22',1),(735,'agenda','2013-04-22',1),(736,'demos/pagination','2013-04-22',2),(737,'homepage','2013-04-22',5),(738,'modules','2013-04-22',2),(739,'newsletter','2013-04-22',48),(740,'banshee/error','2013-04-22',2),(741,'demos','2013-04-22',2),(742,'banshee/login','2013-04-22',4),(743,'demos/alphabetize','2013-04-22',1),(744,'demos/system_message','2013-04-22',1),(745,'agenda','2013-04-23',1),(746,'demos/pagination','2013-04-23',5),(747,'demos/alphabetize','2013-04-23',4),(748,'weblog','2013-04-23',1),(749,'modules','2013-04-23',1),(750,'banshee/login','2013-04-23',1),(751,'password','2013-04-23',1),(752,'banshee/error','2013-04-23',1),(753,'agenda','2013-04-24',2),(754,'demos/alphabetize','2013-04-24',5),(755,'news','2013-04-24',1),(756,'photo','2013-04-24',1),(757,'homepage','2013-04-24',25),(758,'banshee/login','2013-04-24',10),(759,'demos','2013-04-24',3),(760,'demos/captcha','2013-04-24',23),(761,'captcha','2013-04-24',9),(762,'weblog','2013-04-24',1),(763,'banshee/error','2013-04-24',3),(764,'demos/*/parameter','2013-04-24',1),(765,'demo','2013-04-24',1),(766,'modules','2013-04-24',2),(767,'demos/pagination','2013-04-25',2),(768,'poll','2013-04-25',1),(769,'demos/alphabetize','2013-04-25',3),(770,'demos/ajax','2013-04-25',1),(771,'weblog','2013-04-25',1),(772,'homepage','2013-04-25',2),(773,'search','2013-04-25',26),(774,'agenda','2013-04-25',1),(775,'banshee/login','2013-04-25',1),(776,'forum','2013-04-25',1),(777,'demos','2013-04-25',1),(778,'banshee/error','2013-04-25',1),(779,'demos/alphabetize','2013-04-26',2),(780,'demos/googlemaps','2013-04-26',1),(781,'weblog','2013-04-26',1),(782,'dictionary','2013-04-26',2),(783,'contact','2013-04-26',1),(784,'faq','2013-04-26',1),(785,'private','2013-04-26',1),(786,'homepage','2013-04-26',1),(787,'banshee/error','2013-04-26',1),(788,'banshee/login','2013-04-26',2),(789,'demos/alphabetize','2013-04-27',6),(790,'agenda','2013-04-27',1),(791,'demos/splitform','2013-04-27',1),(792,'banshee/login','2013-04-27',2),(793,'dictionary','2013-04-27',1),(794,'homepage','2013-04-27',2),(795,'weblog','2013-04-27',1),(796,'demos/alphabetize','2013-04-28',2),(797,'demos/ckeditor','2013-04-28',1),(798,'demos/pagination','2013-04-28',1),(799,'agenda','2013-04-28',2),(800,'weblog','2013-04-28',2),(801,'homepage','2013-04-29',2),(802,'weblog','2013-04-29',1),(803,'demos/alphabetize','2013-04-29',2),(804,'banshee/error','2013-04-29',1),(805,'guestbook','2013-04-29',1),(806,'banshee/login','2013-04-29',2),(807,'faq','2013-04-29',1),(808,'demos/pdf','2013-04-29',1),(809,'demos/alphabetize','2013-04-30',1),(810,'banshee/error','2013-04-30',1),(811,'collection','2013-04-30',1),(812,'forum','2013-04-30',2),(813,'homepage','2013-04-30',3),(814,'modules','2013-04-30',1),(815,'banshee/login','2013-04-30',3);
/*!40000 ALTER TABLE `log_page_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_referers`
--

DROP TABLE IF EXISTS `log_referers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_referers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostname` tinytext NOT NULL,
  `url` text NOT NULL,
  `date` date NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `verified` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_referers`
--

LOCK TABLES `log_referers` WRITE;
/*!40000 ALTER TABLE `log_referers` DISABLE KEYS */;
INSERT INTO `log_referers` VALUES (1,'wappalyzer.com','http://wappalyzer.com/applications/banshee','2012-11-20',1,'0'),(2,'s.nsdsvc.com','http://s.nsdsvc.com/App/DddWrapper.swf?c=4','2012-11-20',1,'0'),(3,'us.yhs4.search.yahoo.com','http://us.yhs4.search.yahoo.com/yhs/search;_ylt=A0oG7pYokgxR6G8ABwOl87UF?p=www.welcometobanshee.com&fr=sfp&type=W3i_SP%2C204%2C0_0%2CStartPage%2C20130105%2C19631%2C0%2C18%2C6477&hspart=w3i&hsimp=yhs-syctransfer','2013-02-02',1,'0');
/*!40000 ALTER TABLE `log_referers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_search_queries`
--

DROP TABLE IF EXISTS `log_search_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_search_queries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `query` tinytext NOT NULL,
  `date` date NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_search_queries`
--

LOCK TABLES `log_search_queries` WRITE;
/*!40000 ALTER TABLE `log_search_queries` DISABLE KEYS */;
INSERT INTO `log_search_queries` VALUES (1,'www.welcometobanshee.com','2013-02-05',1),(2,'WWW.WELCOMETOBANSHEE.COM','2013-02-10',1),(3,'banshee php framework','2013-03-13',1);
/*!40000 ALTER TABLE `log_search_queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_visits`
--

DROP TABLE IF EXISTS `log_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_visits`
--

LOCK TABLES `log_visits` WRITE;
/*!40000 ALTER TABLE `log_visits` DISABLE KEYS */;
INSERT INTO `log_visits` VALUES (1,'2012-11-13',3),(2,'2012-11-14',2),(3,'2012-11-16',9),(4,'2012-11-17',8),(5,'2012-11-18',5),(6,'2012-11-20',5),(7,'2012-11-21',4),(8,'2012-11-22',1),(9,'2012-11-23',7),(10,'2012-11-24',1),(11,'2012-11-26',1),(12,'2012-11-27',1),(13,'2012-11-28',3),(14,'2012-12-03',1),(15,'2012-12-05',2),(16,'2012-12-06',1),(17,'2012-12-07',4),(18,'2012-12-08',1),(19,'2012-12-10',1),(20,'2012-12-13',1),(21,'2012-12-14',6),(22,'2012-12-17',1),(23,'2012-12-19',47),(24,'2012-12-20',2),(25,'2012-12-21',2),(26,'2012-12-23',1),(27,'2012-12-24',3),(28,'2012-12-27',2),(29,'2012-12-29',2),(30,'2012-12-30',1),(31,'2013-01-03',3),(32,'2013-01-04',1),(33,'2013-01-07',1),(34,'2013-01-08',1),(35,'2013-01-09',3),(36,'2013-01-10',2),(37,'2013-01-12',1),(38,'2013-01-15',2),(39,'2013-01-16',4),(40,'2013-01-18',1),(41,'2013-01-22',4),(42,'2013-01-23',81),(43,'2013-01-24',4),(44,'2013-01-25',6),(45,'2013-01-26',3),(46,'2013-01-27',4),(47,'2013-01-28',4),(48,'2013-01-29',13),(49,'2013-01-30',7),(50,'2013-01-31',2),(51,'2013-02-01',5),(52,'2013-02-02',4),(53,'2013-02-03',3),(54,'2013-02-04',5),(55,'2013-02-05',2),(56,'2013-02-06',5),(57,'2013-02-07',5),(58,'2013-02-08',4),(59,'2013-02-09',5),(60,'2013-02-10',2),(61,'2013-02-11',2),(62,'2013-02-12',1),(63,'2013-02-13',17),(64,'2013-02-14',4),(65,'2013-02-15',2),(66,'2013-02-16',5),(67,'2013-02-17',7),(68,'2013-02-18',4),(69,'2013-02-19',3),(70,'2013-02-20',4),(71,'2013-02-21',3),(72,'2013-02-22',6),(73,'2013-02-23',12),(74,'2013-02-24',8),(75,'2013-02-25',15),(76,'2013-02-26',5),(77,'2013-02-27',3),(78,'2013-02-28',5),(79,'2013-03-01',4),(80,'2013-03-02',12),(81,'2013-03-03',6),(82,'2013-03-04',5),(83,'2013-03-05',5),(84,'2013-03-06',7),(85,'2013-03-07',4),(86,'2013-03-08',3),(87,'2013-03-09',7),(88,'2013-03-10',3),(89,'2013-03-11',7),(90,'2013-03-12',3),(91,'2013-03-13',3),(92,'2013-03-14',7),(93,'2013-03-15',15),(94,'2013-03-16',6),(95,'2013-03-17',3),(96,'2013-03-18',7),(97,'2013-03-19',9),(98,'2013-03-20',7),(99,'2013-03-21',19),(100,'2013-03-22',7),(101,'2013-03-23',7),(102,'2013-03-24',18),(103,'2013-03-25',12),(104,'2013-03-26',14),(105,'2013-03-27',9),(106,'2013-03-28',7),(107,'2013-03-29',10),(108,'2013-03-30',6),(109,'2013-03-31',46),(110,'2013-04-01',5),(111,'2013-04-02',4),(112,'2013-04-03',9),(113,'2013-04-04',8),(114,'2013-04-05',66),(115,'2013-04-06',7),(116,'2013-04-07',4),(117,'2013-04-08',26),(118,'2013-04-09',16),(119,'2013-04-10',16),(120,'2013-04-11',9),(121,'2013-04-12',12),(122,'2013-04-13',9),(123,'2013-04-14',6),(124,'2013-04-15',8),(125,'2013-04-16',5),(126,'2013-04-17',6),(127,'2013-04-18',27),(128,'2013-04-19',12),(129,'2013-04-20',6),(130,'2013-04-21',7),(131,'2013-04-22',10),(132,'2013-04-23',15),(133,'2013-04-24',22),(134,'2013-04-25',14),(135,'2013-04-26',11),(136,'2013-04-27',13),(137,'2013-04-28',8),(138,'2013-04-29',9),(139,'2013-04-30',7);
/*!40000 ALTER TABLE `log_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailbox`
--

DROP TABLE IF EXISTS `mailbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) unsigned NOT NULL,
  `to_user_id` int(10) unsigned NOT NULL,
  `subject` tinytext NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailbox`
--

LOCK TABLES `mailbox` WRITE;
/*!40000 ALTER TABLE `mailbox` DISABLE KEYS */;
INSERT INTO `mailbox` VALUES (1,1,2,'Hello','Hi user,\r\n\r\nHow are you today?\r\n\r\nGreetings,\r\nAdministrator','2013-02-13 13:31:02',0,NULL),(2,2,1,'Re: Hello','Thanks, I\'m fine.\r\n\r\nUser\r\n\r\n\r\n> Hi user,\r\n> \r\n> How are you today?\r\n> \r\n> Greetings,\r\n> Administrator','2013-02-13 13:31:46',0,NULL);
/*!40000 ALTER TABLE `mailbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `text` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,0,'Home','/'),(2,0,'Modules','/modules'),(3,0,'Demos','/demos'),(4,3,'Test','/test'),(5,0,'CMS','/admin');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Lorum ipsum','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac elit quam. Nullam aliquam justo et nisi dictum pretium interdum tellus hendrerit. Aenean tristique posuere dictum. Maecenas nec sapien ut magna suscipit euismod quis ut metus. Aenean sit amet metus a turpis iaculis mollis. Nam faucibus mauris vel ligula ultricies dapibus. Nullam quis orci ac sem convallis malesuada nec id nisi. Praesent quis tellus nec sapien viverra blandit at ut erat. Curabitur bibendum malesuada erat, in suscipit leo porta et. Cras quis arcu sit amet nibh molestie mollis eu eget nulla. Vivamus sed enim fringilla elit pretium feugiat. Nullam elementum fermentum nunc in sodales.</p>\r\n\r\n<p>Mauris nec nunc quis enim porttitor consectetur at et lorem. Vivamus ac rutrum sapien. Nullam metus lectus, lobortis sit amet vulputate sit amet, fermentum sed velit. Phasellus ac libero urna. Maecenas tellus massa, ultrices sed pretium non, faucibus ut lorem. Donec aliquam vehicula ante, eu sodales felis ullamcorper at. Sed sed odio ipsum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam laoreet tristique est in molestie. Sed lacinia euismod porttitor. Praesent ullamcorper fringilla arcu sit amet viverra. Aliquam erat volutpat.</p>\r\n\r\n<p>Nulla vel eros quam. Nam nec turpis ac turpis pulvinar facilisis non non nunc. Nam bibendum nunc in velit cursus rutrum. Integer at ultricies orci. Suspendisse vitae sodales dui. Integer malesuada hendrerit dui, a ullamcorper mauris aliquam sit amet. Nulla dignissim tortor accumsan velit laoreet non eleifend massa aliquet. Quisque luctus dapibus viverra. Aliquam sed lorem diam. Phasellus condimentum lectus vitae ipsum molestie a vestibulum risus malesuada. Duis posuere urna a arcu facilisis sit amet blandit lacus tempus. Vestibulum vel arcu nunc, ut imperdiet massa. Donec congue risus nec urna laoreet et euismod magna semper. Fusce pharetra porttitor ultrices.</p>','2013-04-30 08:17:30');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisations`
--

DROP TABLE IF EXISTS `organisations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `name_2` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisations`
--

LOCK TABLES `organisations` WRITE;
/*!40000 ALTER TABLE `organisations` DISABLE KEYS */;
INSERT INTO `organisations` VALUES (1,'My organisation');
/*!40000 ALTER TABLE `organisations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_access`
--

DROP TABLE IF EXISTS `page_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_access` (
  `page_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `page_access_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  CONSTRAINT `page_access_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_access`
--

LOCK TABLES `page_access` WRITE;
/*!40000 ALTER TABLE `page_access` DISABLE KEYS */;
INSERT INTO `page_access` VALUES (4,2,1);
/*!40000 ALTER TABLE `page_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `language` varchar(2) NOT NULL,
  `layout` varchar(100) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `style` text,
  `title` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `back` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'/homepage','en','Default layout',0,'img.logo {\r\n  float:right;\r\n  margin-left:20px;\r\n}','Welcome to Banshee, the secure PHP framework','','','<p>Banshee is a PHP website framework, which aims at to be secure, fast and easy to use. It uses the Model-View-Control architecture with XSLT for the View. Although it was designed to use MySQL as the database, other database applications can be used as well with only little effort. For more information about Banshee, visit the <a href=\"http://www.banshee-php.org/\">Banshee website</a>.</p>\r\n\r\n<img src=\"http://www.banshee-php.org/logo.php\" class=\"logo\" alt=\"Banshee logo\">\r\n\r\n<p>In this default installation, there are two users available: \'admin\' and \'user\'. Both have the password \'banshee\'.</p>\r\n\r\n<p>If security is a high priority for your website, you should take a look at the <a href=\"http://www.hiawatha-webserver.org\">Hiawatha webserver</a>.</p>',1,0),(2,'/modules','en','Default layout',0,'','Banshee modules','Modules in Banshee','modules','<ul>\r\n<li><a href=\"/agenda\">Agenda</a></li>\r\n<li><a href=\"/contact\">Contact form</a></li>\r\n<li><a href=\"/dictionary\">Dictionary</a></li>\r\n<li><a href=\"/faq\">F.A.Q.</a></li>\r\n<li><a href=\"/forum\">Forum</a></li>\r\n<li><a href=\"/guestbook\">Guestbook</a></li>\r\n<li><a href=\"/links\">Links</a></li>\r\n<li><a href=\"/mailbox\">Mailbox</a></li>\r\n<li><a href=\"/news\">News</a></li>\r\n<li><a href=\"/newsletter\">Newsletter</a></li>\r\n<li><a href=\"/photo\">Photo album</a></li>\r\n<li><a href=\"/collection\">Photo album collections</a></li>\r\n<li><a href=\"/poll\">Poll</a></li>\r\n<li><a href=\"/profile\">Profile manager</a></li>\r\n<li><a href=\"/search\">Search</a></li>\r\n<li><a href=\"/session\">Session manager</a></li>\r\n<li><a href=\"/weblog\">Weblog</a></li>\r\n</ul>',1,0),(3,'/demos','en','Default layout',0,'','Banshee functionality demos','Banshee demos','banshee, demos','<ul>\r\n<li>Support for <a href=\"/demos/ajax\">AJAX</a>.</li>\r\n<li>A <a href=\"/demos/calendar\">calendar</a> webform object.</li>\r\n<li>The <a href=\"/demos/captcha\">captcha</a> library.</li>\r\n<li>This page shows an <a href=\"/demos/errors\">error message</a>.</li>\r\n<li>An <a href=\"/invisible\">invisible</a> page, a <a href=\"/private\">private</a> page and a <a href=\"/void\">non-existing</a> page.</li>\r\n<li>The WYSIWYG <a href=\"/demos/ckeditor\">CKEditor</a>.</li>\r\n<li><a href=\"/demos/googlemaps\">GoogleMaps static map</a> demo.</a></li>\r\n<li><a href=\"/demos/openstreetmap\">OpenStreetMap static map</a> demo.</a></li>\r\n<li>Browse the available and ready-to-use <a href=\"/demos/layout\">layouts</a>.</li>\r\n<li>A <a href=\"/demos/pagination\">pagination</a> library.</li>\r\n<li>An <a href=\"/demos/alphabetize\">alphabetize</a> library.</li>\r\n<li>The <a href=\"/demos/pdf\">FPDF</a> library.</li>\r\n<li>A <a href=\"/demos/poll\">poll</a> module.</li>\r\n<li>The <a href=\"/demos/posting\">posting</a> library.</li>\r\n<li>The <a href=\"/demos/tablemanager\">tablemanager</a> library.</li>\r\n<li>The <a href=\"/demos/splitform\">splitform</a> library.</li>\r\n<li><a href=\"/demos/utf8\">UTF-8</a> character encoding.</li>\r\n<li>A library for <a href=\"/demos/banshee_website\">remote connection</a> to another Banshee based website.</li>\r\n<li>A library for <a href=\"/demos/validation\">input validation</a>.</li>\r\n<li><a href=\"/demos/system_message\">System message</a> functionality.</li>\r\n<li><a href=\"/demos/readonly\">Read-only</a> access rights.</li>\r\n</ul>\r\n',1,0),(4,'/private','en','',1,NULL,'Private page','','','<p>This is a private page.</p>\r\n\r\n<input type=\"button\" value=\"Back\" class=\"button\" onClick=\"javascript:document.location=\'/demos\'\" />',1,0),(5,'/invisible','en','',0,NULL,'Invisible page','','','<p>This page is invisible to normal users and visitors. Only users with access to the page administration page can view this page.</p>\r\n<p>Page administrators can use this feature to verify a page before making it available to visitors.</p>\r\n\r\n<input type=\"button\" value=\"Back\" class=\"button\" onClick=\"javascript:document.location=\'/demos\'\" />',0,0),(6,'/demos/utf8','en','Default layout',0,'','UTF-8 demo','','','<p>這是一個測試頁，以顯示漢字。</p>',1,1);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photo_albums`
--

DROP TABLE IF EXISTS `photo_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo_albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo_albums`
--

LOCK TABLES `photo_albums` WRITE;
/*!40000 ALTER TABLE `photo_albums` DISABLE KEYS */;
INSERT INTO `photo_albums` VALUES (1,'Wallpapers','Collection of wallpapers','2010-08-21 18:56:40');
/*!40000 ALTER TABLE `photo_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `photo_album_id` int(10) unsigned NOT NULL,
  `extension` varchar(6) NOT NULL,
  `overview` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `photo_album_id` (`photo_album_id`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`photo_album_id`) REFERENCES `photo_albums` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` VALUES (1,'Hiawatha webserver',1,'png',1),(6,'Valley',1,'jpg',1),(7,'Sunset',1,'jpg',1),(8,'Rivendell',1,'jpg',1);
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_answers`
--

DROP TABLE IF EXISTS `poll_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL,
  `answer` varchar(50) NOT NULL,
  `votes` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  CONSTRAINT `poll_answers_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_answers`
--

LOCK TABLES `poll_answers` WRITE;
/*!40000 ALTER TABLE `poll_answers` DISABLE KEYS */;
INSERT INTO `poll_answers` VALUES (1,1,'Lorum',2),(2,1,'Ipsum',4),(3,1,'Dolor',1),(4,2,'Hiawatha',0),(5,2,'Apache',0),(6,2,'Cherokee',0),(7,2,'Nginx',0),(8,2,'Lighttpd',0);
/*!40000 ALTER TABLE `poll_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(100) NOT NULL,
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (1,'Lorum ipsum','2012-01-01','2012-12-31'),(2,'The best webserver','2013-01-01','2029-12-31');
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `profile` tinyint(4) DEFAULT '0',
  `system/sso` tinyint(4) DEFAULT '0',
  `admin` tinyint(4) DEFAULT '0',
  `admin/access` tinyint(4) DEFAULT '0',
  `admin/action` tinyint(4) DEFAULT '0',
  `admin/agenda` tinyint(4) DEFAULT '0',
  `admin/albums` tinyint(4) DEFAULT '0',
  `admin/collection` tinyint(4) DEFAULT '0',
  `admin/dictionary` tinyint(4) DEFAULT '0',
  `admin/faq` tinyint(4) DEFAULT '0',
  `admin/file` tinyint(4) DEFAULT '0',
  `admin/forum` tinyint(4) DEFAULT '0',
  `admin/guestbook` tinyint(4) DEFAULT '0',
  `admin/languages` tinyint(4) DEFAULT '0',
  `admin/links` tinyint(4) DEFAULT '0',
  `admin/logging` tinyint(4) DEFAULT '0',
  `admin/menu` tinyint(4) DEFAULT '0',
  `admin/news` tinyint(4) DEFAULT '0',
  `admin/newsletter` tinyint(4) DEFAULT '0',
  `admin/organisation` tinyint(4) DEFAULT '0',
  `admin/page` tinyint(4) DEFAULT '0',
  `admin/photos` tinyint(4) DEFAULT '0',
  `admin/poll` tinyint(4) DEFAULT '0',
  `admin/role` tinyint(4) DEFAULT '0',
  `admin/settings` tinyint(4) DEFAULT '0',
  `admin/subscriptions` tinyint(4) DEFAULT '0',
  `admin/switch` tinyint(4) DEFAULT '0',
  `admin/user` tinyint(4) DEFAULT '0',
  `admin/weblog` tinyint(4) DEFAULT '0',
  `mailbox` tinyint(4) DEFAULT '0',
  `session` tinyint(4) DEFAULT '0',
  `demos/tablemanager` tinyint(4) DEFAULT '0',
  `demos/readonly` tinyint(4) DEFAULT '0',
  `admin/apitest` tinyint(4) DEFAULT '0',
  `admin/forum/section` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(2,'User',1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,2,0,0);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `content` text,
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL,
  `name` tinytext,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `type` varchar(8) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'admin_page_size','integer','25'),(31,'photo_page_size','integer','10'),(5,'default_language','string','en'),(32,'photo_thumbnail_height','integer','100'),(9,'start_page','string','homepage'),(10,'webmaster_email','string','info@banshee-php.org'),(30,'forum_page_size','string','25'),(12,'forum_maintainers','string','Moderator'),(13,'guestbook_page_size','integer','10'),(14,'guestbook_maintainers','string','Publisher'),(15,'news_page_size','integer','5'),(16,'news_rss_page_size','string','30'),(17,'newsletter_bcc_size','integer','100'),(18,'newsletter_code_timeout','string','15 minutes'),(19,'newsletter_email','string','info@banshee-php.org'),(20,'newsletter_name','string','Hugo Leisink'),(36,'contact_email','string','info@banshee-php.org'),(22,'poll_max_answers','integer','10'),(23,'poll_bans','string',''),(24,'weblog_page_size','string','5'),(25,'weblog_rss_page_size','integer','30'),(26,'head_title','string','Banshee'),(27,'head_description','string','Secure PHP framework'),(28,'head_keywords','string','banshee, secure, php, framework'),(33,'photo_image_height','integer','450'),(35,'secret_website_code','string','CHANGE_ME_INTO_A_RANDOM_STRING'),(37,'photo_thumbnail_width','integer','100'),(38,'photo_image_width','integer','700'),(39,'hiawatha_cache_time','integer','0'),(40,'photo_album_size','integer','18');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,'hugo@banshee-php.org');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (2,2),(1,1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `password` varchar(128) NOT NULL,
  `one_time_key` varchar(128) DEFAULT NULL,
  `cert_serial` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `organisation_id` (`organisation_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'admin','c10b391ff5e75af6ee8469539e6a5428f09eff7e693d6a8c4de0e5525cd9b287',NULL,NULL,1,'Administrator','admin@banshee-php.org'),(2,1,'user','b4f6b1c67ef4f9c3dc67aae05c5d09411fa927e360063f7fd983710dc882cb3c',NULL,NULL,1,'Normal user','user@banshee-php.org');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weblog_comments`
--

DROP TABLE IF EXISTS `weblog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weblog_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weblog_id` int(10) unsigned NOT NULL,
  `author` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weblog_id` (`weblog_id`),
  CONSTRAINT `weblog_comments_ibfk_1` FOREIGN KEY (`weblog_id`) REFERENCES `weblogs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weblog_tagged`
--

DROP TABLE IF EXISTS `weblog_tagged`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weblog_tagged` (
  `weblog_id` int(10) unsigned NOT NULL,
  `weblog_tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`weblog_id`,`weblog_tag_id`),
  KEY `weblog_tag_id` (`weblog_tag_id`),
  CONSTRAINT `weblog_tagged_ibfk_1` FOREIGN KEY (`weblog_id`) REFERENCES `weblogs` (`id`),
  CONSTRAINT `weblog_tagged_ibfk_2` FOREIGN KEY (`weblog_tag_id`) REFERENCES `weblog_tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weblog_tagged`
--

LOCK TABLES `weblog_tagged` WRITE;
/*!40000 ALTER TABLE `weblog_tagged` DISABLE KEYS */;
INSERT INTO `weblog_tagged` VALUES (1,1);
/*!40000 ALTER TABLE `weblog_tagged` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weblog_tags`
--

DROP TABLE IF EXISTS `weblog_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weblog_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weblog_tags`
--

LOCK TABLES `weblog_tags` WRITE;
/*!40000 ALTER TABLE `weblog_tags` DISABLE KEYS */;
INSERT INTO `weblog_tags` VALUES (1,'lorum ipsum');
/*!40000 ALTER TABLE `weblog_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weblogs`
--

DROP TABLE IF EXISTS `weblogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weblogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `weblogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weblogs`
--

LOCK TABLES `weblogs` WRITE;
/*!40000 ALTER TABLE `weblogs` DISABLE KEYS */;
INSERT INTO `weblogs` VALUES (1,1,'Lorum ipsum','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac elit quam. Nullam aliquam justo et nisi dictum pretium interdum tellus hendrerit. Aenean tristique posuere dictum. Maecenas nec sapien ut magna suscipit euismod quis ut metus. Aenean sit amet metus a turpis iaculis mollis. Nam faucibus mauris vel ligula ultricies dapibus. Nullam quis orci ac sem convallis malesuada nec id nisi. Praesent quis tellus nec sapien viverra blandit at ut erat. Curabitur bibendum malesuada erat, in suscipit leo porta et. Cras quis arcu sit amet nibh molestie mollis eu eget nulla. Vivamus sed enim fringilla elit pretium feugiat. Nullam elementum fermentum nunc in sodales.</p>\r\n\r\n<p>Mauris nec nunc quis enim porttitor consectetur at et lorem. Vivamus ac rutrum sapien. Nullam metus lectus, lobortis sit amet vulputate sit amet, fermentum sed velit. Phasellus ac libero urna. Maecenas tellus massa, ultrices sed pretium non, faucibus ut lorem. Donec aliquam vehicula ante, eu sodales felis ullamcorper at. Sed sed odio ipsum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam laoreet tristique est in molestie. Sed lacinia euismod porttitor. Praesent ullamcorper fringilla arcu sit amet viverra. Aliquam erat volutpat.</p>\r\n\r\n<p>Nulla vel eros quam. Nam nec turpis ac turpis pulvinar facilisis non non nunc. Nam bibendum nunc in velit cursus rutrum. Integer at ultricies orci. Suspendisse vitae sodales dui. Integer malesuada hendrerit dui, a ullamcorper mauris aliquam sit amet. Nulla dignissim tortor accumsan velit laoreet non eleifend massa aliquet. Quisque luctus dapibus viverra. Aliquam sed lorem diam. Phasellus condimentum lectus vitae ipsum molestie a vestibulum risus malesuada. Duis posuere urna a arcu facilisis sit amet blandit lacus tempus. Vestibulum vel arcu nunc, ut imperdiet massa. Donec congue risus nec urna laoreet et euismod magna semper. Fusce pharetra porttitor ultrices.</p>','2013-04-30 08:20:07',1);
/*!40000 ALTER TABLE `weblogs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-30 11:25:34
