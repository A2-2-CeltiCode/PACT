SET SCHEMA 'pact';

--
-- VUES COMPTES
--
-- COMPTE MEMBRE
CREATE OR REPLACE VIEW vue_compte_membre AS
SELECT idCompte, pseudo, mdp, email, numTel, nom, prenom, idAdresse, codePostal, ville, rue
FROM _compte NATURAL JOIN _compteMembre NATURAL JOIN _adresse;

-- COMPTE PRO PRIVE
CREATE OR REPLACE VIEW vue_compte_pro_prive AS
SELECT idCompte, mdp, email, numTel, denominationSociale, raisonSocialePro, banqueRib, numSiren, idAdresse, codePostal, ville, rue
FROM _compte NATURAL JOIN _comptePro NATURAL JOIN _compteProPrive NATURAL JOIN _adresse;

-- COMPTE PRO PUBLIC
CREATE OR REPLACE VIEW vue_compte_pro_public AS
SELECT idCompte, mdp, email, numTel, denominationSociale, raisonSocialePro, banqueRib, idAdresse, codePostal, ville, rue
FROM _compte NATURAL JOIN _comptePro NATURAL JOIN _compteProPublic NATURAL JOIN _adresse;

-- COMPTE PRO
CREATE OR REPLACE VIEW vue_compte_pro AS
SELECT c.idCompte, mdp, email, numTel, denominationSociale, raisonSocialePro, banqueRib, numSiren, c.idAdresse, codePostal, ville, rue
FROM _compte c LEFT JOIN _comptePro cp ON c.idCompte = cp.idCompte
			         LEFT JOIN _compteProPrive cppr ON c.idCompte = cppr.idCompte 
			         LEFT JOIN _adresse a ON c.idAdresse = a.idAdresse
WHERE denominationSociale IS NOT NULL;

--
-- VUE AVIS
--

CREATE OR REPLACE VIEW vue_avis AS
SELECT idAvis, idOffre, idCompte, commentaire, note, titre, contexteVisite, dateVisite, dateAvis, estVu, pouceHaut, pouceBas, estBlacklist
FROM _avis;

CREATE OR REPLACE VIEW vue_reponse AS
SELECT _reponseAvis.idReponse, _reponseAvis.idAvis, _reponseAvis.idCompte, _reponseAvis.commentaire, _reponseAvis.dateReponse, _comptePro.denominationSociale
FROM _reponseAvis NATURAL JOIN _comptePro;

--
-- VUE REPONSE AVIS
--

CREATE OR REPLACE VIEW vue_reponse_avis AS
SELECT idReponse, idAvis, idCompte, commentaire, dateReponse
FROM _reponseAvis;

CREATE OR REPLACE VIEW vue_avis_blacklist AS
SELECT idOffre, idAvis, dateBlacklist
FROM _avis_blacklist;

--
-- VUES OFFRES
--

DROP MATERIALIZED VIEW IF EXISTS vue_offres;
CREATE MATERIALIZED VIEW vue_offres AS
SELECT DISTINCT _offre.idcompte, _offre.idoffre, _offre.idadresse, _offre.nomoption, _offre.nomforfait,
       _offre.titre, _offre.description, _offre.descriptiondetaillee, _offre.siteinternet, _offre.heureOuverture, _offre.heureFermeture,_adresse.codepostal, _adresse.ville,
       COALESCE(_spectacle.nomcategorie, _activite.nomcategorie, _visite.nomcategorie, _parcattractions.nomcategorie, _restaurant.nomcategorie) AS nomcategorie,
       _adresse.rue, _adresse.numtel,_adresse.coordonneesX,_adresse.coordonneesY,
       COALESCE(_spectacle.dateEvenement,_visite.dateEvenement) AS dateEvenement,
       COALESCE(_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix, NULL::numeric) AS valprix,
       COALESCE(_spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes, NULL::integer) AS tempsenminutes,
       _spectacle.capacite, COALESCE(_activite.agemin,_parcattractions.agemin) AS ageMin, _activite.prestation,
       _visite.estguidee, _restaurant.nomgamme, _parcattractions.nbattractions,_offre.estenligne,
        CASE 
            WHEN AVG(note) IS NULL THEN '0'
            ELSE AVG(note)
        END AS moyNotes, _offre.nbJetons,
        CASE
            WHEN _spectacle.idoffre IS NOT NULL THEN 'Spectacle'::text
            WHEN _restaurant.idoffre IS NOT NULL THEN 'Restaurant'::text
            WHEN _parcattractions.idoffre IS NOT NULL THEN 'Parc d''attractions'::text
            WHEN _activite.idoffre IS NOT NULL THEN 'Activite'::text
            WHEN _visite.idoffre IS NOT NULL THEN 'Visite'::text
            ELSE 'Inconnu'::text
        END AS type_offre,
      (SELECT nomImage 
        FROM _representeOffre JOIN _image ON _representeOffre.idImage = _image.idImage 
        WHERE _representeOffre.idOffre = _offre.idoffre 
        FETCH FIRST 1 ROW ONLY) AS nomimage
FROM _offre LEFT JOIN _spectacle ON _offre.idoffre = _spectacle.idoffre
            LEFT JOIN _activite ON _offre.idoffre = _activite.idoffre
            LEFT JOIN _parcattractions ON _offre.idoffre = _parcattractions.idoffre
            LEFT JOIN _restaurant ON _offre.idoffre = _restaurant.idoffre
            LEFT JOIN _visite ON _offre.idoffre = _visite.idoffre
            LEFT JOIN _adresse ON _offre.idadresse = _adresse.idadresse
            LEFT JOIN _option ON _offre.nomoption::text = _option.nomoption::text
            LEFT JOIN _forfait ON _offre.nomforfait::text = _forfait.nomforfait::text
            LEFT JOIN _representeOffre ON _offre.idoffre = _representeOffre.idoffre
            LEFT JOIN vue_avis ON vue_avis.idOffre = _offre.idoffre
GROUP BY _offre.idcompte, _offre.idoffre, _offre.idadresse, _offre.nomoption, _offre.nomforfait,
       _offre.titre, _offre.description, _offre.descriptiondetaillee, _offre.siteinternet, _offre.heureOuverture, _offre.heureFermeture,_adresse.codepostal, _adresse.ville,
       _adresse.rue, _adresse.numtel,_adresse.coordonneesX,_adresse.coordonneesY, _spectacle.dateEvenement,_visite.dateEvenement,_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix,
       _spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes,_spectacle.capacite,_activite.agemin,_parcattractions.agemin, _activite.prestation,
       _spectacle.nomcategorie, _activite.nomcategorie, _visite.nomcategorie, _parcattractions.nomcategorie, _restaurant.nomcategorie,
       _visite.estguidee, _restaurant.nomgamme, _parcattractions.nbattractions,_offre.estenligne, type_offre, nomimage;

-- VISITE
CREATE OR REPLACE VIEW vue_visite AS
SELECT o.idCompte, o.idOffre, a.idAdresse, op.nomOption, f.nomForfait, o.titre, o.description, 
       o.descriptionDetaillee, o.siteInternet, c.nomCategorie, a.codePostal, a.ville, a.rue, 
       a.numTel, valPrix, tempsEnMinutes, v.estGuidee, o.estEnLigne, v.dateEvenement
FROM _offre o LEFT JOIN _visite v ON o.idOffre = v.idOffre
              LEFT JOIN _categorie c ON v.nomCategorie = c.nomCategorie
              LEFT JOIN _adresse a ON o.idAdresse = a.idAdresse
              LEFT JOIN _option op ON o.nomOption = op.nomOption
              LEFT JOIN _forfait f ON o.nomForfait = f.nomForfait
WHERE v.estGuidee IS NOT NULL;

CREATE OR REPLACE VIEW vue_visite_guidee AS
SELECT idOffre, nomLangage
FROM _guideeVisite;

CREATE OR REPLACE VIEW vue_tags_visite (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeVisite;

-- SPECTACLE
CREATE OR REPLACE VIEW vue_spectacle AS
SELECT o.idCompte, o.idOffre, o.idAdresse, op.nomOption, f.nomForfait, o.titre, o.description, o.descriptionDetaillee, o.siteInternet,
       c.nomCategorie, a.codePostal, a.ville, a.rue, a.numTel, valPrix, tempsEnMinutes, s.capacite, o.estEnLigne, s.dateEvenement
FROM _offre o INNER JOIN _option op ON o.nomOption = op.nomOption
              INNER JOIN _forfait f ON o.nomForfait = f.nomForfait
              INNER JOIN _spectacle s ON o.idOffre = s.idOffre
              INNER JOIN _adresse a ON o.idAdresse = a.idAdresse
              INNER JOIN _categorie c ON s.nomCategorie = c.nomCategorie;

CREATE OR REPLACE VIEW vue_tags_spectacle (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeSpectacle;

-- ACTIVITE
CREATE OR REPLACE VIEW vue_activite AS
SELECT o.idCompte, o.idOffre, o.idAdresse, op.nomOption, f.nomForfait, o.titre, o.description, o.descriptionDetaillee, 
       o.siteInternet, o.creaDate, c.nomCategorie, a.codePostal, a.ville, a.rue, a.numTel, valPrix, tempsEnMinutes, 
       ac.ageMin, ac.prestation, o.estEnLigne, o.heureOuverture, o.heureFermeture
FROM _offre o INNER JOIN _option op ON o.nomOption = op.nomOption
              INNER JOIN _forfait f ON o.nomForfait = f.nomForfait
              INNER JOIN _activite ac ON o.idOffre = ac.idOffre
              INNER JOIN _adresse a ON o.idAdresse = a.idAdresse
              INNER JOIN _categorie c ON ac.nomCategorie = c.nomCategorie;

CREATE OR REPLACE VIEW vue_tags_activite (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeActivite;

-- PARC D'ATTRACTIONS
CREATE OR REPLACE VIEW vue_parc_attractions AS
SELECT o.idCompte, o.idOffre, a.idAdresse, op.nomOption, f.nomForfait, o.titre, o.description, 
       o.descriptionDetaillee, o.siteInternet, c.nomCategorie, a.codePostal, a.ville, a.rue, 
       a.numTel, valPrix, pa.ageMin, pa.nbAttractions, i.idImage, i.nomImage, o.estEnLigne
FROM _offre o LEFT JOIN _parcAttractions pa ON o.idOffre = pa.idOffre
              LEFT JOIN _categorie c ON pa.nomCategorie = c.nomCategorie
              LEFT JOIN _adresse a ON o.idAdresse = a.idAdresse
              LEFT JOIN _option op ON o.nomOption = op.nomOption
              LEFT JOIN _forfait f ON o.nomForfait = f.nomForfait
              LEFT JOIN _representeOffre ro ON o.idOffre = ro.idOffre
              LEFT JOIN _image i ON ro.idImage = i.idImage
WHERE nbAttractions IS NOT NULL;

CREATE OR REPLACE VIEW vue_tags_parc_attractions (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeParcAttractions;

-- RESTAURANT
CREATE OR REPLACE VIEW vue_restaurant AS
SELECT o.idCompte, o.idOffre, o.idAdresse, o.nomOption, o.nomForfait, o.titre, o.description, o.descriptionDetaillee, 
       o.siteInternet, c.nomCategorie, a.codePostal, a.ville, a.rue, a.numTel, g.nomGamme, o.estEnLigne, i.idImage, i.nomImage
FROM _offre o LEFT JOIN _restaurant r ON r.idOffre = o.idOffre
              LEFT JOIN _categorie c ON c.nomCategorie = r.nomCategorie
              LEFT JOIN _adresse a ON a.idAdresse = o.idAdresse
              LEFT JOIN _option o2 ON o2.nomOption = o.nomOption
              LEFT JOIN _forfait f ON f.nomForfait = o.nomForfait
              LEFT JOIN _representeOffre ro ON o.idOffre = ro.idOffre
              LEFT JOIN _image i ON ro.idImage = i.idImage
              LEFT JOIN _gamme g ON g.nomGamme = r.nomGamme
WHERE c.nomCategorie = 'Restaurant';
            
CREATE OR REPLACE VIEW vue_menu_restaurant AS
SELECT idOffre, nomRepas
FROM _proposeRestaurant;

CREATE OR REPLACE VIEW vue_tags_restaurant (idOffre, nomTag) AS
SELECT idOffre, nomTag
FROM _possedeRestaurant;

CREATE OR REPLACE VIEW vue_image_offre (idOffre, idImage, nomImage) AS
SELECT idOffre, idImage, nomImage
FROM _representeOffre NATURAL JOIN _image;

CREATE OR REPLACE VIEW vue_image_avis (idAvis, idImage, nomImage) AS
SELECT idAvis, idImage, nomImage
FROM _representeAvis NATURAL JOIN _image;
