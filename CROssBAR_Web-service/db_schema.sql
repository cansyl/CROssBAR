-- MySQL dump 10.15  Distrib 10.0.38-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: crossbar
-- ------------------------------------------------------
-- Server version	10.0.38-MariaDB-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chembl_compound_clusters`
--

DROP TABLE IF EXISTS `chembl_compound_clusters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chembl_compound_clusters` (
  `Compound_Id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Cluster_Members` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Cluster_Size` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chembl_compound_enrichment`
--

DROP TABLE IF EXISTS `chembl_compound_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chembl_compound_enrichment` (
  `molecule_chembl_id` varchar(16) NOT NULL,
  `M` int(4) NOT NULL,
  `N` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deepscreen_enrichment`
--

DROP TABLE IF EXISTS `deepscreen_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deepscreen_enrichment` (
  `CompoundID` varchar(16) NOT NULL,
  `M` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diseases`
--

DROP TABLE IF EXISTS `diseases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diseases` (
  `obo_id` varchar(19) NOT NULL,
  `disease` varchar(121) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diseaseterms_enrichment`
--

DROP TABLE IF EXISTS `diseaseterms_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diseaseterms_enrichment` (
  `obo_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drugbank_enrichment`
--

DROP TABLE IF EXISTS `drugbank_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drugbank_enrichment` (
  `identifier` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drug` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `drug_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14316 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gene_to_acc`
--

DROP TABLE IF EXISTS `gene_to_acc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gene_to_acc` (
  `acc` varchar(10) NOT NULL,
  `gene` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gene_to_acc_tax`
--

DROP TABLE IF EXISTS `gene_to_acc_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gene_to_acc_tax` (
  `acc` varchar(64) DEFAULT NULL,
  `gene` varchar(64) DEFAULT NULL,
  `tax` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hpo_enrichment`
--

DROP TABLE IF EXISTS `hpo_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hpo_enrichment` (
  `hpo_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hpo_term_names`
--

DROP TABLE IF EXISTS `hpo_term_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hpo_term_names` (
  `hpo_id` varchar(32) NOT NULL,
  `term_name` tinytext NOT NULL,
  `refs` mediumtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hpoterms`
--

DROP TABLE IF EXISTS `hpoterms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hpoterms` (
  `id` varchar(64) NOT NULL,
  `gene` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intact_relations`
--

DROP TABLE IF EXISTS `intact_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intact_relations` (
  `int_a` int(5) NOT NULL,
  `int_b` int(5) NOT NULL,
  `link` varchar(32) NOT NULL,
  `conf` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intact_uniques`
--

DROP TABLE IF EXISTS `intact_uniques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intact_uniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc` varchar(21) DEFAULT NULL,
  `gene` varchar(22) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46571 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `kegg_disease_drug`
--

DROP TABLE IF EXISTS `kegg_disease_drug`;
/*!50001 DROP VIEW IF EXISTS `kegg_disease_drug`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `kegg_disease_drug` (
  `drugname` tinyint NOT NULL,
  `drugbankid` tinyint NOT NULL,
  `kegg_diseaseid` tinyint NOT NULL,
  `kegg_diseasename` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `kegg_disease_drug_orj`
--

DROP TABLE IF EXISTS `kegg_disease_drug_orj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_disease_drug_orj` (
  `drugname` varchar(41) NOT NULL,
  `drugbankid` varchar(7) NOT NULL,
  `kegg_diseaseid` varchar(6) NOT NULL,
  `kegg_diseasename` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_disease_pathway`
--

DROP TABLE IF EXISTS `kegg_disease_pathway`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_disease_pathway` (
  `kegg_pathwayid` varchar(8) NOT NULL,
  `kegg_pathwayname` varchar(57) NOT NULL,
  `kegg_diseaseid` varchar(6) NOT NULL,
  `kegg_diseasename` varchar(109) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_disease_protein`
--

DROP TABLE IF EXISTS `kegg_disease_protein`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_disease_protein` (
  `kegg_geneid` varchar(13) NOT NULL,
  `uniprotid` varchar(10) NOT NULL,
  `kegg_diseaseid` varchar(6) NOT NULL,
  `kegg_diseasename` varchar(109) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_diseases_alternatives`
--

DROP TABLE IF EXISTS `kegg_diseases_alternatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_diseases_alternatives` (
  `kegg_diseaseid` varchar(6) NOT NULL,
  `kegg_diseasename` varchar(118) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_diseaseterms_enrichment`
--

DROP TABLE IF EXISTS `kegg_diseaseterms_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_diseaseterms_enrichment` (
  `kegg_diseaseid` varchar(6) NOT NULL,
  `kegg_diseasename` varchar(109) NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_pathway_protein`
--

DROP TABLE IF EXISTS `kegg_pathway_protein`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_pathway_protein` (
  `kegg_geneid` varchar(13) NOT NULL,
  `uniprotid` varchar(10) NOT NULL,
  `kegg_pathwayid` varchar(8) NOT NULL,
  `kegg_pathwayname` varchar(61) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kegg_pathways_enrichment`
--

DROP TABLE IF EXISTS `kegg_pathways_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegg_pathways_enrichment` (
  `kegg_pathwayid` varchar(8) NOT NULL,
  `kegg_pathwayname` varchar(61) NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pathways`
--

DROP TABLE IF EXISTS `pathways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pathways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `react_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `pathwayName` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7255 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ppi_enrichment`
--

DROP TABLE IF EXISTS `ppi_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ppi_enrichment` (
  `accession` varchar(16) NOT NULL,
  `M` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `predictions`
--

DROP TABLE IF EXISTS `predictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `predictions` (
  `ChEMBLTargetID` varchar(32) NOT NULL,
  `accession` varchar(32) NOT NULL,
  `compound` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `predictions_new`
--

DROP TABLE IF EXISTS `predictions_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `predictions_new` (
  `accession` varchar(32) NOT NULL,
  `compounds` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proteins_reviewed`
--

DROP TABLE IF EXISTS `proteins_reviewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proteins_reviewed` (
  `accession` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reactome_enrichment`
--

DROP TABLE IF EXISTS `reactome_enrichment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactome_enrichment` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `pathwayName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `M` int(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `kegg_disease_drug`
--

/*!50001 DROP TABLE IF EXISTS `kegg_disease_drug`*/;
/*!50001 DROP VIEW IF EXISTS `kegg_disease_drug`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `kegg_disease_drug` AS select `kegg_disease_drug_orj`.`drugname` AS `drugname`,`kegg_disease_drug_orj`.`drugbankid` AS `drugbankid`,`kegg_disease_drug_orj`.`kegg_diseaseid` AS `kegg_diseaseid`,`kegg_disease_drug_orj`.`kegg_diseasename` AS `kegg_diseasename` from `kegg_disease_drug_orj` group by `kegg_disease_drug_orj`.`drugname`,`kegg_disease_drug_orj`.`drugbankid`,`kegg_disease_drug_orj`.`kegg_diseaseid`,`kegg_disease_drug_orj`.`kegg_diseasename` */;
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

-- Dump completed on 2022-11-30 15:46:53
