-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: imgExcel
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.2

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
-- Table structure for table `masterList`
--

DROP TABLE IF EXISTS `masterList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `masterList` (
  `retailerCode` varchar(45) NOT NULL,
  `retailerName` varchar(255) NOT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `distributionChannel` varchar(255) DEFAULT NULL,
  `origin` varchar(255) NOT NULL,
  `masterListId` int(11) NOT NULL,
  PRIMARY KEY (`retailerCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterList`
--

LOCK TABLES `masterList` WRITE;
/*!40000 ALTER TABLE `masterList` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masterLists`
--

DROP TABLE IF EXISTS `masterLists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `masterLists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `userId` int(11) NOT NULL,
  `numRows` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `masterList_user` (`userId`),
  CONSTRAINT `masterList_user` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masterLists`
--

LOCK TABLES `masterLists` WRITE;
/*!40000 ALTER TABLE `masterLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `masterLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailEntries`
--

DROP TABLE IF EXISTS `retailEntries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retailEntries` (
  `customer` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `customerType` varchar(45) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retailListId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_retailFile_id` (`retailListId`),
  CONSTRAINT `fk_retailFile_id` FOREIGN KEY (`retailListId`) REFERENCES `retailLists` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10072 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailEntries`
--

LOCK TABLES `retailEntries` WRITE;
/*!40000 ALTER TABLE `retailEntries` DISABLE KEYS */;
/*!40000 ALTER TABLE `retailEntries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailLists`
--

DROP TABLE IF EXISTS `retailLists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retailLists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `numRows` int(11) NOT NULL,
  `dateUploaded` datetime NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('UPLOADED','AUTO','COMPLETE') NOT NULL DEFAULT 'UPLOADED',
  PRIMARY KEY (`id`),
  KEY `retail_user` (`userId`),
  CONSTRAINT `retail_user` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailLists`
--

LOCK TABLES `retailLists` WRITE;
/*!40000 ALTER TABLE `retailLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `retailLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploadListEntries`
--

DROP TABLE IF EXISTS `uploadListEntries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploadListEntries` (
  `retailListId` int(11) NOT NULL,
  `retailCode` varchar(45) NOT NULL,
  `company` varchar(255) NOT NULL,
  `storeNumber` varchar(255) DEFAULT NULL,
  `retailerType` varchar(255) DEFAULT NULL,
  `subRetailer` varchar(255) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `upload_retailFile` (`retailListId`),
  CONSTRAINT `upload_retailFile` FOREIGN KEY (`retailListId`) REFERENCES `retailLists` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=618 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploadListEntries`
--

LOCK TABLES `uploadListEntries` WRITE;
/*!40000 ALTER TABLE `uploadListEntries` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploadListEntries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'marin@gatech.edu','Marin','Zaimov','b35a4bfb81aaf0a0a466349e18ead8d895bc54d6','db49af2f4c');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-03 14:32:21
