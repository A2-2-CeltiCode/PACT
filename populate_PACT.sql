SET SCHEMA 'pact';

-- Peupler la table _price
INSERT INTO _price (valPrice) VALUES
(20),
(40),
(60);

-- Peupler la table _duration
INSERT INTO _duration (timeInMinutes) VALUES
(30),
(60),
(120);

-- Peupler la table _adress
INSERT INTO _adress (postalCode, city, streetName, streetNumber, phoneNumber) VALUES
(75001, 'Paris', 'Rue de Rivoli', '1', '+33 1 23 45 67 89'),
(69001, 'Lyon', 'Rue de la République', '2', '+33 4 12 34 56 78'),
(13001, 'Marseille', 'Rue de la Canebière', '3', '+33 4 91 23 45 67');

-- Peupler la table _account
INSERT INTO _account (login, password, email, postalCode, city) VALUES
('user1', 'password1', 'user1@example.com', 75001, 'Paris'),
('user2', 'password2', 'user2@example.com', 69001, 'Lyon'),
('user3', 'password3', 'user3@example.com', 13001, 'Marseille');

-- Peupler la table _accountMember
INSERT INTO _accountMember (idAccount, firstName, familyName) VALUES
(1, 'Jean', 'Dupont');

-- Peupler la table _accountPro
INSERT INTO _accountPro (idAccount, corporateName, companyName, bankRib) VALUES
(2, 'Société A', 'Entreprise A', 'FR7612345678901234567890123'),
(3, 'Société B', 'Entreprise B', 'FR7612345678901234567890124');




-- Peupler la table _offer
INSERT INTO _offer (nameOption, nameFp, title, description, detailedDescription, website) VALUES
('Aucune', 'Gratuite', 'Visite de Paris', 'Découvrez les merveilles de Paris', 'Une visite guidée de 2 heures', 'http://example.com/paris'),
('En relief', 'Standard', 'Spectacle à Lyon', 'Profitez d''un spectacle spectaculaire', 'Marionnettes et tours de cartes bluffant !', 'http://example.com/lyon'),
('A la une', 'Premium', 'Parc d''Angers', 'Parc incroyable', 'Parc proposant des attractions insane', 'http://example.com/angers');

-- Peupler la table _image
INSERT INTO _image (idOffer, name) VALUES
(1, 'paris.jpg'),
(2, 'lyon.jpg'),
(3, 'angers.jpg');

-- Peupler la table _show
INSERT INTO _show (idOffer, nameCategory, timeInMinutes, valPrice, capacity) VALUES
(1, 'Spectacle', 120, 40, 20);

-- Peupler la table _amusementPark
INSERT INTO _amusementPark (idOffer, nameCategory, valPrice, parkMap, nbAttractions, ageMin) VALUES
(3, 'Parc d''attractions', 60, 'map_park.jpg', 10, 6);

-- Peupler la table _visit
INSERT INTO _visit (idOffer, valPrice, timeInMinutes, nameCategory, isGuided) VALUES
(2, 20, 60, 'Visite', TRUE);

-- Peupler la table _ownShow
INSERT INTO _ownShow (idOffer, nameTag) VALUES
(1, 'Nature');

-- Peupler la table _ownAmusementPark
INSERT INTO _ownAmusementPark (idOffer, nameTag) VALUES
(3, 'Urbain');

-- Peupler la table _ownVisit
INSERT INTO _ownVisit (idOffer, nameTag) VALUES
(2, 'Famille');
