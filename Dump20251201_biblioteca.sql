CREATE DATABASE  IF NOT EXISTS `biblioteca` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `biblioteca`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: biblioteca
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `autores`
--

DROP TABLE IF EXISTS `autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `biografia` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autores`
--

LOCK TABLES `autores` WRITE;
/*!40000 ALTER TABLE `autores` DISABLE KEYS */;
INSERT INTO `autores` VALUES (1,'Machado de Assis','Brasileira','1839-06-21','Breve bibliografia','2025-11-06 13:35:07','2025-11-10 22:59:30'),(2,'Clarice Lispector','Brasileira','1920-12-10','','2025-11-06 13:35:07','2025-11-10 22:53:27'),(3,'Jorge Amado','Brasileira','1912-08-10',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(4,'J.K. Rowling','Britânica','1965-07-31',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(5,'George Orwell','Britânica','1903-06-25',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(6,'Gabriel García Márquez','Colombiana','1927-06-06','','2025-11-06 13:35:07','2025-11-10 22:53:42'),(7,'Agatha Christie','Britânica','1890-09-15',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(8,'Stephen King','Americana','1947-09-21',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(9,'Paulo Coelho','Brasileira','1947-08-24',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(10,'Monteiro Lobato','Brasileira','1882-04-18',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(12,'Aldrey Kich','Brasileira','1977-06-30','Teste de Bibliografia.','2025-11-10 22:55:26','2025-11-25 11:01:18');
/*!40000 ALTER TABLE `autores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Ativo','Inativo','Bloqueado') COLLATE utf8mb4_unicode_ci DEFAULT 'Ativo',
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `idx_email` (`email`),
  KEY `idx_nome` (`nome`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Ana Silva Santos','ana.silva@email.com','(11) 98765-4321','123.456.789-00','1995-03-15','Rua das Flores, 123','São Paulo','SP','01234-567','Ativo',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(2,'Carlos Eduardo Souza','carlos.souza@email.com','(11) 97654-3210','234.567.890-11','1988-07-22','Av. Paulista, 1000','São Paulo','SP','01310-100','Ativo',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(3,'Maria Oliveira Lima','maria.lima@email.com','(21) 99876-5432','345.678.901-22','1992-11-08','Rua Copacabana, 456','Rio de Janeiro','RJ','22070-000','Ativo',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(4,'João Pedro Costa','joao.costa@email.com','(41) 98765-1234','456.789.012-33','2000-05-30','Rua XV de Novembro, 789','Curitiba','PR','80020-310','Ativo',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(5,'Juliana Fernandes','juliana.fernandes@email.com','(11) 96543-2109','567.890.123-44','1985-09-12','Rua Augusta, 2500','São Paulo','SP','01412-100','Ativo',NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(6,'Lucas','lucas.dressler.barros@escola.pr.gov.br','45999111568',NULL,NULL,NULL,NULL,NULL,NULL,'Ativo',NULL,'2025-11-06 13:36:06','2025-11-06 13:36:06'),(7,'Marcio','marcio@escola.pr.gov.br','45999111123',NULL,NULL,NULL,NULL,NULL,NULL,'Ativo',NULL,'2025-11-06 14:24:08','2025-11-06 14:24:08'),(8,'Junior','Juninho@play.com','4599882244',NULL,NULL,NULL,NULL,NULL,NULL,'Ativo',NULL,'2025-11-06 14:27:06','2025-11-06 14:27:06'),(9,'Debora','deboramaria@gmail.com','45999221313',NULL,NULL,NULL,NULL,NULL,NULL,'Ativo',NULL,'2025-11-06 19:30:52','2025-11-06 19:30:52'),(13,'Aldrey Kich','aldrey.kich@gmail.com','45999449138',NULL,NULL,NULL,NULL,NULL,NULL,'Ativo',NULL,'2025-11-10 22:56:25','2025-11-10 22:56:25');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emprestimos`
--

DROP TABLE IF EXISTS `emprestimos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emprestimos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `livro_id` int NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_devolucao_prevista` date NOT NULL,
  `data_devolucao_real` date DEFAULT NULL,
  `status` enum('Ativo','Devolvido','Atrasado','Cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'Ativo',
  `multa` decimal(10,2) DEFAULT '0.00',
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cliente` (`cliente_id`),
  KEY `idx_livro` (`livro_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_emprestimo` (`data_emprestimo`),
  KEY `idx_data_devolucao` (`data_devolucao_prevista`),
  CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emprestimos`
--

LOCK TABLES `emprestimos` WRITE;
/*!40000 ALTER TABLE `emprestimos` DISABLE KEYS */;
INSERT INTO `emprestimos` VALUES (1,1,7,'2025-11-01','2025-11-22','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 13:35:07','2025-11-06 20:20:44'),(2,2,9,'2025-11-03','2025-11-10','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 13:35:07','2025-11-06 20:20:39'),(3,3,3,'2025-10-28','2025-11-04','2025-11-06','Devolvido',5.00,NULL,'2025-11-06 13:35:07','2025-11-06 20:16:19'),(4,4,5,'2025-10-25','2025-11-01',NULL,'Devolvido',0.00,NULL,'2025-11-06 13:35:07','2025-11-06 13:35:07'),(5,5,12,'2025-11-02','2025-11-09','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 13:35:07','2025-11-06 20:20:41'),(6,6,4,'2025-11-06','2025-11-20','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 13:36:21','2025-11-06 20:20:30'),(7,7,9,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 14:24:15','2025-11-06 20:20:33'),(8,8,9,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 14:27:17','2025-11-06 20:20:34'),(9,2,10,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 19:24:40','2025-11-06 20:20:36'),(10,9,14,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 19:30:59','2025-11-06 20:20:37'),(11,9,13,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 20:20:07','2025-11-06 20:20:38'),(12,1,13,'2025-11-06','2025-11-13','2025-11-06','Devolvido',0.00,NULL,'2025-11-06 20:25:20','2025-11-06 20:30:45'),(13,2,14,'2025-11-06','2025-11-13','2025-11-08','Devolvido',0.00,NULL,'2025-11-06 20:26:50','2025-11-07 23:27:44'),(14,2,15,'2025-11-06','2025-11-13','2025-11-08','Devolvido',0.00,NULL,'2025-11-06 20:27:56','2025-11-07 23:29:03'),(15,6,14,'2025-11-06','2025-11-09','2025-11-12','Devolvido',7.50,NULL,'2025-11-06 22:52:01','2025-11-11 23:51:37'),(16,6,5,'2025-11-07','2025-11-21','2025-11-07','Devolvido',0.00,NULL,'2025-11-07 21:58:51','2025-11-07 22:01:03'),(31,6,5,'2025-11-11','2025-11-25',NULL,'Ativo',0.00,NULL,'2025-11-11 22:39:20','2025-11-11 23:51:17'),(32,8,5,'2025-11-11','2025-11-25',NULL,'Ativo',0.00,NULL,'2025-11-11 22:42:49','2025-11-11 23:47:18'),(33,7,12,'2025-11-11','2025-11-18',NULL,'Ativo',0.00,NULL,'2025-11-11 22:47:11','2025-11-11 22:47:11'),(34,3,6,'2025-11-11','2025-11-18',NULL,'Ativo',0.00,NULL,'2025-11-11 22:48:58','2025-11-11 22:48:58'),(35,9,6,'2025-11-11','2025-11-18',NULL,'Ativo',0.00,NULL,'2025-11-11 22:50:47','2025-11-11 22:50:47'),(36,13,2,'2025-11-11','2025-12-02','2025-11-12','Devolvido',0.00,NULL,'2025-11-11 22:53:13','2025-11-11 23:46:58'),(37,5,4,'2025-11-11','2025-11-18','2025-11-12','Devolvido',0.00,NULL,'2025-11-11 22:55:39','2025-11-11 23:41:21'),(38,4,3,'2025-11-11','2025-11-18','2025-11-12','Devolvido',0.00,NULL,'2025-11-11 22:57:20','2025-11-11 23:41:47'),(40,1,10,'2025-11-12','2025-11-26',NULL,'Ativo',0.00,NULL,'2025-11-11 23:01:54','2025-11-11 23:34:21'),(41,1,13,'2025-11-12','2025-11-26','2025-11-12','Devolvido',0.00,NULL,'2025-11-11 23:08:37','2025-11-11 23:37:48');
/*!40000 ALTER TABLE `emprestimos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livros`
--

DROP TABLE IF EXISTS `livros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor_id` int NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano_publicacao` year DEFAULT NULL,
  `editora` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_paginas` int DEFAULT NULL,
  `quantidade_total` int DEFAULT '1',
  `quantidade_disponivel` int DEFAULT '1',
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capa_imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localizacao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `idx_titulo` (`titulo`),
  KEY `idx_autor` (`autor_id`),
  KEY `idx_isbn` (`isbn`),
  CONSTRAINT `livros_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livros`
--

LOCK TABLES `livros` WRITE;
/*!40000 ALTER TABLE `livros` DISABLE KEYS */;
INSERT INTO `livros` VALUES (2,'Memórias Póstumas de Brás Cubas',1,'978-8535911671',2000,'Companhia das Letras',368,2,2,'Romance','49a5a1fdb296ededa5351b68ce74973d.jpg','Estante A1','2025-11-06 13:35:07','2025-11-11 23:46:58'),(3,'A Hora da Estrela',2,'978-8520925683',1977,'Rocco',88,2,1,'Romance','dc0c3816e73d204872666a4a8960d740.jpg','Estante A2','2025-11-06 13:35:07','2025-11-11 23:41:47'),(4,'A Paixão Segundo G.H.',2,'978-8532511010',1964,'Rocco',176,1,1,'Romance','833818e12215e5852a88ba4c234b7896.jpg','Estante A2','2025-11-06 13:35:07','2025-11-11 23:41:22'),(5,'Capitães da Areia',3,'978-8535914063',1937,'Companhia das Letras',280,4,1,'Romance','b9cfcbb0f554ba7fcc51814fa9a71a06.png','Estante A3','2025-11-06 13:35:07','2025-11-11 22:55:28'),(6,'Gabriela, Cravo e Canela',3,'978-8535911046',1958,'Companhia das Letras',424,2,0,'Romance','18a4b3a3f4b941f1c249871a1a12a057.jpg','Estante A3','2025-11-06 13:35:07','2025-11-11 22:50:47'),(7,'Harry Potter e a Pedra Filosofal',4,'978-8532530787',1997,'Rocco',264,5,4,'Fantasia','e1a9feec7414d3db21795d9fd7624d94.jpg','Estante B1','2025-11-06 13:35:07','2025-11-11 22:28:35'),(9,'1984',5,'978-8535914849',1949,'Companhia das Letras',416,4,3,'Ficção','1389a15edd2d2a401c703c86d5c20ee7.jpg','Estante B2','2025-11-06 13:35:07','2025-11-11 23:09:38'),(10,'A Revolução dos Bichos',5,'978-8535909555',1945,'Companhia das Letras',152,3,2,'Ficção','4ee8ef50ee292676a760b1215c492ae4.jpg','Estante B2','2025-11-06 13:35:07','2025-11-11 23:01:54'),(11,'Cem Anos de Solidão',6,'978-8501061294',1967,'Record',424,2,2,'Romance','be64601abfdca667c717c6c4c0cd6de5.jpg','Estante C1','2025-11-06 13:35:07','2025-11-11 22:27:03'),(12,'Assassinato no Expresso do Oriente',7,'978-8595084841',1934,'HarperCollins',256,3,1,'Mistério','901aa5ddaf5f35b722b90ce2bafd49e7.jpg','Estante C2','2025-11-06 13:35:07','2025-11-11 22:55:30'),(13,'O Iluminado',8,'978-8581050584',1977,'Suma',464,2,1,'Terror','af936f1660823aabe0be2724aa7433f7.jpg','Estante C3','2025-11-06 13:35:07','2025-11-11 23:37:48'),(14,'O Alquimista',9,'978-8522008865',1988,'Rocco',224,5,8,'Ficção','ad784aaf69001850d55c1cdc79147486.jpg','Estante D1','2025-11-06 13:35:07','2025-11-11 23:51:37'),(15,'O Sítio do Picapau Amarelo',10,'978-8525406293',1920,'Globo',288,4,4,'Infantil','5a9ac21b9c3f1450f2554e27f25f703b.jpg','Estante D2','2025-11-06 13:35:07','2025-11-11 22:19:33');
/*!40000 ALTER TABLE `livros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_auditoria`
--

DROP TABLE IF EXISTS `login_auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_auditoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `email_tentado` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_hora` datetime NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucesso` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `login_auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_auditoria`
--

LOCK TABLES `login_auditoria` WRITE;
/*!40000 ALTER TABLE `login_auditoria` DISABLE KEYS */;
INSERT INTO `login_auditoria` VALUES (1,5,'aldrey.govbr@gmail.com','2025-11-24 21:34:32','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(2,5,'aldrey.govbr@gmail.com','2025-11-24 21:41:50','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(3,1,'admin@biblioteca.com','2025-11-24 21:42:30','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(4,1,'admin@biblioteca.com','2025-11-25 07:39:08','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(5,1,'admin@biblioteca.com','2025-11-25 09:30:13','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(6,1,'admin@biblioteca.com','2025-11-25 11:02:34','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(7,1,'admin@biblioteca.com','2025-11-27 08:16:05','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(8,1,'admin@biblioteca.com','2025-11-27 10:16:25','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(9,2,'aldrey.kich@gmail.com','2025-11-27 10:16:39','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1),(10,1,'admin@biblioteca.com','2025-12-01 18:41:13','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',1);
/*!40000 ALTER TABLE `login_auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfil` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cliente',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_expira` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Administrador','admin@biblioteca.com','$2y$10$QAKtwWjr0VT87XzsU8H85.3T3vziHNJircGSN9LCPGSCV1eR0M12m','admin',1,'2025-11-11 23:56:18',NULL,NULL),(2,'Aldrey Kich','aldrey.kich@gmail.com','$2y$10$71lXcijr6whvVicmL7deROctJ3PYNs8RyVnYCBiXr86SlSj5hxHOu','admin',1,'2025-11-12 13:44:43','cb9dd09564b0cc5af0aad597a4ed214a3c2071a88d1d7cdb0d68238f871c03cf','2025-11-25 14:30:08'),(3,'bibliotecario','bibliotecario@biblioteca.com.br','$2y$10$ldbqL0CjitT67Zk4.DRWYePMASugZ1WxeXzdc8pxIgfW.k3yCZoDy','bibliotecario',1,'2025-11-12 16:43:48',NULL,NULL),(5,'Teste da Silva','aldrey.govbr@gmail.com','$2y$10$Spr85Ail7O7jh/Fw0o2YguHNd0/1AsMxJI6HshjsyHR4Z5xKsR2F2','admin',1,'2025-11-25 00:12:50','66d053f4b870ba3105459f44890d9f28b19044da57e8d71de6b0ee9b2d7bf2d4','2025-11-25 02:24:39');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_emprestimos_completos`
--

DROP TABLE IF EXISTS `vw_emprestimos_completos`;
/*!50001 DROP VIEW IF EXISTS `vw_emprestimos_completos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_emprestimos_completos` AS SELECT 
 1 AS `id`,
 1 AS `data_emprestimo`,
 1 AS `data_devolucao_prevista`,
 1 AS `data_devolucao_real`,
 1 AS `status`,
 1 AS `multa`,
 1 AS `cliente_id`,
 1 AS `cliente_nome`,
 1 AS `cliente_email`,
 1 AS `cliente_telefone`,
 1 AS `livro_id`,
 1 AS `livro_titulo`,
 1 AS `livro_isbn`,
 1 AS `autor_nome`,
 1 AS `dias_atraso`,
 1 AS `multa_calculada`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_estatisticas_biblioteca`
--

DROP TABLE IF EXISTS `vw_estatisticas_biblioteca`;
/*!50001 DROP VIEW IF EXISTS `vw_estatisticas_biblioteca`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_estatisticas_biblioteca` AS SELECT 
 1 AS `total_livros`,
 1 AS `total_exemplares`,
 1 AS `exemplares_disponiveis`,
 1 AS `clientes_ativos`,
 1 AS `emprestimos_ativos`,
 1 AS `emprestimos_atrasados`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_emprestimos_completos`
--

/*!50001 DROP VIEW IF EXISTS `vw_emprestimos_completos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_emprestimos_completos` AS select `e`.`id` AS `id`,`e`.`data_emprestimo` AS `data_emprestimo`,`e`.`data_devolucao_prevista` AS `data_devolucao_prevista`,`e`.`data_devolucao_real` AS `data_devolucao_real`,`e`.`status` AS `status`,`e`.`multa` AS `multa`,`c`.`id` AS `cliente_id`,`c`.`nome` AS `cliente_nome`,`c`.`email` AS `cliente_email`,`c`.`telefone` AS `cliente_telefone`,`l`.`id` AS `livro_id`,`l`.`titulo` AS `livro_titulo`,`l`.`isbn` AS `livro_isbn`,`a`.`nome` AS `autor_nome`,(to_days(curdate()) - to_days(`e`.`data_devolucao_prevista`)) AS `dias_atraso`,(case when ((`e`.`status` = 'Ativo') and (curdate() > `e`.`data_devolucao_prevista`)) then ((to_days(curdate()) - to_days(`e`.`data_devolucao_prevista`)) * 2.50) else 0 end) AS `multa_calculada` from (((`emprestimos` `e` join `clientes` `c` on((`e`.`cliente_id` = `c`.`id`))) join `livros` `l` on((`e`.`livro_id` = `l`.`id`))) join `autores` `a` on((`l`.`autor_id` = `a`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_estatisticas_biblioteca`
--

/*!50001 DROP VIEW IF EXISTS `vw_estatisticas_biblioteca`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_estatisticas_biblioteca` AS select (select count(0) from `livros`) AS `total_livros`,(select sum(`livros`.`quantidade_total`) from `livros`) AS `total_exemplares`,(select sum(`livros`.`quantidade_disponivel`) from `livros`) AS `exemplares_disponiveis`,(select count(0) from `clientes` where (`clientes`.`status` = 'Ativo')) AS `clientes_ativos`,(select count(0) from `emprestimos` where (`emprestimos`.`status` = 'Ativo')) AS `emprestimos_ativos`,(select count(0) from `emprestimos` where ((`emprestimos`.`status` = 'Ativo') and (`emprestimos`.`data_devolucao_prevista` < curdate()))) AS `emprestimos_atrasados` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-01 18:44:02
