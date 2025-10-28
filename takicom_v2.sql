-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 oct. 2025 à 04:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `takicom_v2`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `reference` varchar(100) NOT NULL,
  `code_barre` varchar(100) DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_achat_ht` decimal(15,3) DEFAULT 0.000,
  `prix_vente_ht` decimal(15,3) DEFAULT 0.000,
  `taux_tva` decimal(5,2) DEFAULT 19.00,
  `unite` varchar(20) DEFAULT 'U',
  `gestion_stock` tinyint(1) DEFAULT 1,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `id_categorie`, `reference`, `code_barre`, `designation`, `description`, `prix_achat_ht`, `prix_vente_ht`, `taux_tva`, `unite`, `gestion_stock`, `actif`, `date_creation`) VALUES
(1, 3, 'ART00001', 'zzerzerzerzerzerzer', 'ordinateur dell A2', 'ordinateur dell A2 ordinateur dell A2', 1300.000, 1350.000, 19.00, 'm', 1, 1, '2025-10-05 22:37:46'),
(2, 4, '40100', '', 'DORMANT Z TUBULAIRE ', '', 70.000, 90.000, 19.00, 'U', 1, 1, '2025-10-08 14:24:54');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_article`
--

CREATE TABLE `categorie_article` (
  `id_categorie` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `id_categorie_parent` int(11) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `categorie_article`
--

INSERT INTO `categorie_article` (`id_categorie`, `code`, `libelle`, `description`, `id_categorie_parent`, `actif`, `date_creation`) VALUES
(1, 'ORDI', 'ordinateur', 'ordinateur tous', NULL, 1, '2025-10-05 21:45:59'),
(2, 'SAM', 'sammm', 'sammmm', NULL, 1, '2025-10-05 22:36:01'),
(3, 'SALAFU', 'salandjn', 'dzkljndza kdzakjd', 1, 1, '2025-10-05 22:36:18'),
(4, 'PROFIL', 'Profil alim', 'Profil alim', NULL, 1, '2025-10-08 14:20:02'),
(5, 'PROFILII', 'Profil alim', 'Profil alim', 1, 1, '2025-10-08 14:20:25');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `code_client` varchar(50) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `raison_sociale` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `gouvernorat` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `matricule_fiscale` varchar(50) DEFAULT NULL,
  `code_tva` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `code_client`, `nom`, `prenom`, `raison_sociale`, `adresse`, `code_postal`, `ville`, `gouvernorat`, `telephone`, `mobile`, `email`, `matricule_fiscale`, `code_tva`, `notes`, `actif`, `date_creation`) VALUES
(1, 'CLI00001', 'nasri', 'ibtihel', '', 'rue de la poste sidi bouzid', '9100', 'sidi bouzid', 'Sidi Bouzid', '50234000', '50234000', 'afisemah36@gmail.com', '', '', '3arrefni 3lih Med', 1, '2025-10-05 21:14:34');

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `id_devis` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `numero_devis` varchar(50) NOT NULL,
  `date_devis` date NOT NULL,
  `date_validite` date DEFAULT NULL,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000,
  `total_remise` decimal(15,3) DEFAULT 0.000,
  `statut` varchar(50) DEFAULT 'brouillon',
  `notes` text DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id_devis`, `id_client`, `id_utilisateur`, `numero_devis`, `date_devis`, `date_validite`, `montant_ht`, `montant_tva`, `montant_ttc`, `total_remise`, `statut`, `notes`, `conditions`, `date_creation`, `date_modification`) VALUES
(1, 1, 2, 'DEV-2025-00001', '2025-10-06', '2025-11-05', 1309.500, 248.805, 1558.305, 40.500, 'brouillon', 'notes', 'Conditions\r\n', '2025-10-06 14:10:30', '2025-10-06 14:10:30');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id_entreprise` int(11) NOT NULL,
  `raison_sociale` varchar(255) NOT NULL,
  `forme_juridique` varchar(50) DEFAULT NULL,
  `matricule_fiscale` varchar(50) DEFAULT NULL,
  `code_tva` varchar(50) DEFAULT NULL,
  `code_douane` varchar(50) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `gouvernorat` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `rib` varchar(50) DEFAULT NULL,
  `capital_social` varchar(100) DEFAULT NULL,
  `registre_commerce` varchar(100) DEFAULT NULL,
  `mentions_legales` text DEFAULT NULL,
  `conditions_generales` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id_entreprise`, `raison_sociale`, `forme_juridique`, `matricule_fiscale`, `code_tva`, `code_douane`, `adresse`, `code_postal`, `ville`, `gouvernorat`, `telephone`, `fax`, `email`, `site_web`, `logo_url`, `rib`, `capital_social`, `registre_commerce`, `mentions_legales`, `conditions_generales`, `date_creation`) VALUES
(1, 'info smile', 'SARL', '123988D', '123988D', '', 'rue de la poste Sidi Bouzid', '9100', 'Sidi Bouzid', 'Sidi Bouzid', '50234000', '', 'afisemah36@gmail.com', '', '/uploads/logo_1759746093.png', '785875757657657657', '', '', 'Mentions légales', 'Conditions générales de vente', '2025-10-06 10:21:33');

-- --------------------------------------------------------

--
-- Structure de la table `facture_client`
--

CREATE TABLE `facture_client` (
  `id_facture_client` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `numero_facture` varchar(50) NOT NULL,
  `date_facture` date NOT NULL,
  `date_echeance` date DEFAULT NULL,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000,
  `total_remise` decimal(15,3) DEFAULT 0.000,
  `statut` varchar(50) DEFAULT 'brouillon',
  `mode_reglement` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `conditions_reglement` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `facture_client`
--

INSERT INTO `facture_client` (`id_facture_client`, `id_client`, `id_utilisateur`, `numero_facture`, `date_facture`, `date_echeance`, `montant_ht`, `montant_tva`, `montant_ttc`, `total_remise`, `statut`, `mode_reglement`, `notes`, `conditions_reglement`, `date_creation`, `date_modification`) VALUES
(1, 1, 2, 'FAC-2025-00001', '2025-10-06', '2025-11-05', 3888.000, 738.720, 4626.720, 162.000, 'validée', 'espèces', 'ok ', NULL, '2025-10-06 09:12:49', '2025-10-06 09:12:49'),
(2, 1, 2, 'FAC-2025-00002', '2025-10-06', '2025-11-05', 1350.000, 256.500, 1606.500, 0.000, 'validée', 'espèces', '', NULL, '2025-10-06 10:35:58', '2025-10-06 10:35:58'),
(3, 1, 2, 'FAC-2025-00003', '2025-10-08', '2025-11-07', 1438.200, 273.258, 1711.458, 1.800, 'brouillon', '', '', NULL, '2025-10-08 14:36:27', '2025-10-08 14:36:27'),
(4, 1, 2, 'FAC-2025-00004', '2025-10-15', '2025-11-14', 6750.000, 1282.500, 8032.500, 0.000, 'brouillon', '', '', NULL, '2025-10-15 13:00:45', '2025-10-15 13:00:45');

-- --------------------------------------------------------

--
-- Structure de la table `facture_fournisseur`
--

CREATE TABLE `facture_fournisseur` (
  `id_facture_fournisseur` int(11) NOT NULL,
  `id_fournisseur` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `numero_facture` varchar(50) NOT NULL,
  `date_facture` date NOT NULL,
  `date_echeance` date DEFAULT NULL,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000,
  `total_remise` decimal(15,3) DEFAULT 0.000,
  `statut` varchar(50) DEFAULT 'brouillon',
  `mode_reglement` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `facture_fournisseur`
--

INSERT INTO `facture_fournisseur` (`id_facture_fournisseur`, `id_fournisseur`, `id_utilisateur`, `numero_facture`, `date_facture`, `date_echeance`, `montant_ht`, `montant_tva`, `montant_ttc`, `total_remise`, `statut`, `mode_reglement`, `notes`, `date_creation`, `date_modification`) VALUES
(1, 1, 2, 'F_2025_1', '2025-10-06', '2025-10-20', 24000.000, 4560.000, 28560.000, 0.000, 'brouillon', 'espèces', 'merci', '2025-10-05 23:25:14', '2025-10-05 23:25:14'),
(2, 1, 2, 'FA002', '2025-10-08', '2025-10-08', 84.600, 16.070, 100.670, 5.400, 'brouillon', 'espèces', 'min taraf omar', '2025-10-08 14:31:22', '2025-10-08 14:31:22'),
(3, 1, 2, 'FAC-2025-00001', '2025-10-15', '0000-00-00', 3000.000, 570.000, 3570.000, 0.000, 'brouillon', 'virement', '', '2025-10-15 13:00:10', '2025-10-15 13:00:10');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `code_fournisseur` varchar(50) DEFAULT NULL,
  `raison_sociale` varchar(255) NOT NULL,
  `nom_contact` varchar(100) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `gouvernorat` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `matricule_fiscale` varchar(50) DEFAULT NULL,
  `code_tva` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `code_fournisseur`, `raison_sociale`, `nom_contact`, `adresse`, `code_postal`, `ville`, `gouvernorat`, `telephone`, `mobile`, `email`, `matricule_fiscale`, `code_tva`, `notes`, `actif`, `date_creation`) VALUES
(1, 'FOU00001', 'ben hnia', 'omar ben hania', 'rue de la poste', '9100', 'Sidi Bouzid', 'Sidi Bouzid', '50234000', '', 'afisemah36@gmail.com', '123988D', '', 'omar', 1, '2025-10-05 21:17:11');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_historique` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `table_concernee` varchar(100) NOT NULL,
  `id_enregistrement` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `donnees_avant` text DEFAULT NULL,
  `donnees_apres` text DEFAULT NULL,
  `adresse_ip` varchar(50) DEFAULT NULL,
  `date_action` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`id_historique`, `id_utilisateur`, `table_concernee`, `id_enregistrement`, `action`, `donnees_avant`, `donnees_apres`, `adresse_ip`, `date_action`) VALUES
(1, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-05 19:14:04'),
(2, 2, 'utilisateur', 2, 'LOGOUT', NULL, NULL, '::1', '2025-10-05 19:14:10'),
(3, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-05 19:14:15'),
(4, 2, 'utilisateur', 2, 'LOGOUT', NULL, NULL, '::1', '2025-10-05 19:15:08'),
(5, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '127.0.0.1', '2025-10-05 21:12:48'),
(6, 2, 'client', 1, 'CREATE', NULL, '{\"csrf_token\":\"598a22ce6e1f6fd1c325155441188833ec44ec307871ed64c734fdf1ef8d2ada\",\"code_client\":\"CLI00001\",\"type_client\":\"particulier\",\"nom\":\"nasri\",\"prenom\":\"ibtihel\",\"raison_sociale\":\"\",\"matricule_fiscale\":\"\",\"code_tva\":\"\",\"adresse\":\"rue de la poste sidi bouzid\",\"code_postal\":\"9100\",\"ville\":\"sidi bouzid\",\"gouvernorat\":\"Sidi Bouzid\",\"telephone\":\"50234000\",\"mobile\":\"50234000\",\"email\":\"afisemah36@gmail.com\",\"notes\":\"3arrefni 3lih Med\",\"actif\":\"1\"}', '::1', '2025-10-05 21:14:34'),
(7, 2, 'fournisseur', 1, 'CREATE', NULL, '{\"csrf_token\":\"598a22ce6e1f6fd1c325155441188833ec44ec307871ed64c734fdf1ef8d2ada\",\"code_fournisseur\":\"FOU00001\",\"raison_sociale\":\"ben hnia\",\"nom_contact\":\"omar ben hania\",\"matricule_fiscale\":\"123988D\",\"code_tva\":\"\",\"adresse\":\"rue de la poste\",\"code_postal\":\"9100\",\"ville\":\"Sidi Bouzid\",\"gouvernorat\":\"Sidi Bouzid\",\"telephone\":\"50234000\",\"mobile\":\"\",\"email\":\"afisemah36@gmail.com\",\"notes\":\"omar\",\"actif\":\"1\"}', '::1', '2025-10-05 21:17:11'),
(8, 2, 'categorie_article', 1, 'CREATE', NULL, '{\"code\":\"ORDI\",\"libelle\":\"ordinateur\",\"description\":\"ordinateur tous\",\"id_categorie_parent\":null,\"actif\":1}', '::1', '2025-10-05 21:45:59'),
(9, 2, 'categorie_article', 2, 'CREATE', NULL, '{\"code\":\"SAM\",\"libelle\":\"sammm\",\"description\":\"sammmm\",\"id_categorie_parent\":null,\"actif\":1}', '::1', '2025-10-05 22:36:01'),
(10, 2, 'categorie_article', 3, 'CREATE', NULL, '{\"code\":\"SALAFU\",\"libelle\":\"salandjn\",\"description\":\"dzkljndza kdzakjd\",\"id_categorie_parent\":\"1\",\"actif\":1}', '::1', '2025-10-05 22:36:18'),
(11, 2, 'article', 1, 'CREATE', NULL, '{\"csrf_token\":\"598a22ce6e1f6fd1c325155441188833ec44ec307871ed64c734fdf1ef8d2ada\",\"reference\":\"ART00001\",\"code_barre\":\"zzerzerzerzerzerzer\",\"designation\":\"ordinateur dell A2\",\"description\":\"ordinateur dell A2 ordinateur dell A2\",\"id_categorie\":\"3\",\"prix_achat_ht\":\"1300\",\"prix_vente_ht\":\"1350\",\"taux_tva\":\"19\",\"unite\":\"m\",\"gestion_stock\":\"1\",\"quantite_initiale\":\"0\",\"actif\":\"1\"}', '::1', '2025-10-05 22:37:46'),
(12, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-06 07:38:27'),
(13, 2, 'facture_client', 1, 'CREATE', NULL, '{\"id_client\":\"1\",\"id_utilisateur\":2,\"numero_facture\":\"FAC-2025-00001\",\"date_facture\":\"2025-10-06\",\"date_echeance\":\"2025-11-05\",\"montant_ht\":\"3888.000\",\"montant_tva\":\"738.720\",\"montant_ttc\":\"4626.720\",\"total_remise\":\"162.000\",\"statut\":\"validée\",\"mode_reglement\":\"espèces\",\"notes\":\"ok \",\"conditions_reglement\":null}', '::1', '2025-10-06 09:12:49'),
(14, 2, 'entreprise', 1, 'UPDATE', NULL, '{\"raison_sociale\":\"info smile\",\"forme_juridique\":\"SARL\",\"matricule_fiscale\":\"123988D\",\"code_tva\":\"\",\"code_douane\":\"\",\"adresse\":\"rue de la poste Sidi Bouzid\",\"code_postal\":\"9100\",\"ville\":\"Sidi Bouzid\",\"gouvernorat\":\"Sidi Bouzid\",\"telephone\":\"50234000\",\"fax\":\"\",\"email\":\"afisemah36@gmail.com\",\"site_web\":\"\",\"rib\":\"785875757657657657\",\"capital_social\":\"\",\"registre_commerce\":\"\",\"mentions_legales\":\"Mentions légales\",\"conditions_generales\":\"Conditions générales de vente\",\"logo_url\":\"\\/uploads\\/logo_1759746093.png\"}', '::1', '2025-10-06 10:21:33'),
(15, 2, 'entreprise', 1, 'UPDATE', '{\"id_entreprise\":1,\"raison_sociale\":\"info smile\",\"forme_juridique\":\"SARL\",\"matricule_fiscale\":\"123988D\",\"code_tva\":\"\",\"code_douane\":\"\",\"adresse\":\"rue de la poste Sidi Bouzid\",\"code_postal\":\"9100\",\"ville\":\"Sidi Bouzid\",\"gouvernorat\":\"Sidi Bouzid\",\"telephone\":\"50234000\",\"fax\":\"\",\"email\":\"afisemah36@gmail.com\",\"site_web\":\"\",\"logo_url\":\"\\/uploads\\/logo_1759746093.png\",\"rib\":\"785875757657657657\",\"capital_social\":\"\",\"registre_commerce\":\"\",\"mentions_legales\":\"Mentions légales\",\"conditions_generales\":\"Conditions générales de vente\",\"date_creation\":\"2025-10-06 11:21:33\"}', '{\"raison_sociale\":\"info smile\",\"forme_juridique\":\"SARL\",\"matricule_fiscale\":\"123988D\",\"code_tva\":\"123988D\",\"code_douane\":\"\",\"adresse\":\"rue de la poste Sidi Bouzid\",\"code_postal\":\"9100\",\"ville\":\"Sidi Bouzid\",\"gouvernorat\":\"Sidi Bouzid\",\"telephone\":\"50234000\",\"fax\":\"\",\"email\":\"afisemah36@gmail.com\",\"site_web\":\"\",\"rib\":\"785875757657657657\",\"capital_social\":\"\",\"registre_commerce\":\"\",\"mentions_legales\":\"Mentions légales\",\"conditions_generales\":\"Conditions générales de vente\"}', '::1', '2025-10-06 10:33:37'),
(16, 2, 'facture_client', 2, 'CREATE', NULL, '{\"id_client\":\"1\",\"id_utilisateur\":2,\"numero_facture\":\"FAC-2025-00002\",\"date_facture\":\"2025-10-06\",\"date_echeance\":\"2025-11-05\",\"montant_ht\":\"1350.000\",\"montant_tva\":\"256.500\",\"montant_ttc\":\"1606.500\",\"total_remise\":\"0.000\",\"statut\":\"validée\",\"mode_reglement\":\"espèces\",\"notes\":\"\",\"conditions_reglement\":null}', '::1', '2025-10-06 10:35:58'),
(17, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-06 13:49:55'),
(18, 2, 'devis', 1, 'CREATE', NULL, '{\"id_client\":\"1\",\"id_utilisateur\":2,\"numero_devis\":\"DEV-2025-00001\",\"date_devis\":\"2025-10-06\",\"date_validite\":\"2025-11-05\",\"montant_ht\":\"1309.500\",\"montant_tva\":\"248.805\",\"montant_ttc\":\"1558.305\",\"total_remise\":\"40.500\",\"statut\":\"brouillon\",\"notes\":\"notes\",\"conditions\":\"Conditions\\r\\n\"}', '::1', '2025-10-06 14:10:30'),
(19, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-08 14:17:59'),
(20, 2, 'categorie_article', 4, 'CREATE', NULL, '{\"code\":\"PROFIL\",\"libelle\":\"Profil alim\",\"description\":\"Profil alim\",\"id_categorie_parent\":null,\"actif\":1}', '::1', '2025-10-08 14:20:02'),
(21, 2, 'categorie_article', 5, 'CREATE', NULL, '{\"code\":\"PROFILII\",\"libelle\":\"Profil alim\",\"description\":\"Profil alim\",\"id_categorie_parent\":\"1\",\"actif\":1}', '::1', '2025-10-08 14:20:25'),
(22, 2, 'article', 2, 'CREATE', NULL, '{\"csrf_token\":\"70f73dae0e7a979563da07ed89d579e2fdce47d347febad861d2737374392cfb\",\"reference\":\"40100\",\"code_barre\":\"\",\"designation\":\"DORMANT Z TUBULAIRE \",\"description\":\"\",\"id_categorie\":\"4\",\"prix_achat_ht\":\"70\",\"prix_vente_ht\":\"90\",\"taux_tva\":\"19\",\"unite\":\"U\",\"gestion_stock\":\"1\",\"quantite_initiale\":\"0\",\"actif\":\"1\"}', '::1', '2025-10-08 14:24:54'),
(23, 2, 'facture_client', 3, 'CREATE', NULL, '{\"id_client\":\"1\",\"id_utilisateur\":2,\"numero_facture\":\"FAC-2025-00003\",\"date_facture\":\"2025-10-08\",\"date_echeance\":\"2025-11-07\",\"montant_ht\":\"1438.200\",\"montant_tva\":\"273.258\",\"montant_ttc\":\"1711.458\",\"total_remise\":\"1.800\",\"statut\":\"brouillon\",\"mode_reglement\":\"\",\"notes\":\"\",\"conditions_reglement\":null}', '::1', '2025-10-08 14:36:27'),
(24, 2, 'utilisateur', 2, 'LOGOUT', NULL, NULL, '::1', '2025-10-08 15:16:23'),
(25, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-15 12:57:55'),
(26, 2, 'facture_client', 4, 'CREATE', NULL, '{\"id_client\":\"1\",\"id_utilisateur\":2,\"numero_facture\":\"FAC-2025-00004\",\"date_facture\":\"2025-10-15\",\"date_echeance\":\"2025-11-14\",\"montant_ht\":\"6750.000\",\"montant_tva\":\"1282.500\",\"montant_ttc\":\"8032.500\",\"total_remise\":\"0.000\",\"statut\":\"brouillon\",\"mode_reglement\":\"\",\"notes\":\"\",\"conditions_reglement\":null}', '::1', '2025-10-15 13:00:45'),
(27, 2, 'utilisateur', 2, 'LOGIN', NULL, NULL, '::1', '2025-10-24 02:02:31');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_devis`
--

CREATE TABLE `ligne_devis` (
  `id_ligne` int(11) NOT NULL,
  `id_devis` int(11) NOT NULL,
  `id_article` int(11) DEFAULT NULL,
  `ordre` int(11) DEFAULT 0,
  `designation` varchar(255) NOT NULL,
  `quantite` decimal(15,3) DEFAULT 1.000,
  `prix_unitaire_ht` decimal(15,3) DEFAULT 0.000,
  `taux_tva` decimal(5,2) DEFAULT 19.00,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `taux_remise` decimal(5,2) DEFAULT 0.00,
  `montant_remise` decimal(15,3) DEFAULT 0.000,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligne_devis`
--

INSERT INTO `ligne_devis` (`id_ligne`, `id_devis`, `id_article`, `ordre`, `designation`, `quantite`, `prix_unitaire_ht`, `taux_tva`, `montant_tva`, `taux_remise`, `montant_remise`, `montant_ht`, `montant_ttc`) VALUES
(1, 1, 1, 1, 'ordinateur dell A2', 1.000, 1350.000, 19.00, 248.805, 3.00, 40.500, 1309.500, 1558.305);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_facture_client`
--

CREATE TABLE `ligne_facture_client` (
  `id_ligne` int(11) NOT NULL,
  `id_facture_client` int(11) NOT NULL,
  `id_article` int(11) DEFAULT NULL,
  `ordre` int(11) DEFAULT 0,
  `designation` varchar(255) NOT NULL,
  `quantite` decimal(15,3) DEFAULT 1.000,
  `prix_unitaire_ht` decimal(15,3) DEFAULT 0.000,
  `taux_tva` decimal(5,2) DEFAULT 19.00,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `taux_remise` decimal(5,2) DEFAULT 0.00,
  `montant_remise` decimal(15,3) DEFAULT 0.000,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligne_facture_client`
--

INSERT INTO `ligne_facture_client` (`id_ligne`, `id_facture_client`, `id_article`, `ordre`, `designation`, `quantite`, `prix_unitaire_ht`, `taux_tva`, `montant_tva`, `taux_remise`, `montant_remise`, `montant_ht`, `montant_ttc`) VALUES
(1, 1, 1, 1, 'ordinateur dell A2', 2.000, 1350.000, 19.00, 487.350, 5.00, 135.000, 2565.000, 3052.350),
(2, 1, 1, 2, 'ordinateur dell A2', 1.000, 1350.000, 19.00, 251.370, 2.00, 27.000, 1323.000, 1574.370),
(3, 2, 1, 1, 'ordinateur dell A2', 1.000, 1350.000, 19.00, 256.500, 0.00, 0.000, 1350.000, 1606.500),
(4, 3, 2, 1, 'DORMANT Z TUBULAIRE ', 1.000, 90.000, 19.00, 16.758, 2.00, 1.800, 88.200, 104.958),
(5, 3, 1, 2, 'ordinateur dell A2', 1.000, 1350.000, 19.00, 256.500, 0.00, 0.000, 1350.000, 1606.500),
(6, 4, 1, 1, 'ordinateur dell A2', 5.000, 1350.000, 19.00, 1282.500, 0.00, 0.000, 6750.000, 8032.500);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_facture_fournisseur`
--

CREATE TABLE `ligne_facture_fournisseur` (
  `id_ligne` int(11) NOT NULL,
  `id_facture_fournisseur` int(11) NOT NULL,
  `id_article` int(11) DEFAULT NULL,
  `ordre` int(11) DEFAULT 0,
  `designation` varchar(255) NOT NULL,
  `quantite` decimal(15,3) DEFAULT 1.000,
  `prix_unitaire_ht` decimal(15,3) DEFAULT 0.000,
  `taux_tva` decimal(5,2) DEFAULT 19.00,
  `montant_tva` decimal(15,3) DEFAULT 0.000,
  `taux_remise` decimal(5,2) DEFAULT 0.00,
  `montant_remise` decimal(15,3) DEFAULT 0.000,
  `montant_ht` decimal(15,3) DEFAULT 0.000,
  `montant_ttc` decimal(15,3) DEFAULT 0.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligne_facture_fournisseur`
--

INSERT INTO `ligne_facture_fournisseur` (`id_ligne`, `id_facture_fournisseur`, `id_article`, `ordre`, `designation`, `quantite`, `prix_unitaire_ht`, `taux_tva`, `montant_tva`, `taux_remise`, `montant_remise`, `montant_ht`, `montant_ttc`) VALUES
(1, 1, 1, 1, '', 20.000, 0.000, 19.00, 3800.000, 0.00, 0.000, 20000.000, 23800.000),
(2, 1, 1, 2, '', 2.000, 0.000, 19.00, 760.000, 0.00, 0.000, 4000.000, 4760.000),
(3, 2, 2, 1, '', 1.000, 0.000, 19.00, 16.070, 6.00, 5.400, 84.600, 100.670),
(4, 3, 1, 1, '', 3.000, 0.000, 19.00, 570.000, 0.00, 0.000, 3000.000, 3570.000);

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE `parametres` (
  `id_parametre` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `valeur` text DEFAULT NULL,
  `type_donnee` varchar(50) DEFAULT 'string',
  `description` text DEFAULT NULL,
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `parametres`
--

INSERT INTO `parametres` (`id_parametre`, `code`, `libelle`, `valeur`, `type_donnee`, `description`, `date_modification`) VALUES
(1, 'NUM_DEVIS_FORMAT', 'Format numéro devis', 'DEV-{ANNEE}-{NUM:5}', 'string', 'Format de numérotation des devis', '2025-10-05 14:32:27'),
(2, 'NUM_FACTURE_FORMAT', 'Format numéro facture', 'FAC-{ANNEE}-{NUM:5}', 'string', 'Format de numérotation des factures', '2025-10-05 14:32:27'),
(3, 'TVA_DEFAUT', 'TVA par défaut', '19', 'decimal', 'Taux de TVA par défaut en Tunisie (19%)', '2025-10-05 14:32:27'),
(4, 'DELAI_PAIEMENT', 'Délai de paiement', '30', 'integer', 'Délai de paiement par défaut en jours', '2025-10-05 14:32:27'),
(5, 'DEVISE', 'Devise', 'DT', 'string', 'Dinar Tunisien', '2025-10-05 14:32:27'),
(6, 'DEVISE_SYMBOLE', 'Symbole devise', 'DT', 'string', 'Symbole de la devise', '2025-10-05 14:32:27'),
(7, 'SEUIL_STOCK_ALERTE', 'Seuil alerte stock', '10', 'integer', 'Quantité minimum avant alerte', '2025-10-05 14:32:27'),
(8, 'VALIDITE_DEVIS', 'Validité devis', '30', 'integer', 'Durée de validité des devis en jours', '2025-10-05 14:32:27');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `code`, `libelle`, `description`, `permissions`, `actif`, `date_creation`) VALUES
(1, 'ADMIN', 'Administrateur', 'Accès complet à toutes les fonctionnalités', '{\"all\": true}', 1, '2025-10-05 14:32:27'),
(2, 'GESTIONNAIRE', 'Gestionnaire', 'Gestion commerciale complète', '{\"factures\": true, \"devis\": true, \"articles\": true, \"clients\": true, \"fournisseurs\": true, \"stock\": true}', 1, '2025-10-05 14:32:27'),
(3, 'VENDEUR', 'Vendeur', 'Création de devis et consultation', '{\"devis\": true, \"clients_read\": true, \"articles_read\": true}', 1, '2025-10-05 14:32:27'),
(4, 'COMPTABLE', 'Comptable', 'Gestion des factures et suivi', '{\"factures\": true, \"fournisseurs\": true, \"clients_read\": true}', 1, '2025-10-05 14:32:27');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `quantite_disponible` int(11) DEFAULT 0,
  `quantite_reservee` int(11) DEFAULT 0,
  `quantite_minimum` int(11) DEFAULT 0,
  `seuil_alerte` int(11) DEFAULT 10,
  `emplacement` varchar(100) DEFAULT NULL,
  `derniere_maj` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id_stock`, `id_article`, `quantite_disponible`, `quantite_reservee`, `quantite_minimum`, `seuil_alerte`, `emplacement`, `derniere_maj`) VALUES
(1, 1, 15, 0, 0, 10, NULL, '2025-10-15 13:00:45'),
(2, 2, 0, 0, 0, 10, NULL, '2025-10-08 14:36:27');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `derniere_connexion` timestamp NULL DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `id_role`, `login`, `password_hash`, `nom`, `prenom`, `email`, `telephone`, `actif`, `derniere_connexion`, `date_creation`, `date_modification`) VALUES
(1, 1, 'admin', '$2y$10$X7v5QZ6aB8cD9eF0gH1iJ2kL3mN4oP5qR6sT7uV8wX9yZ0aB1cD2e', 'Admin', 'Super', 'admin@gmail.com', '0600000000', 1, NULL, '2025-10-05 18:53:54', '2025-10-05 18:53:54'),
(2, 2, 'afisemah', '$2y$10$xD2sL/Ra8EkT2FPDyEWADusN5tcLTv1djH9UzLR9PZxw.x/uR22.u', 'AFI', 'semah', 'afisemah36@gmail.com', '50234000', 1, '2025-10-24 03:02:31', '2025-10-05 19:05:58', '2025-10-24 03:02:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `idx_reference` (`reference`),
  ADD KEY `idx_designation` (`designation`);

--
-- Index pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id_categorie_parent` (`id_categorie_parent`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `code_client` (`code_client`),
  ADD KEY `idx_code_client` (`code_client`),
  ADD KEY `idx_raison_sociale` (`raison_sociale`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`id_devis`),
  ADD UNIQUE KEY `numero_devis` (`numero_devis`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_numero_devis` (`numero_devis`),
  ADD KEY `idx_date_devis` (`date_devis`),
  ADD KEY `idx_statut` (`statut`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id_entreprise`),
  ADD UNIQUE KEY `matricule_fiscale` (`matricule_fiscale`);

--
-- Index pour la table `facture_client`
--
ALTER TABLE `facture_client`
  ADD PRIMARY KEY (`id_facture_client`),
  ADD UNIQUE KEY `numero_facture` (`numero_facture`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_numero_facture` (`numero_facture`),
  ADD KEY `idx_date_facture` (`date_facture`),
  ADD KEY `idx_statut` (`statut`);

--
-- Index pour la table `facture_fournisseur`
--
ALTER TABLE `facture_fournisseur`
  ADD PRIMARY KEY (`id_facture_fournisseur`),
  ADD KEY `id_fournisseur` (`id_fournisseur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_numero_facture` (`numero_facture`),
  ADD KEY `idx_date_facture` (`date_facture`),
  ADD KEY `idx_statut` (`statut`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD UNIQUE KEY `code_fournisseur` (`code_fournisseur`),
  ADD KEY `idx_code_fournisseur` (`code_fournisseur`),
  ADD KEY `idx_raison_sociale` (`raison_sociale`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_table_id` (`table_concernee`,`id_enregistrement`),
  ADD KEY `idx_date` (`date_action`);

--
-- Index pour la table `ligne_devis`
--
ALTER TABLE `ligne_devis`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_devis` (`id_devis`),
  ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `ligne_facture_client`
--
ALTER TABLE `ligne_facture_client`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_facture_client` (`id_facture_client`),
  ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `ligne_facture_fournisseur`
--
ALTER TABLE `ligne_facture_fournisseur`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_facture_fournisseur` (`id_facture_fournisseur`),
  ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `parametres`
--
ALTER TABLE `parametres`
  ADD PRIMARY KEY (`id_parametre`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD UNIQUE KEY `id_article` (`id_article`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id_devis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `facture_client`
--
ALTER TABLE `facture_client`
  MODIFY `id_facture_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `facture_fournisseur`
--
ALTER TABLE `facture_fournisseur`
  MODIFY `id_facture_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `ligne_devis`
--
ALTER TABLE `ligne_devis`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ligne_facture_client`
--
ALTER TABLE `ligne_facture_client`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `ligne_facture_fournisseur`
--
ALTER TABLE `ligne_facture_fournisseur`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `parametres`
--
ALTER TABLE `parametres`
  MODIFY `id_parametre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_article` (`id_categorie`);

--
-- Contraintes pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  ADD CONSTRAINT `categorie_article_ibfk_1` FOREIGN KEY (`id_categorie_parent`) REFERENCES `categorie_article` (`id_categorie`);

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `devis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `facture_client`
--
ALTER TABLE `facture_client`
  ADD CONSTRAINT `facture_client_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `facture_client_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `facture_fournisseur`
--
ALTER TABLE `facture_fournisseur`
  ADD CONSTRAINT `facture_fournisseur_ibfk_1` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`),
  ADD CONSTRAINT `facture_fournisseur_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `ligne_devis`
--
ALTER TABLE `ligne_devis`
  ADD CONSTRAINT `ligne_devis_ibfk_1` FOREIGN KEY (`id_devis`) REFERENCES `devis` (`id_devis`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_devis_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ligne_facture_client`
--
ALTER TABLE `ligne_facture_client`
  ADD CONSTRAINT `ligne_facture_client_ibfk_1` FOREIGN KEY (`id_facture_client`) REFERENCES `facture_client` (`id_facture_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_facture_client_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ligne_facture_fournisseur`
--
ALTER TABLE `ligne_facture_fournisseur`
  ADD CONSTRAINT `ligne_facture_fournisseur_ibfk_1` FOREIGN KEY (`id_facture_fournisseur`) REFERENCES `facture_fournisseur` (`id_facture_fournisseur`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_facture_fournisseur_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
