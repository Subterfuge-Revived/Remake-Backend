-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: events_ongoing_rooms
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
-- Table structure for table `events_room_1`
--

DROP TABLE IF EXISTS `events_room_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_1` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `at_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_1`
--

LOCK TABLES `events_room_1` WRITE;
/*!40000 ALTER TABLE `events_room_1` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_11`
--

DROP TABLE IF EXISTS `events_room_11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_11` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_issued` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `occurs_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_11`
--

LOCK TABLES `events_room_11` WRITE;
/*!40000 ALTER TABLE `events_room_11` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_11` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_12`
--

DROP TABLE IF EXISTS `events_room_12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_12` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_issued` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `occurs_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_12`
--

LOCK TABLES `events_room_12` WRITE;
/*!40000 ALTER TABLE `events_room_12` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_12` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_13`
--

DROP TABLE IF EXISTS `events_room_13`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_13` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_issued` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `occurs_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event_msg` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_13`
--

LOCK TABLES `events_room_13` WRITE;
/*!40000 ALTER TABLE `events_room_13` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_13` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_14`
--

DROP TABLE IF EXISTS `events_room_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_14` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_issued` int(11) NOT NULL,
  `occurs_at` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `event_msg` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_14`
--

LOCK TABLES `events_room_14` WRITE;
/*!40000 ALTER TABLE `events_room_14` DISABLE KEYS */;
INSERT INTO `events_room_14` VALUES (1,1571156625,1571159999,4,'Event message something ok'),(2,1571157547,123872183,1,'EVENT 2 EVENT EVENT'),(3,1571157604,1238721345,1,'EVENT 2 EVENT EVENT');
/*!40000 ALTER TABLE `events_room_14` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_2`
--

DROP TABLE IF EXISTS `events_room_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_2` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `at_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_2`
--

LOCK TABLES `events_room_2` WRITE;
/*!40000 ALTER TABLE `events_room_2` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_room_4`
--

DROP TABLE IF EXISTS `events_room_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_room_4` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `at_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `player_id` int(11) NOT NULL,
  `event` varchar(200) NOT NULL,
  `occurred_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_room_4`
--

LOCK TABLES `events_room_4` WRITE;
/*!40000 ALTER TABLE `events_room_4` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_room_4` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-15 18:55:49
