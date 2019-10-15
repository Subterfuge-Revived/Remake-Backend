-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: sandbox
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Table structure for table `ongoing_rooms`
--

DROP TABLE IF EXISTS `ongoing_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ongoing_rooms` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `rated` tinyint(1) NOT NULL,
  `player_count` int(11) NOT NULL,
  `min_rating` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `goal` int(11) NOT NULL,
  `anonymity` tinyint(1) NOT NULL,
  `map` int(11) NOT NULL,
  `seed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ongoing_rooms`
--

LOCK TABLES `ongoing_rooms` WRITE;
/*!40000 ALTER TABLE `ongoing_rooms` DISABLE KEYS */;
INSERT INTO `ongoing_rooms` VALUES (1,1,1,2,880,'Backend Testing',1,0,1,1571079360),(2,1,1,2,1190,'New Room 2',1,0,1,1571134878),(3,4,1,2,1195,'Room',1,0,2,1571135043),(4,1,1,2,1200,'New Room 4',1,0,2,1571135171),(5,4,1,2,800,'Test Room 1',1,0,2,1571154097),(6,1,0,2,0,'fff',1,0,1,1571154337),(7,1,0,2,0,'fff2',1,0,1,1571154428),(8,1,0,2,0,'fff22',1,0,1,1571154455),(9,1,0,2,0,'fff22',1,0,1,1571154780),(10,1,0,2,0,'fff22',1,0,1,1571155163),(11,1,0,2,0,'fff22',1,0,1,1571155331),(12,1,0,2,0,'fff22',1,0,1,1571155373),(13,1,0,2,0,'fff22',1,0,1,1571156330),(14,1,0,2,0,'fff22',1,0,1,1571156613);
/*!40000 ALTER TABLE `ongoing_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `open_rooms`
--

DROP TABLE IF EXISTS `open_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `open_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `rated` tinyint(1) NOT NULL,
  `max_players` int(11) NOT NULL,
  `player_count` int(11) NOT NULL,
  `min_rating` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `goal` int(11) NOT NULL COMMENT '0 = Mine 200 Neptunium\n1 = Control 40 Outposts',
  `anonymity` tinyint(1) NOT NULL,
  `map` int(11) NOT NULL COMMENT '0 = random\n1 = custom generator heavy\n2 = custom balanced\n3 = custom factory heavy',
  `seed` int(11) NOT NULL DEFAULT '0' COMMENT 'seed based on ''map''',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COMMENT='List of open game rooms';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `open_rooms`
--

LOCK TABLES `open_rooms` WRITE;
/*!40000 ALTER TABLE `open_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `open_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_administrative_info`
--

DROP TABLE IF EXISTS `player_administrative_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_administrative_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(15) NOT NULL,
  `password` varchar(64) NOT NULL,
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_administrative_info_mail_uindex` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Stores necessary information for authentication';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_administrative_info`
--

LOCK TABLES `player_administrative_info` WRITE;
/*!40000 ALTER TABLE `player_administrative_info` DISABLE KEYS */;
INSERT INTO `player_administrative_info` VALUES (1,'XATEV','$2y$10$J7RYnd.E1Dp3CUUsPifFYe7RAEWrzA1rD1YX9kj85vJFltwdOiKCu','xatev@gmail.com'),(2,'Player','$2y$10$apSZLmaHykzOdTlvczC39eKGpR.tw2/5h.ZZnuBsyQ7ZnmB7.pjRS','player@gmail.com'),(3,'User','$2y$10$yw2zlXZ/.wqhYeqGo9t/WeQB3F.Zm4/zDVRhotYm0woTE0izRK7gm','user@gmail.com'),(4,'Test','$2y$10$gAPTQXr.bwRRgjXoJQM7kOa3hAAAfQngaDVGWcOTJqvfBdgl.uILK','test@gmail.com');
/*!40000 ALTER TABLE `player_administrative_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_open_room`
--

DROP TABLE IF EXISTS `player_open_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_open_room` (
  `player_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  UNIQUE KEY `player_open_room_pk` (`player_id`,`room_id`),
  KEY `player_open_room_open_rooms_id_fk` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Assign player to a 1..* rooms';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_open_room`
--

LOCK TABLES `player_open_room` WRITE;
/*!40000 ALTER TABLE `player_open_room` DISABLE KEYS */;
INSERT INTO `player_open_room` VALUES (1,1),(4,1),(1,2),(4,2),(1,3),(4,3),(1,4),(4,4),(1,5),(4,5),(1,6),(4,6),(1,7),(4,7),(1,8),(4,8),(1,9),(4,9),(1,10),(4,10),(1,11),(4,11),(1,12),(4,12),(1,13),(4,13),(1,14),(4,14);
/*!40000 ALTER TABLE `player_open_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_session`
--

DROP TABLE IF EXISTS `player_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_session` (
  `player_id` int(11) NOT NULL,
  `session_id` varchar(400) DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  PRIMARY KEY (`player_id`),
  UNIQUE KEY `player_session_id_uindex` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_session`
--

LOCK TABLES `player_session` WRITE;
/*!40000 ALTER TABLE `player_session` DISABLE KEYS */;
INSERT INTO `player_session` VALUES (1,'50b1dcb4084596825872db1e8f6ad356ffd3b22ad24b94574061ccbb6041bf7cd7df4797c4443c36','2019-10-15 18:48:42'),(2,'812c703a9a636f7ddd834d8120e41057337e66b0651d944ab1dcc37e1a4ce1b07f7cce7886d0bdd4','2019-10-14 21:23:32'),(3,'291e579a06e03f6160fcb22df32455b32be7e667fb2a2bbcc232eac4f3cad1a5c6a0cf67f60a4572','2019-10-14 21:23:43'),(4,'ba6e95fb1f284276ebcfa6557a6b1b929b6566c19104e43c6aa5e8396c87a666c03e0f73e7fb13f9','2019-10-15 18:49:02');
/*!40000 ALTER TABLE `player_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_statistics`
--

DROP TABLE IF EXISTS `player_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_statistics` (
  `player_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `games_played` int(11) NOT NULL DEFAULT '0',
  `wins` int(11) NOT NULL DEFAULT '0',
  `resigned` int(11) NOT NULL DEFAULT '0',
  `last_online` datetime NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_statistics`
--

LOCK TABLES `player_statistics` WRITE;
/*!40000 ALTER TABLE `player_statistics` DISABLE KEYS */;
INSERT INTO `player_statistics` VALUES (1,1200,0,0,0,'2019-10-14 20:53:10'),(2,1200,0,0,0,'2019-10-14 20:53:32'),(3,1200,0,0,0,'2019-10-14 20:53:43'),(4,1200,0,0,0,'2019-10-14 20:54:17');
/*!40000 ALTER TABLE `player_statistics` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-15 18:55:02
