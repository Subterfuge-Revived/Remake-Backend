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
-- Table structure for table `open_rooms`
--

DROP TABLE IF EXISTS `open_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `open_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_size` int(11) NOT NULL,
  `min_rating` int(11) NOT NULL,
  `mode` varchar(32) NOT NULL,
  `description` varchar(50) NOT NULL,
  `creator` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of open game rooms';
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COMMENT='Stores necessary information for authentication';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_administrative_info`
--

LOCK TABLES `player_administrative_info` WRITE;
/*!40000 ALTER TABLE `player_administrative_info` DISABLE KEYS */;
INSERT INTO `player_administrative_info` VALUES (1,'XATEV','$2y$10$xd2H6h2DtUV4ebLjlsmgj.6t8xhiRByCq3GrIsF2zsPqj/OBJ8BYC','bd6a91c6242e81c704e49e47ded28116@gmail.com'),(7,'XATEVThe3rd','$2y$10$H/QRM5UwcyH1z0tBMcCwFeGajqjE0rFkO79XbZyIUzOQXVdbk1kee','f4f697ce74d78885a7291e9fc9006fc7@gmail.com'),(8,'XATEV99','$2y$10$/9RsGHQXg6ZfUGFSqKwI3O1SkkcZ1OWavUQtZhS44Qfm3QAh7HW5.','aaabbbccc@gmail.com'),(9,'XATEV993','$2y$10$JdGrAqVWlkTYjn4CgfC2p.dOayu8bGuApU6bu6Q6.6eU2UG.SeEwq','aaabbbcecc@gmail.com'),(11,'XATEV4444','$2y$10$1ESoCFqiy8PTW.7x386MuuPBwId0QK5VNqnLt9NgQP7.JvEo4qrnm','aaabbbccdc@gmail.com'),(12,'XATEV444422','$2y$10$Nb/VSbeIsQH9B/RU1X9OOOZfCVQtxRbB8p8WngT5YixMnRtaZ0gKq','aaabbbc22cdc@gmail.com'),(13,'Aaaa','$2y$10$5XljcnnatYIRo6ORS7pZx.Kksw1Q1gYx4lUrx5kp9enENGhtWu7bS','123de@gmail.com'),(14,'Aiaiaia','$2y$10$dEAx6ATfK8IfGDKyZgGlAOaN8tim0BfKjy/fZfFbT6Pojf0aqv30.','te783uie@gmail.com'),(15,'Aiaiaiadfdf','$2y$10$mvcKPgbetaPXmqbHl7neteiXpy0tmbhgzVC4X/rq7/e8jhrhm3.J.','te783ufdfie@gmail.com'),(16,'adfdfrerer','$2y$10$Ygb3GEKfRG8/ig/uMd3yzOp4IGnfUR/IODeurHDxihmiPAvRlZlJO','te783ufdferereie@gmail.com'),(17,'erewfgtgfd','$2y$10$npaZkpDthiiO.mAEmcnvdOPHcwOI//t9D3ggWVMpdk29XNLC7lTUe','fdgdfgdfgfg@gmail.com'),(18,'erewfgtgfdrer','$2y$10$Hg47h10atFDi44aoMqOvF.97jRELa5gUuXg/YQMTjA1viQDfCXbdi','refdgdfgdfgfg@gmail.com'),(19,'erewfgtgfdr345','$2y$10$xzMPQkprrDeOnhy3fcQeMO7OZkFw1Ug2UqwPpLa1J0vEb9HVw626C','refdgdfgdfg543fg@gmail.com'),(20,'ere87egfdr345','$2y$10$nPCZCHDlrkdZDn/C7jcB9OgXNX3N4wb49YSkA7UdDQOZYQbKhN/Uy','refdgdf65g543fg@gmail.com'),(21,'ere85egfdr345','$2y$10$ZEiNmh0ffeALRkkk08Gk4OED5/FKJALgOIuXkx3R.0nxYSyLUg.ry','refdgdf45g543fg@gmail.com'),(22,'ere85egfdrf345','$2y$10$/eQTsNq85le4dOpZu4jgIel96QoI.ga87Qthxd7uZ5FTKEuDLSj0W','refdgdf4f5g543fg@gmail.com'),(23,'eegfdrf345','$2y$10$h5F0ld5eCpW5RpEEG2lhTeFbgGWOA96q2KKTCKNg3iGKJFDvDe0ka','rgdf4f5g543fg@gmail.com'),(24,'eeffgfdrf345','$2y$10$IWJfWpQezYWmWrDNq5ew0u5HexxeT9u9zxn9PDKzp6i0EjfJ3Hpei','rgdf4fff5g543fg@gmail.com'),(25,'eeffgfdrf34f5','$2y$10$SuhDRE.vgQLtgZOW1GfBfech9CBhPVDmBj/dqc/RDDW1cG9qdp5/G','rgdf4fff5g543ffg@gmail.com'),(26,'erf34frt5','$2y$10$ugaKnOyiEhdEZY36IZxfXuaT14VGCSqVOwxZwk14.tkAlrzBvtn3W','rgdf4fff5g5tr43ffg@gmail.com'),(27,'Player101','$2y$10$jUAXR4XVQnUayPIjQUZspODtdc5M0py/BwKvCwwuAghFbij5j1Uau','jde387b@gmail.com'),(28,'Player102','$2y$10$gdzdkWSWoSbUOAQA9ZHGaeJxJqd9gCECFQ1AZ80sn7KOX00z1rzoW','jde387a@gmail.com'),(29,'Player103','$2y$10$9.E56KYFHFCKlyWRhREJQeOWCNJCdOkSVdU4pKsBWUlYHTpZAXzU.','jde3ff87a@gmail.com'),(30,'Player1035','$2y$10$6d86wxjXvIQVk6LyN8NTsOcX1SZ98LRqUCPSwunJ3hqKKbCgz8Zxq','jde3f5f87a@gmail.com'),(31,'Player1035w','$2y$10$UzOTTvgLDIRDii.SvBnC0O2gtGyb3HtcAE0G6nPgIjg/mltmnnLJK','jde3f5f8d7a@gmail.com'),(32,'Player1035wr','$2y$10$o1s73KgqT/Fm38TQz7zvI.OEGK0cy4S5K5vMfGtap/QZ8WbRhI.yq','jde3f5fr8d7a@gmail.com');
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
  KEY `player_open_room_open_rooms_id_fk` (`room_id`),
  CONSTRAINT `player_open_room_open_rooms_id_fk` FOREIGN KEY (`room_id`) REFERENCES `open_rooms` (`id`),
  CONSTRAINT `player_open_room_player_administrative_info_id_fk` FOREIGN KEY (`player_id`) REFERENCES `player_administrative_info` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Assign player to a 1..* rooms';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_open_room`
--

LOCK TABLES `player_open_room` WRITE;
/*!40000 ALTER TABLE `player_open_room` DISABLE KEYS */;
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
INSERT INTO `player_session` VALUES (13,'2076d2e9ea81a55ab5f4ee4d8036119e063bcae7','2019-10-12 21:47:16'),(25,'32d2c122107147eb353a1e18a74a8bf0445a9419','2019-10-12 22:09:07'),(26,'a5301a719a3f048fae239660376dd9ca4d8342fc','2019-10-12 22:09:57'),(27,'acb915ea4315f7e56d64105d68241af5be3d7c41','2019-10-12 22:10:58'),(28,'16719b7f99bc98615a31175f4e32d1cf5485b76b','2019-10-12 22:22:12'),(29,'9781382530d2fa0b6297e4de02da45432e1df8c6','2019-10-12 22:22:56'),(30,'0e430451c0bb0d9becfd7a94bde2af9c72b525b3','2019-10-12 22:23:19'),(31,'4e29d5acfc9be777e7d15e02cf25434be0473016','2019-10-12 22:23:41'),(32,'6698d96bd0f75761bed2d84d5581e9ee163977b2','2019-10-12 22:25:05');
/*!40000 ALTER TABLE `player_session` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-12 23:08:49
