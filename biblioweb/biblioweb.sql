-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 26 mai 2021 à 11:52
-- Version du serveur :  10.4.13-MariaDB
-- Version de PHP : 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biblioweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `authors`
--

CREATE TABLE `authors` (
  `id` int(10) UNSIGNED NOT NULL,
  `lastname` varchar(30) NOT NULL DEFAULT '',
  `firstname` varchar(30) NOT NULL DEFAULT '',
  `nationality` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `authors`
--

INSERT INTO `authors` (`id`, `lastname`, `firstname`, `nationality`) VALUES
(1, 'Simenon', 'Georges', 'Belgique'),
(2, 'Zola', 'Émile', 'France'),
(3, 'Clark', 'Marie-Higgins', 'Grande-Bretagne'),
(4, 'Dick', 'Philip K.', 'États-Unis'),
(5, 'Balzac', 'Honoré de', 'France'),
(7, 'Maupassant', 'Guy', 'France');

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `ref` int(10) UNSIGNED NOT NULL,
  `title` varchar(60) NOT NULL DEFAULT '',
  `author_id` int(10) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`ref`, `title`, `author_id`, `description`, `cover_url`) VALUES
(1, 'Ubik', 4, 'Ubik (titre original Ubik) est un roman de Philip K. Dick, écrit en 1966, publié aux États-Unis en 1969 et en France en 1970 dans une traduction d\'Alain Dorémieux.\r\n\r\nŒuvre classique de la littérature de science-fiction, en 2005 le magazine Time le classait parmi les 100 meilleurs romans écrits en anglais depuis 1923 ; le critique Lev Grossman a commenté ce livre comme une « histoire d\'horreur existentielle profondément troublante, un cauchemar dont vous ne serez jamais sûr de vous être réveillé1. »', 'Philip_K_Dick_Ubik.jpg'),
(2, 'Une vie', 7, 'Une vie est le premier roman de Guy de Maupassant, paru d\'abord en feuilleton en 1883 dans le Gil Blas, puis en livre, la même année, sous le titre Une vie (L\'humble vérité). Il décrit la vie « d\'une femme, depuis l\'heure où s\'éveille son cœur jusqu\'à sa mort1. »', 'Maupassant_Une_vie.jpg'),
(3, 'Germinal', 2, 'Germinal est un roman d\'Émile Zola publié en 1885. Il s\'agit du treizième roman de la série des Rougon-Macquart. Écrit d\'avril 1884 à janvier 1885, le roman paraît d\'abord en feuilleton entre novembre 1884 et février 1885 dans le Gil Blas. Il connaît sa première édition en mars 1885. Depuis, il a été publié dans plus d\'une centaine de pays.', 'Zola_Germinal.jpg'),
(4, 'Maigret et le voleur paresseux', 1, 'Maigret et le Voleur paresseux est un roman policier de Georges Simenon publié en 1961. Il fait partie de la série des Maigret. Son écriture s\'est déroulée entre les 17 et 23 janvier 1961.\r\nUne nuit, un homme est découvert, le crâne défoncé, au Bois de Boulogne. Le Parquet trouve sur les lieux Maigret que l\'inspecteur Fumel, du XVIe arrondissement, a cru bon d\'appeler, mais ces messieurs laissent entendre au commissaire qu\'il a d\'autres tâches à accomplir en un temps où les hold-up se multiplient.', 'Simenon_Maigret_et_le_voleur_paresseux.jpg'),
(5, 'Un crime en Hollande', 1, 'Un crime en Hollande est un roman policier de Georges Simenon écrit en mai à bord de l’Ostrogoth, Morsang-sur-Seine (Seine-et-Marne), et publié en juillet 1931. Il fait partie de la série des Maigret.\r\nAprès une soirée donnée chez les Popinga en l\'honneur du professeur Jean Duclos venu faire une conférence à Delfzijl, Conrad Popinga a été tué d\'un coup de revolver. Maigret est envoyé dans la ville afin d\'enquêter sur le meurtre. Les suspects ne manquent pas : Duclos lui-même, qui a découvert bien vite l\'arme du crime ; Beetje Liewens, maîtresse de Conrad, revenue vers la maison des Popinga alors que son amant l\'avait reconduite chez elle ; l\'irascible fermier Liewens, qui avait appris la liaison de sa fille et la désapprouvait ; le jeune Cornélius Barens, amoureux de Beetje ; Oosting, le vieux marin dont la casquette a été retrouvée dans la salle de bains des Popinga ; enfin, Madame Popinga et sa sœur Any, restées à la maison après le départ des invités. La casquette d\'Oosting est l\'élément insolite de l\'affaire, car Maigret acquiert rapidement la certitude que le marin n\'avait aucune raison de tuer Popinga ; on lui a donc volé sa casquette pour le faire accuser.', 'Simenon_Maigret_Un_crime_en_Hollande.jpg'),
(6, 'Le Chien Jaune', 1, 'À Concarneau, des faits troublants qui s’enchaînent jettent l’émoi. C’est d’abord la tentative d’assassinat dont est victime l’honorable M. Mostaguen, un soir au sortir de sa partie de cartes à l’Hôtel de l’Amiral : il reçoit au ventre une balle tirée de la boîte aux lettres d’une maison vide. Et le sort semble s’acharner sur ses partenaires, car, deux jours après l’arrivée du commissaire Maigret, l’un des habitués du café, un journaliste du nom de Jean Servières disparaît, et sa voiture est retrouvée dans les environs, le siège avant maculé de sang. Puis, c’est au tour de M. Le Pommeret, qui meurt empoisonné. Le quatrième du groupe, le docteur Michoux, qui s’attend à y passer aussi, n’en mène pas large, et Maigret le fait incarcérer pour le protéger.', 'Simenon_Maigret_Le_chien_jaune.jpg'),
(7, 'Maigret à la mer', 1, 'C\'était merveilleux d\'être là, les yeux clos, de sentir les paupières picoter sous la caresse d\'un soleil filtré par les rideaux jaunes ; c\'était merveilleux surtout de se dire qu\'il était deux heures et demie, ou trois heures de l\'après-midi, peut-être plus, peut-être moins, et qu\'au surplus ce rongeur de l\'existence qu\'on appelle une montre n\'avait en l\'occurrence aucune importance.', 'Simenon_Maigret_a_la_mer.jpg'),
(8, 'La Danseuse du Gai-Moulin', 1, 'La Danseuse du Gai-Moulin est un roman policier de Georges Simenon écrit dans une villa d\'Ouistreham en septembre 1931 et publié en novembre 1931. Il fait partie de la série des Maigret.\r\nDeux jeunes noceurs endettés – un bourgeois désaxé et le fils d\'un employé – fréquentent à Liège « Le Gai-Moulin », une boîte de nuit où ils courtisent l\'entraîneuse Adèle. À la fin d\'une soirée qu\'elle a passée, à une table voisine des jeunes gens, en compagnie d\'un Levantin arrivé le jour même dans la ville, Delfosse et Chabot se laissent enfermer dans la cave de l\'établissement afin de s\'emparer de la recette. Dans l\'obscurité, ils entr\'aperçoivent ce qu\'ils croient être un cadavre, celui du Levantin ; ils prennent la fuite. Le lendemain, émoi dans la presse : le corps d\'Ephraïm Graphopoulos, le client de passage, est découvert à l\'intérieur d\'une manne d\'osier abandonnée dans un jardin public.', 'Simenon_Maigret_La_danseuse_du_Gai-Moulin.jpg'),
(9, 'Le Chat', 1, 'Le Chat est un roman de l\'écrivain Georges Simenon, publié en 1967.\r\n\r\nLe roman est adapté au cinéma par Pierre Granier-Deferre sous le titre Le Chat dans un film sorti en 1971 en France, avec pour interprètes principaux Jean Gabin et Simone Signoret, et au théâtre pour la première fois en 2016 au Théâtre de l\'Atelier avec Myriam Boyer et Jean Benguigui dans les rôles principaux.\r\nÉmile est un ancien ouvrier au naturel bourru, sans complications comme sans éducation. Marguerite, à l\'opposé, est une femme délicate, d\'une douceur affectée, mais sournoise et avare, vivant à côté de la vie. Elle provient d\'une famille propriétaire, dans le quartier, de nombreux immeubles qu\'on est occupé à démolir. Ils étaient voisins lorsqu\'ils se sont rencontrés par hasard et ils se sont mariés, lui à 65 ans, elle à 63, peut-être par peur de la solitude et de la vieillesse. Le souvenir de leur conjoint disparu – sa première femme, Adèle, était une bonne fille d\'une gaieté communicative ; son premier mari, Charmois, était un musicien aux manières distinguées – ne fait qu\'aviver un manque de compréhension qui ne tarde pas à se muer en hostilité sourde.', 'Simenon_Le_chat.jpg'),
(10, 'Blade Runner (Do Androids Dream of Electric Sheep?)', 4, 'Les androïdes rêvent-ils de moutons électriques ?N 1 (titre original : Do Androids Dream of Electric Sheep?) est un roman de science-fiction écrit par Philip K. Dick en 1966[réf. souhaitée] et publié deux ans plus tard aux États-Unis. Il fut publié en français pour la première fois en 1976 par les éditions Champ libre, dans la collection « Chute libre ».\r\n\r\nŒuvre majeure dans la bibliographie de son auteur, elle marqua le début de sa reconnaissance par le public américain grâce à son adaptation cinématographique par Ridley Scott sortie en 1982, avec le film Blade Runner. Le roman a d\'ailleurs été réédité par la suite sous le titre Blade Runner.', 'Dick_Blade_Runner.jpg'),
(11, 'Minority Report', 4, 'Rapport minoritaire (titre original : The Minority Report) est une nouvelle de science-fiction de Philip K. Dick publiée pour la première fois en janvier 1956.\r\n\r\nLa nouvelle a été adaptée au cinéma par Steven Spielberg en 2002 sous le titre Minority Report.\r\n\r\nL\'histoire se déroule dans une société où les meurtres peuvent être prédits grâce à des mutants doués de précognition : les « précogs ». Au nombre de trois, ces mutants ont une vision souvent convergente des crimes devant advenir. Il arrive cependant que l\'une de ces trois visions soit en désaccord avec les deux autres, d\'où le titre (commun à la nouvelle et au film) de \"rapport minoritaire\".\r\nParadoxes et réalités conditionnelles commencent à émaner de ces précogs, lorsque le chef de la police reçoit une précognition, révélant qu\'il tuera prochainement un homme qu\'il n\'a jamais rencontré ni connu.', 'Fantastic_universe_195601.jpg'),
(12, 'Paycheck', 4, 'La Clause du salaire (titre original : Paycheck) est une nouvelle de Philip K. Dick parue en juin 1953 dans le magazine Imagination.\r\nJennings, un ingénieur en électronique, accepte un contrat secret avec Rethrick Construction. Les termes du contrat exigent qu\'il travaille pendant deux ans sur un projet secret, après quoi sa mémoire sera effacée en échange d\'une importante somme d\'argent. À son réveil, tout ne se passe pas comme prévu.', 'Imagination_195306.jpg'),
(13, 'En attendant l\'année dernière', 4, 'En attendant l\'année dernière (titre original : Now Wait for Last Year) est un roman de Philip K. Dick paru en 1966 et édité pour la première fois en France en 1968 aux éditions OPTA.\r\nDans une Amérique futuriste, le docteur Eric Sweetscent, médecin de talent mais timide et criblé de dettes, est tyrannisé par sa femme Kathy, belle et arrogante, qui collectionne les antiquités du xxe siècle pour le compte d\'un milliardaire plus que centenaire. La Terre est soumise à un quasi-dictateur, Gino Molinari, mais celui-ci est « un homme malade, un hypocondriaque démoralisé ». Il a été entraîné contre son gré dans une guerre interstellaire opposant les Lilistariens, humanoïdes sans pitié, aux reegs, dont on ne sait pas grand-chose à part leur apparence insectoïde.', 'Dick_En_attendant_l_annee_derniere.jpg'),
(14, 'Le Crâne', 4, 'Premier des huit volumes de l\'intégrale des nouvelles de Philip K. Dick restées inédites en France ou devenues difficilement accessibles, Le Crâne comporte, présentés dans l\'ordre chronologique de parution,\r\nsept récits datant des années 1952-1953. On y voit déjà se dessiner certaines des composantes majeures de l\'œuvre future de Dick : le simulacre, comme dans cette \"Colonie\" peuplée d\'entités capables de simuler tous les objets inanimés contenus dans le vaisseau de leurs envahisseurs pour les assassiner par surprise, et d\'une façon générale, la méfiance vis-à-vis du réel. Un thème que développe, à titre de préface, le texte d\'une conférence célèbre, \"Comment construire un univers qui ne s\'effondre pas deux jours plus tard\", qui a donné lieu à une adaptation théâtrale au festival d\'Avignon 93.', 'Dick_Le_crane.jpg'),
(15, 'Le Horla', 7, 'Le Horla est une longue nouvelle fantastique et psychologique de Guy de Maupassant parue en 1886, puis dans une seconde version en 1887. L\'auteur y décrit la déchéance progressive et dramatique du narrateur poursuivi par une créature invisible, baptisée « le Horla », dont il ne sait si elle est réelle ou le résultat d\'un trouble psychiatrique. Il cherchera à s\'en débarrasser par tous les moyens possibles. Dans ce récit psychologique, Maupassant présente un personnage autodestructeur constamment torturé, d\'abord gagné par le doute et qui finit par sombrer dans la démence en passant par divers états, tels la paranoïa, les hallucinations, les crises d\'angoisse, la paralysie du sommeil, avec lesquels il se débat. La forme du journal intime de la seconde version, la plus connue, favorise encore davantage l\'identification au narrateur.', 'Guy_de_Maupassant_le_Horla-edition1908.jpg'),
(16, 'Boule de Suif', 7, 'Boule de Suif est une nouvelle de Guy de Maupassant, écrite dans le courant de l\'année 1879, rendue publique en 1880, d\'abord par une lecture faite en janvier par l\'auteur devant ses amis du « groupe de Médan », puis par la publication au sein d\'un recueil collectif de nouvelles titré Les Soirées de Médan, le 15 avril 1880.\r\nPendant l\'hiver, 1870-71, durant la guerre franco-prussienne, la ville de Rouen (Normandie) est envahie par les Prussiens. Pour fuir l\'occupation, dix personnes prennent la diligence de Dieppe : un couple de commerçants, un couple de bourgeois, un couple de nobles, deux religieuses, un démocrate et enfin, une prostituée, la patriotique Élisabeth Rousset, surnommée « Boule de Suif ».', 'Maupassant_Boule-de-suif.jpg'),
(17, 'Bel Ami', 7, 'Bel-Ami est un roman réaliste de Guy de Maupassant (1850-1893), publié en 1885. Le roman paraît d\'abord sous forme de feuilleton dans le quotidien Gil Blas, avant d\'être édité en volume aux éditions Victor Havard. Les éditions Ollendorff publieront la première édition illustrée en 1895. L\'action du récit se déroule à Paris pendant la seconde moitié du xixe siècle.\r\n\r\nLe roman retrace l’ascension sociale de Georges Duroy (ou Georges Du Roy de Cantel), homme ambitieux et séducteur sans scrupules (arriviste et opportuniste), employé au bureau des chemins de fer du Nord, parvenu au sommet de la pyramide sociale parisienne grâce à ses maîtresses et à la collusion entre la finance, la presse et la politique. Sur fond de politique coloniale, Maupassant décrit les liens étroits entre le capitalisme, la politique, la presse mais aussi l’influence des femmes, privées de vie politique depuis le code Napoléon et qui œuvrent dans l’ombre pour éduquer et conseiller.', 'Maupassant_Bel-Ami.jpg'),
(18, 'L\'Assommoir', 2, 'L\'Assommoir est un roman d\'Émile Zola publié en feuilleton dès 1876 dans Le Bien public, puis dans La République des Lettres1, avant sa sortie en livre en 1877 chez l\'éditeur Georges Charpentier. C\'est le septième volume de la série Les Rougon-Macquart. L\'ouvrage est totalement consacré au monde ouvrier et, selon Zola, c\'est « le premier roman sur le peuple, qui ne mente pas et qui ait l\'odeur du peuple2 ». L\'écrivain y restitue la langue et les mœurs des ouvriers, tout en décrivant les ravages causés par la misère et l\'alcoolisme. À sa parution, l\'ouvrage suscite de vives polémiques car il est jugé trop cru. Mais c\'est ce naturalisme qui, cependant, provoque son succès, assurant à l\'auteur fortune et célébrité.', 'Emile_Zola_l_assommoir.jpg'),
(19, 'La Bête humaine', 2, 'La Bête humaine est un roman d\'Émile Zola publié en 1890, dix-septième volume de la série Les Rougon-Macquart. Il est le résultat de la fusion d\'un roman sur la justice et d\'un roman sur le monde ferroviaire, ce qui n\'était pas dans le dessein initial de l\'auteur.\r\n', 'Zola_La-Bete-humaine.jpg'),
(20, 'Nana', 2, 'Nana est un roman d’Émile Zola d\'abord publié sous forme de feuilleton dans Le Voltaire du 16 octobre 1879 au 5 février 1880, puis en volume chez Georges Charpentier, le 14 février 1880. C\'est le neuvième volume de la série Les Rougon-Macquart. Cet ouvrage traite du thème de la prostitution féminine à travers le parcours d’une lorette puis cocotte dont les charmes ont affolé les plus hauts dignitaires du Second Empire. Le récit est présenté comme la suite de L\'Assommoir.\r\n\r\nL’histoire commence en 1867, peu avant la deuxième exposition universelle, et dépeint deux catégories sociales symboliques, celle des courtisanes et celle des noceurs. Zola, chef de file du mouvement naturaliste, prétend montrer la société telle qu’elle était mais choisit aussi ce sujet scandaleux car il fait vendre, 55 000 exemplaires du texte de Charpentier étant achetés dès le premier jour de sa publication.', 'Zola_Nana.jpg'),
(21, 'Thérèse Raquin', 2, 'Thérèse Raquin est le troisième roman de l\'écrivain français Émile Zola publié en 1867. Ce roman, qui présente déjà les caractéristiques du naturalisme développé plus tard dans le cycle des Rougon-Macquart, fera connaître l\'écrivain au public parisien. L\'auteur en tirera lui-même, en 1873, une pièce de théâtre intitulée Thérèse Raquin : drame en 4 actes.', 'Zola_Therese_Raquin.jpg'),
(22, 'La Croisière de Noël', 3, 'Mary Higgins Clark et sa fille Carol vous accueillent à bord du Royal Mermaid.\r\nComme Alvirah Meehan et Regan Reilly, leurs héroïnes préférées, vous ne risquez pas d\'oublier cette croisière de Noël. Disparitions, menaces, détournements... Le voyage s\'annonce mouvementé !\r\n\r\nSens de l\'intrigue, rebondissements inattendus, humour : les deux reines du suspense vous souhaitent un Noël plein de frissons...', 'Clark_La-croisiere-de-Noel.jpg'),
(23, 'Cette chanson que je n\'oublierai jamais', 3, 'Cette chanson que je n\'oublierai jamais (I Heard That Song Before) est un roman policier de Mary Higgins Clark paru en 2007.\r\nPeter Carrington est accusé de meurtre. Tout le monde le lâche, sauf sa femme. Celle-ci connaît un secret qui mettra sa vie et celle de son mari en danger.', 'Clark_Cette_chanson.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `loans`
--

CREATE TABLE `loans` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `loans`
--

INSERT INTO `loans` (`id`, `user_id`, `book_id`, `return_date`) VALUES
(3, 23, 1, '2021-03-31'),
(4, 4, 2, '2021-03-26');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `statut` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `password` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `statut`, `password`, `created_at`, `updated_at`) VALUES
(4, 'ced', 'ceruth@epfc.eu', 'admin', '$2y$10$ob1SqnDOG1SpwrZvI1QOHOmFpT7NcnnQrPOOAAQjqaVGyB1ItPQti', '2007-06-21 00:00:00', NULL),
(23, 'jim', 'jim@sull.com', 'novice', NULL, '2020-04-24 13:39:03', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ref`),
  ADD KEY `titre` (`title`),
  ADD KEY `auteur_id` (`author_id`);

--
-- Index pour la table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `nom` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `ref` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`ref`) ON UPDATE CASCADE,
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
