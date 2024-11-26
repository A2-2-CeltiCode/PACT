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
INSERT INTO _adresse (codePostal, ville, rue, numTel) VALUES
(22000, 'Saint-Brieuc', '2 Rue nominoë','+33 1 23 45 67 89'),
(29600, 'Morlaix', '4 Rue Nos','+33 4 12 34 56 78'),
(29200, 'Brest', '9 Rue Jean Jaures', '+33 4 12 34 56 78'),
(35400, 'Saint-Malo', '7 Rue de la République', '+33 4 12 34 56 78'),
(56100, 'Lorient', '3 Rue Baignus', '+33 4 12 34 56 78'),
(29000, 'Quimper', '1 Rue Contours', '+33 4 12 34 56 78'),
(35000, 'Rennes', '45 Rue les Maux', '+33 4 91 23 45 67');

-- Peupler la table _compte
INSERT INTO _compte (mdp, email, idAdresse) VALUES
('e92fb5cef0be3f9c9ad78d2872bf084e94fd89b06eb98f3667609ff3640b58f0', 'test@gmail.com', 1),
('8b2ad379781f7e2073a1478f80ea507d10e28488d079b3a8fe9602b4892a668c', 'test2@gmail.com', 2),
('9e56e2ff9b0cda81821524df739598640c16d4a4ea1d48be97ca331724773531', 'test3@gmail.com', 3);

-- Peupler la table _compteMembre
INSERT INTO _compteMembre (idCompte,pseudo, prenom, nom) VALUES
(1, 'user1', 'Jean','Dupont');

-- Peupler la table _comptePro
INSERT INTO _comptePro (idCompte, denominationSociale, raisonSocialePro, banqueRib) VALUES
(2, 'Société A', 'Entreprise A', 'FR7612345678901234567890123'),
(3, 'Société B', 'Entreprise B', 'FR7612345678901234567890124');

INSERT INTO _compteProPrive (idCompte,numSiren) VALUES
(2, 'numerosiren1');

INSERT INTO _compteProPublic (idCompte) VALUES
(3);

-- Peupler la table _offre
INSERT INTO _offre (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne,idAdresse, heureOuverture, heureFermeture) VALUES
(2,'Aucune', 'Gratuit', 'Visite de Saint-Brieuc', 'Découvrez les merveilles de Saint-Brieuc', 'Une visite guidée de 2 heures', 'http://example.com/saintbrieuc', TRUE, 1,'06:00','17:00'),
(2,'En relief', 'Standard', 'Spectacle à Morlaix', 'Profitez d''un spectacle spectaculaire', 'Marionnettes et tours de cartes bluffant !', 'http://example.com/Morlaix', FALSE, 2,'07:00','16:00'),
(2,'A la une', 'Premium', 'Parc d''attractions de Brest', 'Parc incroyable', 'Parc proposant des attractions phenomenale', 'http://example.com/brest', TRUE, 3,'08:00','15:00'),
(2,'Aucune', 'Gratuit', 'Restaurant gastronomique de Saint-Malo', 'Restaurant aux 7 saveurs !', '', 'http://example.com/saintmalo', TRUE, 4,'17:00','01:00'),
(3,'A la une', 'Standard', 'Activite de plongée', 'Parcourez les profondeurs marins', 'Decouvrer les profondeurs de Lorient', 'http://example.com/lorient', TRUE, 5,'02:00','14:00'),
(3,'En relief', 'Premium', 'Visite de Quimper', 'Visite mémorable', '', 'http://example.com/', TRUE, 6,'00:00','10:00');

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
INSERT INTO _spectacle (idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite, dateEvenement) VALUES
(2, 'Spectacle', 120, 40, 20, now());

-- Peupler la table _parcAttractions
INSERT INTO _parcAttractions (idOffre, nomCategorie, valPrix, carteParc, nbAttractions, ageMin) VALUES
(3, 'Parc d''attractions', 60, NULL , 10, 6);

-- Peupler la table _visite
INSERT INTO _visite (idOffre, valPrix, tempsEnMinutes, nomCategorie, estGuidee,dateEvenement) VALUES
(1, 20, 60, 'Visite', TRUE, now()),
(6, 80, 90, 'Visite', FALSE, now());

-- Peupler la table _activite
INSERT INTO _activite (idOffre, nomCategorie, tempsEnMinutes, valPrix, ageMin, prestation) VALUES
(5, 'Activite', 120, 40, 8, 'kit de plongée');

-- Peupler la table _restaurant
INSERT INTO _restaurant (idOffre, nomCategorie, nomGamme, menuRestaurant) VALUES
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

-- Peupler la table _avis
INSERT INTO _avis (idOffre, commentaire, note, titre, contexteVisite, dateVisite) VALUES
(1, 'Une visite magnifique avec des guides passionnants et une vue incroyable sur Saint-Brieuc.', 5.0, 'Incroyable visite', 'Famille', '2024-11-10'),
(2, 'Le spectacle était décevant, les marionnettes manquaient de finesse et les tours de cartes étaient trop répétitifs.', 2.5, 'Deception...', 'Amis', '2024-11-15'),
(3, 'Super parc d’attractions, les enfants ont adoré ! Il y a beaucoup d’attractions adaptées pour tous les âges.', 4.5, 'Je recommande pour la famille', 'Famille', '2024-11-18'),
(4, 'Le restaurant était incroyable, une expérience culinaire de haute qualité. Un peu cher, mais ça en valait la peine.', 5.0, 'Délicieux repas', 'Couple', '2024-11-20'),
(5, 'L’activité de plongée était fantastique. Le matériel était neuf et la plongée dans les eaux claires de Lorient a été un moment inoubliable.', 5.0, 'Magnifique environnement sous-marins', 'Amis', '2024-11-22'),
(6, 'La visite de Quimper était bien, mais pas aussi exceptionnelle que je m’y attendais. Les guides étaient un peu trop pressés.', 3.0, 'Visite sympa avec mauvais guide', 'Solo', '2024-11-23'),
(1, 'Excellente visite guidée, les informations étaient claires et intéressantes. Parfait pour une première découverte de la ville.', 4.5, 'Très bonne visite', 'Amis', '2024-11-11'),
(2, 'Mauvaise expérience. Le spectacle était en retard et la qualité des prestations était loin de ce à quoi je m’attendais.', 1.5, 'Spectacle ABSOLUMENT PAS original', 'Famille', '2024-11-16'),
(3, 'Parc très bien entretenu, mais certains manèges étaient en maintenance pendant notre visite, ce qui a réduit l’expérience.', 4.0, 'Incomplet mais sympa', 'Famille', '2024-11-19'),
(4, 'Le cadre est magnifique, mais le service était un peu lent. Nous avons attendu longtemps avant d’être servis.', 3.0, '45min d''attente...', 'Couple', '2024-11-21'),
(1, 'La visite était fascinante et très bien structurée. Nous avons appris beaucoup de choses tout en profitant de magnifiques panoramas.', 5.0, 'Visite passionnante', 'Famille', '2024-11-18'),
(1, 'Excellente visite, même si la météo n’était pas parfaite, cela n’a pas gâché l’expérience. Les guides étaient super.', 4.5, 'Malgré la pluie, un super moment', 'Couple', '2024-11-19'),
(1, 'Une activité agréable, mais un peu trop rapide à mon goût. J’aurais aimé passer plus de temps à explorer.', 4.0, 'Trop rapide', 'Solo', '2024-11-20'),
(1, 'Je recommande vivement cette visite. Les explications étaient claires et les lieux visités étaient magnifiques.', 4.5, 'Très bonne expérience', 'Amis', '2024-11-21'),
(1, 'La vue était incroyable, mais le groupe était trop grand pour bien profiter de la visite. Il était difficile d’entendre les explications du guide.', 3.0, 'Groupe trop grand', 'Famille', '2024-11-22'),
(1, 'Visite agréable, mais j’aurais aimé un peu plus d’interaction avec le guide. Le temps est passé vite mais je reste un peu sur ma faim.', 4.0, 'Bien mais perfectible', 'Couple', '2024-11-23'),
(1, 'Une très belle expérience, le guide était passionné et nous a donné de nombreuses anecdotes intéressantes sur la ville.', 5.0, 'À faire absolument', 'Amis', '2024-11-24'),
(1, 'Très bon rapport qualité-prix. L’organisation était parfaite et les explications très enrichissantes. Je recommande.', 4.5, 'Très bon moment', 'Solo', '2024-11-25'),
(1, 'La visite était agréable, mais j’ai trouvé qu’il manquait un peu d’animation. Un peu plus de dynamisme aurait été bien.', 3.5, 'Peut mieux faire', 'Famille', '2024-11-26'),
(1, 'Une découverte magnifique de la ville, avec une vue incroyable. L''itinéraire était parfait pour apprécier chaque site en profondeur.', 5.0, 'Un incontournable', 'Couple', '2024-11-27'),
(1, 'Visite enrichissante, le guide était très professionnel et a su répondre à toutes nos questions. À faire absolument!', 4.5, 'Visite très informative', 'Famille', '2024-11-12'),
(1, 'Superbe expérience. Les paysages étaient époustouflants et l''accueil très chaleureux. On reviendra!', 5.0, 'Une expérience mémorable', 'Couple', '2024-11-13'),
(1, 'Belle découverte de la ville, mais un peu trop de monde ce jour-là. Le groupe était un peu trop grand.', 4.0, 'Bien mais trop fréquenté', 'Amis', '2024-11-14'),
(1, 'Une excellente activité pour toute la famille. Le guide a su adapter la visite aux enfants. Moment très agréable!', 5.0, 'Adapté aux enfants', 'Famille', '2024-11-16'),
(1, 'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 3.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17');
