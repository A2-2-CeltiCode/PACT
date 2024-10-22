SET SCHEMA 'pact';

-- Peupler la table _option
INSERT INTO _option (nomOption) VALUES
('Aucune'),
('En relief'),
('A la une');

-- Peupler la table _gamme
INSERT INTO _gamme (nomGamme) VALUES
('Leger'),
('Moyen'),
('Fort');

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
('Gratuite'),
('Standard'),
('Premium');

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
