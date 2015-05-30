DROP DATABASE IF EXISTS blackroom;

CREATE DATABASE IF NOT EXISTS blackroom;

USE blackroom;

--
-- Table structure for table `customers_auth`
--

CREATE TABLE IF NOT EXISTS `customers_auth` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
);

--
-- Dumping data for table `customers_auth`
--

INSERT INTO `customers_auth` (`name`, `email`, `password`, `created`) VALUES
('Swadesh Behera', 	'swadesh@gmail.com', '$2a$10$251b3c3d020155f7553c1ugKfEH04BD6nbCbo78AIDVOqS3GVYQ46', '2014-08-31 18:21:20'),
('Ipsita Sahoo', 	'ipsita@gmail.com',  '$2a$10$d84ffcf46967db4e1718buENHT7GVpcC7FfbSqCLUJDkKPg4RcgV2', '2014-08-31 18:30:58'),
('Trisha Tamanna', 	'trisha@gmail.com',  '$2a$10$c9b32f5baa3315554bffcuWfjiXNhO1Rn4hVxMXyJHJaesNHL9U/O', '2014-08-31 18:32:03'),
('Sai Rimsha', 		'rimsha@gmail.com',  '$2a$10$477f7567571278c17ebdees5xCunwKISQaG8zkKhvfE5dYem5sTey', '2014-08-31 20:34:21'),
('Satwik Mohanty', 	'satwik@gmail.com',  '$2a$10$2b957be577db7727fed13O2QmHMd9LoEUjioYe.zkXP5lqBumI6Dy', '2014-08-31 20:36:02'),
('Tapaswini Sahoo', 'linky@gmail.com', 	 '$2a$10$b2f3694f56fdb5b5c9ebeulMJTSx2Iv6ayQR0GUAcDsn0Jdn4c1we', '2014-08-31 20:44:54'),
('Manas Ranjan', 	'manas@gmail.com', 	 '$2a$10$03ab40438bbddb67d4f13Odrzs6Rwr92xKEYDbOO7IXO8YvBaOmlq', '2014-08-31 20:45:08'),
('Angular Admin',	'admin@angular.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2014-08-31 21:00:26');