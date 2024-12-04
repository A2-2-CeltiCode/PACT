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
(22000, 'Saint-Brieuc', '2 Rue nominoë','01 23 45 67 89'),
(29600, 'Morlaix', '4 Rue Nos','04 12 34 56 78'),
(29200, 'Brest', '9 Rue Jean Jaures', '04 12 34 56 78'),
(35400, 'Saint-Malo', '7 Rue de la République', '04 12 34 56 78'),
(56100, 'Lorient', '3 Rue Baignus', '04 12 34 56 78'),
(29000, 'Quimper', '1 Rue Contours', '04 12 34 56 78'),
(35000, 'Rennes', '45 Rue les Maux', '04 91 23 45 67'),
(33000, 'Bordeaux', '10 Rue de la Lune', '05 56 78 90 12'),
(44000, 'Nantes', '20 Rue de la Liberté', '02 40 12 34 56'),
(75000, 'Paris', '12 Rue de la Paix', '01 44 56 78 90'),
(13000, 'Marseille', '15 Rue du Panier', '04 91 23 45 67'),
(69000, 'Lyon', '8 Rue Mercière', '04 72 12 34 56'),
(57000, 'Metz', '5 Rue des Allemands', '03 87 54 32 10'),
(20000, 'Ajaccio', '6 Rue du Général', '04 95 20 30 40'),
(34000, 'Montpellier', '22 Rue des Écus', '04 67 12 34 56'),
(80000, 'Amiens', '3 Rue de l''Université', '03 22 33 44 55'),
(75001, 'Paris', '9 Rue du Faubourg', '01 42 67 89 01'),
(59100, 'Roubaix', '17 Rue du Commerce', '03 20 91 23 45'),
(86000, 'Poitiers', '1 Rue des Lilas', '05 49 87 65 43'),
(24000, 'Périgueux', '14 Rue de la Cloche', '05 53 72 89 01'),
(31000, 'Toulouse', '12 Rue des Argoulets', '05 61 23 45 67'),
(67000, 'Strasbourg', '3 Rue de la Mésange', '03 88 12 34 56'),
(21000, 'Dijon', '8 Rue de la Libération', '03 80 25 67 89'),
(49000, 'Angers', '21 Rue du Cormier', '02 41 23 45 67'),
(90000, 'Belfort', '5 Rue de l''Industrie', '03 84 58 69 01');

-- Peupler la table _compte
INSERT INTO _compte (mdp, email, idAdresse) VALUES
('e92fb5cef0be3f9c9ad78d2872bf084e94fd89b06eb98f3667609ff3640b58f0', 'test@gmail.com', 2),
('8b2ad379781f7e2073a1478f80ea507d10e28488d079b3a8fe9602b4892a668c', 'test2@gmail.com', 3),
('9e56e2ff9b0cda81821524df739598640c16d4a4ea1d48be97ca331724773531', 'test3@gmail.com', 4),
('033afd34a37e50cec9b2913c7f20aa9006f72e1e119dade921741716185d2e56', 'sandwich4@gmail.com', 8),
('7b87d4b5c9d1a076535450b0ceadab100e5d072167337252854ff237db79bbc2', 'Kinderbueno@gmail.com', 9),
('68985322e106fb7f530bf4fbe97c2ca8a21e1beaf2a4c8d48e815d0034da3ace', 'user1@gmail.com', 10),
('25d5251779954a8f724f64669723c19768cc2024dcdb43a1a7ce1dbc467fe93a', 'user2@gmail.com', 11),
('22cc158e741fa180410e00beb00fece49036da48b6cdb5215101ba722bc966b3', 'user3@gmail.com', 12),
('0facab375f12e5c3372b8a9985943e42353ae50fa5b6778bd6b37b6323f49de1', 'user4@gmail.com', 13),
('5bd574339da43df97e4c3f82bbbe1ed33a81b249cee14eaec6f42870c1bfe7ff', 'user5@gmail.com', 14),
('245d09835528ecca628984c91c716106a1c381555a2bd3f89ada498ec37a7b0a', 'user6@gmail.com', 15),
('501fe884fdbbb0bceafff5f4fd2f3423cd32d435e96a0a85e56cdf067a632894', 'user7@gmail.com', 16),
('0fa7e7580d41dc88cd65a16d473802ab5854a87e6ddfa43b00f669836ec96d9b', 'user8@gmail.com', 17),
('420aa0474fa671d1c92abba9df2763a1ac07343f61b5f93bca7618d212313180', 'user9@gmail.com', 18),
('dce985c7af1ac4aac8ee851ec2507738f870e72882373215848f94d829e244b9', 'user10@gmail.com', 19),
('3946ab12b74862cfd075b4f69f142b9b524ecadf024426c6206fd9ed547679f1', 'user11@gmail.com', 20),
('0952c3c1da99414130cf203e6e399b8d5b8d6578b4e25149b14f2397949b10d1', 'user12@gmail.com', 21),
('3e78f1c32984ed554b602f052a6ee1dccf5e37b59fa0dac6d2712cf119d214dd', 'user13@gmail.com', 22),
('9011963039465470c0036871db381adacb92ced945356958c58f5054cca0ac2d', 'user14@gmail.com', 23),
('822b343fb9eecc852f4fe6b4ebd27b35f6dcca6e1a19cc7453c2626d98641389', 'user15@gmail.com', 24);

-- Peupler la table _compteMembre
INSERT INTO _compteMembre (idCompte,pseudo, prenom, nom) VALUES
(1, 'user1', 'Jean','Dupont'),
(5, 'user5', 'Martin', 'Sophie'),
(6, 'user6', 'Lemoine', 'Julien'),
(7, 'user7', 'Leblanc', 'Claire'),
(8, 'user8', 'Lemoins', 'Antoine'),
(9, 'user9', 'Boucher', 'Élise'),
(10, 'user10', 'Moreau', 'Lucie'),
(11, 'user11', 'Girard', 'Michel'),
(12, 'user12', 'Petit', 'Isabelle'),
(13, 'user13', 'Rousseau', 'Jean'),
(14, 'user14', 'Garnier', 'Marie'),
(15, 'user15', 'Dufresne', 'Hélène'),
(16, 'user16', 'Lefevre', 'François'),
(17, 'user17', 'Leclerc', 'Nicolas'),
(18, 'user18', 'Hervé', 'Sandrine'),
(19, 'user19', 'Fournier', 'Chantal'),
(20, 'user20', 'Caron', 'Nathalie');

-- Peupler la table _comptePro
INSERT INTO _comptePro (idCompte, denominationSociale, raisonSocialePro, banqueRib) VALUES
(2, 'Société A', 'Entreprise A', 'FR7612345678901234567890123'),
(3, 'Société B', 'Entreprise B', 'FR7612345678901234567890124'),
(4, 'Société C', 'Entreprise C', 'FR7612345678901234567890125');

INSERT INTO _compteProPrive (idCompte,numSiren) VALUES
(2, 'numerosiren1');

INSERT INTO _compteProPublic (idCompte) VALUES
(3),(4);

-- Peupler la table _offre
INSERT INTO _offre (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne, idAdresse, creaDate, heureOuverture, heureFermeture) VALUES
(2,'En relief', 'Standard', 'Visite de Saint-Brieuc', 'Découvrez les merveilles de Saint-Brieuc', 'Une visite guidée de 2 heures', 'http://example.com/saintbrieuc', TRUE, 7,'2024-09-13','06:00','17:00'),
(2,'A la une', 'Premium', 'Spectacle à Morlaix', 'Profitez d''un spectacle spectaculaire', 'Marionnettes et tours de cartes bluffant !', 'http://example.com/Morlaix', FALSE, 2,'2024-10-01','07:00','16:00'),
(2,'A la une', 'Premium', 'Parc d''attractions de Brest', 'Parc incroyable', 'Parc proposant des attractions phenomenale', 'http://example.com/brest', TRUE, 3,'2024-03-20','08:00','15:00'),
(2,'Aucune', 'Gratuit', 'Restaurant gastronomique de Saint-Malo', 'Restaurant aux 7 saveurs !', '', 'http://example.com/saintmalo', TRUE, 4,'2024-09-15','17:00','01:00'),
(3,'A la une', 'Standard', 'Activite de plongée', 'Parcourez les profondeurs marins', 'Decouvrer les profondeurs de Lorient', 'http://example.com/lorient', TRUE, 5,'2024-07-08','02:00','14:00'),
(3,'En relief', 'Premium', 'Visite de Quimper', 'Visite mémorable', '', 'http://example.com/', TRUE, 6,'2023-11-14','00:00','10:00');

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
INSERT INTO _avis (idOffre,idCompte, commentaire, note, titre, contexteVisite, dateVisite) VALUES
(1, 1,'Une visite magnifique avec des guides passionnants et une vue incroyable sur Saint-Brieuc.', 5.0, 'Incroyable visite', 'Famille', '2024-09-10'),
(2, 1,'Le spectacle était décevant, les marionnettes manquaient de finesse et les tours de cartes étaient trop répétitifs.', 2.5, 'Deception...', 'Amis', '2024-10-15'),
(3, 1,'Super parc d’attractions, les enfants ont adoré ! Il y a beaucoup d’attractions adaptées pour tous les âges.', 4.5, 'Je recommande pour la famille', 'Famille', '2024-11-18'),
(4, 1,'Le restaurant était incroyable, une expérience culinaire de haute qualité. Un peu cher, mais ça en valait la peine.', 5.0, 'Délicieux repas', 'Couple', '2024-11-20'),
(5, 1,'L’activité de plongée était fantastique. Le matériel était neuf et la plongée dans les eaux claires de Lorient a été un moment inoubliable.', 5.0, 'Magnifique environnement sous-marins', 'Amis', '2024-11-22'),
(6, 1,'La visite de Quimper était bien, mais pas aussi exceptionnelle que je m’y attendais. Les guides étaient un peu trop pressés.', 3.0, 'Visite sympa avec mauvais guide', 'Solo', '2024-11-23'),
(1, 5,'Excellente visite guidée, les informations étaient claires et intéressantes. Parfait pour une première découverte de la ville.', 4.5, 'Très bonne visite', 'Amis', '2024-11-11'),
(2, 5,'Mauvaise expérience. Le spectacle était en retard et la qualité des prestations était loin de ce à quoi je m’attendais.', 1.5, 'Spectacle ABSOLUMENT PAS original', 'Famille', '2024-11-16'),
(3, 5,'Parc très bien entretenu, mais certains manèges étaient en maintenance pendant notre visite, ce qui a réduit l’expérience.', 4.0, 'Incomplet mais sympa', 'Famille', '2024-11-19'),
(4, 5,'Le cadre est magnifique, mais le service était un peu lent. Nous avons attendu longtemps avant d’être servis.', 3.0, '45min d''attente...', 'Couple', '2024-11-21'),
(1, 6,'La visite était fascinante et très bien structurée. Nous avons appris beaucoup de choses tout en profitant de magnifiques panoramas.', 5.0, 'Visite passionnante', 'Famille', '2024-11-18'),
(1, 7,'Excellente visite, même si la météo n’était pas parfaite, cela n’a pas gâché l’expérience. Les guides étaient super.', 4.5, 'Malgré la pluie, un super moment', 'Couple', '2024-11-19'),
(1, 8,'Une activité agréable, mais un peu trop rapide à mon goût. J’aurais aimé passer plus de temps à explorer.', 4.0, 'Trop rapide', 'Solo', '2024-11-20'),
(1, 9,'Je recommande vivement cette visite. Les explications étaient claires et les lieux visités étaient magnifiques.', 4.5, 'Très bonne expérience', 'Amis', '2024-11-21'),
(1, 10,'La vue était incroyable, mais le groupe était trop grand pour bien profiter de la visite. Il était difficile d’entendre les explications du guide.', 3.0, 'Groupe trop grand', 'Famille', '2024-11-22'),
(1, 11,'Visite agréable, mais j’aurais aimé un peu plus d’interaction avec le guide. Le temps est passé vite mais je reste un peu sur ma faim.', 4.0, 'Bien mais perfectible', 'Couple', '2024-11-23'),
(1, 12,'Une très belle expérience, le guide était passionné et nous a donné de nombreuses anecdotes intéressantes sur la ville.', 5.0, 'À faire absolument', 'Amis', '2024-11-24'),
(1, 13,'Très bon rapport qualité-prix. L’organisation était parfaite et les explications très enrichissantes. Je recommande.', 4.5, 'Très bon moment', 'Solo', '2024-11-25'),
(1, 14,'La visite était agréable, mais j’ai trouvé qu’il manquait un peu d’animation. Un peu plus de dynamisme aurait été bien.', 3.5, 'Peut mieux faire', 'Famille', '2024-11-26'),
(1, 15,'Une découverte magnifique de la ville, avec une vue incroyable. L''itinéraire était parfait pour apprécier chaque site en profondeur.', 5.0, 'Un incontournable', 'Couple', '2024-11-27'),
(1, 16,'Visite enrichissante, le guide était très professionnel et a su répondre à toutes nos questions. À faire absolument!', 4.5, 'Visite très informative', 'Famille', '2024-11-12'),
(1, 17,'Superbe expérience. Les paysages étaient époustouflants et l''accueil très chaleureux. On reviendra!', 5.0, 'Une expérience mémorable', 'Couple', '2024-11-13'),
(1, 18,'Belle découverte de la ville, mais un peu trop de monde ce jour-là. Le groupe était un peu trop grand.', 4.0, 'Bien mais trop fréquenté', 'Amis', '2024-11-14'),
(6, 5,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 0.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 6,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 0.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 7,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 1.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 8,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 1.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 9,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 2.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 10,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 2.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 11,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 3.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 12,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 3.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 13,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 4.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 14,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 4.5, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17'),
(6, 15,'Le tour était intéressant mais j''aurais préféré plus de détails sur l''histoire de la ville. Cependant, la vue était magnifique.', 5.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17');

INSERT INTO _facture (idOffre, datePrestaServices, dateEcheance) VALUES
(1, '2024-09-01','2024-10-20'),
(1, '2024-10-01','2024-11-20'),
(1, '2024-11-01','2024-12-20'),
(2, '2024-10-01','2024-11-20'),
(2, '2024-11-01','2024-12-20');

/*INSERT INTO _souscription (nbSemaines, debutOption) VALUES
(1, '2024-09-09'),
(4, '2024-09-23'),
(3, '2024-10-14'),
(2, '2024-11-11');*/

INSERT INTO _historiqueEnLigne(idOffre, jourDebut, jourFin) VALUES
(1,'2024-09-02','2024-09-22'),
(1,'2024-10-15','2024-10-27'),
(1,'2024-10-29','2024-11-13'),
(1,'2024-11-19', null),
(2,'2024-10-29','2024-11-03');

INSERT INTO _annulationOption(nbSemaines, debutOption, idOffre, nomOption, estAnnulee) VALUES
(1, '2024-09-09',1,'En relief',False),
(4, '2024-09-23',1,'En relief',True),
(3, '2024-10-14',1,'A la une',False),
(2, '2024-11-11',1,'En relief',True);

INSERT INTO _imageavis VALUES
(9, 'test.png'),
(11, 'baldachi.png'),
(11, 'pearto.png');
