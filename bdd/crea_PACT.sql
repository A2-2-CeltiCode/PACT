--
-- SCHEMA
--

DROP SCHEMA IF EXISTS pact CASCADE;
CREATE SCHEMA pact;
SET SCHEMA 'pact';

--
-- TABLE ADRESSE
--

CREATE TABLE _adresse(
    idAdresse     SERIAL,
    codePostal    VARCHAR(5),
    ville         VARCHAR(50),
    rue           VARCHAR(50),
    numTel        VARCHAR(20), -- indicatif international différent selon le pays
    coordonneesX   FLOAT,
    coordonneesY   FLOAT,
    CONSTRAINT adresse_pk PRIMARY KEY(idAdresse)
);

--
-- TABLE GAMME
--

CREATE TABLE _gamme(
    nomGamme    VARCHAR(20),
    CONSTRAINT gamme_pk PRIMARY KEY(nomGamme)
);

--
-- TABLE APPLICATION PRIX
--

CREATE TABLE _applicationPrix(
    dateApplication   DATE,
    CONSTRAINT applicationPrix_pk PRIMARY KEY(dateApplication)
);

--
-- TABLES OPTION
--

CREATE TABLE _option(
    nomOption   VARCHAR(50),
    CONSTRAINT option_pk PRIMARY KEY(nomOption)
);

CREATE TABLE _appliqueOption(
    nomOption         VARCHAR(50),
    dateApplication   DATE,
    prixHT            NUMERIC(5,2) NOT NULL,
    prixTTC           NUMERIC(5,2) NOT NULL,
    CONSTRAINT appliqueOption_pk PRIMARY KEY(nomOption,dateApplication),
    CONSTRAINT appliqueOption_fk_option FOREIGN KEY (nomOption)
        REFERENCES _option(nomOption),
    CONSTRAINT appliqueOption_fk_applicationPrix FOREIGN KEY (dateApplication)
        REFERENCES _applicationPrix(dateApplication)
);

--
-- TABLES FORFAIT
--

CREATE TABLE _forfait(
    nomForfait  VARCHAR(50),
    CONSTRAINT forfait_pk PRIMARY KEY(nomForfait)
);

CREATE TABLE _forfaitPublic(
    nomForfait  VARCHAR(50),
    CONSTRAINT forfaitPublic_fk_forfait FOREIGN KEY (nomForfait) 
        REFERENCES _forfait(nomForfait),
    CONSTRAINT forfaitPublic_pk PRIMARY KEY(nomForfait)
);

CREATE TABLE _forfaitPro(
    nomForfait  VARCHAR(50),
    CONSTRAINT forfaitPro_fk_forfait FOREIGN KEY (nomForfait) 
        REFERENCES _forfait(nomForfait),
    CONSTRAINT forfaitPro_pk PRIMARY KEY(nomForfait)
);

CREATE TABLE _appliqueForfait(
    nomForfait        VARCHAR(50),
    dateApplication   DATE,
    prixHT            NUMERIC(5,2) NOT NULL,
    prixTTC           NUMERIC(5,2) NOT NULL,
    CONSTRAINT appliqueForfait_pk PRIMARY KEY(nomForfait,dateApplication),
    CONSTRAINT appliqueForfait_fk_forfait FOREIGN KEY (nomForfait)
        REFERENCES _forfait(nomForfait),
    CONSTRAINT appliqueForfait_fk_applicationPrix FOREIGN KEY (dateApplication)
        REFERENCES _applicationPrix(dateApplication)
);

--
-- TABLE CONTEXTE
--

CREATE TABLE _contexte(
    nomContexte   VARCHAR(10),
    CONSTRAINT contexte_pk PRIMARY KEY(nomContexte)
);

--
-- TABLES COMPTES
--

CREATE TABLE _compte (
    idCompte    SERIAL,
    idAdresse   SERIAL,
    mdp         VARCHAR(255),
    email       VARCHAR(255),
    CONSTRAINT compte_pk PRIMARY KEY(idCompte),
    CONSTRAINT compte_fk_adresse FOREIGN KEY (idAdresse)
        REFERENCES _adresse(idAdresse)
);

CREATE TABLE _compteMembre(
    idCompte  SERIAL,
    prenom    VARCHAR(50),
    nom       VARCHAR(50),
    pseudo     VARCHAR(255) UNIQUE,
    CONSTRAINT compteMembre_pk PRIMARY KEY(idCompte),
    CONSTRAINT compteMembre_fk_compte FOREIGN KEY (idCompte) 
        REFERENCES _compte(idCompte)
);

CREATE TABLE _comptePro (
    idCompte              SERIAL,
    denominationSociale   VARCHAR(50) NOT NULL,  
    raisonSocialePro      VARCHAR(50) NOT NULL,
    banqueRib             VARCHAR(35),
    CONSTRAINT comptePro_pk PRIMARY KEY(idCompte),
    CONSTRAINT comptePro_fk_compte FOREIGN KEY (idCompte) 
        REFERENCES _compte(idCompte)
);

CREATE TABLE _compteProPublic (
    idCompte     SERIAL,
    CONSTRAINT compteProPublic_pk PRIMARY KEY(idCompte),
    CONSTRAINT compteProPublic_fk_compte FOREIGN KEY (idCompte) 
        REFERENCES _comptePro(idCompte)
);

CREATE TABLE _compteProPrive (
    idCompte    SERIAL,
    numSiren    CHAR(14) NOT NULL UNIQUE,
    CONSTRAINT compteProPrive_pk PRIMARY KEY(idCompte),
    CONSTRAINT compteProPrive_fk_comptePro FOREIGN KEY (idCompte) 
        REFERENCES _comptePro(idCompte)
);

--
-- TABLE OFFRE
--

CREATE TABLE _offre(
    idOffre                 SERIAL,
    idCompte                SERIAL,
    titre                   VARCHAR(50) NOT NULL,
    description             VARCHAR(511) NOT NULL,
    descriptionDetaillee    VARCHAR(1023),
    siteInternet            VARCHAR(50),
    nomOption               VARCHAR(50) NOT NULL,
    nomForfait              VARCHAR(50) NOT NULL,
    estEnLigne              BOOLEAN NOT NULL,
    idAdresse               SERIAL NOT NULL,
    creaDate                DATE DEFAULT CURRENT_TIMESTAMP,
    heureOuverture          TIME NOT NULL,
    heureFermeture          TIME NOT NULL,
    nbJetons                INTEGER DEFAULT 3 CHECK (nbJetons > -1 AND nbJetons < 4),
    CONSTRAINT offre_pk PRIMARY KEY(idOffre),
    CONSTRAINT offre_fk_comptePro FOREIGN KEY (idCompte) 
        REFERENCES _comptePro(idCompte),
    CONSTRAINT offre_fk_option FOREIGN KEY (nomOption)
        REFERENCES _option(nomOption),
    CONSTRAINT offre_fk_forfait FOREIGN KEY (nomForfait)
        REFERENCES _forfait(nomForfait)
);

--
-- TABLE IMAGE
--

CREATE TABLE _image(
    idImage   SERIAL,
    nomImage  VARCHAR(100) NOT NULL,
    CONSTRAINT image_pk PRIMARY KEY(idImage)
);

CREATE TABLE _representeOffre(
    idOffre INTEGER,
    idImage INTEGER,
    CONSTRAINT representeOffre_pk PRIMARY KEY(idOffre,idImage),
    CONSTRAINT representeOffre_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT representeOffre_fk_image FOREIGN KEY (idImage)
        REFERENCES _image(idImage)
);

--
-- TABLE FACTURE
--

CREATE TABLE _facture(
    idFacture             SERIAL,
    idOffre               SERIAL NOT NULL,
    datePrestaServices    DATE NOT NULL,
    dateEcheance          DATE NOT NULL,
    CONSTRAINT facture_pk PRIMARY KEY(idFacture),
    CONSTRAINT facture_fk_offre FOREIGN KEY (idOffre) 
        REFERENCES _offre(idOffre)
);

CREATE TABLE _annulationOption(
    nbSemaines    INTEGER NOT NULL,
    debutOption		DATE NOT NULL,
    idOffre       INTEGER NOT NULL,
    nomOption     VARCHAR(50) NOT NULL,
    estAnnulee    BOOLEAN DEFAULT FALSE,
    CONSTRAINT annulationOption_pk PRIMARY KEY(nbSemaines,debutOption,idOffre,nomOption),
    CONSTRAINT annulationOption_fk_offre FOREIGN KEY (idOffre) 
        REFERENCES _offre(idOffre),
    CONSTRAINT annulationOption_fk_option FOREIGN KEY (nomOption)
        REFERENCES _option(nomOption)
);

CREATE TABLE _historiqueEnLigne(
    idOffre       INTEGER,
    jourDebut     DATE,
    jourFin       DATE,
    CONSTRAINT historiqueEnLigne_pk PRIMARY KEY(idOffre,jourDebut),
    CONSTRAINT historiqueEnLigne_fk_offre FOREIGN KEY (idOffre) 
        REFERENCES _offre(idOffre)
);

--
-- TABLE CATEGORY
--

CREATE TABLE _categorie(
    nomCategorie    VARCHAR(50) NOT NULL,
    CONSTRAINT categorie_pk PRIMARY KEY(nomCategorie)
);

CREATE TABLE _spectacle(
    idOffre         SERIAL,
    nomCategorie    VARCHAR(50) NOT NULL,
    tempsEnMinutes  INTEGER NOT NULL,
    valPrix         NUMERIC(5,2) NOT NULL,
    capacite        INTEGER NOT NULL,
    dateEvenement   DATE NOT NULL,
    CONSTRAINT spectacle_pk PRIMARY KEY (idOffre),
    CONSTRAINT spectacle_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT spectacle_fk_categorie FOREIGN KEY (nomCategorie)
        REFERENCES _categorie(nomCategorie)
);

CREATE TABLE _activite(
    idOffre         SERIAL,
    nomCategorie    VARCHAR(50) NOT NULL,
    tempsEnMinutes  INTEGER,
    valPrix         NUMERIC(5,2) NOT NULL,
    ageMin          INTEGER NOT NULL,
    prestation      VARCHAR(50) NOT NULL,
    CONSTRAINT activite_pk PRIMARY KEY (idOffre),
    CONSTRAINT activite_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT activite_fk_categorie FOREIGN KEY (nomCategorie)
        REFERENCES _categorie(nomCategorie)
);

CREATE TABLE _parcAttractions(
    idOffre         SERIAL,
    nomCategorie    VARCHAR(50) NOT NULL,
    valPrix         NUMERIC(5,2) NOT NULL,
    carteParc       INTEGER,
    nbAttractions   INTEGER NOT NULL,
    ageMin          INTEGER NOT NULL,
    CONSTRAINT parcAttractions_pk PRIMARY KEY (idOffre),
    CONSTRAINT parcAttractions_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT parcAttractions_fk_categorie FOREIGN KEY (nomCategorie)
        REFERENCES _categorie(nomCategorie),
    CONSTRAINT parcAttractions_fk_image FOREIGN KEY(carteParc)
        REFERENCES _image(idImage)
);

CREATE TABLE _visite(
    idOffre         SERIAL,
    valPrix         NUMERIC(5,2) NOT NULL,
    estGuidee       BOOLEAN NOT NULL,
    tempsEnMinutes  INTEGER,
    nomCategorie    VARCHAR(50) NOT NULL,
    dateEvenement   DATE NOT NULL,
    CONSTRAINT visite_pk PRIMARY KEY (idOffre),
    CONSTRAINT visite_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT visite_fk_categorie FOREIGN KEY (nomCategorie)
        REFERENCES _categorie(nomCategorie)
);

CREATE TABLE _restaurant(
    idOffre           SERIAL,
    nomCategorie      VARCHAR(50) NOT NULL,
    nomGamme          VARCHAR(20) NOT NULL,
    menuRestaurant    INTEGER,
    CONSTRAINT restaurant_pk PRIMARY KEY (idOffre),
    CONSTRAINT restaurant_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT restaurant_fk_categorie FOREIGN KEY (nomCategorie)
        REFERENCES _categorie(nomCategorie),
    CONSTRAINT restaurant_fk_gamme FOREIGN KEY (nomGamme)
        REFERENCES _gamme(nomGamme),
    CONSTRAINT restaurant_fk_image FOREIGN KEY (menuRestaurant)
        REFERENCES _image(idImage)
);

CREATE TABLE _repas(
    nomRepas   VARCHAR(50) NOT NULL,
    CONSTRAINT repas_pk PRIMARY KEY(nomRepas)
);

--
-- TABLE LANGAGE
--

CREATE TABLE _langage(
    nomLangage    VARCHAR(50) NOT NULL,
    CONSTRAINT langage_pk PRIMARY KEY(nomLangage)
);

--
-- TABLE TAG
--

CREATE TABLE _tag(
    nomTag    VARCHAR(50),
    CONSTRAINT tag_pk PRIMARY KEY(nomTag)
);

CREATE TABLE _tagAutre(
    nomTag    VARCHAR(50),
    CONSTRAINT tagAutre_pk PRIMARY KEY(nomTag),
    CONSTRAINT tagAutre_fk_tag FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
);

CREATE TABLE _tagRestaurant(
    nomTag    VARCHAR(50),
    CONSTRAINT tagRestaurant_pk PRIMARY KEY(nomTag),
    CONSTRAINT tagRestaurant_fk_tag FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
);

--
-- TABLE AVIS
--

CREATE TABLE _avis(
    idAvis          SERIAL,
    idOffre         INTEGER,
    idCompte        INTEGER,
    commentaire     VARCHAR(255),
    note            NUMERIC(2,1),
    titre           VARCHAR(50),
    contexteVisite  VARCHAR(10),
    dateVisite      DATE,
    dateAvis        DATE DEFAULT CURRENT_TIMESTAMP,
    estVu           BOOLEAN DEFAULT FALSE,
    pouceHaut       INTEGER DEFAULT 0,
    pouceBas        INTEGER DEFAULT 0,
    estBlacklist       BOOLEAN DEFAULT FALSE,
    CONSTRAINT avis_pk PRIMARY KEY(idAvis),
    CONSTRAINT avis_fk_offre FOREIGN KEY (idOffre)
        REFERENCES _offre(idOffre),
    CONSTRAINT avis_fk_contexte FOREIGN KEY(contexteVisite)
        REFERENCES _contexte(nomContexte),
    CONSTRAINT avis_fk_compte FOREIGN KEY(idCompte)
        REFERENCES _compte(idCompte)
);

CREATE TABLE _representeAvis(
    idAvis      INTEGER,
    idImage     INTEGER,
    CONSTRAINT representeAvis_pk PRIMARY KEY(idAvis,idImage),
    CONSTRAINT representeAvis_fk_avis FOREIGN KEY (idAvis)
        REFERENCES _avis(idAvis),
    CONSTRAINT representeAvis_fk_image FOREIGN KEY (idImage)
        REFERENCES _image(idImage)
);

CREATE TABLE _avis_votes (
    idAvis      INTEGER,
    idCompte    INTEGER,
    type        VARCHAR(10) NOT NULL,
    CONSTRAINT avis_votes_pk PRIMARY KEY (idAvis, idCompte),
    CONSTRAINT avis_votes_fk_avis FOREIGN KEY (idAvis) REFERENCES _avis(idAvis),
    CONSTRAINT avis_votes_fk_compte FOREIGN KEY (idCompte) REFERENCES _compte(idCompte)
);

CREATE TABLE _avis_blacklist (
    idOffre         INTEGER NOT NULL,
    idAvis          INTEGER NOT NULL,
    dateBlacklist   TIMESTAMP DEFAULT current_timestamp,
    CONSTRAINT avis_blacklist_pk PRIMARY KEY (idOffre, idAvis),
    CONSTRAINT avis_blacklist_fk_offre FOREIGN KEY (idOffre) REFERENCES _offre(idOffre),
    CONSTRAINT avis_blacklist_fk_avis FOREIGN KEY (idAvis) REFERENCES _avis(idAvis)
);

--
-- TABLE REPONSE AVIS
--

CREATE TABLE _reponseAvis(
    idReponse     SERIAL,
    idAvis        INTEGER NOT NULL,
    idCompte      INTEGER NOT NULL,
    commentaire   VARCHAR(255) NOT NULL,
    dateReponse   DATE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT reponseAvis_pk PRIMARY KEY(idReponse),
    CONSTRAINT reponseAvis_fk_avis FOREIGN KEY (idAvis)
        REFERENCES _avis(idAvis),
    CONSTRAINT reponseAvis_fk_compte FOREIGN KEY (idCompte)
        REFERENCES _compte(idCompte)
);

--
-- TABLE ASSOCIATION GUIDEE
--

CREATE TABLE _guideeVisite (
    idOffre      SERIAL,
    nomLangage    VARCHAR(50) NOT NULL,
    CONSTRAINT guideeVisite_pk PRIMARY KEY(idOffre,nomLangage),
    CONSTRAINT guideeVisite_fk_visite FOREIGN KEY (idOffre)
        REFERENCES _visite(idOffre),
    CONSTRAINT guideeVisite_fk_langage FOREIGN KEY (nomLangage)
        REFERENCES _langage(nomLangage)
);

--
-- TABLE ASSOCIATION PROPOSE
--

CREATE TABLE _proposeRestaurant (
    idOffre      SERIAL,
    nomRepas     VARCHAR(50),
    CONSTRAINT proposeRestaurant_pk PRIMARY KEY(idOffre,nomRepas),
    CONSTRAINT proposeRestaurant_fk_repas FOREIGN KEY (nomRepas)
        REFERENCES _repas(nomRepas),
    CONSTRAINT proposeRestaurant_fk_restaurant FOREIGN KEY (idOffre)
        REFERENCES _restaurant(idOffre)
);

--
-- TABLE ASSOCIATION POSSEDE
--

CREATE TABLE _possedeSpectacle (
    idOffre      SERIAL,
    nomTag       VARCHAR(50),
    CONSTRAINT possedeSpectacle_pk PRIMARY KEY(idOffre,nomTag),
    CONSTRAINT possedeSpectacle_fk_spectacle FOREIGN KEY (idOffre)
        REFERENCES _spectacle(idOffre),
    CONSTRAINT possedeSpectacle_fk_tagAutre FOREIGN KEY (nomTag)
        REFERENCES _tagAutre(nomTag)
);

CREATE TABLE _possedeActivite (
    idOffre      SERIAL,
    nomTag       VARCHAR(50),
    CONSTRAINT possedeActivite_pk PRIMARY KEY(idOffre,nomTag),
    CONSTRAINT possedeActivite_fk_activite FOREIGN KEY (idOffre)
        REFERENCES _activite(idOffre),
    CONSTRAINT possedeActivite_fk_tagAutre FOREIGN KEY (nomTag)
        REFERENCES _tagAutre(nomTag)
);

CREATE TABLE _possedeVisite (
    idOffre      SERIAL,
    nomTag      VARCHAR(50),
    CONSTRAINT possedeVisite_pk PRIMARY KEY(idOffre,nomTag),
    CONSTRAINT possedeVisit_fk_visit FOREIGN KEY (idOffre)
        REFERENCES _visite(idOffre),
    CONSTRAINT possedeVisit_fk_tagAutre FOREIGN KEY (nomTag)
        REFERENCES _tagAutre(nomTag)
);

CREATE TABLE _possedeParcAttractions (
    idOffre      SERIAL,
    nomTag      VARCHAR(50),
    CONSTRAINT possedeParcAttractions_pk PRIMARY KEY(idOffre,nomTag),
    CONSTRAINT possedeParcAttractions_fk_parcAttractions FOREIGN KEY (idOffre)
        REFERENCES _parcAttractions(idOffre),
    CONSTRAINT possedeParcAttractions_fk_tagAutre FOREIGN KEY (nomTag)
        REFERENCES _tagAutre(nomTag)
);

CREATE TABLE _possedeRestaurant (
    idOffre      SERIAL,
    nomTag      VARCHAR(50),
    CONSTRAINT possedeRestaurant_pk PRIMARY KEY(idOffre,nomTag),
    CONSTRAINT possedeRestaurant_fk_restaurant FOREIGN KEY (idOffre)
        REFERENCES _restaurant(idOffre),
    CONSTRAINT possedeRestaurant_fk_tagRestaurant FOREIGN KEY (nomTag)
        REFERENCES _tagRestaurant(nomTag)
);

CREATE TABLE _cleApi (
    idCompte INT PRIMARY KEY,
    cleAPI CHAR(64) UNIQUE,
    FOREIGN KEY (idCompte) REFERENCES _compte(idCompte)
);

CREATE TABLE _totpSecret (
    idCompte INT PRIMARY KEY,
    secret CHAR(152) UNIQUE,
    FOREIGN KEY (idCompte) REFERENCES _compte(idCompte)
);