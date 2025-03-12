SET SCHEMA 'pact';

-- Peupler la table _adresse
INSERT INTO _adresse (codePostal, ville, rue, numTel, coordonneesX, coordonneesY) VALUES
(22000, 'Saint-Brieuc', '2 Rue nominoë', '01 23 45 67 89', 48.5146, -2.7653),
(29600, 'Morlaix', '4 Rue Nos', '04 12 34 56 78', 48.5784, -3.8274),
(29200, 'Brest', '9 Rue Jean Jaures', '04 12 34 56 78', 48.3904, -4.4861),
(35400, 'Saint-Malo', '7 Rue de la République', '04 12 34 56 78', 48.6493, -2.0257),
(56100, 'Lorient', '3 Rue Baignus', '04 12 34 56 78', 47.7489, -3.3666),
(29000, 'Quimper', '1 Rue Contours', '04 12 34 56 78', 47.9961, -4.0963),
(35000, 'Rennes', '45 Rue les Maux', '04 91 23 45 67', 48.1173, -1.6778),
(33000, 'Bordeaux', '10 Rue de la Lune', '05 56 78 90 12', 44.8378, -0.5792),
(44000, 'Nantes', '20 Rue de la Liberté', '02 40 12 34 56', 47.2184, -1.5536),
(75000, 'Paris', '12 Rue de la Paix', '01 44 56 78 90', 48.8566, 2.3522),
(13000, 'Marseille', '15 Rue du Panier', '04 91 23 45 67', 43.2965, 5.3698),
(69000, 'Lyon', '8 Rue Mercière', '04 72 12 34 56', 45.7640, 4.8357),
(57000, 'Metz', '5 Rue des Allemands', '03 87 54 32 10', 49.1193, 6.1757),
(20000, 'Ajaccio', '6 Rue du Général', '04 95 20 30 40', 41.9192, 8.7386),
(34000, 'Montpellier', '22 Rue des Écus', '04 67 12 34 56', 43.6119, 3.8772),
(80000, 'Amiens', '3 Rue de l''Université', '03 22 33 44 55', 49.8950, 2.3023),
(75001, 'Paris', '9 Rue du Faubourg', '01 42 67 89 01', 48.8566, 2.3522),
(59100, 'Roubaix', '17 Rue du Commerce', '03 20 91 23 45', 50.6927, 3.1746),
(86000, 'Poitiers', '1 Rue des Lilas', '05 49 87 65 43', 46.5802, 0.3404),
(24000, 'Périgueux', '14 Rue de la Cloche', '05 53 72 89 01', 45.1840, 0.7210),
(31000, 'Toulouse', '12 Rue des Argoulets', '05 61 23 45 67', 43.6047, 1.4442),
(67000, 'Strasbourg', '3 Rue de la Mésange', '03 88 12 34 56', 48.5734, 7.7521),
(21000, 'Dijon', '8 Rue de la Libération', '03 80 25 67 89', 47.3220, 5.0415),
(49000, 'Angers', '21 Rue du Cormier', '02 41 23 45 67', 47.4784, -0.5632),
(90000, 'Belfort', '5 Rue de l''Industrie', '03 84 58 69 01', 47.6396, 6.8638),
(29200, 'Brest', 'Place De Lattre de Tassigny', '01 24 28 67 95', 48.3904, -4.4861),
(56170, 'Quiberon', 'Rue des Alizés', '01 48 97 24 95', 47.4825, -3.1204),
(22700, 'Perros-Guirec', 'Rue Antoine Laurent de Lavoisier', '04 98 78 97 75', 48.8154, -3.4453),
(35400, 'Saint-Malo', 'Rue Jules Saffray', '07 34 61 72 12', 48.6493, -2.0257),
(29100, 'Douarnenez', 'Rue Jeanne d''Arc', '02 78 98 34 56', 48.0956, -4.3290),
(22500, 'Paimpol', 'Chemin de Kerano', '02 78 04 21 78', 48.7800, -3.0450),
(35000, 'Rennes', 'Rue Gurvand', '02 95 21 46 91', 48.1173, -1.6778),
(56000, 'Vannes', 'Rue des Luscanen', '09 24 78 24 36', 47.6582, -2.7608),
(35000, 'Rennes', 'Rue Hippolyte Lucas', '01 75 62 38 04', 48.1173, -1.6778),
(22000, 'Saint-Brieuc', '2 Rue nominoë', '01 23 45 67 89', 48.5146, -2.7653);

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
('822b343fb9eecc852f4fe6b4ebd27b35f6dcca6e1a19cc7453c2626d98641389', 'user15@gmail.com', 24),
('2907a88109b26939d31308d4dec243389125d28355a494428ef1f4db9ee87eb6','user16@gmail.com', 25);

-- Peupler la table _compteMembre
INSERT INTO _compteMembre (idCompte, pseudo, prenom, nom) VALUES
(1, 'JeanDarkMaster', 'Jean', 'Dupont'),
(5, 'Sophie_MartinX', 'Martin', 'Sophie'),
(6, 'Julien_Lemoine7', 'Julien', 'Lemoine'),
(7, 'Claire_LeBlanc', 'Claire', 'Leblanc'),
(8, 'Antoine_Lm_89', 'Antoine', 'Lemoins'),
(9, 'EliseB_09', 'Élise', 'Boucher'),
(10, 'Lucie_Thunder', 'Lucie', 'Moreau'),
(11, 'M1chel_Girard', 'Michel', 'Girard'),
(12, 'Izzabelle.Petit', 'Isabelle', 'Petit'),
(13, 'Jean_R0usseau', 'Jean', 'Rousseau'),
(14, 'Marie_GarnierX', 'Marie', 'Garnier'),
(15, 'H3l3ne.Dufresne', 'Hélène', 'Dufresne'),
(16, 'Francois_L3fevre', 'François', 'Lefevre'),
(17, 'Nico_LeclercXx', 'Nicolas', 'Leclerc'),
(18, 'Sandrine_H3rv3', 'Sandrine', 'Hervé'),
(19, 'Ch4ntal_Fournier', 'Chantal', 'Fournier'),
(20, 'Nathalie_Car0n', 'Nathalie', 'Caron');

-- Peupler la table _comptePro
INSERT INTO _comptePro (idCompte, denominationSociale, raisonSocialePro, banqueRib) VALUES
(2, 'Société A', 'Entreprise A', 'FR7612345678901234567890123'),
(3, 'Société B', 'Entreprise B', 'FR7612345678901234567890124'),
(4, 'Société C', 'Entreprise C', 'FR7612345678901234567890125'),
(21,'Société D', 'Entreprise D', 'FR7612345678901234567890126');

INSERT INTO _compteProPrive (idCompte,numSiren) VALUES
(2, '987 654 321'),
(21,'123 456 789');

INSERT INTO _compteProPublic (idCompte) VALUES
(3),(4);

INSERT INTO _offre (idCompte, nomOption, nomForfait, titre, description, descriptionDetaillee, siteInternet, estEnLigne, idAdresse, creaDate, heureOuverture, heureFermeture) VALUES
(2, 'En relief', 'Premium', 'Visite de Saint-Brieuc', 'Plongez dans l''histoire et la culture de Saint-Brieuc à travers une visite guidée immersive, où chaque ruelle, chaque monument a une histoire à raconter. Un voyage fascinant pour les passionnés de patrimoine et de paysages bretons.', 'Cette visite guidée de 2 heures vous permettra de découvrir les joyaux cachés de Saint-Brieuc. Vous parcourrez les rues historiques de la ville, en passant devant des bâtiments emblématiques et en admirant les vues spectaculaires sur la baie. Votre guide passionné vous racontera l''histoire locale, des temps anciens aux événements marquants de la ville. Profitez également de moments de calme dans les jardins publics et sur les quais, tout en admirant la beauté naturelle de cette région du nord de la Bretagne.', 'http://example.com/saintbrieuc', TRUE, 36,'2024-09-13','06:00','17:00'),
(2, 'A la une', 'Premium', 'Spectacle à Morlaix', 'Venez vivre un spectacle exceptionnel à Morlaix, où des artistes de talent vous transporteront dans un univers magique fait de marionnettes, tours de cartes et illusions époustouflantes. Une soirée divertissante pour tous les âges.', 'Le spectacle à Morlaix vous offrira une expérience incroyable, alliant magie et créativité. Plongez dans un univers visuel où les marionnettes prennent vie et où chaque tour de cartes vous émerveille. Laissez-vous surprendre par des illusions visuelles bluffantes réalisées par des magiciens confirmés. Ce spectacle est conçu pour captiver les petits comme les grands, en offrant un moment de pure magie et de féerie. Parfait pour une sortie familiale ou entre amis.', 'http://example.com/Morlaix', FALSE, 3,'2024-10-01','07:00','16:00'),
(2, 'A la une', 'Premium', 'Parc d''attractions de Brest', 'Le parc d''attractions de Brest est l’endroit idéal pour vivre une journée pleine d''aventure, de sensations fortes et de rires. Entre montagnes russes, attractions aquatiques et spectacles à couper le souffle, il y en a pour tous les goûts.', 'Situé en plein cœur de Brest, ce parc propose une multitude d''attractions qui raviront les amateurs de sensations fortes et les familles en quête de divertissement. Vous pourrez tester des montagnes russes palpitantes, vous rafraîchir dans des attractions aquatiques ou assister à des spectacles exceptionnels en plein air. Pour les plus jeunes, le parc propose également des attractions ludiques et pédagogiques. Que vous soyez en quête d’adrénaline ou de moments de détente, ce parc saura répondre à toutes vos attentes et vous garantir une journée mémorable.', 'http://example.com/brest', TRUE, 4,'2024-12-02','08:00','15:00'),
(2, 'Aucune', 'Standard', 'Restaurant gastronomique de Saint-Malo', 'Venez savourer des plats raffinés au restaurant gastronomique de Saint-Malo, où chaque bouchée est une explosion de saveurs. Offrez-vous un voyage culinaire exceptionnel dans un cadre élégant et accueillant.', 'Ce restaurant gastronomique à Saint-Malo est un lieu unique où l''art culinaire rencontre la tradition bretonne. Le chef met un point d''honneur à utiliser des produits locaux de qualité pour créer des plats innovants qui mettent en valeur la richesse des saveurs bretonnes. De l''entrée au dessert, chaque plat est une œuvre d''art, parfaitement équilibrée, aux textures subtiles et aux présentations soignées. Laissez-vous emporter par une expérience gastronomique inoubliable, accompagnée d''une sélection de vins raffinés qui sublimeront chaque dégustation.', 'http://example.com/saintmalo', TRUE, 5,'2024-09-15','17:00','01:00'),
(3, 'Aucune', 'Gratuit', 'Activite de plongée', 'Explorez les profondeurs marines de Lorient et découvrez la richesse de la faune et de la flore sous-marine. Cette activité de plongée est l''occasion rêvée de vous immerger dans un monde fascinant et plein de surprises.', 'Offrez-vous une aventure inoubliable en plongeant dans les eaux cristallines de Lorient. Que vous soyez débutant ou plongeur expérimenté, cette activité est accessible à tous grâce à un encadrement professionnel. Lors de votre plongée, vous aurez l’opportunité d’observer une diversité incroyable de poissons, crustacés et plantes marines qui peuplent les fonds marins bretons. Le guide, passionné par la mer, partagera avec vous ses connaissances sur les écosystèmes sous-marins et vous fera découvrir des sites encore préservés de la région. Une expérience qui marquera à jamais vos souvenirs.', 'http://example.com/lorient', TRUE, 6,'2024-07-08','02:00','14:00'),
(3, 'Aucune', 'Gratuit', 'Visite de Quimper', 'Partez à la découverte de Quimper, une ville pleine de charme et d’histoire. Lors de cette visite, vous plongerez dans l''âme de la Bretagne, à travers ses monuments historiques et son riche patrimoine culturel.', 'Lors de cette visite à Quimper, vous explorerez ses ruelles médiévales et son patrimoine exceptionnel. Vous découvrirez des sites incontournables comme la cathédrale Saint-Corentin, véritable chef-d’œuvre de l''architecture gothique, ainsi que ses maisons à colombages pittoresques. La ville vous offrira également un aperçu de la culture bretonne à travers ses traditions, son art et ses spécialités culinaires. Un véritable voyage dans le temps et la culture bretonne, tout en profitant de l''ambiance unique de cette ville portuaire.', 'http://example.com/', TRUE, 7,'2023-11-14','00:00','10:00'),
(3, 'Aucune', 'Gratuit', 'Visite guidée de Brest', 'Explorez Brest, une ville portuaire dynamique et pleine d''histoire, grâce à une visite guidée qui vous fera découvrir la rade, le château et le musée de la Marine.', 'Cette visite guidée de Brest vous emmènera à la découverte de l''histoire maritime de la ville, en commençant par le Château de Brest, un imposant fort qui surplombe la rade. Vous explorerez également le Musée de la Marine, où vous en apprendrez davantage sur le passé naval de la ville et son rôle stratégique à travers les siècles. Le parcours vous offrira des vues spectaculaires sur la rade de Brest, l''un des plus grands ports militaires de France, et vous permettra de mieux comprendre l''impact de la mer sur l''histoire locale.', 'http://example.com/brest', TRUE, 27, '2024-04-05', '09:00', '17:00'),
(3, 'Aucune', 'Gratuit', 'Spectacle à Quiberon', 'Assistez à un spectacle spectaculaire de marionnettes à Quiberon. Un événement magique pour toute la famille, mêlant art et divertissement dans un cadre idyllique.', 'Ce spectacle de marionnettes à Quiberon vous transportera dans un univers féérique, où des personnages fascinants prennent vie. Que ce soit pour les enfants ou les adultes, ce spectacle saura émerveiller les spectateurs par son côté surprenant et enchanteur. Les marionnettes, réalisées avec soin, évolueront dans des scénarios dynamiques et pleins d''humour. Idéal pour passer un moment convivial en famille ou entre amis, ce spectacle est un véritable événement à ne pas manquer pendant votre séjour à Quiberon.', 'ff', FALSE, 28, '2024-06-15', '10:00', '18:00'),
(3, 'Aucune', 'Gratuit', 'Montagnes Russes à Perros-Guirec', 'Venez tester vos nerfs sur les montagnes russes à Perros-Guirec, tout en explorant les magnifiques côtes bretonnes en kayak. Une combinaison parfaite d''aventure et de nature.', 'À Perros-Guirec, préparez-vous à vivre des sensations fortes à bord des montagnes russes, avant de partir pour une balade en kayak le long des magnifiques côtes bretonnes. Les montagnes russes de Perros-Guirec offrent une expérience palpitante avec des descentes vertigineuses et des virages serrés, parfaits pour les amateurs de sensations fortes. Après cette montée d''adrénaline, vous pourrez vous détendre lors d''une balade en kayak, où vous naviguerez sur des eaux cristallines entourées de paysages spectaculaires, parfaits pour les amoureux de la nature et de l''aventure.', 'http://example.com/perrosguirec', TRUE, 29, '2024-07-10', '08:00', '20:00'),
(4, 'Aucune', 'Gratuit', 'Randonnée sur le GR34 à Saint-Malo', 'Embarquez pour une randonnée époustouflante le long du GR34 à Saint-Malo. Un parcours de 180 kilomètres au cœur de la Bretagne, offrant des vues imprenables sur la mer et les paysages sauvages.', 'Le GR34, surnommé le sentier des douaniers, vous invite à découvrir les magnifiques paysages côtiers de la Bretagne. Ce parcours de randonnée vous emmènera le long de falaises escarpées, de plages sauvages et de petits villages pittoresques. Vous aurez l''occasion d''admirer la richesse naturelle de la région, entre mer et campagne, tout en respirant l''air frais de l''océan Atlantique. Chaque étape de la randonnée offre des vues incroyables sur la mer, les îles voisines et les paysages préservés de la Bretagne, pour une expérience inoubliable.', 'http://example.com/saintmalo', FALSE, 30, '2024-08-01', '07:00', '21:00'),
(4, 'Aucune', 'Gratuit', 'Balade en bateau à Douarnenez', 'Découvrez le charmant port de Douarnenez et ses alentours à bord d’un bateau, lors d''une excursion en mer à couper le souffle.', 'Lors de cette excursion en bateau à Douarnenez, vous naviguerez sur les eaux calmes de la baie et pourrez admirer les paysages spectaculaires de la région. Vous longerez les côtes sauvages et découvrirez les petits ports typiques, tout en profitant de la beauté naturelle de cette partie de la Bretagne. Le capitaine, passionné par la mer, partagera avec vous des anecdotes locales et des informations sur la faune et la flore maritimes. Une sortie agréable et tranquille pour explorer la baie de Douarnenez d’une manière unique.', 'http://example.com/douarnenez', TRUE, 31, '2024-09-15', '10:00', '18:00'),
(4, 'Aucune', 'Gratuit', 'Visite du Musée de la mer à Paimpol', 'Plongez dans l''histoire de la pêche et de la mer en visitant le Musée de la mer à Paimpol, un lieu incontournable pour les passionnés d''histoire maritime.', 'Le Musée de la mer à Paimpol vous propose une immersion fascinante dans l''histoire de la mer, à travers des expositions captivantes sur la pêche, la navigation et la vie maritime. Vous découvrirez des objets anciens, des maquettes de bateaux, ainsi que des témoignages émouvants sur la vie des marins et des pêcheurs bretons. Ce musée est l''occasion idéale de mieux comprendre l''importance de la mer dans l’histoire de la région, tout en explorant des collections exceptionnelles qui retracent l''évolution des métiers maritimes au fil des siècles.', 'http://example.com/paimpol', TRUE, 32, '2024-10-20', '09:00', '19:00'),
(21, 'A la une', 'Standard', 'Restaurant à Rennes', 'Venez goûter aux spécialités bretonnes à Rennes, dans un restaurant où la tradition et l''innovation se rencontrent pour un repas inoubliable.', 'Ce restaurant à Rennes vous invite à découvrir les saveurs de la Bretagne à travers une cuisine locale savoureuse et authentique. Vous pourrez déguster des crêpes, des galettes bretonnes préparées avec des ingrédients frais et locaux, ainsi que du cidre de qualité. Le cadre chaleureux et accueillant du restaurant vous permettra de passer un moment agréable en famille ou entre amis, tout en savourant des plats traditionnels qui mettent en valeur les produits du terroir breton. Une expérience culinaire à ne pas manquer lors de votre séjour à Rennes.', 'http://example.com/rennes', FALSE, 33, '2024-11-10', '11:00', '16:00'),
(21, 'A la une', 'Standard', 'Visite de Vannes', 'Explorez la beauté de Vannes, une ville pleine de charme, avec ses ruelles médiévales et son port pittoresque. Une visite incontournable pour les amoureux de l’histoire et de la nature.', 'Lors de cette visite guidée de Vannes, vous découvrirez l’âme de la ville à travers ses rues pavées, ses remparts et ses monuments historiques. Vous admirerez la cathédrale Saint-Pierre, un chef-d’œuvre de l’architecture gothique, et explorerez le vieux quartier de la ville avec ses maisons à colombages. Vous en apprendrez également davantage sur l''histoire de Vannes, depuis son époque médiévale jusqu’à aujourd’hui, en découvrant des lieux emblématiques comme le château de l''Hermine et le port de plaisance. Cette visite vous offrira un aperçu complet de cette ville au charme intemporel.', 'http://example.com/cotesarmor', TRUE, 34, '2024-12-01', '08:00', '18:00'),
(21, 'A la une', 'Standard', 'Restaurant exotique de Rennes', 'Laissez-vous emporter par les saveurs exotiques au restaurant de Rennes, un lieu unique qui marie subtilement la cuisine tropicale et les influences bretonnes.', 'Le restaurant exotique de Rennes vous invite à découvrir une cuisine originale où les épices et les fruits tropicaux se mêlent aux produits locaux pour créer des plats savoureux et surprenants. Vous pourrez savourer des plats inspirés des îles et des cuisines du monde entier, le tout dans un cadre coloré et chaleureux. Le restaurant met un accent particulier sur la fraîcheur des ingrédients et la qualité des produits, pour offrir une expérience culinaire inoubliable. Une excellente opportunité de découvrir des saveurs nouvelles et de s''évader le temps d’un repas.', 'http://example.com/delices', TRUE, 35, '2024-12-02', '05:00', '23:00');

-- Peupler la table _image
INSERT INTO _image (nomImage) VALUES
('saintbrieuc1.jpg'),
('visitesaintbrieuc.jpg'),
('morlaix.jpg'),
('parcBrest.jpg'),
('saintmalo.jpg'),
('Lorient.jpg'),
('fonds-marin.jpg'),
('quimper.jpg'),
('brest.jpg'),
('quiberon.jpg'),
('port-perros.jpg'),
('saint malo.jpg'),
('Douarnenez.jpg'),
('Paimpol.jpg'),
('rennes.jpg'),
('vannes.jpg'),
('restau.jpg'),
('parc.jpg'),
('bat saint brieuc.jpg'),
('gare-de-saint-brieuc.jpg');

INSERT INTO _representeOffre (idOffre, idImage) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 4),
(4, 5),
(5, 6),
(5, 7),
(6, 8),
(7, 9),
(8, 10),
(9, 11),
(10, 12),
(11, 13),
(12, 14),
(13, 15),
(14, 16),
(15, 17);

-- Peupler la table _spectacle
INSERT INTO _spectacle (idOffre, nomCategorie, tempsEnMinutes, valPrix, capacite, dateEvenement) VALUES
(2, 'Spectacle', 120, 40, 20, '2025-06-21'),
(8, 'Spectacle', 60, 10, 50, '2024-12-15');

-- Peupler la table _parcAttractions
INSERT INTO _parcAttractions (idOffre, nomCategorie, valPrix, carteParc, nbAttractions, ageMin) VALUES
(3, 'Parc d''attractions', 60, NULL , 10, 6),
(9, 'Parc d''attractions', 30, NULL, 15, 4);

-- Peupler la table _visite
INSERT INTO _visite (idOffre, valPrix, tempsEnMinutes, nomCategorie, estGuidee, dateEvenement) VALUES
(1, 20, 60, 'Visite', TRUE, '2025-05-21'),
(6, 10, 90, 'Visite', FALSE, '2025-03-04'),
(7, 0, 120, 'Visite', TRUE, '2025-02-18'),
(12, 30, 45, 'Visite', FALSE, '2025-07-02'),
(14, 40, 75, 'Visite', FALSE, '2025-09-08');

-- Peupler la table _activite
INSERT INTO _activite (idOffre, nomCategorie, tempsEnMinutes, valPrix, ageMin, prestation) VALUES
(5, 'Activite', 120, 40, 8, 'kit de plongée'),
(10, 'Activite', 90, 20, 5, ''),
(11, 'Activite', 45, 30, 2, 'kit de sauvetage, repas');

-- Peupler la table _restaurant
INSERT INTO _restaurant (idOffre, nomCategorie, nomGamme, menuRestaurant) VALUES
(4, 'Restaurant', '€€ (25-40€)', NULL),
(13, 'Restaurant', '€€€ (+40€)', NULL),
(15, 'Restaurant', '€ (-25€)', NULL);

INSERT INTO _guideeVisite (IdOffre,nomLangage) VALUES
(1,'Français'),
(1,'Espagnol'),
(7,'Français'),
(7,'Anglais');

-- Peupler la table _possedeSpectacle
INSERT INTO _possedeSpectacle (idOffre, nomTag) VALUES
(2, 'Nature'),
(2, 'Urbain'),
(8, 'Humour');

-- Peupler la table _possedeParcAttractions
INSERT INTO _possedeParcAttractions (idOffre, nomTag) VALUES
(3, 'Urbain'),
(9, 'Plein air'),
(9, 'Famille');

-- Peupler la table _possedeVisite
INSERT INTO _possedeVisite (idOffre, nomTag) VALUES
(1, 'Famille'),
(7, 'Patrimoine'),
(12, 'Musée'),
(12, 'Culturel'),
(12, 'Histoire'),
(14, 'Plein air'),
(14, 'Urbain'); 

-- Peupler la table _possedeRestaurant
INSERT INTO _possedeRestaurant (idOffre, nomTag) VALUES
(4, 'Gastronomique'),
(4, 'Française'),
(13, 'Restauration rapide');

INSERT INTO _proposeRestaurant (idOffre, nomRepas) VALUES
(4, 'Brunch'),
(4, 'Dejeuner'),
(4, 'Diner'),
(13, 'Dejeuner'),
(15, 'Diner');

-- Peupler la table _avis
INSERT INTO _avis (idOffre, idCompte, commentaire, note, titre, contexteVisite, dateVisite, estVu, estBlacklist) VALUES
(1, 1, 'Une visite magnifique avec des guides passionnants et une vue incroyable sur Saint-Brieuc.', 5.0, 'Incroyable visite', 'Famille', '2024-09-10', FALSE, TRUE),
(2, 1, 'Le spectacle était décevant, les marionnettes manquaient de finesse et les tours de cartes étaient trop répétitifs.', 2.5, 'Deception...', 'Amis', '2024-10-15', FALSE, FALSE),
(3, 1, 'Super parc d’attractions, les enfants ont adoré ! Il y a beaucoup d’attractions adaptées pour tous les âges.', 4.5, 'Je recommande pour la famille', 'Famille', '2024-11-18', FALSE, FALSE),
(4, 1, 'Le restaurant était incroyable, une expérience culinaire de haute qualité. Un peu cher, mais ça en valait la peine.', 5.0, 'Délicieux repas', 'Couple', '2024-11-20', FALSE, FALSE),
(5, 1, 'L’activité de plongée était fantastique. Le matériel était neuf et la plongée dans les eaux claires de Lorient a été un moment inoubliable.', 5.0, 'Magnifique environnement sous-marins', 'Amis', '2024-11-22', FALSE, FALSE),
(6, 1, 'La visite de Quimper était bien, mais pas aussi exceptionnelle que je m’y attendais. Les guides étaient un peu trop pressés.', 3.0, 'Visite sympa avec mauvais guide', 'Solo', '2024-11-23', FALSE, TRUE),
(1, 5, 'Excellente visite guidée, les informations étaient claires et intéressantes. Parfait pour une première découverte de la ville.', 4.5, 'Très bonne visite', 'Amis', '2024-11-11', FALSE, FALSE),
(2, 5, 'Mauvaise expérience. Le spectacle était en retard et la qualité des prestations était loin de ce à quoi je m’attendais.', 1.5, 'Spectacle ABSOLUMENT PAS original', 'Famille', '2024-11-16', FALSE, FALSE),
(3, 5, 'Parc très bien entretenu, mais certains manèges étaient en maintenance pendant notre visite, ce qui a réduit l’expérience.', 4.0, 'Incomplet mais sympa', 'Famille', '2024-11-19', FALSE, FALSE),
(4, 5, 'Le cadre est magnifique, mais le service était un peu lent. Nous avons attendu longtemps avant d’être servis.', 3.0, '45min d''attente...', 'Couple', '2024-11-21', FALSE, FALSE),
(1, 6, 'La visite était fascinante et très bien structurée. Nous avons appris beaucoup de choses tout en profitant de magnifiques panoramas.', 5.0, 'Visite passionnante', 'Famille', '2024-11-18', FALSE, FALSE),
(1, 7, 'Excellente visite, même si la météo n’était pas parfaite, cela n’a pas gâché l’expérience. Les guides étaient super.', 4.5, 'Malgré la pluie, un super moment', 'Couple', '2024-11-19', FALSE, FALSE),
(1, 8, 'Une activité agréable, mais un peu trop rapide à mon goût. J’aurais aimé passer plus de temps à explorer.', 4.0, 'Trop rapide', 'Solo', '2024-11-20', FALSE, FALSE),
(1, 9, 'Je recommande vivement cette visite. Les explications étaient claires et les lieux visités étaient magnifiques.', 4.5, 'Très bonne expérience', 'Amis', '2024-11-21', FALSE, FALSE),
(1, 10, 'La vue était incroyable, mais le groupe était trop grand pour bien profiter de la visite. Il était difficile d’entendre les explications du guide.', 3.0, 'Groupe trop grand', 'Famille', '2024-11-22', FALSE, FALSE),
(1, 11, 'Visite agréable, mais j’aurais aimé un peu plus d’interaction avec le guide. Le temps est passé vite mais je reste un peu sur ma faim.', 4.0, 'Bien mais perfectible', 'Couple', '2024-11-23', FALSE, FALSE),
(1, 12, 'Une très belle expérience, le guide était passionné et nous a donné de nombreuses anecdotes intéressantes sur la ville.', 5.0, 'À faire absolument', 'Amis', '2024-11-24', FALSE, FALSE),
(1, 13, 'Très bon rapport qualité-prix. L’organisation était parfaite et les explications très enrichissantes. Je recommande.', 4.5, 'Très bon moment', 'Solo', '2024-11-25', FALSE, FALSE),
(1, 14, 'La visite était agréable, mais j’ai trouvé qu’il manquait un peu d’animation. Un peu plus de dynamisme aurait été bien.', 3.5, 'Peut mieux faire', 'Famille', '2024-11-26', FALSE, FALSE),
(1, 15, 'Une découverte magnifique de la ville, avec une vue incroyable. L''itinéraire était parfait pour apprécier chaque site en profondeur.', 5.0, 'Un incontournable', 'Couple', '2024-11-27', FALSE, FALSE),
(1, 16, 'Visite enrichissante, le guide était très professionnel et a su répondre à toutes nos questions. À faire absolument!', 4.5, 'Visite très informative', 'Famille', '2024-11-12', FALSE, FALSE),
(1, 17, 'Superbe expérience. Les paysages étaient époustouflants et l''accueil très chaleureux. On reviendra!', 5.0, 'Une expérience mémorable', 'Couple', '2024-11-13', FALSE, FALSE),
(1, 18, 'Belle découverte de la ville, mais un peu trop de monde ce jour-là. Le groupe était un peu trop grand.', 4.0, 'Bien mais trop fréquenté', 'Amis', '2024-11-14', FALSE, FALSE),
(6, 5, 'Temps pluvieux, c''est dommage', 1.0, 'Belle vue mais manque d''histoire', 'Solo', '2024-11-17', FALSE, FALSE),
(6, 6, 'Je suis déçu. La ville est belle, mais la visite était mal organisée et les arrêts étaient trop courts pour vraiment profiter des lieux.', 2.0, 'Visite mal organisée', 'Famille', '2024-10-02', FALSE, FALSE),
(6, 7, 'Je ne recommande pas cette visite. Le guide était ennuyeux et ne savait pas répondre aux questions. L''ambiance était terne.', 1.5, 'Guide ennuyeux', 'Solo', '2024-11-03', FALSE, FALSE),
(6, 8, 'Pas assez d''informations intéressantes. La visite manquait d''animation et le guide semblait pressé. Nous avons à peine eu le temps de poser des questions.', 2.5, 'Visite trop rapide et fade', 'Amis', '2024-10-04', FALSE, FALSE),
(6, 9, 'Très déçu de cette visite. Le groupe était trop grand et le guide avait du mal à s''adapter. Beaucoup de bruit et de distractions.', 2.0, 'Trop de monde et peu d''attention', 'Couple', '2024-11-05', FALSE, FALSE),
(6, 10, 'La visite était longue et monotone. Il n''y avait pas beaucoup de dynamisme et cela ne m''a pas du tout captivé. Je m''attendais à mieux.', 2.0, 'Visite trop monotone', 'Famille', '2024-11-06', FALSE, FALSE),
(6, 11, 'Une expérience fantastique. Le guide était passionné et a rendu chaque moment intéressant. Nous avons découvert des coins cachés de Quimper que nous n’aurions jamais trouvés seuls.', 5.0, 'À faire absolument', 'Famille', '2024-11-06', FALSE, FALSE),
(6, 12, 'Une visite fascinante avec un guide incroyablement compétent. Quimper est une ville magnifique et cette visite en a révélé tous les secrets. Très bien organisé, je recommande à 100%.', 5.0, 'Expérience mémorable', 'Solo', '2024-11-07', FALSE, FALSE),
(6, 13, 'Visite très intéressante, mais les informations étaient parfois un peu superficielles. Le guide pourrait approfondir certains points.', 3.5, 'Bonne visite, mais manque de détails', 'Famille', '2024-11-28', FALSE, FALSE),
(6, 14, 'L’ambiance était agréable, mais j’aurais aimé plus de temps pour explorer la ville à mon rythme.', 4.0, 'Sympathique mais trop court', 'Solo', '2024-11-29', FALSE, FALSE),
(7, 5, 'Une visite guidée très enrichissante ! La rade de Brest, le château et le musée de la Marine étaient impressionnants. Très bien organisé, je recommande.', 5.0, 'Visite incontournable', 'Famille', '2024-10-10', FALSE, FALSE),
(8, 6, 'Le spectacle était bien, mais je m’attendais à plus de finesse dans les marionnettes. Un peu déçu par l’ensemble.', 2.5, 'Dommage', 'Amis', '2024-09-16', FALSE, FALSE),
(9, 8, 'Les montagnes russes à Perros-Guirec étaient fantastiques, mais la balade en kayak le long des côtes rocheuses était encore plus incroyable. À refaire !', 5.0, 'Superbes paysages', 'Famille', '2024-09-12', FALSE, FALSE),
(10, 13, 'Très belle randonnée sur le GR34 à Saint-Malo. La vue sur l’océan était magnifique, mais l’itinéraire était un peu trop long et fatigant pour les enfants.', 3.5, 'Belle expérience, mais fatigante', 'Famille', '2024-08-05', FALSE, FALSE),
(11, 12, 'Une excursion en bateau à Douarnenez très agréable. Le paysage était superbe, mais le bateau était un peu vieux et il manquait un peu d’animations pendant la traversée.', 3.5, 'Bateau agréable, mais vieillissant', 'Couple', '2024-09-18', FALSE, FALSE),
(12, 7, 'Le musée de la mer à Paimpol est une excellente visite pour les passionnés d’histoire maritime. Les expositions étaient très intéressantes et bien présentées.', 5.0, 'Visite fascinante', 'Solo', '2024-10-21', FALSE, FALSE),
(13, 6, 'Le restaurant à Rennes offre une très bonne expérience gastronomique bretonne. Les crêpes et le cidre étaient délicieux, mais le service était un peu lent.', 4.0, 'Bonne expérience mais service lent', 'Couple', '2024-11-11', FALSE, FALSE),
(14, 18, 'Une belle visite de Vannes, mais il y avait trop de monde ce jour-là. Le guide était sympathique, mais nous avons eu du mal à entendre les explications.', 3.0, 'Sympa mais trop fréquenté', 'Amis', '2024-12-02', FALSE, FALSE),
(15, 15, 'Le restaurant exotique à Rennes est une expérience culinaire unique. Les plats étaient délicieux et originaux, mais l’ambiance était un peu trop bruyante pour profiter pleinement.', 4.0, 'Bonne expérience mais bruyant', 'Famille', '2024-12-03', FALSE, FALSE);

-- Peupler la table _reponseAvis
INSERT INTO _reponseAvis (idAvis, idCompte, commentaire) VALUES
(1, 2, 'Merci pour votre avis positif ! Nous sommes ravis que vous ayez apprécié la visite.'),
(2, 3, 'Nous sommes désolés que le spectacle n’ait pas été à la hauteur de vos attentes.'),
(3, 4, 'Merci pour votre retour ! Nous espérons vous revoir bientôt.'),
(4, 5, 'Nous sommes heureux que vous ayez aimé le restaurant. À bientôt !');

INSERT INTO _facture (idOffre, datePrestaServices, dateEcheance) VALUES -- septembre octobre rien septembre
(1, '2024-09-01','2024-10-20'),
(1, '2024-10-01','2024-11-20'),
(1, '2024-11-01','2024-12-20'),
(1, '2024-12-01','2025-01-20'),
(2, '2024-10-01','2024-11-20'),
(2, '2024-11-01','2024-12-20'),
(2, '2024-12-01','2025-01-20'),
(3, '2024-12-01','2025-01-20'),
(4, '2024-09-01','2024-10-20'),
(4, '2024-10-01','2024-11-20'),
(4, '2024-11-01','2024-12-20'),
(4, '2024-12-01','2025-01-20'),
(13,'2024-12-01','2025-01-20'),
(14,'2025-01-01','2025-02-20'),
(15,'2025-01-01','2025-02-20');

INSERT INTO _historiqueEnLigne(idOffre, jourDebut, jourFin) VALUES
(1,'2024-09-13','2024-09-22'),
(1,'2024-10-15','2024-10-27'),
(1,'2024-10-29','2024-10-31'),
(1,'2024-11-01','2024-11-13'),
(1,'2024-11-19','2024-11-30'),
(1,'2024-12-01', null),
(2,'2024-10-13','2024-10-22'),
(2,'2024-11-15','2024-11-27'),
(3,'2024-12-03', null),
(4,'2024-09-29','2024-09-30'),
(4,'2024-10-01','2024-10-31'),
(4,'2024-11-01','2024-11-13'),
(4,'2024-11-19','2024-11-30'),
(13,'2024-12-01','2024-12-13'),
(14,'2025-01-04',null);

INSERT INTO _annulationOption(nbSemaines, debutOption, idOffre, nomOption, estAnnulee) VALUES
(1, '2024-09-09',1, 'En relief',False),
(2, '2024-10-07',1, 'A la une' ,false),
(4, '2024-09-23',1, 'En relief',True),
(1, '2024-10-28',1, 'A la une',False),
(2, '2024-12-02',1, 'A la une',false),
(4, '2024-10-14',2, 'En relief',False),
(2, '2024-11-11',2, 'A la une' ,false),
(4, '2024-12-02',2, 'A la une' ,false),
(4, '2024-12-02',3, 'En relief',true),
(4, '2024-12-02',3, 'A la une' ,false),
(4, '2024-09-23',4, 'En relief',True),
(3, '2024-10-14',4, 'A la une',False),
(4, '2025-01-06',13, 'A la une' ,false),
(3, '2025-01-06',14, 'A la une' ,false),
(2, '2025-01-06',15, 'A la une' ,false);

INSERT INTO _representeAvis(idAvis, idImage) VALUES
(9, 18),
(11, 19),
(11, 20);
