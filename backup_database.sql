/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.5.2-MariaDB, for Android (armv7-a)
--
-- Host: localhost    Database: absensi_siswa
-- ------------------------------------------------------
-- Server version	11.5.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `absen_siswa`
--

DROP TABLE IF EXISTS `absen_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `absen_siswa` (
  `absensi_id` int(11) NOT NULL AUTO_INCREMENT,
  `hari_tanggal` int(2) NOT NULL,
  `tanggal_lengkap` date NOT NULL DEFAULT current_timestamp(),
  `id_siswa` int(11) NOT NULL,
  `status` enum('izin','sakit','alpha') NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`absensi_id`),
  KEY `fk_absen` (`id_siswa`),
  CONSTRAINT `fk_absen` FOREIGN KEY (`id_siswa`) REFERENCES `data_siswa` (`id_siswa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absen_siswa`
--

LOCK TABLES `absen_siswa` WRITE;
/*!40000 ALTER TABLE `absen_siswa` DISABLE KEYS */;
INSERT INTO `absen_siswa` VALUES
(8,9,'2024-09-09',6,'izin',''),
(10,11,'2024-09-11',8,'sakit',''),
(11,11,'2024-09-11',6,'izin','');
/*!40000 ALTER TABLE `absen_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_siswa`
--

DROP TABLE IF EXISTS `data_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `nis` int(11) NOT NULL,
  `nama_siswa` varchar(255) NOT NULL,
  `kelas` int(11) NOT NULL,
  `jenis_kelamin` enum('laki_laki','perempuan') NOT NULL,
  PRIMARY KEY (`id_siswa`),
  KEY `fk_kelas` (`kelas`),
  CONSTRAINT `fk_kelas` FOREIGN KEY (`kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_siswa`
--

LOCK TABLES `data_siswa` WRITE;
/*!40000 ALTER TABLE `data_siswa` DISABLE KEYS */;
INSERT INTO `data_siswa` VALUES
(5,222222222,'galih',2,'laki_laki'),
(6,12666643,'ridwan',3,'laki_laki'),
(8,319493,'Irvan',3,'laki_laki');
/*!40000 ALTER TABLE `data_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES
(1,'12 rpl 2','rekayasa perangkat lunak'),
(2,'12 rpl 1','rekayasa perangkat lunak'),
(3,'12 rpl 3','rekayasa perangkat lunak'),
(4,'12 rpl 3','rekayasa perangkat lunak');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unik_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'test',''),
(2,'testing','$2y$10$EAt/glhz5hDHqv7JeWb7EO70QpmhIs2NcMY1i7vVgRRMp3M4xiY/S');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-09-12 19:59:29
