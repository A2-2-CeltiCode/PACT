SET SCHEMA 'pact';

-- Peupler la table _option
INSERT INTO _option (nameOption) VALUES
('Aucune'),
('En relief'),
('A la une');

-- Peupler la table _category
INSERT INTO _category (nameCategory) VALUES
('Visite'),
('Spectacle'),
('Activite'),
('Parc d''attractions'),
('Restaurant');

-- Peupler la table _fixedPrice
INSERT INTO _fixedPrice (nameFp) VALUES
('Gratuite'),
('Standard'),
('Premium');

-- Peupler la table _language
INSERT INTO _language (nameLanguage) VALUES
('Français'),
('Anglais'),
('Espagnol');

-- Peupler la table _tag
INSERT INTO _tag (nameTag) VALUES
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

-- Peupler la table _tagOther
INSERT INTO _tagOther (nameTag) VALUES
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
INSERT INTO _tagRestaurant (nameTag) VALUES
('Française'),
('Fruits de mer'),
('Asiatique'),
('Indienne'),
('Italienne'),
('Gastronomique'),
('Restauration rapide'),
('Crêperie');
