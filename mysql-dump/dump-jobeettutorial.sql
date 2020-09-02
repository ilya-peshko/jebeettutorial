-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: jobeettutorial
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF34668989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Manager','manager','2020-08-10 14:14:16','2020-08-10 14:14:16'),(2,'Programmist','programmist','2020-08-10 14:14:25','2020-08-10 14:14:25'),(3,'Cook','cook','2020-08-10 14:14:31','2020-08-10 14:14:31');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4FBF094FA76ED395` (`user_id`),
  CONSTRAINT `FK_4FBF094FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (3,'Abdulabum','Pirogovaq','88888',NULL,NULL,'2020-08-13 15:56:31','2020-08-13 15:56:31',1);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_job`
--

DROP TABLE IF EXISTS `cron_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_job`
--

LOCK TABLES `cron_job` WRITE;
/*!40000 ALTER TABLE `cron_job` DISABLE KEYS */;
INSERT INTO `cron_job` VALUES (1,'check_disabled_users','app:check-enabled-account','00 3 * * *','Checking disabled users and sends them emails.',1);
/*!40000 ALTER TABLE `cron_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_report`
--

DROP TABLE IF EXISTS `cron_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `run_at` datetime NOT NULL,
  `run_time` double NOT NULL,
  `exit_code` int(11) NOT NULL,
  `output` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6C6A7F5BE04EA9` (`job_id`),
  CONSTRAINT `FK_B6C6A7F5BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `cron_job` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_report`
--

LOCK TABLES `cron_report` WRITE;
/*!40000 ALTER TABLE `cron_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `cron_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20200721070626','2020-08-07 19:11:11',624),('DoctrineMigrations\\Version20200722093623','2020-08-07 19:11:12',126),('DoctrineMigrations\\Version20200724051203','2020-08-07 19:11:12',21),('DoctrineMigrations\\Version20200724053504','2020-08-07 19:11:12',53),('DoctrineMigrations\\Version20200724084916','2020-08-07 19:11:12',84),('DoctrineMigrations\\Version20200727101112','2020-08-07 19:11:12',58),('DoctrineMigrations\\Version20200727102235','2020-08-07 19:11:12',95),('DoctrineMigrations\\Version20200727105935','2020-08-07 19:11:12',81),('DoctrineMigrations\\Version20200727110301','2020-08-07 19:11:13',87),('DoctrineMigrations\\Version20200727110959','2020-08-07 19:11:13',84),('DoctrineMigrations\\Version20200727111056','2020-08-07 19:11:13',88),('DoctrineMigrations\\Version20200727112000','2020-08-07 19:11:13',13),('DoctrineMigrations\\Version20200728055619','2020-08-07 19:11:13',59),('DoctrineMigrations\\Version20200728062517','2020-08-07 19:11:13',115),('DoctrineMigrations\\Version20200728082023','2020-08-07 19:11:13',555),('DoctrineMigrations\\Version20200729100745','2020-08-07 19:11:14',246),('DoctrineMigrations\\Version20200730054352','2020-08-07 19:11:14',364),('DoctrineMigrations\\Version20200730055916','2020-08-07 19:11:14',88),('DoctrineMigrations\\Version20200803065735','2020-08-07 19:11:14',511),('DoctrineMigrations\\Version20200804065902','2020-08-07 19:11:15',47),('DoctrineMigrations\\Version20200804114807','2020-08-07 19:11:15',93),('DoctrineMigrations\\Version20200804114955','2020-08-07 19:11:15',13),('DoctrineMigrations\\Version20200804115249','2020-08-07 19:11:15',17),('DoctrineMigrations\\Version20200807063155','2020-08-07 19:11:15',96),('DoctrineMigrations\\Version20200807084711','2020-08-07 19:11:15',166),('DoctrineMigrations\\Version20200807121129','2020-08-07 19:11:38',116),('DoctrineMigrations\\Version20200807125950','2020-08-07 19:59:58',180),('DoctrineMigrations\\Version20200825091215','2020-08-25 16:12:26',269);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_application`
--

DROP TABLE IF EXISTS `job_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `resume_id` int(11) NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C737C688BE04EA9` (`job_id`),
  KEY `IDX_C737C688D262AF09` (`resume_id`),
  CONSTRAINT `FK_C737C688BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  CONSTRAINT `FK_C737C688D262AF09` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_application`
--

LOCK TABLES `job_application` WRITE;
/*!40000 ALTER TABLE `job_application` DISABLE KEYS */;
INSERT INTO `job_application` VALUES (1,1,1,0);
/*!40000 ALTER TABLE `job_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `how_to_apply` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `activated` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A8936DC512469DE2` (`category_id`),
  KEY `IDX_A8936DC5979B1AD6` (`company_id`),
  CONSTRAINT `FK_A8936DC512469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_A8936DC5979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,1,'full-time','http://123','6575','123213','123123','1231',1,1,'uyg@u.ru','2020-09-13 15:13:04','2020-08-14 15:13:04','2020-08-14 15:13:04',NULL,NULL,3,'Test');
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_request`
--

LOCK TABLES `reset_password_request` WRITE;
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
INSERT INTO `reset_password_request` VALUES (1,4,'xFHBkTX7bgHPJ6B2a1Zp','u5GQgXnLfTPYKXGF7eMmuKoSGy+vZ1Czrq1o9OvLCiE=','2020-08-13 18:05:23','2020-08-13 19:05:23');
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resumes`
--

DROP TABLE IF EXISTS `resumes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about_me` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `views_count` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_of_residence` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birthday` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CDB8AD33A76ED395` (`user_id`),
  CONSTRAINT `FK_CDB8AD33A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resumes`
--

LOCK TABLES `resumes` WRITE;
/*!40000 ALTER TABLE `resumes` DISABLE KEYS */;
INSERT INTO `resumes` VALUES (1,2,'First resume','123',0,'Ilya','Pepe','Nsk','Male','1996-04-04 00:00:00'),(2,2,'ASd','123',0,'Ilya','Pepe','Moscow','Male','1925-02-02 00:00:00'),(3,2,'gggg','bvc',0,'Dasha','Mas','Piter','Male','1911-02-05 00:00:00'),(4,2,'jjjjjjjjj','zxc',0,'Masha','Mas','Voronej','Female','1906-04-05 00:00:00'),(5,2,'Bonjur','asd',0,'Jim','Bim','Zaka','Male','1914-07-13 00:00:00'),(6,2,'Bonjur','asd',0,'Masha','Pepe','Nsk','Female','1902-02-03 00:00:00'),(7,2,'vbnm','mnb',0,'Jim','Mas','Piter','Male','1907-06-06 00:00:00'),(8,2,'zxcxzcz','jkjkjk',0,'Jim','Pepe','Moscow','Male','1901-06-04 00:00:00'),(9,2,'sdsds','qwqwqw',0,'iuiui','hjhjhj','vbvbvb','Male','1909-05-09 00:00:00'),(10,2,'zzzzz','bbbbb',0,'eyeyey','titititi','Kasha','Female','1930-05-09 00:00:00'),(11,2,'ASd','q',0,'Ilya','Pepe','Moscow','Male','1904-07-02 00:00:00');
/*!40000 ALTER TABLE `resumes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `roles` json NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'ilya','ilya@example.com',1,'$2y$13$miC9qrHpsbohUFFZ86tozupp6Z88xVK0w68eqCOL2laKwgwtglhWi',NULL,'[\"ROLE_EMPLOYER\"]',1,NULL),(2,'andy','andy@example.com',1,'$2y$13$CK6BKjYyMoTdNXdAOF5lGemvmmCrR4Xu7UB8NQXpcZBrAqLGKFtdm',NULL,'[\"ROLE_APPLICANT\"]',1,NULL),(3,'admin','admin@admin.ru',1,'$2y$13$PixFgkC2lNNUD3G5nbKiee6Gx/FGEFq0Kcmc/68AqX5O4uqCjr4Wu',NULL,'[\"ROLE_ADMIN\"]',1,NULL),(4,'alkatras421@mail.ru','alkatras421@mail.ru',1,'$2y$13$rOf.hwYJDnyBNMDgBZ43HujnzGqsp0/mfR7aTgtboUCPOwVitec4C',NULL,'[]',0,'3297165460370432'),(5,'test','peshkoi@mail.ru',0,'$2y$13$1BO1C2vuzeI8loc3Qf18Tuv9jnR3tCn5qSqto1GJjGnKUNNQ4ZLrS',NULL,'[\"ROLE_APPLICANT\"]',1,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'jobeettutorial'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-01 18:27:31
