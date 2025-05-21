CREATE TABLE `account` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int DEFAULT NULL
);

INSERT INTO `account` (`id`, `email`, `password`, `role`) VALUES
(9, 'kelvin@kelvin.nl', '$2y$12$w2fuXiPg1m2jC.C9BCCB5ebeEPNUcwxVp2StqdFJa9y62xwwmfKWK', NULL),
(10, 'cassandra@cassandra.nl', '$2y$12$pVGqaOKe9t0QZZozeub4ueghtgx09JEKWb/ohSPhh6VCucC8Zpplm', NULL);

ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
  
ALTER TABLE `account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `gas` varchar(10) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `seats` varchar(50) NOT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
);

INSERT INTO `cars` (`name`, `slug`, `type`, `price`, `gas`, `transmission`, `seats`, `description`, `image`) VALUES
('Koenigsegg Agera RS', 'koenigsegg-agera-rs', 'Supercar', 450.00, '90l', 'Automaat', '2 Personen', 'Een van de snelste productieauto\'s ter wereld met ongeëvenaarde prestaties en luxe.', 'assets/images/products/Car (0).svg'),
('Nissan GTR R35', 'nissan-gtr-r35', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (1).svg'),
('Rolls Royce Wraith', 'rolls-royce-wraith', 'Luxe Sedan', 300.00, '85l', 'Automaat', '4 Personen', 'De ultieme luxe cabriolet met ongeëvenaarde elegantie en comfort.', 'assets/images/products/Car (2).svg'),
('Nissan GTR R35', 'nissan-gtr-r35-2', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (3).svg'),;
