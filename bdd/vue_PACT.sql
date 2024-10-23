--
-- SCHEMA
--

SET SCHEMA 'pact';

--
-- VUES COMPTES
--

-- COMPTE MEMBRE

CREATE OR REPLACE VIEW vue_compte_membre AS
SELECT idCompte, login, mdp, email, numTel, nom, prenom
FROM _compte NATURAL JOIN _compteMembre NATURAL JOIN _adresse;

-- COMPTE PRO PRIVE

CREATE OR REPLACE VIEW vue_compte_pro_prive AS
SELECT idCompte, mdp, email, numTel, denominationSociale, raisonSocialePro, banqueRib, numSiren
FROM _compte NATURAL JOIN _comptePro NATURAL JOIN _compteProPrive NATURAL JOIN _adresse;

-- COMPTE PRO PUBLIC

CREATE OR REPLACE VIEW vue_compte_pro_public AS
SELECT idCompte, mdp, email, numTel, denominationSociale, raisonSocialePro, banqueRib
FROM _compte NATURAL JOIN _comptePro NATURAL JOIN _compteProPublic NATURAL JOIN _adresse;

--
-- VUES OFFRES
--

-- VISITE

CREATE OR REPLACE VIEW vue_visite AS
SELECT idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, estGuidee, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _visite NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _duree;

CREATE OR REPLACE VIEW vue_visite_guidee AS
SELECT idOffre, nomLangage
FROM _guideeVisite;

CREATE OR REPLACE VIEW vue_tags_visite (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeVisite;

-- SPECTACLE

CREATE OR REPLACE VIEW vue_spectacle AS
SELECT idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, capacite, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _spectacle NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _duree;

CREATE OR REPLACE VIEW vue_tags_spectacle (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeSpectacle;

-- ACTIVITE

CREATE OR REPLACE VIEW vue_activite AS
SELECT idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, tempsEnMinutes, ageMin, prestation, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _activite NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _duree;

CREATE OR REPLACE VIEW vue_tags_activite (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeActivite;

-- PARC D'ATTRACTIONS

CREATE OR REPLACE VIEW vue_parc_attractions AS
SELECT idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, ageMin, nbAttractions, idImage, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _parcAttractions NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix;

CREATE OR REPLACE VIEW vue_tags_parc_attractions (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeParcAttractions;

-- RESTAURANT

CREATE OR REPLACE VIEW vue_restaurant AS
SELECT idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, nomRue, numRue, numTel, valPrix, nomGamme, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _restaurant NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix;

CREATE OR REPLACE VIEW vue_menu_restaurant AS
SELECT idOffre, nomRepas
FROM _proposeRestaurant;

CREATE OR REPLACE VIEW vue_tags_restaurant (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeRestaurant;

CREATE OR REPLACE VIEW vue_image_offre (idOffre, idImage) AS
SELECT idOffre, idImage
FROM _image;

/*idCompte, idOffre, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne,codePostal,ville,nomRue,numRue*/
