SET SCHEMA 'pact';

-- Peupler la table _prix
INSERT INTO _prix (valPrix) VALUES
(20),
(40),
(60),
(80),
(100);

-- Peupler la table _duree
INSERT INTO _duree (tempsEnMinutes) VALUES
(30),
(60),
(90),
(120);

-- Peupler la table _adresse
INSERT INTO _adresse (codePostal, ville, nomRue, numRue, numTel) VALUES
(22000, 'Saint-Brieuc', 'Rue nominoë', '1', '+33 1 23 45 67 89'),
(29600, 'Morlaix', 'Rue Nos', '9', '+33 4 12 34 56 78'),
(29200, 'Brest', 'Rue Jean Jaures', '3', '+33 4 12 34 56 78'),
(35400, 'Saint-Malo', 'Rue de la République', '54', '+33 4 12 34 56 78'),
(56100, 'Lorient', 'Rue Baignus', '98', '+33 4 12 34 56 78'),
(29000, 'Quimper', 'Rue Contours', '45', '+33 4 12 34 56 78'),
(35000, 'Rennes', 'Rue les Maux', '7', '+33 4 91 23 45 67');

-- Peupler la table _compte
INSERT INTO _compte (mdp, email, idAdresse) VALUES
('Credit3$', 'test@gmail.com', 1),
('Maroc45$', 'test2@gmail.com', 2),
('Passw0rd$', 'test3@gmail.com', 3);

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
INSERT INTO _offre (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne,idAdresse) VALUES
(2,'Aucune', 'Gratuit', 'Visite de Saint-Brieuc', 'Découvrez les merveilles de Saint-Brieuc', 'Une visite guidée de 2 heures', 'http://example.com/saintbrieuc', TRUE, 1),
(2,'En relief', 'Standard', 'Spectacle à Morlaix', 'Profitez d''un spectacle spectaculaire', 'Marionnettes et tours de cartes bluffant !', 'http://example.com/Morlaix', FALSE, 2),
(2,'A la une', 'Premium', 'Parc d''attractions de Brest', 'Parc incroyable', 'Parc proposant des attractions phenomenale', 'http://example.com/brest', TRUE, 3),
(2,'Aucune', 'Gratuit', 'Restaurant gastronomique de Saint-Malo', 'Restaurant aux 7 saveurs !', '', 'http://example.com/saintmalo', TRUE, 4),
(3,'A la une', 'Standard', 'Activite de plongée', 'Parcourez les profondeurs marins', 'Decouvrer les profondeurs de Lorient', 'http://example.com/lorient', TRUE, 5),
(3,'En relief', 'Premium', 'Visite de Quimper', 'Visite mémorable', '', 'http://example.com/', TRUE, 6);

-- Peupler la table _image
INSERT INTO _image (idOffre, nomImage) VALUES
(1, 'saintbrieuc1.jpg'),
(1, 'visitesaintbrieuc.jpg'),
(2, 'morlaix.jpg'),
(3, 'parcBrest.jpg'),
(4, 'saintmalo.jpg'),
(5, 'Lorient.jpg'),
(5, 'fonds-marin.jpg'),
(6, 'quimper.jpg');

-- Peupler la table _spectacle
INSERT INTO _spectacle (idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite) VALUES
(2, 'Spectacle', 120, 40, 20);

-- Peupler la table _parcAttractions
INSERT INTO _parcAttractions (idOffre, nomCategorie, valPrix, idImage, nbAttractions, ageMin) VALUES
(3, 'Parc d''attractions', 60, 4, 10, 6);

-- Peupler la table _visite
INSERT INTO _visite (idOffre, valPrix, tempsEnMinutes, nomCategorie, estGuidee) VALUES
(1, 20, 60, 'Visite', TRUE),
(6, 80, 90, 'Visite', FALSE);

-- Peupler la table _activite
INSERT INTO _activite (idOffre, nomCategorie, tempsEnMinutes, valPrix, ageMin, prestation) VALUES
(5, 'Activite', 120, 40, 8, 'kit de plongée');

-- Peupler la table _restaurant
INSERT INTO _restaurant (idOffre, nomCategorie, nomGamme, idImage) VALUES
(4, 'Restaurant', '€€ (25-40€)', 5);

INSERT INTO _guideeVisite (IdOffre,nomLangage) VALUES
(1,'Français'),
(1,'Espagnol');

-- Peupler la table _possedeSpectacle
INSERT INTO _possedeSpectacle (idOffre, nomTag) VALUES
(2, 'Nature'),
(2, 'Urbain');

-- Peupler la table _possedeParcAttractions
INSERT INTO _possedeParcAttractions (idOffre, nomTag) VALUES
(3, 'Urbain');

-- Peupler la table _possedeVisite
INSERT INTO _possedeVisite (idOffre, nomTag) VALUES
(1, 'Famille');
