SET SCHEMA 'pact';

-- Peupler la table _option
INSERT INTO _option (nomOption) VALUES
('Aucune'),
('En relief'),
('A la une');

INSERT INTO _applicationPrix(dateApplication, dateFin) VALUES
(now(),null);

-- Peupler la table _appliqueOption
INSERT INTO _appliqueOption (nomOption, dateApplication, prixHT, prixTTC) VALUES
('Aucune', now(), 0.0, 0.0),
('En relief', now(), 8.34, 10.0),
('A la une', now(), 16.68, 20.0);


-- Peupler la table _gamme
INSERT INTO _gamme (nomGamme) VALUES
('€ (-25€)'),
('€€ (25-40€)'),
('€€€ (+40€)');

-- Peupler la table _repas
INSERT INTO _repas (nomRepas) VALUES
('Petit-Dejeuner'),
('Brunch'),
('Dejeuner'),
('Diner'),
('Boissons');

-- Peupler la table _categorie
INSERT INTO _categorie (nomCategorie) VALUES
('Visite'),
('Spectacle'),
('Activite'),
('Parc d''attractions'),
('Restaurant');

-- Peupler la table _forfait
INSERT INTO _forfait (nomForfait) VALUES
('Gratuit'),
('Standard'),
('Premium');

-- Peupler la table _forfait
INSERT INTO _forfaitPublic (nomForfait) VALUES
('Gratuit');

-- Peupler la table _forfait
INSERT INTO _forfaitPro (nomForfait) VALUES
('Standard'),
('Premium');

-- Peupler la table _appliqueForfait
INSERT INTO _appliqueForfait (nomForfait, dateApplication, prixHT, prixTTC) VALUES
('Gratuit', now(), 0.0, 0.0),
('Standard', now(), 1.67, 2.0),
('Premium', now(), 3.34, 4.0);

-- Peupler la table _language
INSERT INTO _langage (nomLangage) VALUES
('Français'),
('Anglais'),
('Espagnol');

-- Peupler la table _tag
INSERT INTO _tag (nomTag) VALUES
('Culturel'),
('Patrimoine'),
('Histoire'),
('Urbain'),
('Nature'),
('Plein air'),
('Sport'),
('Nautique'),
('Gastronomie'),
('Musée'),
('Atelier'),
('Musique'),
('Famille'),
('Cinéma'),
('Cirque'),
('Son et Lumière'),
('Humour'),
('Française'),
('Fruits de mer'),
('Asiatique'),
('Indienne'),
('Italienne'),
('Gastronomique'),
('Restauration rapide'),
('Crêperie');

-- Peupler la table _tagAutre
INSERT INTO _tagAutre (nomTag) VALUES
('Culturel'),
('Patrimoine'),
('Histoire'),
('Urbain'),
('Nature'),
('Plein air'),
('Sport'),
('Nautique'),
('Gastronomie'),
('Musée'),
('Atelier'),
('Musique'),
('Famille'),
('Cinéma'),
('Cirque'),
('Son et Lumière'),
('Humour');


-- Peupler la table _tagRestaurant
INSERT INTO _tagRestaurant (nomTag) VALUES
('Française'),
('Fruits de mer'),
('Asiatique'),
('Indienne'),
('Italienne'),
('Gastronomique'),
('Restauration rapide'),
('Crêperie');

-- Peupler la table _contexte
INSERT INTO _contexte (nomContexte) VALUES
('Affaires'),
('Couple'),
('Famille'),
('Amis'),
('Solo');

-- Peupler la table _adresse
INSERT INTO _adresse (codePostal, ville, rue, numTel) VALUES
(22300, 'Lannion', 'Rue Edouard Branly','+33 2 96 46 93 00');