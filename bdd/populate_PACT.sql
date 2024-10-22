SET SCHEMA 'pact';

-- Peupler la table _prix
INSERT INTO _prix (valPrix) VALUES
(20),
(40),
(60);

-- Peupler la table _duree
INSERT INTO _duree (tempsEnMinutes) VALUES
(30),
(60),
(120);

-- Peupler la table _adresse
INSERT INTO _adresse (codePostal, ville, nomRue, numRue, numTel) VALUES
(75001, 'Paris', 'Rue de Rivoli', '1', '+33 1 23 45 67 89'),
(69001, 'Lyon', 'Rue de la République', '2', '+33 4 12 34 56 78'),
(13001, 'Marseille', 'Rue de la Canebière', '3', '+33 4 91 23 45 67');

-- Peupler la table _compte
INSERT INTO _compte (mdp, email, codePostal, ville) VALUES
('password1', 'user1@example.com', 75001, 'Paris'),
('password2', 'user2@example.com', 69001, 'Lyon'),
('password3', 'user3@example.com', 13001, 'Marseille');

-- Peupler la table _compteMembre
INSERT INTO _compteMembre (idCompte,login, prenom, nom) VALUES
(1, 'user1',  'Jean','Dupont');

-- Peupler la table _comptePro
INSERT INTO _comptePro (idCompte, denominationSociale, raisonSocialePro, banqueRib) VALUES
(2, 'Société A', 'Entreprise A', 'FR7612345678901234567890123'),
(3, 'Société B', 'Entreprise B', 'FR7612345678901234567890124');

INSERT INTO _compteProPrive (idCompte,numSiren) VALUES
(2, 'numerosiren1');

INSERT INTO _compteProPublic (idCompte) VALUES
(3);

-- Peupler la table _offre
INSERT INTO _offre (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne,codePostal,ville) VALUES
(2,'Aucune', 'Gratuite', 'Visite de Paris', 'Découvrez les merveilles de Paris', 'Une visite guidée de 2 heures', 'http://example.com/paris', TRUE, 75001, 'Paris'),
(2,'En relief', 'Standard', 'Spectacle à Lyon', 'Profitez d''un spectacle spectaculaire', 'Marionnettes et tours de cartes bluffant !', 'http://example.com/lyon', FALSE, 69001, 'Lyon'),
(3,'A la une', 'Premium', 'Parc d''Angers', 'Parc incroyable', 'Parc proposant des attractions insane', 'http://example.com/angers', TRUE, 13001, 'Marseille');

-- Peupler la table _image
INSERT INTO _image (idOffre, nomImage) VALUES
(3, 'paris.jpg'),
(1, 'lyon.jpg'),
(2, 'angers.jpg');

-- Peupler la table _spectacle
INSERT INTO _spectacle (idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite) VALUES
(1, 'Spectacle', 120, 40, 20);

-- Peupler la table _parcAttractions
INSERT INTO _parcAttractions (idOffre, nomCategorie, valPrix, planParc, nbAttractions, ageMin) VALUES
(3, 'Parc d''attractions', 60, 'map_park.jpg', 10, 6);

-- Peupler la table _visitE
INSERT INTO _visite (idOffre, valPrix, tempsEnMinutes, nomCategorie, estGuidee) VALUES
(2, 20, 60, 'Visite', TRUE);

INSERT INTO _guideeVisite (IdOffre,nomLangage) VALUES
(2,'Français'),
(2,'Espagnol');

-- Peupler la table _possedeSpectacle
INSERT INTO _possedeSpectacle (idOffre, nomTag) VALUES
(1, 'Nature');

-- Peupler la table _possedeParcAttractions
INSERT INTO _possedeParcAttractions (idOffre, nomTag) VALUES
(3, 'Urbain');

-- Peupler la table _possedeVisite
INSERT INTO _possedeVisite (idOffre, nomTag) VALUES
(2, 'Famille');
