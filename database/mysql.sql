-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: banshee_dev
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

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
-- Dumping data for table `agenda`
--

LOCK TABLES `agenda` WRITE;
/*!40000 ALTER TABLE `agenda` DISABLE KEYS */;
INSERT INTO `agenda` VALUES (1,'2012-12-25 00:00:00','2012-12-26 23:59:59','Christmas','Merry Christmas!'),(2,'2012-01-01 00:00:00','2012-01-01 23:59:59','New Year\'s Day','Happy new year!'),(3,'2012-06-13 12:00:00','2012-06-16 15:00:00','Lorum ipsum','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer a purus velit, et porttitor diam. Pellentesque porttitor tempor malesuada. Proin cursus pretium nulla, sed imperdiet dolor auctor non. Donec vitae dolor quis est euismod ornare. Donec iaculis tristique lacus in egestas. Vivamus pellentesque massa et enim vulputate et mollis turpis interdum. Mauris nec mi sit amet eros mattis elementum at eu quam. Nulla facilisi. Nullam euismod volutpat lectus, ac porta libero tincidunt ac. Aliquam nec nisl tellus, et pellentesque lorem. Nunc sed lacinia augue. Aenean facilisis ligula id odio blandit et interdum dui accumsan. '),(4,'2012-04-01 12:00:00','2012-04-01 15:00:00','Sed posuere','Sed posuere, mauris nec pharetra pulvinar, nunc purus venenatis sapien, bibendum tempor sem nibh vitae tortor. Curabitur sodales lectus id ipsum pulvinar a sollicitudin tortor consectetur. Donec feugiat tempor posuere. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed adipiscing fermentum justo, id vehicula est tempor nec. Morbi aliquet semper eros, et tincidunt tortor sagittis quis. Nulla nulla enim, convallis in semper sed, tempus sed ante. Fusce viverra tempor purus, vitae laoreet elit tristique eget. Donec laoreet porttitor turpis nec vestibulum. Donec gravida pellentesque commodo. Morbi sodales orci sed leo dictum vehicula. Suspendisse potenti. ');
/*!40000 ALTER TABLE `agenda` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `collection_album` VALUES (1,2),(1,1);
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
INSERT INTO `dictionary` VALUES (1,'Test 2','And this is test 2','Vestibulum ullamcorper porta pede. Mauris posuere lacus id magna. Nunc leo felis, sodales vel, venenatis vitae, convallis a, ligula. Suspendisse dui mi, fringilla in, adipiscing nec, adipiscing sit amet, mi. Nulla justo. Duis dictum tincidunt pede. Cras volutpat laoreet enim. Nullam aliquet laoreet nulla. Nunc est augue, dictum nec, sodales sed, aliquet vitae, nibh. Suspendisse justo. Duis non velit a leo placerat sagittis. Morbi nisl. Mauris fermentum, dui ullamcorper commodo fermentum, risus orci commodo metus, nec gravida leo neque quis nulla. Maecenas accumsan, nisl quis tristique sagittis, dui lorem malesuada pede, non sollicitudin eros magna vitae nulla. In sed ligula. '),(2,'Test 1','This is test 1','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam egestas luctus velit. Cras posuere diam nec nulla. Nunc commodo ligula nec nulla. Morbi semper turpis vel magna. Vivamus iaculis porta lorem. Ut volutpat. Fusce enim ligula, egestas ut, auctor eget, tincidunt nec, urna. Quisque ut dolor id dolor sodales gravida. Vestibulum pede augue, accumsan nec, sollicitudin non, vehicula et, purus. Fusce lorem leo, dapibus vitae, volutpat quis, placerat vitae, enim. Maecenas a ipsum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin rhoncus cursus quam. Mauris viverra mi posuere eros semper fringilla. Maecenas lobortis ornare dui. Nam vehicula consequat arcu. Curabitur risus neque, venenatis at, euismod sed, porta id, dui. Etiam ipsum ligula, tempus quis, suscipit eget, tincidunt a, nibh. Aliquam velit magna, dignissim vitae, suscipit at, volutpat eget, purus. '),(3,'Test 3','And last but not least, test 3','Nullam eget enim nec elit blandit semper. Morbi ipsum nisi, lobortis nec, luctus sed, venenatis et, nunc. Aliquam at metus. Donec a justo. In hac habitasse platea dictumst. Nulla congue lacus a augue malesuada tincidunt. Cras vulputate lacus hendrerit risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec tortor diam, pharetra et, bibendum et, commodo at, turpis. Fusce luctus, mi non suscipit mattis, sem lacus adipiscing tellus, vitae scelerisque nisl quam eu velit. Aliquam erat volutpat. '),(4,'Word','Just the short description.',''),(5,'Lorum ipsum','Lorem ipsum dolor sit amet, consectetur adipiscing elit.','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id nunc. Morbi sit amet ipsum ac metus volutpat consectetur. Aenean elementum ligula sit amet tellus. Aenean tincidunt. Quisque mollis eros vitae dui. Nulla congue lobortis lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In id turpis sit amet ipsum tincidunt sodales. Mauris mi ligula, aliquet vitae, porta quis, euismod in, ligula. Sed eu risus. Quisque quam ipsum, convallis vitae, ultricies sed, consequat eu, neque. Nam augue ante, adipiscing ut, scelerisque nec, pharetra eu, magna. Nulla cursus hendrerit quam. Nam quis felis. Aliquam volutpat luctus lectus. In a mi. Praesent sed augue vel dui consectetur eleifend. Suspendisse potenti. ');
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
INSERT INTO `faq_sections` VALUES (1,'General'),(2,'Test');
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
INSERT INTO `faqs` VALUES (1,1,'How much is one plus one?','<p>One plus one equals two.</p>'),(2,1,'Lorum ipsum?','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vehicula. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pretium nibh ac dui. Integer suscipit sem ut nisl. Maecenas imperdiet. Praesent elit. In tempor. Nulla id libero. Nullam quis massa. Vivamus sapien ante, placerat at, commodo laoreet, aliquet vitae, lorem. Sed pulvinar libero quis magna. Sed molestie, velit sit amet euismod rutrum, diam eros egestas massa, vel porttitor dolor tortor sed mi. Praesent ligula lacus, sodales non, pellentesque non, semper a, massa. Pellentesque mattis dui eu magna. Fusce hendrerit, nunc eget blandit convallis, lacus tortor luctus tellus, nec sodales ipsum est ut orci. Cras elementum mi a augue. In hac habitasse platea dictumst.</p>'),(3,2,'Test question','<p>Test answer</p>');
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
INSERT INTO `forum_messages` VALUES (1,1,1,NULL,'2010-05-13 22:19:21','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer a purus velit, et porttitor diam. Pellentesque porttitor tempor malesuada. Proin cursus pretium nulla, sed imperdiet dolor auctor non. Donec vitae dolor quis est euismod ornare. Donec iaculis tristique lacus in egestas. Vivamus pellentesque massa et enim vulputate et mollis turpis interdum. Mauris nec mi sit amet eros mattis elementum at eu quam. Nulla facilisi. Nullam euismod volutpat lectus, ac porta libero tincidunt ac. Aliquam nec nisl tellus, et pellentesque lorem. Nunc sed lacinia augue. Aenean facilisis ligula id odio blandit et interdum dui accumsan.\r\n\r\nSed posuere, mauris nec pharetra pulvinar, nunc purus venenatis sapien, bibendum tempor sem nibh vitae tortor. Curabitur sodales lectus id ipsum pulvinar a sollicitudin tortor consectetur. Donec feugiat tempor posuere. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed adipiscing fermentum justo, id vehicula est tempor nec. Morbi aliquet semper eros, et tincidunt tortor sagittis quis. Nulla nulla enim, convallis in semper sed, tempus sed ante. Fusce viverra tempor purus, vitae laoreet elit tristique eget. Donec laoreet porttitor turpis nec vestibulum. Donec gravida pellentesque commodo. Morbi sodales orci sed leo dictum vehicula. Suspendisse potenti.','85.145.28.103'),(2,1,2,NULL,'2010-05-13 22:20:09','Nunc imperdiet turpis nec nisi tristique sagittis. Maecenas blandit eleifend dolor vitae vestibulum. Maecenas consectetur eleifend pellentesque. Donec volutpat lectus id purus vehicula sed aliquet sapien ornare. Vivamus elementum ipsum a sem adipiscing mollis. In vehicula fermentum ipsum ac rutrum. Nulla rhoncus tempus nunc, at ultricies tellus convallis ut. Vestibulum luctus nibh id elit suscipit ornare. Donec facilisis molestie ligula, id rhoncus nunc aliquet at. Vestibulum massa lectus, iaculis ut commodo ut, semper sed felis. Sed sed magna dui, vel varius velit. In nec lacus quis est rutrum congue vel auctor neque. Vivamus lectus tellus, consequat vel ornare nec, fermentum consectetur nibh. Maecenas commodo dui et diam vehicula vel mattis odio sodales. Nam risus erat, malesuada eu hendrerit et, tristique nec turpis. Nam id augue in ligula imperdiet adipiscing. Integer interdum convallis aliquet. Cras tincidunt lorem vitae risus blandit adipiscing porttitor sapien venenatis. Suspendisse sit amet lorem nunc.','85.145.28.103'),(3,2,2,NULL,'2010-05-13 22:20:37','Vivamus in est nec tortor sollicitudin fringilla. Sed in viverra justo. Quisque metus nunc, ornare sit amet ornare sed, auctor eget dolor. Nam ut dolor ipsum. In dignissim vestibulum rutrum. Praesent sit amet elit sed mi faucibus blandit vel vitae massa. Vestibulum mauris arcu, scelerisque ut suscipit ac, placerat aliquet ligula. Morbi tincidunt semper molestie. Vivamus vel lectus at purus interdum fringilla a sed mauris. Praesent vel magna et mauris tempor adipiscing. Sed at tellus leo. Donec sed ultrices sapien. Sed commodo est sit amet ipsum elementum ac lobortis ante consequat. Aliquam vel erat id tellus hendrerit bibendum vitae vitae risus. Donec sagittis nunc ut libero egestas ultrices. Sed felis nunc, pellentesque nec hendrerit eu, varius ac leo. Donec sed venenatis urna. ','85.145.28.103'),(4,2,1,NULL,'2010-05-13 22:21:08','Duis a placerat dolor. Aenean fringilla, mi ut rhoncus venenatis, mauris neque euismod nibh, rhoncus interdum nunc erat vitae elit. Etiam ultrices tincidunt erat, non volutpat sem placerat a. Morbi sollicitudin cursus risus quis vestibulum. In hac habitasse platea dictumst. Nunc non sagittis eros. Morbi a leo a nunc luctus fermentum in ac justo. Morbi dapibus rutrum dolor, quis tristique mauris sodales ullamcorper. Nullam ac dui eu velit volutpat mattis nec vel orci. Cras placerat nulla mollis est posuere porta. Mauris nisl dolor, egestas id auctor quis, eleifend nec mauris. Curabitur eu risus dui. Maecenas nulla eros, blandit id sodales a, porta vel sapien. Nullam at dui elit, auctor egestas diam. Proin molestie facilisis semper.\r\n\r\nNulla lacus enim, tristique vel vestibulum a, gravida at mauris. Duis ultrices ultricies libero et varius. Morbi auctor dolor congue nulla pellentesque non ornare nisi varius. Fusce non metus nec turpis luctus ornare. Pellentesque condimentum lectus vel ligula mollis fermentum. Nunc adipiscing neque augue. Suspendisse mollis sem sed metus vestibulum id placerat enim venenatis. Pellentesque rutrum arcu ut nisl malesuada a ornare justo lobortis. Curabitur dolor velit, aliquet fermentum aliquet eu, ultricies at elit. Curabitur dolor risus, sagittis a congue non, feugiat sit amet nisi. Curabitur fringilla sagittis interdum. Curabitur venenatis auctor ipsum quis dapibus. Praesent imperdiet gravida pulvinar.','85.145.28.103'),(5,3,2,NULL,'2010-05-13 22:21:39','Nulla sodales auctor neque, id malesuada risus hendrerit quis. Vestibulum pulvinar pretium ullamcorper. Etiam vestibulum semper commodo. Duis auctor erat non turpis luctus fermentum. Proin eu dui eget mauris ultricies viverra nec quis magna. Morbi consequat porta leo sed faucibus. Donec sagittis faucibus augue, condimentum lacinia tortor blandit vel. Vivamus sapien nulla, porttitor in commodo vel, sodales vel quam. Quisque a quam ipsum. Maecenas aliquam imperdiet mi, id fringilla nulla luctus varius. Curabitur posuere, felis id mattis tristique, odio nunc blandit neque, at lobortis nulla purus at nunc.\r\n\r\nNam mi ante, ullamcorper eu tincidunt ullamcorper, auctor quis leo. Suspendisse leo velit, placerat eu vestibulum vitae, gravida sit amet arcu. Sed hendrerit, metus eu pharetra pretium, mi nisi luctus erat, et vulputate mi ligula et nulla. Sed accumsan consectetur euismod. Aenean sit amet sem id augue accumsan feugiat. Maecenas non ante ac lacus hendrerit ullamcorper eu auctor tortor. Mauris sed tellus arcu. Suspendisse aliquam mollis nulla, eget iaculis ligula tempor sit amet. Nullam fermentum mollis sapien, quis elementum lacus lacinia a. Sed viverra sollicitudin lectus, a eleifend orci pharetra quis. Pellentesque nunc nunc, eleifend in consequat at, egestas in tellus. Cras a adipiscing leo. Ut non tortor lorem, sed vehicula urna. Donec pellentesque tempus suscipit.','85.145.28.103'),(6,3,1,NULL,'2010-05-13 22:22:01','Sed posuere, dolor eget ultrices lobortis, quam ipsum imperdiet sem, sed malesuada lectus nibh eget nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras sodales luctus nunc mollis faucibus. Curabitur feugiat dapibus augue, vitae ullamcorper quam luctus nec. Nulla tristique posuere turpis vitae dignissim. Aliquam erat volutpat. Sed quam mi, vulputate adipiscing ultricies eu, consequat nec tortor. Proin a lectus ut dui vulputate mollis. Morbi eu nisl sed tellus consequat suscipit at id neque. Vestibulum id aliquam nulla. Cras quis dapibus velit. Morbi nulla augue, placerat a malesuada quis, cursus sed ante. Mauris vel felis quam, in volutpat velit. Cras sit amet tortor at augue aliquet viverra id at neque. Quisque rhoncus blandit mollis. Duis nec arcu urna. Integer sed laoreet mauris.\r\n\r\nDuis a placerat dolor. Aenean fringilla, mi ut rhoncus venenatis, mauris neque euismod nibh, rhoncus interdum nunc erat vitae elit. Etiam ultrices tincidunt erat, non volutpat sem placerat a. Morbi sollicitudin cursus risus quis vestibulum. In hac habitasse platea dictumst. Nunc non sagittis eros. Morbi a leo a nunc luctus fermentum in ac justo. Morbi dapibus rutrum dolor, quis tristique mauris sodales ullamcorper. Nullam ac dui eu velit volutpat mattis nec vel orci. Cras placerat nulla mollis est posuere porta. Mauris nisl dolor, egestas id auctor quis, eleifend nec mauris. Curabitur eu risus dui. Maecenas nulla eros, blandit id sodales a, porta vel sapien. Nullam at dui elit, auctor egestas diam. Proin molestie facilisis semper.\r\n\r\nNulla lacus enim, tristique vel vestibulum a, gravida at mauris. Duis ultrices ultricies libero et varius. Morbi auctor dolor congue nulla pellentesque non ornare nisi varius. Fusce non metus nec turpis luctus ornare. Pellentesque condimentum lectus vel ligula mollis fermentum. Nunc adipiscing neque augue. Suspendisse mollis sem sed metus vestibulum id placerat enim venenatis. Pellentesque rutrum arcu ut nisl malesuada a ornare justo lobortis. Curabitur dolor velit, aliquet fermentum aliquet eu, ultricies at elit. Curabitur dolor risus, sagittis a congue non, feugiat sit amet nisi. Curabitur fringilla sagittis interdum. Curabitur venenatis auctor ipsum quis dapibus. Praesent imperdiet gravida pulvinar.','85.145.28.103'),(7,3,NULL,'Test user','2011-02-06 10:57:00','Test message','94.209.60.210');
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
INSERT INTO `forum_topics` VALUES (1,1,'Lorem ipsum dolor sit amet.'),(2,1,'Vivamus in est nec tortor sollicitudin fringilla.'),(3,2,'Nulla sodales auctor neque.');
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
INSERT INTO `forums` VALUES (1,'Lorum ipsum','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer a purus velit, et porttitor diam.',1),(2,'Sed posuere','Sed posuere, mauris nec pharetra pulvinar, nunc purus venenatis sapien, bibendum tempor sem.',2);
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
-- Dumping data for table `guestbook`
--

LOCK TABLES `guestbook` WRITE;
/*!40000 ALTER TABLE `guestbook` DISABLE KEYS */;
INSERT INTO `guestbook` VALUES (1,'Lorem ipsum','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat iaculis neque sed pellentesque. Sed eleifend tortor mauris. Etiam sem risus, ultrices id tempor sed, pharetra non lorem. Duis accumsan justo risus. In hac habitasse platea dictumst. Vestibulum scelerisque, justo et varius pellentesque, neque diam ullamcorper arcu, id interdum urna tellus laoreet metus. Integer bibendum arcu id libero sagittis eget mollis nisl viverra. Cras auctor fermentum dui in laoreet. Nullam non massa a eros commodo tristique ut a turpis. Maecenas ante lectus, viverra quis adipiscing sit amet, vulputate vel orci. Pellentesque a augue est, quis tristique felis. Praesent lobortis lorem ut lectus convallis non dictum ante pulvinar. Integer non lacus ac mi blandit rutrum a id erat. Vivamus egestas ante et quam sollicitudin cursus. Pellentesque elementum lectus et nunc commodo ut condimentum eros imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.','2010-05-13 22:24:31','85.145.28.103'),(2,'Morbi ipsum','Morbi eu ipsum vel diam consectetur sagittis. Aenean mattis lorem quis eros volutpat aliquam. Sed porta, metus eget tristique mattis, nulla orci vulputate eros, quis lobortis enim tellus sit amet ante. Morbi venenatis porttitor nibh, congue consequat nunc rhoncus in. Ut eget leo at mauris laoreet convallis in commodo mi. Morbi egestas augue vitae elit gravida volutpat. Donec at nunc eget orci condimentum ullamcorper mollis id urna. Nulla facilisi. Mauris ut est at elit ultricies faucibus. Ut sapien est, tempor vitae luctus eget, ornare eu lacus. Etiam ornare bibendum sollicitudin. Praesent a augue lectus, quis auctor lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.','2010-05-13 22:24:45','85.145.28.103'),(3,'Nunc vitae','Nunc vitae dolor lectus, sed feugiat massa. Mauris lacus dui, semper id sollicitudin tempor, fermentum sed mauris. Donec lobortis purus id elit rutrum convallis. Nulla iaculis, ante id laoreet mattis, justo dolor gravida sapien, sed posuere justo turpis vel velit. Aliquam aliquam est sit amet dui feugiat a suscipit libero consequat. Pellentesque a arcu nibh. Phasellus tellus magna, faucibus ut viverra vel, eleifend vitae diam. Fusce vulputate felis id est tincidunt sodales. Cras iaculis bibendum felis id vestibulum. Vivamus turpis mauris, fringilla a suscipit semper, sollicitudin eu tellus. Integer vel sodales velit. Sed in lectus nisl. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent orci elit, ullamcorper vel commodo in, tincidunt nec lacus. Cras bibendum sollicitudin est, at pellentesque nisl tincidunt a. Suspendisse at accumsan nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aenean id tellus magna. Mauris mattis, nulla in feugiat consectetur, velit nibh posuere nibh, quis fermentum purus orci a orci. Nunc fermentum risus massa, at rhoncus ligula.','2010-05-13 22:25:00','85.145.28.103');
/*!40000 ALTER TABLE `guestbook` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
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
INSERT INTO `menu` VALUES (1,0,1,'Homepage','/homepage'),(2,0,3,'Demos','/demos'),(4,0,4,'CMS','/admin'),(10,0,2,'Modules','/modules'),(11,10,1,'Test1','/test1'),(12,10,2,'Test2','/test2'),(13,10,3,'Test3','/test3');
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
INSERT INTO `news` VALUES (1,'Lorum ipsum','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tincidunt, lectus ut tempus semper, tellus elit ullamcorper leo, sed tincidunt mi leo non augue. Vivamus imperdiet turpis ut leo. Aliquam erat volutpat. Sed auctor magna rutrum mi aliquet feugiat. Curabitur id mauris. Sed ipsum risus, mattis in, lacinia nec, aliquam non, dolor. Sed lorem magna, elementum eu, rutrum sed, varius quis, lectus. Vestibulum metus. Praesent scelerisque orci porttitor sapien. Aenean dignissim. Maecenas semper urna eu turpis pulvinar sagittis.</p>\r\n\r\n<p>Ut consectetur sodales odio. Vestibulum odio massa, ultrices sed, vestibulum eget, suscipit vel, lectus. Morbi semper. Nulla ligula augue, auctor sed, tincidunt nec, condimentum non, erat. Morbi pharetra, metus in congue pellentesque, dolor tortor consequat ipsum, eu aliquet nibh pede nec diam. Duis suscipit euismod eros. Vivamus imperdiet tellus ut metus. Cras augue nulla, laoreet at, ornare id, convallis et, tortor. Ut tincidunt lacus id justo. Ut pulvinar. Suspendisse potenti. Nam non sem. Suspendisse est justo, euismod eu, aliquam suscipit, ornare id, urna. Phasellus sed est. Curabitur condimentum dui ut arcu congue commodo. Praesent tempus mollis erat. Nam quis diam sed nulla fringilla pretium. Donec vel nisi. Cras at mi nec nulla fringilla consectetur.</p>\r\n\r\n<p>Cras diam. Maecenas elit. Vivamus adipiscing semper turpis. Sed sit amet dolor. Vestibulum malesuada dolor id lorem. Integer dolor felis, gravida a, egestas in, adipiscing ut, quam. Morbi cursus facilisis libero. Praesent eros lorem, feugiat in, imperdiet id, rutrum sit amet, magna. Integer est. Morbi suscipit ligula laoreet eros venenatis ullamcorper. Nunc viverra. Morbi volutpat vulputate dolor. Morbi lacinia dui molestie ante. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>\r\n\r\n<p>Suspendisse sapien mi, consequat nec, tincidunt eu, adipiscing at, lacus. Nam quis dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusce sem urna, eleifend eget, pulvinar eget, tincidunt sed, erat. Maecenas lectus. Nullam ut purus. Nulla facilisi. Cras scelerisque. Mauris justo turpis, cursus eu, dignissim et, accumsan in, justo. Proin gravida elementum dui.</p>\r\n\r\n<p>Integer bibendum, nisi in pharetra fringilla, massa dolor pulvinar nunc, vitae laoreet metus quam id sem. Donec tincidunt. In bibendum. Pellentesque lacinia lacus sed sapien. Praesent eros nibh, auctor id, iaculis vitae, auctor quis, risus. Morbi euismod porttitor odio. Ut in mauris nec enim viverra euismod. Phasellus vulputate consectetur mi. Vivamus tincidunt elit vitae tortor. Mauris iaculis, nibh non tempor iaculis, metus augue feugiat lacus, sit amet sollicitudin eros purus vel neque.</p>','2008-11-22 23:27:33'),(2,'Nam quis ligula sit amet','<p>Nam quis ligula sit amet velit rutrum pulvinar. Sed magna lorem, vulputate sit amet, tincidunt vitae, dignissim eget, dolor. Fusce purus felis, porta et, pretium et, mattis ut, mi. Sed volutpat elit at sem. In vel neque. Aenean eget dolor. Aenean rutrum, ante nec bibendum congue, dolor magna iaculis nunc, hendrerit semper lorem nunc ut tellus. Phasellus posuere. Phasellus et lacus at leo pharetra faucibus. Donec eu nibh. Nunc pretium odio. Duis vel pede ut ipsum hendrerit ullamcorper. Proin molestie, diam sit amet mollis ultrices, est nibh iaculis orci, eget lacinia est neque sit amet nisl. Cras mattis. Aliquam quis purus ultrices purus iaculis varius.</p>\r\n\r\n<p>Donec ut nibh. Aenean viverra, velit in blandit facilisis, elit libero mattis nibh, sed tristique risus velit sit amet risus. Mauris justo nibh, egestas at, viverra eu, faucibus ac, nunc. Aenean consectetur elementum diam. Aliquam pulvinar tortor et quam. Suspendisse mattis gravida nibh. Cras sodales enim ut pede bibendum molestie. Praesent tincidunt sapien mollis velit rhoncus pellentesque. Pellentesque ac libero. Sed vel sem. In tempor rhoncus libero.</p>\r\n\r\n<p>Ut iaculis vestibulum sem. Pellentesque egestas dui non nisl. Integer eleifend. Maecenas sodales, dui a dictum vehicula, ipsum dui lacinia tortor, in semper orci pede ac purus. Proin ut risus. Nulla lobortis felis a felis. Ut ultricies, urna at auctor varius, leo nulla imperdiet velit, ut condimentum dui nulla non dolor. Proin ut est. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec accumsan nisl in pede.</p>','2008-12-13 14:25:48');
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
INSERT INTO `pages` VALUES (1,'/homepage','en','Default layout',0,'img.logo {\r\n  float:right;\r\n  margin-left:20px;\r\n}','Welcome to Banshee, the secure PHP framework','','','<p>Banshee is a PHP website framework, which aims at to be secure, fast and easy to use. It uses the Model-View-Control architecture with XSLT for the View. Although it was designed to use MySQL as the database, other database applications can be used as well with only little effort. For more information about Banshee, visit the <a href=\"http://www.banshee-php.org/\">Banshee website</a>.</p>\r\n\r\n<img src=\"http://www.banshee-php.org/logo.php\" class=\"logo\" alt=\"Banshee logo\">\r\n\r\n<p>In this default installation, there are two users available: \'admin\' and \'user\'. Both have the password \'banshee\'.</p>\r\n\r\n<p>If security is a high priority for your website, you should take a look at the <a href=\"http://www.hiawatha-webserver.org\">Hiawatha webserver</a>.</p>',1,0),(2,'/modules','en','Default layout',0,'','Banshee modules','Modules in Banshee','modules','<ul>\r\n<li><a href=\"/agenda\">Agenda</a></li>\r\n<li><a href=\"/contact\">Contact form</a></li>\r\n<li><a href=\"/dictionary\">Dictionary</a></li>\r\n<li><a href=\"/faq\">F.A.Q.</a></li>\r\n<li><a href=\"/forum\">Forum</a></li>\r\n<li><a href=\"/guestbook\">Guestbook</a></li>\r\n<li><a href=\"/links\">Links</a></li>\r\n<li><a href=\"/mailbox\">Mailbox</a></li>\r\n<li><a href=\"/news\">News</a></li>\r\n<li><a href=\"/newsletter\">Newsletter</a></li>\r\n<li><a href=\"/photo\">Photo album</a></li>\r\n<li><a href=\"/collection\">Photo album collections</a></li>\r\n<li><a href=\"/poll\">Poll</a></li>\r\n<li><a href=\"/profile\">Profile manager</a></li>\r\n<li><a href=\"/search\">Search</a></li>\r\n<li><a href=\"/session\">Session manager</a></li>\r\n<li><a href=\"/weblog\">Weblog</a></li>\r\n</ul>',1,0),(3,'/demos','en','Default layout',0,'','Banshee functionality demos','Banshee demos','banshee, demos','<ul>\r\n<li>Support for <a href=\"/demos/ajax\">AJAX</a>.</li>\r\n<li>A <a href=\"/demos/calendar\">calendar</a> webform object.</li>\r\n<li>The <a href=\"/demos/captcha\">captcha</a> library.</li>\r\n<li>This page shows an <a href=\"/demos/errors\">error message</a>.</li>\r\n<li>An <a href=\"/invisible\">invisible</a> page, a <a href=\"/private\">private</a> page and a <a href=\"/void\">non-existing</a> page.</li>\r\n<li>The WYSIWYG <a href=\"/demos/ckeditor\">CKEditor</a>.</li>\r\n<li><a href=\"/demos/googlemaps\">GoogleMaps static map</a> demo.</a></li>\r\n<li><a href=\"/demos/openstreetmap\">OpenStreetMap static map</a> demo.</a></li>\r\n<li>Browse the available and ready-to-use <a href=\"/demos/layout\">layouts</a>.</li>\r\n<li>A <a href=\"/demos/pagination\">pagination</a> library.</li>\r\n<li>An <a href=\"/demos/alphabetize\">alphabetize</a> library.</li>\r\n<li>The <a href=\"/demos/pdf\">FPDF</a> library.</li>\r\n<li>A <a href=\"/demos/poll\">poll</a> module.</li>\r\n<li>The <a href=\"/demos/posting\">posting</a> library.</li>\r\n<li>The <a href=\"/demos/tablemanager\">tablemanager</a> library.</li>\r\n<li>The <a href=\"/demos/splitform\">splitform</a> library.</li>\r\n<li>Page with <a href=\"/demos/7/parameter\">parameter</a> inside URL.</li>\r\n<li><a href=\"/demos/utf8\">UTF-8</a> character encoding.</li>\r\n<li>A library for <a href=\"/demos/banshee_website\">remote connection</a> to another Banshee based website.</li>\r\n<li>A library for <a href=\"/demos/validation\">input validation</a>.</li>\r\n<li><a href=\"/demos/system_message\">System message</a> functionality.</li>\r\n<li><a href=\"/demos/readonly\">Read-only</a> access rights.</li>\r\n</ul>\r\n',1,0),(4,'/private','en','',1,NULL,'Private page','','','<p>This is a private page.</p>\r\n\r\n<input type=\"button\" value=\"Back\" class=\"button\" onClick=\"javascript:document.location=\'/demos\'\" />',1,0),(5,'/invisible','en','',0,NULL,'Invisible page','','','<p>This page is invisible to normal users and visitors. Only users with access to the page administration page can view this page.</p>\r\n<p>Page administrators can use this feature to verify a page before making it available to visitors.</p>\r\n\r\n<input type=\"button\" value=\"Back\" class=\"button\" onClick=\"javascript:document.location=\'/demos\'\" />',0,0),(6,'/demos/utf8','en','Default layout',0,'','UTF-8 demo','','','<p>這是一個測試頁，以顯示漢字。</p>',1,1);
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
INSERT INTO `photo_albums` VALUES (1,'Hiawatha webserver','Images about the Hiawatha webserver.','2010-08-21 18:56:40'),(2,'Banshee PHP framework','Images about the Banshee PHP framework.','2011-08-13 07:12:56');
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
INSERT INTO `photos` VALUES (1,'Hiawatha webserver wallpaper',1,'png',1),(2,'Horizontal banner',1,'png',0),(3,'Vertical banner',1,'png',0),(4,'Button',1,'png',0),(5,'Banshee logo',2,'png',1);
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
INSERT INTO `poll_answers` VALUES (4,1,'Windows XP',1),(5,1,'Ubuntu Linux',4),(6,1,'MacOS X',2),(7,2,'Chicken',1),(8,2,'Egg',1),(9,2,'MS-DOS',2),(13,4,'One',0),(14,4,'Two',0),(15,4,'Three',0),(16,3,'1',0),(17,3,'2',0),(18,3,'10',2),(19,3,'11',0);
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
INSERT INTO `polls` VALUES (1,'What OS do you use?','2009-04-01','2010-04-30'),(2,'Which came first?','2009-05-01','2020-12-31'),(3,'How much is \"1 and 1\"','2010-01-01','2010-12-31'),(4,'Test','2009-07-01','2009-07-31');
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
  `admin` tinyint(4) DEFAULT '0',
  `admin/file` tinyint(4) DEFAULT '0',
  `admin/menu` tinyint(4) DEFAULT '0',
  `admin/page` tinyint(4) DEFAULT '0',
  `admin/role` tinyint(4) DEFAULT '0',
  `admin/switch` tinyint(4) DEFAULT '0',
  `admin/user` tinyint(4) DEFAULT '0',
  `demos/tablemanager` tinyint(4) DEFAULT '0',
  `admin/access` tinyint(4) DEFAULT '0',
  `admin/action` tinyint(4) DEFAULT '0',
  `admin/languages` tinyint(4) DEFAULT '0',
  `admin/organisation` tinyint(4) DEFAULT '0',
  `admin/settings` tinyint(4) DEFAULT '0',
  `session` tinyint(4) DEFAULT '0',
  `system/sso` tinyint(4) DEFAULT '0',
  `admin/agenda` tinyint(4) DEFAULT '0',
  `admin/albums` tinyint(4) DEFAULT '0',
  `admin/collection` tinyint(4) DEFAULT '0',
  `admin/dictionary` tinyint(4) DEFAULT '0',
  `admin/faq` tinyint(4) DEFAULT '0',
  `admin/forum` tinyint(4) DEFAULT '0',
  `admin/guestbook` tinyint(4) DEFAULT '0',
  `admin/links` tinyint(4) DEFAULT '0',
  `admin/newsletter` tinyint(4) DEFAULT '0',
  `admin/photos` tinyint(4) DEFAULT '0',
  `admin/poll` tinyint(4) DEFAULT '0',
  `admin/subscriptions` tinyint(4) DEFAULT '0',
  `admin/news` tinyint(4) DEFAULT '0',
  `admin/weblog` tinyint(4) DEFAULT '0',
  `mailbox` tinyint(4) DEFAULT '0',
  `admin/logging` tinyint(4) DEFAULT '0',
  `demos/readonly` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(2,'User',1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,2);
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
INSERT INTO `settings` VALUES (1,'admin_page_size','integer','25'),(31,'photo_page_size','integer','10'),(5,'default_language','string','en'),(7,'page_after_login','string','admin'),(32,'photo_thumbnail_height','integer','100'),(9,'start_page','string','homepage'),(10,'webmaster_email','string','info@banshee-php.org'),(30,'forum_page_size','string','25'),(12,'forum_maintainers','string','Moderator'),(13,'guestbook_page_size','integer','10'),(14,'guestbook_maintainers','string','Publisher'),(15,'news_page_size','integer','5'),(16,'news_rss_page_size','string','30'),(17,'newsletter_bcc_size','integer','100'),(18,'newsletter_code_timeout','string','15 minutes'),(19,'newsletter_email','string','info@banshee-php.org'),(20,'newsletter_name','string','Hugo Leisink'),(36,'contact_email','string','info@banshee-php.org'),(22,'poll_max_answers','integer','10'),(23,'poll_bans','string',''),(24,'weblog_page_size','string','5'),(25,'weblog_rss_page_size','integer','30'),(26,'head_title','string','Banshee'),(27,'head_description','string','Secure PHP framework'),(28,'head_keywords','string','banshee, secure, php, framework'),(33,'photo_image_height','integer','450'),(35,'secret_website_code','string','CHANGE_ME_INTO_A_RANDOM_STRING'),(37,'photo_thumbnail_width','integer','100'),(38,'photo_image_width','integer','700'),(39,'hiawatha_cache_time','integer','300'),(40,'photo_album_size','integer','18');
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
INSERT INTO `user_role` VALUES (1,1),(2,2);
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
  `username` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `password` varchar(128) NOT NULL,
  `one_time_key` varchar(128) DEFAULT NULL,
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
INSERT INTO `users` VALUES (1,1,'admin','c10b391ff5e75af6ee8469539e6a5428f09eff7e693d6a8c4de0e5525cd9b287',NULL,2,'Administrator','admin@banshee-php.org'),(2,1,'user','b4f6b1c67ef4f9c3dc67aae05c5d09411fa927e360063f7fd983710dc882cb3c',NULL,2,'Normal user','user@banshee-php.org');
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
-- Dumping data for table `weblog_comments`
--

LOCK TABLES `weblog_comments` WRITE;
/*!40000 ALTER TABLE `weblog_comments` DISABLE KEYS */;
INSERT INTO `weblog_comments` VALUES (1,3,'Visitor','Interesting weblog article.','2010-05-06 07:30:12','62.177.172.222');
/*!40000 ALTER TABLE `weblog_comments` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `weblog_tagged` VALUES (1,1),(2,1),(1,2),(2,2),(3,2);
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
INSERT INTO `weblog_tags` VALUES (1,'lorem'),(2,'test');
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
INSERT INTO `weblogs` VALUES (1,1,'Lorum ipsum','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec diam ipsum, fringilla nec aliquam quis, tincidunt sit amet sapien. In ut malesuada orci. Suspendisse pharetra, lacus eget consectetur feugiat, libero augue dapibus justo, ut tempor odio arcu sit amet odio. Donec eget elit sit amet nisl accumsan suscipit sed vel velit. Donec dictum nisi vel mi elementum mattis. Fusce vestibulum placerat dignissim. Morbi vel quam id dui tempor posuere ac vitae risus. Sed id lectus eros, non euismod nisi. In a tortor lorem, vitae suscipit lorem. Nullam dolor felis, elementum adipiscing pretium eu, dapibus at nibh. Nullam mollis ornare massa lobortis consequat. Donec mollis faucibus massa vitae iaculis. Curabitur imperdiet, dui nec convallis consectetur, mi nulla aliquam massa, ut tempus arcu lorem a quam.</p>\r\n\r\n<p>Proin quis iaculis neque. Mauris et pellentesque ante. Mauris posuere iaculis enim sit amet congue. Duis vitae felis et velit vulputate elementum. Aliquam vel elementum tellus. Cras sed pellentesque eros. Nunc scelerisque accumsan nunc, vulputate mollis sem fermentum sed. Proin facilisis magna facilisis augue bibendum mollis. Nullam vitae tortor ac est sagittis sollicitudin volutpat eget nibh. Morbi gravida faucibus consequat. Sed lorem nisi, pharetra sit amet scelerisque at, tempus at libero. Cras volutpat nunc non quam lacinia vulputate. Aenean non nisi diam. Suspendisse accumsan mi a nisi accumsan a consectetur nulla iaculis.</p>\r\n\r\n<p>Suspendisse potenti. Integer rutrum fermentum dignissim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras sodales ultricies pretium. Sed massa dolor, laoreet at tincidunt eget, dictum a neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean quis egestas lacus. Maecenas feugiat posuere lectus eu tristique. Nulla sem nisi, aliquet eu aliquam sit amet, fringilla non tortor. Nam mattis est non magna ultricies porta. Phasellus iaculis vulputate diam, vel elementum libero imperdiet eu. Sed euismod augue ut nulla egestas rutrum. Vivamus lacus justo, iaculis et lobortis sed, volutpat sed nunc. Sed enim ante, ullamcorper vel fermentum vitae, placerat eu est. Vivamus eu mauris condimentum urna pretium ullamcorper. Duis nunc mauris, ultrices eu fermentum a, pellentesque et sem.</p>','2009-04-20 10:00:00',1),(2,2,'Suspendisse potenti','<p>Suspendisse potenti. Integer rutrum fermentum dignissim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras sodales ultricies pretium. Sed massa dolor, laoreet at tincidunt eget, dictum a neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean quis egestas lacus. Maecenas feugiat posuere lectus eu tristique. Nulla sem nisi, aliquet eu aliquam sit amet, fringilla non tortor. Nam mattis est non magna ultricies porta. Phasellus iaculis vulputate diam, vel elementum libero imperdiet eu. Sed euismod augue ut nulla egestas rutrum. Vivamus lacus justo, iaculis et lobortis sed, volutpat sed nunc. Sed enim ante, ullamcorper vel fermentum vitae, placerat eu est. Vivamus eu mauris condimentum urna pretium ullamcorper. Duis nunc mauris, ultrices eu fermentum a, pellentesque et sem.</p>\r\n\r\n<p>Ut quis pharetra sem. Aliquam urna augue, ultricies non volutpat sit amet, porta et arcu. Ut dictum erat et justo venenatis a feugiat erat accumsan. Donec dapibus commodo leo, in adipiscing felis ultrices quis. Quisque ac malesuada nulla. Nunc lobortis, mauris id vulputate cursus, nibh urna euismod mauris, ut bibendum erat dolor id massa. Pellentesque risus erat, dictum euismod aliquet ut, adipiscing non nibh. In bibendum urna eget quam accumsan facilisis. Etiam pharetra, purus ac malesuada congue, felis orci ultrices lectus, ultrices pretium nisi mi vel quam. Curabitur vitae neque malesuada nisl luctus eleifend. Phasellus et leo urna, sed vehicula nisi. Donec blandit nibh et felis tempor volutpat. Aliquam mauris augue, tempor varius sodales sed, porttitor ut neque. Morbi gravida tortor a turpis pellentesque aliquet. Aenean sed iaculis ante. Quisque non enim lectus, sit amet commodo augue. Curabitur fermentum cursus turpis sit amet convallis. Aenean eu sapien eu ipsum faucibus suscipit.</p>\r\n\r\n<p>Curabitur lacinia feugiat adipiscing. Donec porta lacus id metus fringilla nec imperdiet felis congue. Nulla facilisi. Etiam commodo imperdiet metus, quis placerat ligula vehicula sed. Morbi egestas convallis luctus. Nullam ut ligula quis metus sodales porttitor. Donec eget mauris erat. Donec in convallis urna. Integer volutpat, libero ut tincidunt aliquet, erat massa condimentum augue, a eleifend nisl mi vitae enim. Proin fringilla tincidunt metus, varius fringilla urna pulvinar eget.</p>','2009-05-10 18:00:00',1),(3,1,'Ut eget sem justo','<p>Ut eget sem justo. Fusce at mi quis odio molestie interdum. Nunc libero urna, varius nec suscipit id, commodo a sapien. Sed eros magna, interdum et varius a, egestas a lorem. Cras quis neque non mauris pretium rhoncus. Praesent sit amet turpis mauris. Aliquam venenatis pulvinar cursus. Proin ac ante a diam porta sollicitudin. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur erat nibh, ornare pharetra pretium vel, interdum eu tortor. Nullam laoreet tempus sem. In facilisis scelerisque auctor. Proin lobortis pretium elementum. Vivamus rhoncus interdum orci sagittis vehicula. Pellentesque pharetra rhoncus viverra. Duis quis mi eros. Nullam a est ipsum. Aliquam erat volutpat. Pellentesque tempus, ligula id tempor commodo, tellus nunc tempor eros, vitae cursus metus mauris in ligula. Quisque enim leo, dapibus sit amet posuere volutpat, bibendum ut massa.</p>\r\n\r\n<p>Vestibulum vehicula mi vel felis consequat placerat. Duis sed diam risus. Integer imperdiet magna vitae erat sodales sed tristique quam cursus. Etiam id rhoncus justo. Duis consequat libero ac massa ornare dictum pulvinar sem cursus. Nunc id quam a magna viverra tempor. Nulla facilisi. Proin facilisis luctus bibendum. Sed mauris turpis, suscipit sit amet rhoncus ac, varius dignissim neque. Donec consectetur tortor eget elit pulvinar in pretium purus euismod. Nunc quis mi nec lectus malesuada lacinia. Pellentesque vitae enim massa.</p>\r\n\r\n<p>Nullam porta ullamcorper dolor varius tincidunt. Pellentesque sed lectus purus. Integer sollicitudin leo et leo laoreet pulvinar. Duis eget elit id dui volutpat tincidunt in nec magna. Vivamus tempor vehicula leo at convallis. Donec at metus a ante mollis vestibulum ut eu turpis. Suspendisse interdum consequat iaculis. Vestibulum porttitor felis eu ligula gravida suscipit. Vivamus sed nisi ut metus dignissim vehicula at a elit. Proin nec ipsum velit, tempor placerat est. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent nisi nulla, ullamcorper eget venenatis sit amet, suscipit a metus. Integer nec porta nibh. Donec egestas porttitor malesuada. Mauris nisl justo, aliquam at aliquet et, mollis nec ligula. Integer ultricies pretium orci, ac aliquet felis rhoncus sed. Pellentesque ac faucibus erat. Maecenas vel mi in turpis ultricies faucibus. Suspendisse quam mauris, molestie nec fermentum ut, pulvinar tempus diam. Praesent ligula mauris, ullamcorper eget luctus et, tempus eget leo.</p>','2009-05-15 13:00:00',1);
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

-- Dump completed on 2012-09-05 18:10:21
