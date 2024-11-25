--
-- SCHEMA
--
SET SCHEMA 'pact';

--
-- VUES COMPTES
--

-- COMPTE MEMBRE

CREATE OR REPLACE VIEW vue_compte_membre AS
SELECT idCompte, pseudo, mdp, email, numTel, nom, prenom
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

CREATE OR REPLACE VIEW pact.vue_offres AS
SELECT _offre.idcompte, _offre.idoffre, _offre.idadresse, _offre.nomoption, _offre.nomforfait,
       _offre.titre, _offre.description, _offre.descriptiondetaillee, _offre.siteinternet, _offre.heureOuverture, _offre.heureFermeture,_adresse.codepostal, _adresse.ville,
       COALESCE(_spectacle.nomcategorie, _activite.nomcategorie, _visite.nomcategorie, _parcattractions.nomcategorie, _restaurant.nomcategorie) AS nomcategorie,
       _adresse.rue, _adresse.numtel,
       COALESCE(_spectacle.dateEvenement,_visite.dateEvenement) AS dateEvenement,
       COALESCE(_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix, NULL::numeric) AS valprix,
       COALESCE(_spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes, NULL::integer) AS tempsenminutes,
       _spectacle.capacite, _activite.agemin, _activite.prestation, _visite.estguidee, _restaurant.nomgamme, _parcattractions.nbattractions,_offre.estenligne,
        CASE
            WHEN _spectacle.idoffre IS NOT NULL THEN 'Spectacle'::text
            WHEN _restaurant.idoffre IS NOT NULL THEN 'Restaurant'::text
            WHEN _parcattractions.idoffre IS NOT NULL THEN 'Parc d''attractions'::text
            WHEN _activite.idoffre IS NOT NULL THEN 'ActivitÃ©'::text
            WHEN _visite.idoffre IS NOT NULL THEN 'Visite'::text
            ELSE 'Inconnu'::text
        END AS type_offre,
    _image.idimage,
    _image.nomimage
FROM pact._offre LEFT JOIN pact._spectacle ON _offre.idoffre = _spectacle.idoffre
                 LEFT JOIN pact._activite ON _offre.idoffre = _activite.idoffre
                 LEFT JOIN pact._parcattractions ON _offre.idoffre = _parcattractions.idoffre
                 LEFT JOIN pact._restaurant ON _offre.idoffre = _restaurant.idoffre
                 LEFT JOIN pact._visite ON _offre.idoffre = _visite.idoffre
                 LEFT JOIN pact._adresse ON _offre.idadresse = _adresse.idadresse
                 LEFT JOIN pact._option ON _offre.nomoption::text = _option.nomoption::text
                 LEFT JOIN pact._forfait ON _offre.nomforfait::text = _forfait.nomforfait::text
                 LEFT JOIN pact._prix ON COALESCE(_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix) = _prix.valprix
                 LEFT JOIN pact._duree ON COALESCE(_spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes) = _duree.tempsenminutes
                 LEFT JOIN pact._image ON _offre.idoffre = _image.idoffre;

-- VISITE

CREATE OR REPLACE VIEW vue_visite AS
SELECT idCompte, idOffre, idAdresse, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, rue, numTel, valPrix, tempsEnMinutes, estGuidee, estEnLigne, dateEvenement
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
SELECT idCompte, idOffre, idAdresse, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, rue, numTel, valPrix, tempsEnMinutes, capacite, estEnLigne, dateEvenement
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _spectacle NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _duree;

CREATE OR REPLACE VIEW vue_tags_spectacle (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeSpectacle;

-- ACTIVITE

CREATE OR REPLACE VIEW vue_activite AS
SELECT idCompte, idOffre, idAdresse, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, rue, numTel, valPrix, tempsEnMinutes, ageMin, prestation, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _activite NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _duree;

CREATE OR REPLACE VIEW vue_tags_activite (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeActivite;

-- PARC D'ATTRACTIONS

CREATE OR REPLACE VIEW vue_parc_attractions AS
SELECT idCompte, idOffre, idAdresse, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, rue, numTel, valPrix, ageMin, nbAttractions, idImage, nomImage, estEnLigne
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _parcAttractions NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _prix NATURAL JOIN _image;

CREATE OR REPLACE VIEW vue_tags_parc_attractions (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeParcAttractions;

-- RESTAURANT

CREATE OR REPLACE VIEW vue_restaurant AS
SELECT idCompte, idOffre, idAdresse, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet,
       nomCategorie, codePostal, ville, rue, numTel, nomGamme, estEnLigne, idImage,nomImage
FROM _offre NATURAL JOIN _categorie NATURAL JOIN _restaurant NATURAL JOIN _adresse NATURAL JOIN _option
            NATURAL JOIN _forfait NATURAL JOIN _image;

CREATE OR REPLACE VIEW vue_menu_restaurant AS
SELECT idOffre, nomRepas
FROM _proposeRestaurant;

CREATE OR REPLACE VIEW vue_tags_restaurant (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeRestaurant;

CREATE OR REPLACE VIEW vue_image_offre (idOffre, idImage) AS
SELECT idOffre, idImage
FROM _image;

--
-- VUE AVIS
--


CREATE OR REPLACE VIEW vue_avis AS
SELECT idAvis, idOffre, commentaire, note, titre, contexteVisite, dateVisite, dateAvis
FROM _avis;

