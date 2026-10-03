-- MySQL dump 10.13  Distrib 8.2.0, for Win64 (x86_64)
--
-- Host: gestion-scolaire-db-espoirmmuk-ec75.j.aivencloud.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.4.8

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
-- Table structure for table `activites`
--

DROP TABLE IF EXISTS `activites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activites` (
  `id_activite` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `titre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_activite` date DEFAULT NULL,
  `lieu` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `budget` decimal(15,2) DEFAULT NULL,
  `devise` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `responsable` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('PREVUE','EN_COURS','TERMINEE','ANNULEE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PREVUE',
  PRIMARY KEY (`id_activite`),
  KEY `idx_activites_date` (`date_activite`),
  KEY `idx_activites_annee` (`id_annee_scolaire`),
  CONSTRAINT `fk_activite_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_activite_budget` CHECK (((`budget` is null) or (`budget` >= 0))),
  CONSTRAINT `chk_activite_devise` CHECK ((`devise` in (_utf8mb4'USD',_utf8mb4'CDF')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activites`
--

LOCK TABLES `activites` WRITE;
/*!40000 ALTER TABLE `activites` DISABLE KEYS */;
/*!40000 ALTER TABLE `activites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affectations_enseignants`
--

DROP TABLE IF EXISTS `affectations_enseignants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affectations_enseignants` (
  `id_affectation` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `id_enseignant` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `id_matiere` bigint unsigned NOT NULL,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `est_titulaire` tinyint(1) NOT NULL DEFAULT '0',
  `heures_semaine` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIF',
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_modification` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_affectation`),
  UNIQUE KEY `uq_affectation` (`id_enseignant`,`id_classe`,`id_matiere`,`id_annee_scolaire`),
  KEY `affectations_enseignants_id_classe_foreign` (`id_classe`),
  KEY `affectations_enseignants_id_matiere_foreign` (`id_matiere`),
  KEY `affectations_enseignants_id_annee_scolaire_foreign` (`id_annee_scolaire`),
  KEY `affectation_etab_annee_idx` (`id_etablissement`,`id_annee_scolaire`),
  CONSTRAINT `affectations_enseignants_id_annee_scolaire_foreign` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE CASCADE,
  CONSTRAINT `affectations_enseignants_id_classe_foreign` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE CASCADE,
  CONSTRAINT `affectations_enseignants_id_enseignant_foreign` FOREIGN KEY (`id_enseignant`) REFERENCES `personnel` (`id_personnel`) ON DELETE CASCADE,
  CONSTRAINT `affectations_enseignants_id_etablissement_foreign` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE,
  CONSTRAINT `affectations_enseignants_id_matiere_foreign` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`) ON DELETE CASCADE,
  CONSTRAINT `fk_affectation_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_affectation_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_affectation_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `personnel` (`id_personnel`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_affectation_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affectations_enseignants`
--

LOCK TABLES `affectations_enseignants` WRITE;
/*!40000 ALTER TABLE `affectations_enseignants` DISABLE KEYS */;
/*!40000 ALTER TABLE `affectations_enseignants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `annees_scolaires`
--

DROP TABLE IF EXISTS `annees_scolaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annees_scolaires` (
  `id_annee_scolaire` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `libelle` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT '0',
  `est_cloturee` tinyint(1) NOT NULL DEFAULT '0',
  `date_cloture` timestamp NULL DEFAULT NULL,
  `cloturee_par` bigint unsigned DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_annee_scolaire`),
  UNIQUE KEY `uq_annee_etablissement` (`id_etablissement`,`libelle`),
  KEY `idx_annees_etablissement` (`id_etablissement`),
  KEY `idx_annees_active` (`est_active`),
  CONSTRAINT `fk_annee_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_annee_dates` CHECK ((`date_fin` > `date_debut`))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annees_scolaires`
--

LOCK TABLES `annees_scolaires` WRITE;
/*!40000 ALTER TABLE `annees_scolaires` DISABLE KEYS */;
INSERT INTO `annees_scolaires` VALUES (1,1,'2026-2027','2026-09-01','2027-07-02',1,0,NULL,NULL,'2026-08-09 10:46:50');
/*!40000 ALTER TABLE `annees_scolaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bulletins` (
  `id_bulletin` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_eleve` bigint unsigned NOT NULL,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `id_periode` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `moyenne` decimal(8,2) DEFAULT NULL,
  `pourcentage` decimal(8,2) DEFAULT NULL,
  `rang` int unsigned DEFAULT NULL,
  `decision` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_generation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bulletin`),
  UNIQUE KEY `uq_bulletin` (`id_eleve`,`id_annee_scolaire`,`id_periode`),
  KEY `fk_bulletin_classe` (`id_classe`),
  KEY `idx_bulletins_eleve` (`id_eleve`),
  KEY `idx_bulletins_annee` (`id_annee_scolaire`),
  KEY `idx_bulletins_periode` (`id_periode`),
  CONSTRAINT `fk_bulletin_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_bulletin_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_bulletin_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_bulletin_periode` FOREIGN KEY (`id_periode`) REFERENCES `periodes_scolaires` (`id_periode`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_bulletin_moyenne` CHECK (((`moyenne` is null) or (`moyenne` >= 0))),
  CONSTRAINT `chk_bulletin_pourcentage` CHECK (((`pourcentage` is null) or ((`pourcentage` >= 0) and (`pourcentage` <= 100))))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletins`
--

LOCK TABLES `bulletins` WRITE;
/*!40000 ALTER TABLE `bulletins` DISABLE KEYS */;
/*!40000 ALTER TABLE `bulletins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_frais`
--

DROP TABLE IF EXISTS `categories_frais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_frais` (
  `id_categorie_frais` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `libelle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `statut` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id_categorie_frais`),
  UNIQUE KEY `uq_categorie_frais` (`libelle`),
  KEY `categories_frais_id_etablissement_foreign` (`id_etablissement`),
  CONSTRAINT `categories_frais_id_etablissement_foreign` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_frais`
--

LOCK TABLES `categories_frais` WRITE;
/*!40000 ALTER TABLE `categories_frais` DISABLE KEYS */;
INSERT INTO `categories_frais` VALUES (1,1,'Inscription','Frais d’inscription','ACTIVE'),(2,1,'Minerval','Frais scolaires','ACTIVE'),(3,1,'Examen','Frais liés aux examens','ACTIVE'),(4,1,'Uniforme','Uniformes scolaires','ACTIVE'),(5,1,'Transport','Transport scolaire','ACTIVE'),(6,1,'Activites','Activités scolaires','ACTIVE'),(7,1,'Autres','Autres frais','ACTIVE');
/*!40000 ALTER TABLE `categories_frais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_inventaire`
--

DROP TABLE IF EXISTS `categories_inventaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_inventaire` (
  `id_categorie` bigint unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_categorie`),
  UNIQUE KEY `uq_categorie_inventaire` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_inventaire`
--

LOCK TABLES `categories_inventaire` WRITE;
/*!40000 ALTER TABLE `categories_inventaire` DISABLE KEYS */;
INSERT INTO `categories_inventaire` VALUES (1,'Mobilier','Tables, chaises, armoires et bureaux'),(2,'Informatique','Ordinateurs et équipements informatiques'),(3,'Pedagogique','Matériel pédagogique'),(4,'Sport','Matériel sportif'),(5,'Bibliotheque','Livres et ouvrages'),(6,'Electronique','Appareils électroniques'),(7,'Autres','Autres biens');
/*!40000 ALTER TABLE `categories_inventaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id_classe` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `id_niveau` bigint unsigned NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_classe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacite` int unsigned DEFAULT NULL,
  `statut` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id_classe`),
  UNIQUE KEY `uq_classe_annee` (`id_annee_scolaire`,`libelle`),
  KEY `idx_classes_annee` (`id_annee_scolaire`),
  KEY `idx_classes_niveau` (`id_niveau`),
  KEY `classes_id_etablissement_index` (`id_etablissement`),
  CONSTRAINT `classes_id_etablissement_foreign` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE SET NULL,
  CONSTRAINT `fk_classe_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_classe_niveau` FOREIGN KEY (`id_niveau`) REFERENCES `niveaux` (`id_niveau`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_classe_capacite` CHECK (((`capacite` is null) or (`capacite` > 0)))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (2,1,1,3,'4ème','Informatique',50,'ACTIVE'),(4,1,1,2,'1ère','Crèche',50,'ACTIVE');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes_matieres`
--

DROP TABLE IF EXISTS `classes_matieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes_matieres` (
  `id_classe_matiere` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_classe` bigint unsigned NOT NULL,
  `id_matiere` bigint unsigned NOT NULL,
  `id_enseignant` bigint unsigned DEFAULT NULL,
  `nombre_heures` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_classe_matiere`),
  UNIQUE KEY `uq_classe_matiere` (`id_classe`,`id_matiere`),
  KEY `idx_cm_classe` (`id_classe`),
  KEY `idx_cm_matiere` (`id_matiere`),
  KEY `idx_cm_enseignant` (`id_enseignant`),
  CONSTRAINT `fk_cm_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cm_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `personnel` (`id_personnel`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cm_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_nombre_heures` CHECK (((`nombre_heures` is null) or (`nombre_heures` > 0)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes_matieres`
--

LOCK TABLES `classes_matieres` WRITE;
/*!40000 ALTER TABLE `classes_matieres` DISABLE KEYS */;
/*!40000 ALTER TABLE `classes_matieres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id_contact` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sujet` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lu` tinyint(1) NOT NULL DEFAULT '0',
  `date_lu` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Mukongo','espoirmmuk@gmail.com','Assistance','Bonsoir monsieur Espoir. Nous voulons savoir comment utiliser le logiciel GESCO',1,'2026-09-24 16:15:06','2026-09-24 16:14:46','2026-09-24 16:15:06'),(2,'Espoir MUKONGO KIVUVU','espoirmmuk@gmail.com','Assistance','Bonsoir. Nous voulons savoir comment utiliser GESCO',1,'2026-09-24 16:20:18','2026-09-24 16:19:35','2026-09-24 16:20:18');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `depenses` (
  `id_depense` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `id_annee_scolaire` bigint unsigned DEFAULT NULL,
  `date_depense` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categorie` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `devise` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_utilisateur` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_depense`),
  KEY `fk_depense_utilisateur` (`id_utilisateur`),
  KEY `idx_depenses_date` (`date_depense`),
  KEY `idx_depenses_etablissement` (`id_etablissement`),
  KEY `idx_depenses_annee` (`id_annee_scolaire`),
  CONSTRAINT `fk_depense_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_depense_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_depense_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_depense_devise` CHECK ((`devise` in (_utf8mb4'USD',_utf8mb4'CDF'))),
  CONSTRAINT `chk_depense_montant` CHECK ((`montant` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depenses`
--

LOCK TABLES `depenses` WRITE;
/*!40000 ALTER TABLE `depenses` DISABLE KEYS */;
INSERT INTO `depenses` VALUES (1,1,1,'2026-08-15 22:31:00','Transport',45.00,'USD',NULL,1);
/*!40000 ALTER TABLE `depenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_bulletins`
--

DROP TABLE IF EXISTS `details_bulletins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `details_bulletins` (
  `id_detail` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_bulletin` bigint unsigned NOT NULL,
  `id_matiere` bigint unsigned NOT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `moyenne` decimal(8,2) DEFAULT NULL,
  `coefficient` decimal(5,2) NOT NULL DEFAULT '1.00',
  `points` decimal(10,2) DEFAULT NULL,
  `appreciation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `uq_detail_bulletin_matiere` (`id_bulletin`,`id_matiere`),
  KEY `fk_detail_matiere` (`id_matiere`),
  CONSTRAINT `fk_detail_bulletin` FOREIGN KEY (`id_bulletin`) REFERENCES `bulletins` (`id_bulletin`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_detail_coefficient` CHECK ((`coefficient` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_bulletins`
--

LOCK TABLES `details_bulletins` WRITE;
/*!40000 ALTER TABLE `details_bulletins` DISABLE KEYS */;
/*!40000 ALTER TABLE `details_bulletins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_paiements`
--

DROP TABLE IF EXISTS `details_paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `details_paiements` (
  `id_detail_paiement` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_paiement` bigint unsigned NOT NULL,
  `id_frais_eleve` bigint unsigned NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id_detail_paiement`),
  KEY `idx_details_paiement` (`id_paiement`),
  KEY `idx_details_frais` (`id_frais_eleve`),
  CONSTRAINT `fk_detail_frais` FOREIGN KEY (`id_frais_eleve`) REFERENCES `frais_eleves` (`id_frais_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_paiement` FOREIGN KEY (`id_paiement`) REFERENCES `paiements` (`id_paiement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_detail_paiement_montant` CHECK ((`montant` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_paiements`
--

LOCK TABLES `details_paiements` WRITE;
/*!40000 ALTER TABLE `details_paiements` DISABLE KEYS */;
INSERT INTO `details_paiements` VALUES (1,2,1,50.00),(2,3,2,50.00),(3,4,2,30.00),(4,5,3,50.00),(5,6,4,50.00),(8,9,7,30.00);
/*!40000 ALTER TABLE `details_paiements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eleves` (
  `id_eleve` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `matricule` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postnom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe` enum('M','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('ACTIF','ABANDON','TRANSFERE','DIPLOME','RADIE','INACTIF') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIF',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_eleve`),
  UNIQUE KEY `uq_eleve_matricule` (`matricule`),
  KEY `idx_eleves_nom` (`nom`,`postnom`,`prenom`),
  KEY `idx_eleves_statut` (`statut`),
  KEY `eleves_id_etablissement_index` (`id_etablissement`),
  CONSTRAINT `eleves_id_etablissement_foreign` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE SET NULL,
  CONSTRAINT `chk_eleve_email` CHECK (((`email` is null) or (`email` like _utf8mb4'%@%')))
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eleves`
--

LOCK TABLES `eleves` WRITE;
/*!40000 ALTER TABLE `eleves` DISABLE KEYS */;
INSERT INTO `eleves` VALUES (1,1,'0012','Mukongo','Kivuvu','Espoir','M','2026-08-05','Feshi','1413','+243 820607096','espoirmmuk@gmail.com',NULL,'ACTIF','2026-08-08 22:08:17','2026-08-14 10:42:06'),(3,1,'0014','KOMBO','TITI','Tete','F','2026-09-05','Kinshasa','1412','0820607096','espoirmmuk@gmail.com',NULL,'ACTIF','2026-08-09 14:27:49','2026-08-14 10:42:06'),(4,1,'ENS-001','KIESE','MUKONGO','Schiphra','F','2026-09-03','Kinshasa',NULL,NULL,NULL,NULL,'ACTIF','2026-08-09 22:09:14','2026-08-14 10:42:06'),(5,1,'00025','PAPA','MAMAN','Fils','M','2026-08-19','Kinshasa',NULL,NULL,NULL,NULL,'ACTIF','2026-08-09 22:37:19','2026-08-14 10:42:06'),(6,1,'00012','PASCAL','PASCAL','Pascal','M','2026-08-26','Kinshasa',NULL,NULL,NULL,NULL,'ACTIF','2026-08-09 23:47:13','2026-08-14 10:42:06'),(7,1,'000002','OSCAR','OSCAR','OSCAR','F','2026-09-04',NULL,NULL,NULL,NULL,NULL,'ACTIF','2026-08-09 23:52:06','2026-08-14 10:42:06'),(8,1,'0003','Lumière','Lumière','Lumière','F','2026-08-28',NULL,NULL,NULL,NULL,NULL,'ACTIF','2026-08-09 23:53:59','2026-08-14 10:42:06'),(13,1,'789','R','R','R','M','2026-08-18',NULL,NULL,NULL,NULL,NULL,'ACTIF','2026-08-15 23:50:41','2026-08-15 23:50:41'),(15,1,'0023','Mukongo','Kiese','Schiphra','F','2026-02-25','Kinshasa',NULL,NULL,NULL,NULL,'ACTIF','2026-09-07 09:59:16','2026-09-07 09:59:16'),(16,1,'000123','MUK','KIV','Esp','M','1995-08-05','Feshi','Avenue Mateba n°2','+243 820607096','espoirmmuk@gmail.com',NULL,'ACTIF','2026-09-10 09:47:37','2026-09-10 09:47:37');
/*!40000 ALTER TABLE `eleves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eleves_responsables`
--

DROP TABLE IF EXISTS `eleves_responsables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eleves_responsables` (
  `id_eleve` bigint unsigned NOT NULL,
  `id_responsable` bigint unsigned NOT NULL,
  `lien_parente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_principal` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_eleve`,`id_responsable`),
  KEY `fk_er_responsable` (`id_responsable`),
  CONSTRAINT `fk_er_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_er_responsable` FOREIGN KEY (`id_responsable`) REFERENCES `responsables` (`id_responsable`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eleves_responsables`
--

LOCK TABLES `eleves_responsables` WRITE;
/*!40000 ALTER TABLE `eleves_responsables` DISABLE KEYS */;
/*!40000 ALTER TABLE `eleves_responsables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etablissements`
--

DROP TABLE IF EXISTS `etablissements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etablissements` (
  `id_etablissement` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commune` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `directeur` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('ACTIF','INACTIF') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIF',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_etablissement`),
  UNIQUE KEY `uq_etablissement_code` (`code`),
  UNIQUE KEY `uq_etablissement_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etablissements`
--

LOCK TABLES `etablissements` WRITE;
/*!40000 ALTER TABLE `etablissements` DISABLE KEYS */;
INSERT INTO `etablissements` VALUES (1,'Mon établissement','ETB001','ECOLE','Kinshasa','Kinshasa','Mont-ngafula','Avenue Mateba n°2',NULL,NULL,'Akwenudi',NULL,'ACTIF','2026-08-09 10:45:41','2026-08-13 12:13:26'),(2,'EP NGEMBA DIATA','ETB002','École primaire','Kinshasa',NULL,NULL,NULL,NULL,NULL,NULL,'logos/0cxSED080wapx0p45Bux6SvmMrHugpFOMAgNOPqf.jpg','ACTIF','2026-08-13 13:25:33','2026-08-27 14:35:23');
/*!40000 ALTER TABLE `etablissements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluations` (
  `id_evaluation` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `id_matiere` bigint unsigned NOT NULL,
  `id_periode` bigint unsigned NOT NULL,
  `libelle` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_evaluation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_maximale` decimal(6,2) NOT NULL DEFAULT '20.00',
  `date_evaluation` date DEFAULT NULL,
  PRIMARY KEY (`id_evaluation`),
  KEY `fk_evaluation_annee` (`id_annee_scolaire`),
  KEY `idx_evaluations_classe` (`id_classe`),
  KEY `idx_evaluations_matiere` (`id_matiere`),
  KEY `idx_evaluations_periode` (`id_periode`),
  CONSTRAINT `fk_evaluation_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_evaluation_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_evaluation_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_evaluation_periode` FOREIGN KEY (`id_periode`) REFERENCES `periodes_scolaires` (`id_periode`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_note_maximale` CHECK ((`note_maximale` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluations`
--

LOCK TABLES `evaluations` WRITE;
/*!40000 ALTER TABLE `evaluations` DISABLE KEYS */;
INSERT INTO `evaluations` VALUES (1,1,2,1,1,'Contrôle de Mathématique','Contrôle',25.00,'2026-08-11'),(2,1,2,1,1,'Examen de math','Examen',20.00,'2026-08-11');
/*!40000 ALTER TABLE `evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frais_eleves`
--

DROP TABLE IF EXISTS `frais_eleves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `frais_eleves` (
  `id_frais_eleve` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_eleve` bigint unsigned NOT NULL,
  `id_inscription` bigint unsigned NOT NULL,
  `id_tarif` bigint unsigned NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `remise` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_a_payer` decimal(15,2) NOT NULL,
  `montant_paye` decimal(15,2) NOT NULL DEFAULT '0.00',
  `solde` decimal(15,2) NOT NULL DEFAULT '0.00',
  `statut` enum('NON_PAYE','PARTIEL','SOLDE','ANNULE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON_PAYE',
  PRIMARY KEY (`id_frais_eleve`),
  UNIQUE KEY `uq_frais_eleve` (`id_eleve`,`id_inscription`,`id_tarif`),
  KEY `fk_frais_tarif` (`id_tarif`),
  KEY `idx_frais_eleve` (`id_eleve`),
  KEY `idx_frais_inscription` (`id_inscription`),
  KEY `idx_frais_statut` (`statut`),
  CONSTRAINT `fk_frais_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_frais_inscription` FOREIGN KEY (`id_inscription`) REFERENCES `inscriptions` (`id_inscription`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_frais_tarif` FOREIGN KEY (`id_tarif`) REFERENCES `tarifs_scolaires` (`id_tarif`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_frais_a_payer` CHECK ((`montant_a_payer` >= 0)),
  CONSTRAINT `chk_frais_montant` CHECK ((`montant` >= 0)),
  CONSTRAINT `chk_frais_paye` CHECK ((`montant_paye` >= 0)),
  CONSTRAINT `chk_frais_remise` CHECK ((`remise` >= 0)),
  CONSTRAINT `chk_frais_solde` CHECK ((`solde` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frais_eleves`
--

LOCK TABLES `frais_eleves` WRITE;
/*!40000 ALTER TABLE `frais_eleves` DISABLE KEYS */;
INSERT INTO `frais_eleves` VALUES (1,1,1,1,50.00,0.00,50.00,50.00,0.00,'SOLDE'),(2,5,4,1,50.00,0.00,50.00,50.00,0.00,'SOLDE'),(3,6,5,1,50.00,0.00,50.00,50.00,0.00,'SOLDE'),(4,8,7,1,50.00,0.00,50.00,50.00,0.00,'SOLDE'),(5,7,8,1,50.00,0.00,50.00,50.00,0.00,'SOLDE'),(6,13,9,4,500.00,0.00,500.00,500.00,0.00,'SOLDE'),(7,15,10,1,50.00,0.00,50.00,30.00,20.00,'PARTIEL'),(8,15,10,5,100.00,0.00,100.00,0.00,100.00,'NON_PAYE'),(9,16,11,1,50.00,0.00,50.00,0.00,50.00,'NON_PAYE'),(10,16,11,5,100.00,0.00,100.00,0.00,100.00,'NON_PAYE');
/*!40000 ALTER TABLE `frais_eleves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infrastructures`
--

DROP TABLE IF EXISTS `infrastructures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `infrastructures` (
  `id_infrastructure` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `designation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantite` int unsigned NOT NULL DEFAULT '1',
  `etat` enum('BON','MOYEN','A_REHABILITER','HORS_SERVICE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BON',
  `localisation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_infrastructure`),
  KEY `idx_infrastructures_etablissement` (`id_etablissement`),
  CONSTRAINT `fk_infrastructure_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_infrastructure_quantite` CHECK ((`quantite` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infrastructures`
--

LOCK TABLES `infrastructures` WRITE;
/*!40000 ALTER TABLE `infrastructures` DISABLE KEYS */;
INSERT INTO `infrastructures` VALUES (1,1,'Bâtiment','Salle',1,'BON',NULL,NULL);
/*!40000 ALTER TABLE `infrastructures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscriptions` (
  `id_inscription` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_eleve` bigint unsigned NOT NULL,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `date_inscription` date NOT NULL,
  `statut` enum('INSCRIT','ABANDON','TRANSFERE','RADIE','DIPLOME') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INSCRIT',
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_inscription`),
  UNIQUE KEY `uq_inscription_eleve_annee` (`id_eleve`,`id_annee_scolaire`),
  KEY `idx_inscriptions_eleve` (`id_eleve`),
  KEY `idx_inscriptions_classe` (`id_classe`),
  KEY `idx_inscriptions_annee` (`id_annee_scolaire`),
  KEY `idx_inscriptions_statut` (`statut`),
  CONSTRAINT `fk_inscription_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_inscription_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_inscription_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscriptions`
--

LOCK TABLES `inscriptions` WRITE;
/*!40000 ALTER TABLE `inscriptions` DISABLE KEYS */;
INSERT INTO `inscriptions` VALUES (1,1,1,2,'2026-08-09','INSCRIT',NULL),(4,5,1,2,'2026-08-09','INSCRIT',NULL),(5,6,1,2,'2026-08-10','INSCRIT',NULL),(7,8,1,2,'2026-08-10','INSCRIT',NULL),(8,7,1,2,'2026-08-11','INSCRIT',NULL),(9,13,1,4,'2026-08-16','INSCRIT',NULL),(10,15,1,2,'2026-09-07','INSCRIT',NULL),(11,16,1,2,'2026-09-10','INSCRIT',NULL);
/*!40000 ALTER TABLE `inscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventaire`
--

DROP TABLE IF EXISTS `inventaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaire` (
  `id_inventaire` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `id_categorie` bigint unsigned NOT NULL,
  `designation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int unsigned NOT NULL DEFAULT '1',
  `date_acquisition` date DEFAULT NULL,
  `etat` enum('NEUF','BON','MOYEN','MAUVAIS','HORS_SERVICE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BON',
  `localisation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsable` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_inventaire`),
  KEY `idx_inventaire_etablissement` (`id_etablissement`),
  KEY `idx_inventaire_categorie` (`id_categorie`),
  CONSTRAINT `fk_inventaire_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categories_inventaire` (`id_categorie`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_inventaire_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_inventaire_quantite` CHECK ((`quantite` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaire`
--

LOCK TABLES `inventaire` WRITE;
/*!40000 ALTER TABLE `inventaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journaux_activites`
--

DROP TABLE IF EXISTS `journaux_activites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journaux_activites` (
  `id_journal` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_utilisateur` bigint unsigned DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_concernee` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_enregistrement` bigint unsigned DEFAULT NULL,
  `anciennes_valeurs` json DEFAULT NULL,
  `nouvelles_valeurs` json DEFAULT NULL,
  `adresse_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `navigateur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_heure` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_journal`),
  KEY `idx_journal_utilisateur` (`id_utilisateur`),
  KEY `idx_journal_date` (`date_heure`),
  KEY `idx_journal_table` (`table_concernee`),
  CONSTRAINT `fk_journal_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journaux_activites`
--

LOCK TABLES `journaux_activites` WRITE;
/*!40000 ALTER TABLE `journaux_activites` DISABLE KEYS */;
INSERT INTO `journaux_activites` VALUES (1,1,'Ajout d’un élève','eleves',3,NULL,'{\"nom\": \"TOTO\", \"sexe\": \"F\", \"email\": \"espoirmmuk@gmail.com\", \"prenom\": \"Tete\", \"statut\": \"ACTIF\", \"adresse\": \"1412\", \"postnom\": \"TITI\", \"matricule\": \"0014\", \"telephone\": \"0820607096\", \"date_creation\": \"2026-08-09T15:27:49.134405Z\", \"date_naissance\": \"2026-09-05\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09T15:27:49.134441Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:27:49'),(2,1,'Ajout d’une classe','classes',2,NULL,'{\"statut\": \"ACTIVE\", \"libelle\": \"4ème\", \"capacite\": \"50\", \"id_niveau\": \"3\", \"option_classe\": \"Informatique\", \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:32:26'),(3,1,'Modification d’un élève','eleves',3,'{\"nom\": \"TOTO\", \"sexe\": \"F\", \"email\": \"espoirmmuk@gmail.com\", \"photo\": null, \"prenom\": \"Tete\", \"statut\": \"ACTIF\", \"adresse\": \"1412\", \"postnom\": \"TITI\", \"id_eleve\": 3, \"matricule\": \"0014\", \"telephone\": \"0820607096\", \"date_creation\": \"2026-08-09 15:27:49\", \"date_naissance\": \"2026-09-05\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09 15:27:49\"}','{\"nom\": \"TITO\", \"sexe\": \"F\", \"email\": \"espoirmmuk@gmail.com\", \"prenom\": \"Tete\", \"statut\": \"ACTIF\", \"adresse\": \"1412\", \"postnom\": \"TITI\", \"matricule\": \"0014\", \"telephone\": \"0820607096\", \"date_naissance\": \"2026-09-05\", \"lieu_naissance\": \"Kinshasa\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:41:41'),(4,1,'Suppression d’un élève','eleves',2,'{\"nom\": \"Dupont\", \"sexe\": \"F\", \"email\": null, \"photo\": null, \"prenom\": \"Jeannette\", \"statut\": \"ACTIF\", \"adresse\": \"Kinshasa\", \"postnom\": \"TETE\", \"id_eleve\": 2, \"matricule\": \"0013\", \"telephone\": \"0820607096\", \"date_creation\": \"2026-08-09 15:11:13\", \"date_naissance\": \"2026-08-07\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09 15:11:13\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:46:26'),(5,1,'Modification d’une classe','classes',1,'{\"statut\": \"ACTIVE\", \"libelle\": \"3ème A\", \"capacite\": 55, \"id_classe\": 1, \"id_niveau\": 3, \"option_classe\": \"Scientifique\", \"id_annee_scolaire\": 1}','{\"statut\": \"ACTIVE\", \"libelle\": \"3ème A\", \"capacite\": \"50\", \"id_niveau\": \"3\", \"option_classe\": \"Scientifique\", \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:57:07'),(6,1,'Ajout d’une classe','classes',3,NULL,'{\"statut\": \"ACTIVE\", \"libelle\": \"Crèche\", \"capacite\": \"20\", \"id_niveau\": \"1\", \"option_classe\": \"Crèche\", \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 15:58:56'),(7,1,'Suppression d’une classe','classes',1,'{\"statut\": \"ACTIVE\", \"libelle\": \"3ème A\", \"capacite\": 50, \"id_classe\": 1, \"id_niveau\": 3, \"option_classe\": \"Scientifique\", \"id_annee_scolaire\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 16:00:56'),(8,1,'Modification d’une classe','classes',3,'{\"statut\": \"ACTIVE\", \"libelle\": \"Crèche\", \"capacite\": 20, \"id_classe\": 3, \"id_niveau\": 1, \"option_classe\": \"Crèche\", \"id_annee_scolaire\": 1}','{\"statut\": \"ACTIVE\", \"libelle\": \"Crèche\", \"capacite\": \"23\", \"id_niveau\": \"1\", \"option_classe\": \"Crèche\", \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 16:10:50'),(9,1,'Modification d’un élève','eleves',3,'{\"nom\": \"TITO\", \"sexe\": \"F\", \"email\": \"espoirmmuk@gmail.com\", \"photo\": null, \"prenom\": \"Tete\", \"statut\": \"ACTIF\", \"adresse\": \"1412\", \"postnom\": \"TITI\", \"id_eleve\": 3, \"matricule\": \"0014\", \"telephone\": \"0820607096\", \"date_creation\": \"2026-08-09 15:27:49\", \"date_naissance\": \"2026-09-05\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09 16:41:41\"}','{\"nom\": \"KOMBO\", \"sexe\": \"F\", \"email\": \"espoirmmuk@gmail.com\", \"prenom\": \"Tete\", \"statut\": \"ACTIF\", \"adresse\": \"1412\", \"postnom\": \"TITI\", \"matricule\": \"0014\", \"telephone\": \"0820607096\", \"date_naissance\": \"2026-09-05\", \"lieu_naissance\": \"Kinshasa\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 16:11:16'),(10,1,'Ajout d’un membre du personnel','personnel',1,NULL,'{\"nom\": \"KATANGA\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Joseph\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MBASI\", \"fonction\": \"Enseignant\", \"matricule\": \"ENS-001\", \"telephone\": null, \"id_personnel\": 1, \"qualification\": \"Gradué\", \"date_engagement\": \"2026-08-05 00:00:00\", \"id_etablissement\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 16:36:53'),(11,1,'Ajout d’un paiement','paiements',1,NULL,'{\"devise\": \"USD\", \"id_eleve\": \"1\", \"reference\": null, \"id_paiement\": 1, \"numero_recu\": \"REC-001\", \"observation\": null, \"date_paiement\": \"2026-08-09T20:59:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"500.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 21:00:14'),(12,1,'Ajout d’un élève','eleves',4,NULL,'{\"nom\": \"KIESE\", \"sexe\": \"F\", \"email\": null, \"prenom\": \"Schiphra\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MUKONGO\", \"matricule\": \"ENS-001\", \"telephone\": null, \"date_creation\": \"2026-08-09T23:09:14.823291Z\", \"date_naissance\": \"2026-09-03\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09T23:09:14.823324Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 23:09:14'),(13,1,'Ajout d’un paiement','paiements',2,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"50.00\", \"id_paiement\": 2, \"id_frais_eleve\": 1, \"id_detail_paiement\": 1}], \"id_eleve\": \"1\", \"reference\": null, \"id_paiement\": 2, \"numero_recu\": \"0111\", \"observation\": null, \"date_paiement\": \"2026-08-09T23:31:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 23:33:13'),(14,1,'Ajout d’un élève','eleves',5,NULL,'{\"nom\": \"PAPA\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Fils\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MAMAN\", \"matricule\": \"00025\", \"telephone\": null, \"date_creation\": \"2026-08-09T23:37:19.014280Z\", \"date_naissance\": \"2026-08-19\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09T23:37:19.014324Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 23:37:19'),(15,1,'Ajout d’un paiement','paiements',3,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"50.00\", \"id_paiement\": 3, \"id_frais_eleve\": 2, \"id_detail_paiement\": 2}], \"id_eleve\": \"5\", \"reference\": null, \"id_paiement\": 3, \"numero_recu\": \"0012\", \"observation\": null, \"date_paiement\": \"2026-08-09T23:38:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09 23:38:56'),(16,1,'Ajout d’un paiement','paiements',4,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"30.00\", \"id_paiement\": 4, \"id_frais_eleve\": 2, \"id_detail_paiement\": 3}], \"id_eleve\": 5, \"reference\": null, \"id_paiement\": 4, \"numero_recu\": \"0003\", \"observation\": null, \"date_paiement\": \"2026-08-10T00:37:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"30.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:41:25'),(17,1,'Ajout d’un élève','eleves',6,NULL,'{\"nom\": \"PASCAL\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Pascal\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"PASCAL\", \"matricule\": \"00012\", \"telephone\": null, \"date_creation\": \"2026-08-10T00:47:13.222094Z\", \"date_naissance\": \"2026-08-26\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-10T00:47:13.222129Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:47:13'),(18,1,'Ajout d’un paiement','paiements',5,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"50.00\", \"id_paiement\": 5, \"id_frais_eleve\": 3, \"id_detail_paiement\": 4}], \"id_eleve\": 6, \"reference\": null, \"id_paiement\": 5, \"numero_recu\": \"0016\", \"observation\": null, \"date_paiement\": \"2026-08-10T00:49:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:50:59'),(19,1,'Ajout d’un élève','eleves',7,NULL,'{\"nom\": \"OSCAR\", \"sexe\": \"F\", \"email\": null, \"prenom\": \"OSCAR\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"OSCAR\", \"matricule\": \"000002\", \"telephone\": null, \"date_creation\": \"2026-08-10T00:52:06.000588Z\", \"date_naissance\": \"2026-09-04\", \"lieu_naissance\": null, \"date_modification\": \"2026-08-10T00:52:06.000625Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:52:06'),(20,1,'Ajout d’un élève','eleves',8,NULL,'{\"nom\": \"Lumière\", \"sexe\": \"F\", \"email\": null, \"prenom\": \"Lumière\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Lumière\", \"matricule\": \"0003\", \"telephone\": null, \"date_creation\": \"2026-08-10T00:53:59.398099Z\", \"date_naissance\": \"2026-08-28\", \"lieu_naissance\": null, \"date_modification\": \"2026-08-10T00:53:59.398132Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:53:59'),(21,1,'Ajout d’un paiement','paiements',6,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"50.00\", \"id_paiement\": 6, \"id_frais_eleve\": 4, \"id_detail_paiement\": 5}], \"id_eleve\": 8, \"reference\": null, \"id_paiement\": 6, \"numero_recu\": \"0004\", \"observation\": null, \"date_paiement\": \"2026-08-10T00:54:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 00:55:57'),(22,1,'Modification d’un membre du personnel','personnel',1,'{\"nom\": \"KATANGA\", \"sexe\": \"M\", \"email\": null, \"photo\": null, \"prenom\": \"Joseph\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MBASI\", \"fonction\": \"Enseignant\", \"matricule\": \"ENS-001\", \"telephone\": null, \"id_personnel\": 1, \"date_creation\": \"2026-08-09 17:36:53\", \"qualification\": \"Gradué\", \"date_engagement\": \"2026-08-05\", \"id_etablissement\": 1}','{\"nom\": \"KATANGA\", \"sexe\": \"M\", \"email\": null, \"photo\": null, \"prenom\": \"Joseph\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MBASI\", \"fonction\": \"Enseignant\", \"matricule\": \"ENS-001\", \"telephone\": null, \"qualification\": \"Gradué\", \"date_engagement\": \"2026-08-05\", \"id_etablissement\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-10 23:16:49'),(23,1,'Suppression d’une inscription','inscriptions',3,'{\"statut\": \"INSCRIT\", \"id_eleve\": 4, \"id_classe\": 3, \"observation\": null, \"id_inscription\": 3, \"date_inscription\": \"2026-08-09T00:00:00.000000Z\", \"id_annee_scolaire\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 08:23:47'),(24,1,'Modification d’un tarif scolaire','tarifs_scolaires',2,'{\"devise\": \"USD\", \"montant\": \"400.00\", \"id_tarif\": 2, \"id_classe\": 3, \"id_annee_scolaire\": 1, \"id_categorie_frais\": 2}','{\"devise\": \"USD\", \"montant\": \"350.00\", \"id_tarif\": 2, \"id_classe\": 3, \"id_annee_scolaire\": 1, \"id_categorie_frais\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 08:28:18'),(25,1,'Modification d’une catégorie de frais','categories_frais',6,'{\"statut\": \"ACTIVE\", \"libelle\": \"Activites\", \"description\": \"Activités scolaires\", \"id_categorie_frais\": 6}','{\"statut\": \"ACTIVE\", \"libelle\": \"Activite\", \"description\": \"Activités scolaires\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 08:47:18'),(26,1,'Modification d’une catégorie de frais','categories_frais',6,'{\"statut\": \"ACTIVE\", \"libelle\": \"Activite\", \"description\": \"Activités scolaires\", \"id_categorie_frais\": 6}','{\"statut\": \"ACTIVE\", \"libelle\": \"Activites\", \"description\": \"Activités scolaires\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 08:47:38'),(27,1,'Ajout d’une matière','matieres',1,NULL,'{\"code\": \"MATH\", \"statut\": \"ACTIVE\", \"libelle\": \"Mathématiques\", \"coefficient\": \"5\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 09:23:42'),(28,1,'Modification d’une matière','matieres',1,'{\"code\": \"MATH\", \"statut\": \"ACTIVE\", \"libelle\": \"Mathématiques\", \"id_matiere\": 1, \"coefficient\": \"5.00\"}','{\"code\": \"MATH\", \"statut\": \"ACTIVE\", \"libelle\": \"Mathématiques\", \"coefficient\": \"4\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 09:24:02'),(29,NULL,'Modification d\'une évaluation','evaluations',1,NULL,NULL,NULL,NULL,'2026-08-11 13:26:34'),(30,NULL,'Ajout d\'une évaluation','evaluations',2,NULL,NULL,NULL,NULL,'2026-08-11 13:33:47'),(31,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:06:31'),(32,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:06:31'),(33,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:09:03'),(34,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:09:04'),(35,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:09:16'),(36,1,'Modification d’une note','notes',1,'{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','{\"note\": \"15.00\", \"id_note\": 1, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:15:49'),(37,1,'Ajout d’une note','notes',6,NULL,'{\"note\": \"15\", \"id_note\": 6, \"id_eleve\": \"1\", \"appreciation\": null, \"id_evaluation\": \"2\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:16:38'),(38,1,'Modification d’une note','notes',6,'{\"note\": \"15.00\", \"id_note\": 6, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 2}','{\"note\": \"15.00\", \"id_note\": 6, \"id_eleve\": 1, \"appreciation\": null, \"id_evaluation\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:39:52'),(39,1,'Ajout d’une note','notes',7,NULL,'{\"note\": \"10\", \"id_note\": 7, \"id_eleve\": 5, \"appreciation\": null, \"id_evaluation\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:39:52'),(40,1,'Ajout d’une note','notes',8,NULL,'{\"note\": \"20\", \"id_note\": 8, \"id_eleve\": 6, \"appreciation\": null, \"id_evaluation\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:39:53'),(41,1,'Ajout d’une note','notes',9,NULL,'{\"note\": \"13\", \"id_note\": 9, \"id_eleve\": 8, \"appreciation\": null, \"id_evaluation\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:39:53'),(42,1,'Ajout d’une note','notes',10,NULL,'{\"note\": \"15\", \"id_note\": 10, \"id_eleve\": 7, \"appreciation\": null, \"id_evaluation\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-11 13:39:53'),(43,NULL,'Modification d\'une évaluation','evaluations',1,NULL,NULL,NULL,NULL,'2026-08-11 14:57:50'),(44,2,'Modification d’un élève','eleves',4,'{\"nom\": \"KIESE\", \"sexe\": \"F\", \"email\": null, \"photo\": null, \"prenom\": \"Schiphra\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MUKONGO\", \"id_eleve\": 4, \"matricule\": \"ENS-001\", \"telephone\": null, \"date_creation\": \"2026-08-09 23:09:14\", \"date_naissance\": \"2026-09-03\", \"lieu_naissance\": \"Kinshasa\", \"date_modification\": \"2026-08-09 23:09:14\"}','{\"nom\": \"KIESE\", \"sexe\": \"F\", \"email\": null, \"prenom\": \"Schiphra\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"MUKONGO\", \"matricule\": \"ENS-001\", \"telephone\": null, \"date_naissance\": \"2026-09-03\", \"lieu_naissance\": \"Kinshasa\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-14 10:21:58'),(45,1,'Ajout d’une présence','presences',1,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 8, \"id_classe\": 2, \"id_presence\": 1, \"observation\": null, \"date_presence\": \"2026-08-15 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 10:56:13'),(46,1,'Ajout d’une présence','presences',2,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 1, \"id_classe\": 2, \"id_presence\": 2, \"observation\": null, \"date_presence\": \"2026-08-15 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 10:56:13'),(47,1,'Ajout d’une présence','presences',3,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 7, \"id_classe\": 2, \"id_presence\": 3, \"observation\": null, \"date_presence\": \"2026-08-15 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 10:56:13'),(48,1,'Ajout d’une présence','presences',4,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 5, \"id_classe\": 2, \"id_presence\": 4, \"observation\": null, \"date_presence\": \"2026-08-15 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 10:56:13'),(49,1,'Ajout d’une présence','presences',5,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 6, \"id_classe\": 2, \"id_presence\": 5, \"observation\": null, \"date_presence\": \"2026-08-15 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 10:56:13'),(50,1,'Modification d’une présence','presences',1,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 8, \"id_classe\": 2, \"id_presence\": 1, \"observation\": null, \"date_presence\": \"2026-08-15\"}','{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 8, \"id_classe\": 2, \"id_presence\": 1, \"observation\": null, \"date_presence\": \"2026-08-15\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 11:21:54'),(51,1,'Modification d’une présence','presences',2,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 1, \"id_classe\": 2, \"id_presence\": 2, \"observation\": null, \"date_presence\": \"2026-08-15\"}','{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 1, \"id_classe\": 2, \"id_presence\": 2, \"observation\": null, \"date_presence\": \"2026-08-15\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 11:21:54'),(52,1,'Modification d’une présence','presences',3,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 7, \"id_classe\": 2, \"id_presence\": 3, \"observation\": null, \"date_presence\": \"2026-08-15\"}','{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 7, \"id_classe\": 2, \"id_presence\": 3, \"observation\": null, \"date_presence\": \"2026-08-15\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 11:21:54'),(53,1,'Modification d’une présence','presences',4,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 5, \"id_classe\": 2, \"id_presence\": 4, \"observation\": null, \"date_presence\": \"2026-08-15\"}','{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 5, \"id_classe\": 2, \"id_presence\": 4, \"observation\": null, \"date_presence\": \"2026-08-15\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 11:21:54'),(54,1,'Modification d’une présence','presences',5,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 6, \"id_classe\": 2, \"id_presence\": 5, \"observation\": null, \"date_presence\": \"2026-08-15\"}','{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 6, \"id_classe\": 2, \"id_presence\": 5, \"observation\": null, \"date_presence\": \"2026-08-15\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 11:21:54'),(55,1,'Ajout d’une recette','recettes',1,NULL,'{\"devise\": \"USD\", \"source\": \"Location chaises\", \"montant\": \"50\", \"id_recette\": 1, \"description\": null, \"date_recette\": \"2026-08-15 00:00:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 12:48:43'),(56,1,'Ajout d’une recette','recettes',2,NULL,'{\"devise\": \"USD\", \"source\": \"Location chaises\", \"montant\": \"50\", \"id_recette\": 2, \"description\": null, \"date_recette\": \"2026-08-15 00:00:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 14:18:56'),(57,1,'Suppression d’une recette','recettes',1,'{\"devise\": \"USD\", \"source\": \"Location chaises\", \"montant\": \"50.00\", \"id_recette\": 1, \"description\": null, \"date_recette\": \"2026-08-15 00:00:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 14:20:40'),(58,1,'Ajout d’une dépense','depenses',1,NULL,'{\"devise\": \"USD\", \"montant\": \"50\", \"categorie\": \"Transport\", \"id_depense\": 1, \"description\": null, \"date_depense\": \"2026-08-15 22:31:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 22:32:01'),(59,1,'Modification d’une dépense','depenses',1,'{\"devise\": \"USD\", \"montant\": \"50.00\", \"categorie\": \"Transport\", \"id_depense\": 1, \"description\": null, \"date_depense\": \"2026-08-15 22:31:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": 1}','{\"devise\": \"USD\", \"montant\": \"45.00\", \"categorie\": \"Transport\", \"id_depense\": 1, \"description\": null, \"date_depense\": \"2026-08-15 22:31:00\", \"id_utilisateur\": 1, \"id_etablissement\": 1, \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 22:32:14'),(60,1,'Ajout d’une infrastructure','infrastructures',1,NULL,'{\"etat\": \"BON\", \"type\": \"Salle\", \"quantite\": \"1\", \"designation\": \"Bâtiment\", \"observation\": null, \"localisation\": null, \"id_etablissement\": 1, \"id_infrastructure\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 23:03:08'),(61,1,'Modification d’une infrastructure','infrastructures',1,'{\"etat\": \"BON\", \"type\": \"Salle\", \"quantite\": 1, \"designation\": \"Bâtiment\", \"observation\": null, \"localisation\": null, \"id_etablissement\": 1, \"id_infrastructure\": 1}','{\"etat\": \"BON\", \"type\": \"Salle\", \"quantite\": \"1\", \"designation\": \"Bâtiment\", \"observation\": null, \"localisation\": null, \"id_etablissement\": 1, \"id_infrastructure\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-15 23:04:39'),(62,1,'Ajout d’un paiement','paiements',7,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"50.00\", \"id_paiement\": 7, \"id_frais_eleve\": 5, \"id_detail_paiement\": 6}], \"id_eleve\": 7, \"reference\": null, \"id_paiement\": 7, \"numero_recu\": \"REC-002\", \"observation\": null, \"date_paiement\": \"2026-08-16T00:23:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:24:20'),(63,1,'Suppression d’un paiement','paiements',7,'{\"devise\": \"USD\", \"id_eleve\": 7, \"reference\": null, \"id_paiement\": 7, \"numero_recu\": \"REC-002\", \"observation\": null, \"date_paiement\": \"2026-08-16T00:23:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"50.00\", \"id_utilisateur\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:37:12'),(64,1,'Ajout d’un élève','eleves',9,NULL,'{\"nom\": \"T\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"T\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"T\", \"matricule\": \"0112\", \"telephone\": null, \"date_creation\": \"2026-08-16T00:38:39.952871Z\", \"date_naissance\": \"2026-07-30\", \"lieu_naissance\": null, \"id_etablissement\": 1, \"date_modification\": \"2026-08-16T00:38:39.952906Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:38:39'),(65,1,'Ajout d’un élève','eleves',10,NULL,'{\"nom\": \"M\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"M\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"M\", \"matricule\": \"01231\", \"telephone\": null, \"date_creation\": \"2026-08-16T00:41:35.417682Z\", \"date_naissance\": \"2026-01-02\", \"lieu_naissance\": null, \"id_etablissement\": 1, \"date_modification\": \"2026-08-16T00:41:35.417718Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:41:35'),(66,2,'Ajout d’un élève','eleves',12,NULL,'{\"nom\": \"Mon\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Mon\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Mon\", \"matricule\": \"12345\", \"telephone\": null, \"date_creation\": \"2026-08-16T00:44:02.940726Z\", \"date_naissance\": \"2026-08-24\", \"lieu_naissance\": null, \"id_etablissement\": 2, \"date_modification\": \"2026-08-16T00:44:02.940762Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:44:02'),(67,1,'Ajout d’une classe','classes',4,NULL,'{\"statut\": \"ACTIVE\", \"libelle\": \"1ère\", \"capacite\": \"50\", \"id_niveau\": \"2\", \"option_classe\": \"Crèche\", \"id_etablissement\": 1, \"id_annee_scolaire\": \"1\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:46:11'),(68,1,'Ajout d’un élève','eleves',13,NULL,'{\"nom\": \"R\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"R\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"R\", \"matricule\": \"789\", \"telephone\": null, \"date_creation\": \"2026-08-16T00:50:41.198864Z\", \"date_naissance\": \"2026-08-18\", \"lieu_naissance\": null, \"id_etablissement\": 1, \"date_modification\": \"2026-08-16T00:50:41.198901Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:50:41'),(69,1,'Ajout d’un tarif scolaire','tarifs_scolaires',4,NULL,'{\"devise\": \"USD\", \"montant\": \"500.00\", \"id_tarif\": 4, \"id_classe\": 4, \"id_annee_scolaire\": 1, \"id_categorie_frais\": 2}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:51:35'),(70,1,'Ajout d’une inscription','inscriptions',9,NULL,'{\"statut\": \"INSCRIT\", \"id_eleve\": 13, \"id_classe\": 4, \"observation\": null, \"id_inscription\": 9, \"date_inscription\": \"2026-08-16T00:00:00.000000Z\", \"id_annee_scolaire\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:51:58'),(71,1,'Ajout d’un paiement','paiements',8,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"500.00\", \"id_paiement\": 8, \"id_frais_eleve\": 6, \"id_detail_paiement\": 7}], \"id_eleve\": 13, \"reference\": null, \"id_paiement\": 8, \"numero_recu\": \"0258\", \"observation\": null, \"date_paiement\": \"2026-08-16T00:52:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"500.00\", \"id_utilisateur\": 1}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-16 00:52:31'),(72,1,'Ajout d’une présence','presences',6,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 13, \"id_classe\": 4, \"id_presence\": 6, \"observation\": null, \"date_presence\": \"2026-08-18 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:34:34'),(73,1,'Ajout d’une présence','presences',7,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 8, \"id_classe\": 2, \"id_presence\": 7, \"observation\": null, \"date_presence\": \"2026-09-03 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:58:01'),(74,1,'Ajout d’une présence','presences',8,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 1, \"id_classe\": 2, \"id_presence\": 8, \"observation\": null, \"date_presence\": \"2026-09-03 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:58:01'),(75,1,'Ajout d’une présence','presences',9,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 7, \"id_classe\": 2, \"id_presence\": 9, \"observation\": null, \"date_presence\": \"2026-09-03 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:58:01'),(76,1,'Ajout d’une présence','presences',10,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 5, \"id_classe\": 2, \"id_presence\": 10, \"observation\": null, \"date_presence\": \"2026-09-03 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:58:01'),(77,1,'Ajout d’une présence','presences',11,NULL,'{\"motif\": null, \"statut\": \"PRESENT\", \"id_eleve\": 6, \"id_classe\": 2, \"id_presence\": 11, \"observation\": null, \"date_presence\": \"2026-09-03 00:00:00\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:58:01'),(78,1,'Suppression d’une inscription','inscriptions',2,'{\"statut\": \"INSCRIT\", \"id_eleve\": 3, \"id_classe\": 3, \"observation\": null, \"id_inscription\": 2, \"date_inscription\": \"2026-08-09T00:00:00.000000Z\", \"id_annee_scolaire\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-22 08:26:17'),(79,2,'Ajout d’un élève','eleves',14,NULL,'{\"nom\": \"Esp\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Esp\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Esp\", \"matricule\": \"on01\", \"telephone\": null, \"date_creation\": \"2026-08-22T10:54:56.733673Z\", \"date_naissance\": \"2026-08-27\", \"lieu_naissance\": null, \"id_etablissement\": 2, \"date_modification\": \"2026-08-22T10:54:56.733712Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-22 10:54:56'),(80,2,'Modification d’un élève','eleves',14,'{\"nom\": \"Esp\", \"sexe\": \"M\", \"email\": null, \"photo\": null, \"prenom\": \"Esp\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Esp\", \"id_eleve\": 14, \"matricule\": \"on01\", \"telephone\": null, \"date_creation\": \"2026-08-22 10:54:56\", \"date_naissance\": \"2026-08-27\", \"lieu_naissance\": null, \"id_etablissement\": 2, \"date_modification\": \"2026-08-22 10:54:56\"}','{\"nom\": \"Esp\", \"sexe\": \"M\", \"email\": null, \"prenom\": \"Esp\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Esp\", \"matricule\": \"on01\", \"telephone\": null, \"date_naissance\": \"2026-08-20\", \"lieu_naissance\": null, \"date_modification\": \"2026-08-22T10:55:09.874320Z\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-22 10:55:09'),(81,2,'Suppression d’un élève','eleves',14,'{\"nom\": \"Esp\", \"sexe\": \"M\", \"email\": null, \"photo\": null, \"prenom\": \"Esp\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Esp\", \"id_eleve\": 14, \"matricule\": \"on01\", \"telephone\": null, \"date_creation\": \"2026-08-22 10:54:56\", \"date_naissance\": \"2026-08-20\", \"lieu_naissance\": null, \"id_etablissement\": 2, \"date_modification\": \"2026-08-22 10:55:09\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-22 10:55:12'),(82,2,'Ajout d’un tarif scolaire','tarifs_scolaires',5,NULL,'{\"devise\": \"USD\", \"montant\": \"100.00\", \"id_tarif\": 5, \"id_classe\": 2, \"id_annee_scolaire\": 1, \"id_categorie_frais\": 6}','41.243.60.155','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-09-01 08:13:02'),(83,2,'Modification d’une inscription','inscriptions',8,'{\"statut\": \"INSCRIT\", \"id_eleve\": 7, \"id_classe\": 2, \"observation\": null, \"id_inscription\": 8, \"date_inscription\": \"2026-08-11T00:00:00.000000Z\", \"id_annee_scolaire\": 1}','{\"statut\": \"INSCRIT\", \"id_eleve\": 7, \"id_classe\": 2, \"observation\": null, \"id_inscription\": 8, \"date_inscription\": \"2026-08-11T00:00:00.000000Z\", \"id_annee_scolaire\": 1}','41.243.60.155','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-09-01 08:16:05'),(84,2,'Suppression d’un paiement','paiements',8,'{\"devise\": \"USD\", \"id_eleve\": 13, \"reference\": null, \"id_paiement\": 8, \"numero_recu\": \"0258\", \"observation\": null, \"date_paiement\": \"2026-08-16T00:52:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"500.00\", \"id_utilisateur\": 1}',NULL,'41.243.60.155','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-09-01 08:17:38'),(85,2,'Ajout d’un élève','eleves',15,NULL,'{\"nom\": \"Mukongo\", \"sexe\": \"F\", \"email\": null, \"prenom\": \"Schiphra\", \"statut\": \"ACTIF\", \"adresse\": null, \"postnom\": \"Kiese\", \"matricule\": \"0023\", \"telephone\": null, \"date_creation\": \"2026-09-07T09:59:16.089207Z\", \"date_naissance\": \"2026-02-25\", \"lieu_naissance\": \"Kinshasa\", \"id_etablissement\": 1, \"date_modification\": \"2026-09-07T09:59:16.089218Z\"}','169.159.210.107','Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1','2026-09-07 09:59:16'),(86,2,'Ajout d’une inscription','inscriptions',10,NULL,'{\"statut\": \"INSCRIT\", \"id_eleve\": 15, \"id_classe\": 2, \"observation\": null, \"id_inscription\": 10, \"date_inscription\": \"2026-09-07T00:00:00.000000Z\", \"id_annee_scolaire\": 1}','169.159.210.107','Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1','2026-09-07 09:59:57'),(87,2,'Ajout d’un paiement','paiements',9,NULL,'{\"devise\": \"USD\", \"details\": [{\"montant\": \"30.00\", \"id_paiement\": 9, \"id_frais_eleve\": 7, \"id_detail_paiement\": 8}], \"id_eleve\": 15, \"reference\": null, \"id_paiement\": 9, \"numero_recu\": \"REC0023\", \"observation\": null, \"date_paiement\": \"2026-09-07T10:02:00.000000Z\", \"mode_paiement\": \"ESPECES\", \"montant_total\": \"30.00\", \"id_utilisateur\": 2}','169.159.211.107','Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1','2026-09-07 10:06:39'),(88,2,'Ajout d’un élève','eleves',16,NULL,'{\"nom\": \"MUK\", \"sexe\": \"M\", \"email\": \"espoirmmuk@gmail.com\", \"prenom\": \"Esp\", \"statut\": \"ACTIF\", \"adresse\": \"Avenue Mateba n°2\", \"postnom\": \"KIV\", \"matricule\": \"000123\", \"telephone\": \"+243 820607096\", \"date_creation\": \"2026-09-10T09:47:37.707874Z\", \"date_naissance\": \"1995-08-05\", \"lieu_naissance\": \"Feshi\", \"id_etablissement\": 1, \"date_modification\": \"2026-09-10T09:47:37.707882Z\"}','41.243.60.151','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 09:47:38'),(89,2,'Ajout d’une inscription','inscriptions',11,NULL,'{\"statut\": \"INSCRIT\", \"id_eleve\": 16, \"id_classe\": 2, \"observation\": null, \"id_inscription\": 11, \"date_inscription\": \"2026-09-10T00:00:00.000000Z\", \"id_annee_scolaire\": 1}','41.243.60.151','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 09:48:44');
/*!40000 ALTER TABLE `journaux_activites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matieres` (
  `id_matiere` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coefficient` decimal(5,2) NOT NULL DEFAULT '1.00',
  `statut` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id_matiere`),
  UNIQUE KEY `uq_matiere_code` (`code`),
  UNIQUE KEY `uq_matiere_libelle` (`libelle`),
  KEY `matieres_id_etablissement_foreign` (`id_etablissement`),
  CONSTRAINT `matieres_id_etablissement_foreign` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE RESTRICT,
  CONSTRAINT `chk_matiere_coefficient` CHECK ((`coefficient` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matieres`
--

LOCK TABLES `matieres` WRITE;
/*!40000 ALTER TABLE `matieres` DISABLE KEYS */;
INSERT INTO `matieres` VALUES (1,1,'MATH','Mathématiques',4.00,'ACTIVE');
/*!40000 ALTER TABLE `matieres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'2026_08_10_194617_add_id_etablissement_to_users_table',2),(3,'0001_01_01_000001_create_cache_table',3),(4,'0001_01_01_000002_create_jobs_table',3),(5,'2026_08_13_145443_add_module_action_to_permissions_table',4),(6,'2026_08_14_103423_add_id_etablissement_to_eleves_table',5),(7,'2026_08_14_104459_add_id_etablissement_to_classes_table',6),(8,'2026_08_14_115153_add_id_etablissement_to_categories_frais_table',7),(9,'2026_08_15_094805_add_id_etablissement_to_matieres_table',8),(10,'2026_08_16_002739_add_id_paiement_to_recettes_table',9),(11,'2026_08_26_121414_update_affectations_enseignants_table',10),(12,'2026_09_10_100601_ajouter_cloture_aux_annees_scolaires_table',11),(13,'2026_09_24_155153_create_contacts_table',12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `niveaux`
--

DROP TABLE IF EXISTS `niveaux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `niveaux` (
  `id_niveau` bigint unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_niveau`),
  UNIQUE KEY `uq_niveau_libelle` (`libelle`),
  CONSTRAINT `chk_niveau_ordre` CHECK ((`ordre` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `niveaux`
--

LOCK TABLES `niveaux` WRITE;
/*!40000 ALTER TABLE `niveaux` DISABLE KEYS */;
INSERT INTO `niveaux` VALUES (1,'Maternelle',1),(2,'Primaire',2),(3,'Secondaire',3);
/*!40000 ALTER TABLE `niveaux` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes` (
  `id_note` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_evaluation` bigint unsigned NOT NULL,
  `id_eleve` bigint unsigned NOT NULL,
  `note` decimal(6,2) NOT NULL,
  `appreciation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_note`),
  UNIQUE KEY `uq_note_evaluation_eleve` (`id_evaluation`,`id_eleve`),
  KEY `idx_notes_eleve` (`id_eleve`),
  KEY `idx_notes_evaluation` (`id_evaluation`),
  CONSTRAINT `fk_note_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_note_evaluation` FOREIGN KEY (`id_evaluation`) REFERENCES `evaluations` (`id_evaluation`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_note_positive` CHECK ((`note` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (1,1,1,15.00,NULL),(2,1,5,6.00,NULL),(3,1,6,10.00,NULL),(4,1,8,18.00,NULL),(5,1,7,11.99,NULL),(6,2,1,15.00,NULL),(7,2,5,10.00,NULL),(8,2,6,20.00,NULL),(9,2,8,13.00,NULL),(10,2,7,15.00,NULL);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiements` (
  `id_paiement` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_eleve` bigint unsigned NOT NULL,
  `numero_recu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_paiement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `montant_total` decimal(15,2) NOT NULL,
  `devise` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `mode_paiement` enum('ESPECES','BANQUE','MOBILE_MONEY','CHEQUE','AUTRE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ESPECES',
  `reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_utilisateur` bigint unsigned DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_paiement`),
  UNIQUE KEY `uq_numero_recu` (`numero_recu`),
  KEY `idx_paiements_eleve` (`id_eleve`),
  KEY `idx_paiements_date` (`date_paiement`),
  KEY `idx_paiements_utilisateur` (`id_utilisateur`),
  CONSTRAINT `fk_paiement_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_paiement_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_paiement_devise` CHECK ((`devise` in (_utf8mb4'USD',_utf8mb4'CDF'))),
  CONSTRAINT `chk_paiement_montant` CHECK ((`montant_total` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiements`
--

LOCK TABLES `paiements` WRITE;
/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
INSERT INTO `paiements` VALUES (1,1,'REC-001','2026-08-09 20:59:00',500.00,'USD','ESPECES',NULL,1,NULL),(2,1,'0111','2026-08-09 23:31:00',50.00,'USD','ESPECES',NULL,1,NULL),(3,5,'0012','2026-08-09 23:38:00',50.00,'USD','ESPECES',NULL,1,NULL),(4,5,'0003','2026-08-10 00:37:00',30.00,'USD','ESPECES',NULL,1,NULL),(5,6,'0016','2026-08-10 00:49:00',50.00,'USD','ESPECES',NULL,1,NULL),(6,8,'0004','2026-08-10 00:54:00',50.00,'USD','ESPECES',NULL,1,NULL),(9,15,'REC0023','2026-09-07 10:02:00',30.00,'USD','ESPECES',NULL,2,NULL);
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodes_scolaires`
--

DROP TABLE IF EXISTS `periodes_scolaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodes_scolaires` (
  `id_periode` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `libelle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  PRIMARY KEY (`id_periode`),
  UNIQUE KEY `uq_periode_annee` (`id_annee_scolaire`,`libelle`),
  KEY `idx_periode_annee` (`id_annee_scolaire`),
  CONSTRAINT `fk_periode_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_periode_dates` CHECK ((`date_fin` > `date_debut`))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodes_scolaires`
--

LOCK TABLES `periodes_scolaires` WRITE;
/*!40000 ALTER TABLE `periodes_scolaires` DISABLE KEYS */;
INSERT INTO `periodes_scolaires` VALUES (1,1,'1er trimestre','2026-09-01','2026-11-11');
/*!40000 ALTER TABLE `periodes_scolaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id_permission` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_permission`),
  UNIQUE KEY `uq_permission_nom` (`nom`),
  KEY `permissions_module_action_index` (`module`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'tableau_bord','voir','voir_tableau_bord','Consulter le tableau de bord'),(2,NULL,NULL,'gerer_etablissements','Gérer les établissements'),(3,NULL,NULL,'gerer_annees_scolaires','Gérer les années scolaires'),(4,NULL,NULL,'gerer_eleves','Gérer les élèves'),(5,NULL,NULL,'gerer_inscriptions','Gérer les inscriptions'),(6,NULL,NULL,'gerer_responsables','Gérer les responsables'),(7,NULL,NULL,'gerer_classes','Gérer les classes'),(8,NULL,NULL,'gerer_matieres','Gérer les matières'),(9,NULL,NULL,'gerer_personnel','Gérer le personnel'),(10,NULL,NULL,'gerer_enseignants','Gérer les affectations des enseignants'),(11,NULL,NULL,'gerer_evaluations','Gérer les évaluations'),(12,NULL,NULL,'gerer_notes','Saisir et modifier les notes'),(13,NULL,NULL,'gerer_bulletins','Gérer les bulletins'),(14,NULL,NULL,'gerer_presences','Gérer les présences'),(15,NULL,NULL,'gerer_frais','Gérer les frais scolaires'),(16,NULL,NULL,'gerer_paiements','Gérer les paiements'),(17,NULL,NULL,'gerer_recettes','Gérer les recettes'),(18,NULL,NULL,'gerer_depenses','Gérer les dépenses'),(19,NULL,NULL,'gerer_activites','Gérer les activités'),(20,NULL,NULL,'gerer_inventaire','Gérer l’inventaire'),(21,NULL,NULL,'gerer_infrastructures','Gérer les infrastructures'),(22,'rapports','voir','voir_rapports','Consulter les rapports'),(23,NULL,NULL,'exporter_donnees','Exporter les données'),(24,NULL,NULL,'gerer_utilisateurs','Gérer les utilisateurs'),(25,NULL,NULL,'gerer_roles','Gérer les rôles'),(26,'journaux','voir','voir_journaux','Consulter les journaux d’activités'),(27,'etablissements','voir','voir_etablissements','Consulter les établissements'),(28,'etablissements','ajouter','ajouter_etablissements','Ajouter un établissement'),(29,'etablissements','modifier','modifier_etablissements','Modifier un établissement'),(30,'etablissements','supprimer','supprimer_etablissements','Supprimer un établissement'),(31,'annees_scolaires','voir','voir_annees_scolaires','Consulter les années scolaires'),(32,'annees_scolaires','ajouter','ajouter_annees_scolaires','Ajouter une année scolaire'),(33,'annees_scolaires','modifier','modifier_annees_scolaires','Modifier une année scolaire'),(34,'annees_scolaires','supprimer','supprimer_annees_scolaires','Supprimer une année scolaire'),(35,'eleves','voir','voir_eleves','Consulter les élèves'),(36,'eleves','ajouter','ajouter_eleves','Ajouter un élève'),(37,'eleves','modifier','modifier_eleves','Modifier un élève'),(38,'eleves','supprimer','supprimer_eleves','Supprimer un élève'),(39,'eleves','exporter','exporter_eleves','Exporter les élèves'),(40,'responsables','voir','voir_responsables','Consulter les responsables'),(41,'responsables','ajouter','ajouter_responsables','Ajouter un responsable'),(42,'responsables','modifier','modifier_responsables','Modifier un responsable'),(43,'responsables','supprimer','supprimer_responsables','Supprimer un responsable'),(44,'inscriptions','voir','voir_inscriptions','Consulter les inscriptions'),(45,'inscriptions','ajouter','ajouter_inscriptions','Ajouter une inscription'),(46,'inscriptions','modifier','modifier_inscriptions','Modifier une inscription'),(47,'inscriptions','supprimer','supprimer_inscriptions','Supprimer une inscription'),(48,'inscriptions','imprimer','imprimer_inscriptions','Imprimer les inscriptions'),(49,'classes','voir','voir_classes','Consulter les classes'),(50,'classes','ajouter','ajouter_classes','Ajouter une classe'),(51,'classes','modifier','modifier_classes','Modifier une classe'),(52,'classes','supprimer','supprimer_classes','Supprimer une classe'),(53,'matieres','voir','voir_matieres','Consulter les matières'),(54,'matieres','ajouter','ajouter_matieres','Ajouter une matière'),(55,'matieres','modifier','modifier_matieres','Modifier une matière'),(56,'matieres','supprimer','supprimer_matieres','Supprimer une matière'),(57,'personnel','voir','voir_personnel','Consulter le personnel'),(58,'personnel','ajouter','ajouter_personnel','Ajouter un membre du personnel'),(59,'personnel','modifier','modifier_personnel','Modifier un membre du personnel'),(60,'personnel','supprimer','supprimer_personnel','Supprimer un membre du personnel'),(61,'enseignants','voir','voir_enseignants','Consulter les enseignants'),(62,'enseignants','ajouter','ajouter_enseignants','Ajouter un enseignant'),(63,'enseignants','modifier','modifier_enseignants','Modifier un enseignant'),(64,'enseignants','supprimer','supprimer_enseignants','Supprimer un enseignant'),(65,'evaluations','voir','voir_evaluations','Consulter les évaluations'),(66,'evaluations','ajouter','ajouter_evaluations','Ajouter une évaluation'),(67,'evaluations','modifier','modifier_evaluations','Modifier une évaluation'),(68,'evaluations','supprimer','supprimer_evaluations','Supprimer une évaluation'),(69,'notes','voir','voir_notes','Consulter les notes'),(70,'notes','ajouter','ajouter_notes','Ajouter des notes'),(71,'notes','modifier','modifier_notes','Modifier des notes'),(72,'notes','supprimer','supprimer_notes','Supprimer des notes'),(73,'notes','exporter','exporter_notes','Exporter les notes'),(74,'bulletins','voir','voir_bulletins','Consulter les bulletins'),(75,'bulletins','generer','generer_bulletins','Générer les bulletins'),(76,'bulletins','modifier','modifier_bulletins','Modifier les bulletins'),(77,'bulletins','imprimer','imprimer_bulletins','Imprimer les bulletins'),(78,'bulletins','exporter','exporter_bulletins','Exporter les bulletins'),(79,'presences','voir','voir_presences','Consulter les présences'),(80,'presences','ajouter','ajouter_presences','Enregistrer les présences'),(81,'presences','modifier','modifier_presences','Modifier les présences'),(82,'presences','supprimer','supprimer_presences','Supprimer les présences'),(83,'presences','exporter','exporter_presences','Exporter les présences'),(84,'frais','voir','voir_frais','Consulter les frais scolaires'),(85,'frais','ajouter','ajouter_frais','Ajouter des frais scolaires'),(86,'frais','modifier','modifier_frais','Modifier les frais scolaires'),(87,'frais','supprimer','supprimer_frais','Supprimer les frais scolaires'),(88,'paiements','voir','voir_paiements','Consulter les paiements'),(89,'paiements','ajouter','ajouter_paiements','Enregistrer un paiement'),(90,'paiements','modifier','modifier_paiements','Modifier un paiement'),(91,'paiements','supprimer','supprimer_paiements','Supprimer un paiement'),(92,'paiements','imprimer','imprimer_paiements','Imprimer les reçus de paiement'),(93,'paiements','exporter','exporter_paiements','Exporter les paiements'),(94,'recettes','voir','voir_recettes','Consulter les recettes'),(95,'recettes','ajouter','ajouter_recettes','Ajouter une recette'),(96,'recettes','modifier','modifier_recettes','Modifier une recette'),(97,'recettes','supprimer','supprimer_recettes','Supprimer une recette'),(98,'recettes','exporter','exporter_recettes','Exporter les recettes'),(99,'depenses','voir','voir_depenses','Consulter les dépenses'),(100,'depenses','ajouter','ajouter_depenses','Ajouter une dépense'),(101,'depenses','modifier','modifier_depenses','Modifier une dépense'),(102,'depenses','supprimer','supprimer_depenses','Supprimer une dépense'),(103,'depenses','exporter','exporter_depenses','Exporter les dépenses'),(104,'tarifs_scolaires','voir','voir_tarifs_scolaires','Consulter les tarifs scolaires'),(105,'tarifs_scolaires','ajouter','ajouter_tarifs_scolaires','Ajouter un tarif scolaire'),(106,'tarifs_scolaires','modifier','modifier_tarifs_scolaires','Modifier un tarif scolaire'),(107,'tarifs_scolaires','supprimer','supprimer_tarifs_scolaires','Supprimer un tarif scolaire'),(108,'categories_frais','voir','voir_categories_frais','Consulter les catégories de frais'),(109,'categories_frais','ajouter','ajouter_categories_frais','Ajouter une catégorie de frais'),(110,'categories_frais','modifier','modifier_categories_frais','Modifier une catégorie de frais'),(111,'categories_frais','supprimer','supprimer_categories_frais','Supprimer une catégorie de frais'),(112,'periodes_scolaires','voir','voir_periodes_scolaires','Consulter les périodes scolaires'),(113,'periodes_scolaires','ajouter','ajouter_periodes_scolaires','Ajouter une période scolaire'),(114,'periodes_scolaires','modifier','modifier_periodes_scolaires','Modifier une période scolaire'),(115,'periodes_scolaires','supprimer','supprimer_periodes_scolaires','Supprimer une période scolaire'),(116,'utilisateurs','voir','voir_utilisateurs','Consulter les utilisateurs'),(117,'utilisateurs','ajouter','ajouter_utilisateurs','Ajouter un utilisateur'),(118,'utilisateurs','modifier','modifier_utilisateurs','Modifier un utilisateur'),(119,'utilisateurs','supprimer','supprimer_utilisateurs','Supprimer un utilisateur'),(120,'roles','voir','voir_roles','Consulter les rôles'),(121,'roles','ajouter','ajouter_roles','Ajouter un rôle'),(122,'roles','modifier','modifier_roles','Modifier un rôle'),(123,'roles','supprimer','supprimer_roles','Supprimer un rôle'),(124,'activites','voir','voir_activites','Consulter les activités'),(125,'journaux','exporter','exporter_journaux','Exporter les journaux d’activités'),(126,'inventaire','voir','voir_inventaire','Consulter l’inventaire'),(127,'inventaire','ajouter','ajouter_inventaire','Ajouter un élément à l’inventaire'),(128,'inventaire','modifier','modifier_inventaire','Modifier un élément de l’inventaire'),(129,'inventaire','supprimer','supprimer_inventaire','Supprimer un élément de l’inventaire'),(130,'infrastructures','voir','voir_infrastructures','Consulter les infrastructures'),(131,'infrastructures','ajouter','ajouter_infrastructures','Ajouter une infrastructure'),(132,'infrastructures','modifier','modifier_infrastructures','Modifier une infrastructure'),(133,'infrastructures','supprimer','supprimer_infrastructures','Supprimer une infrastructure'),(134,'rapports','exporter','exporter_rapports','Exporter les rapports');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personnel` (
  `id_personnel` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned NOT NULL,
  `matricule` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postnom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe` enum('M','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fonction` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_engagement` date DEFAULT NULL,
  `statut` enum('ACTIF','INACTIF','SUSPENDU') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIF',
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_personnel`),
  UNIQUE KEY `uq_personnel_matricule` (`id_etablissement`,`matricule`),
  KEY `idx_personnel_nom` (`nom`,`postnom`,`prenom`),
  KEY `idx_personnel_fonction` (`fonction`),
  CONSTRAINT `fk_personnel_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnel`
--

LOCK TABLES `personnel` WRITE;
/*!40000 ALTER TABLE `personnel` DISABLE KEYS */;
INSERT INTO `personnel` VALUES (1,1,'ENS-001','KATANGA','MBASI','Joseph','M','Enseignant','Gradué',NULL,NULL,NULL,'2026-08-05','ACTIF',NULL,'2026-08-09 16:36:53');
/*!40000 ALTER TABLE `personnel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presences`
--

DROP TABLE IF EXISTS `presences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presences` (
  `id_presence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_eleve` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `date_presence` date NOT NULL,
  `statut` enum('PRESENT','ABSENT','JUSTIFIE','RETARD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_presence`),
  UNIQUE KEY `uq_presence_eleve_date` (`id_eleve`,`date_presence`),
  KEY `idx_presence_date` (`date_presence`),
  KEY `idx_presence_classe` (`id_classe`),
  CONSTRAINT `fk_presence_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presence_eleve` FOREIGN KEY (`id_eleve`) REFERENCES `eleves` (`id_eleve`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presences`
--

LOCK TABLES `presences` WRITE;
/*!40000 ALTER TABLE `presences` DISABLE KEYS */;
INSERT INTO `presences` VALUES (1,8,2,'2026-08-15','PRESENT',NULL,NULL),(2,1,2,'2026-08-15','PRESENT',NULL,NULL),(3,7,2,'2026-08-15','PRESENT',NULL,NULL),(4,5,2,'2026-08-15','PRESENT',NULL,NULL),(5,6,2,'2026-08-15','PRESENT',NULL,NULL),(6,13,4,'2026-08-18','PRESENT',NULL,NULL),(7,8,2,'2026-09-03','PRESENT',NULL,NULL),(8,1,2,'2026-09-03','PRESENT',NULL,NULL),(9,7,2,'2026-09-03','PRESENT',NULL,NULL),(10,5,2,'2026-09-03','PRESENT',NULL,NULL),(11,6,2,'2026-09-03','PRESENT',NULL,NULL);
/*!40000 ALTER TABLE `presences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recettes`
--

DROP TABLE IF EXISTS `recettes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recettes` (
  `id_recette` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_paiement` bigint unsigned DEFAULT NULL,
  `id_etablissement` bigint unsigned NOT NULL,
  `id_annee_scolaire` bigint unsigned DEFAULT NULL,
  `date_recette` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `source` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `devise` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_utilisateur` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_recette`),
  UNIQUE KEY `recettes_id_paiement_unique` (`id_paiement`),
  KEY `fk_recette_utilisateur` (`id_utilisateur`),
  KEY `idx_recettes_date` (`date_recette`),
  KEY `idx_recettes_etablissement` (`id_etablissement`),
  KEY `idx_recettes_annee` (`id_annee_scolaire`),
  CONSTRAINT `fk_recette_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_recette_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_recette_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_recette_devise` CHECK ((`devise` in (_utf8mb4'USD',_utf8mb4'CDF'))),
  CONSTRAINT `chk_recette_montant` CHECK ((`montant` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recettes`
--

LOCK TABLES `recettes` WRITE;
/*!40000 ALTER TABLE `recettes` DISABLE KEYS */;
INSERT INTO `recettes` VALUES (2,NULL,1,1,'2026-08-15 00:00:00','Location chaises',50.00,'USD',NULL,1),(3,8,1,1,'2026-08-16 00:52:00','Paiement élève - Reçu 0258',500.00,'USD','Paiement de l’élève R R R',1),(4,9,1,1,'2026-09-07 10:02:00','Paiement élève - Reçu REC0023',30.00,'USD','Paiement de l’élève Mukongo Kiese Schiphra',2);
/*!40000 ALTER TABLE `recettes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responsables`
--

DROP TABLE IF EXISTS `responsables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responsables` (
  `id_responsable` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postnom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_responsable`),
  KEY `idx_responsables_nom` (`nom`,`postnom`,`prenom`),
  CONSTRAINT `chk_responsable_email` CHECK (((`email` is null) or (`email` like _utf8mb4'%@%')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responsables`
--

LOCK TABLES `responsables` WRITE;
/*!40000 ALTER TABLE `responsables` DISABLE KEYS */;
/*!40000 ALTER TABLE `responsables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_role` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `uq_role_nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrateur','Gestion complète de la plateforme'),(2,'Directeur','Gestion générale de l’établissement'),(3,'Prefet','Gestion pédagogique'),(4,'Secretaire','Gestion administrative des élèves'),(5,'Comptable','Gestion financière'),(6,'Enseignant','Gestion des notes et présences'),(7,'Inspecteur','Consultation et contrôle des données');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles_permissions`
--

DROP TABLE IF EXISTS `roles_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles_permissions` (
  `id_role` bigint unsigned NOT NULL,
  `id_permission` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_role`,`id_permission`),
  KEY `fk_rp_permission` (`id_permission`),
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`id_permission`) REFERENCES `permissions` (`id_permission`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles_permissions`
--

LOCK TABLES `roles_permissions` WRITE;
/*!40000 ALTER TABLE `roles_permissions` DISABLE KEYS */;
INSERT INTO `roles_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(1,2),(2,2),(7,2),(1,3),(2,3),(7,3),(1,4),(2,4),(3,4),(4,4),(5,4),(7,4),(1,5),(2,5),(3,5),(4,5),(5,5),(7,5),(1,6),(2,6),(3,6),(4,6),(7,6),(1,7),(2,7),(3,7),(4,7),(6,7),(7,7),(1,8),(2,8),(3,8),(6,8),(7,8),(1,9),(2,9),(7,9),(1,10),(2,10),(3,10),(7,10),(1,11),(2,11),(3,11),(6,11),(7,11),(1,12),(2,12),(3,12),(6,12),(7,12),(1,13),(2,13),(3,13),(4,13),(6,13),(7,13),(1,14),(2,14),(3,14),(6,14),(7,14),(1,15),(2,15),(5,15),(7,15),(1,16),(2,16),(5,16),(7,16),(1,17),(2,17),(5,17),(7,17),(1,18),(2,18),(5,18),(7,18),(1,19),(2,19),(7,19),(1,20),(7,20),(1,21),(7,21),(1,22),(2,22),(3,22),(4,22),(5,22),(7,22),(1,23),(2,23),(5,23),(7,23),(1,24),(1,25),(1,26),(7,26),(6,35);
/*!40000 ALTER TABLE `roles_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarifs_scolaires`
--

DROP TABLE IF EXISTS `tarifs_scolaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarifs_scolaires` (
  `id_tarif` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_annee_scolaire` bigint unsigned NOT NULL,
  `id_classe` bigint unsigned NOT NULL,
  `id_categorie_frais` bigint unsigned NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `devise` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  PRIMARY KEY (`id_tarif`),
  UNIQUE KEY `uq_tarif` (`id_annee_scolaire`,`id_classe`,`id_categorie_frais`),
  KEY `fk_tarif_categorie` (`id_categorie_frais`),
  KEY `idx_tarifs_classe` (`id_classe`),
  KEY `idx_tarifs_annee` (`id_annee_scolaire`),
  CONSTRAINT `fk_tarif_annee` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annees_scolaires` (`id_annee_scolaire`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_tarif_categorie` FOREIGN KEY (`id_categorie_frais`) REFERENCES `categories_frais` (`id_categorie_frais`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_tarif_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_tarif_devise` CHECK ((`devise` in (_utf8mb4'USD',_utf8mb4'CDF'))),
  CONSTRAINT `chk_tarif_montant` CHECK ((`montant` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarifs_scolaires`
--

LOCK TABLES `tarifs_scolaires` WRITE;
/*!40000 ALTER TABLE `tarifs_scolaires` DISABLE KEYS */;
INSERT INTO `tarifs_scolaires` VALUES (1,1,2,1,50.00,'USD'),(4,1,4,2,500.00,'USD'),(5,1,2,6,100.00,'USD');
/*!40000 ALTER TABLE `tarifs_scolaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id_etablissement_foreign` (`id_etablissement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `id_utilisateur` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_etablissement` bigint unsigned DEFAULT NULL,
  `nom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('ACTIF','INACTIF','BLOQUE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIF',
  `derniere_connexion` datetime DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `uq_utilisateur_email` (`email`),
  KEY `idx_utilisateurs_etablissement` (`id_etablissement`),
  CONSTRAINT `fk_utilisateur_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissements` (`id_etablissement`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (1,NULL,'Administrateur','admin@gestion-scolaire.local','$2y$12$VEAMNW9H/cyc.jScwRbIzuqwjynQqy3TA/./cQy5qIqeJMqRSV/rG','ACTIF',NULL,'2026-08-08 18:01:53'),(2,1,'Espoir MUKONGO KIVUVU','informatique@gmail.com','$2y$12$RSdLiJQNnktfwZrC4PpoIuqrx1cMfJ3NxU8ubWJrLm8X94/xZvl1i','ACTIF',NULL,'2026-08-13 10:20:51');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs_roles`
--

DROP TABLE IF EXISTS `utilisateurs_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs_roles` (
  `id_utilisateur` bigint unsigned NOT NULL,
  `id_role` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_role`),
  KEY `fk_ur_role` (`id_role`),
  CONSTRAINT `fk_ur_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ur_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs_roles`
--

LOCK TABLES `utilisateurs_roles` WRITE;
/*!40000 ALTER TABLE `utilisateurs_roles` DISABLE KEYS */;
INSERT INTO `utilisateurs_roles` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `utilisateurs_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'defaultdb'
--

--
-- Dumping routines for database 'defaultdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-29 23:58:56
