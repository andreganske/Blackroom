DROP DATABASE IF EXISTS blackroom;

CREATE DATABASE IF NOT EXISTS blackroom;

USE blackroom;

CREATE TABLE IF NOT EXISTS br_customer (
	customer_id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	password varchar(200) NOT NULL,
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	active bool NOT NULL DEFAULT true,
    admin_id int(11),
	PRIMARY KEY (customer_id)
);

CREATE TABLE IF NOT EXISTS br_album (
	album_id int(11) NOT NULL AUTO_INCREMENT,
    customer_id int(11),
    name varchar(50) NOT NULL,
    description varchar(250) NOT NULL,
    active bool NOT NULL DEFAULT true,
    PRIMARY KEY (album_id),
	FOREIGN KEY (customer_id) REFERENCES br_customer(customer_id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS br_image (
	image_id int(11) NOT NULL AUTO_INCREMENT,
    customer_id int(11),
	name varchar(50) NOT NULL,
	type varchar(30) NOT NULL,
	size int NOT NULL,
    path varchar(50) NOT NULL,
	description varchar(250) NOT NULL,
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (image_id),
	FOREIGN KEY (customer_id) REFERENCES br_customer(customer_id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS br_album_image (
	album_image_id int(11) NOT NULL AUTO_INCREMENT,
    album_id int(11),
    image_id int(11),
    PRIMARY KEY (album_image_id),
	FOREIGN KEY (album_id) REFERENCES br_album(album_id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES br_image(image_id) ON DELETE CASCADE
);

INSERT INTO br_customer (name, email, password) VALUES
('Blackroom Admin', 'admin@admin.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S'),
('Angular Admin', 'angular@admin.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S');

INSERT INTO br_customer (name, email, password, admin_id) VALUES
('Blackroom Guest', 'admin_guest', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', 1),
('Angular Guest', 'angular_guest', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', 1);

INSERT INTO br_album (customer_id, name, description) VALUES
(1, 'formatura udesc 2015', 'Fotos para os convites dos formandos'),
(1, 'formatura udesc 2015', 'Fotos da colação de grau'),
(1, 'formatura udesc 2015', 'Fotos da entrada dos formandos'),
(1, 'formatura udesc 2015', 'Fotos do baile'),
(2, 'Casamento da maria e do jose', 'Fotos do último casamento'),
(2, 'Casamento', 'Fotos do último casamento'),
(2, 'Casamento', 'Fotos do último casamento');

INSERT INTO br_image (customer_id, name, type, size, description, path) VALUES
(1, 'caminhos', 'jpg', '7211', 'Uma foto inserida por script', 'api/users/1/photo-1424746219973-8fe3bd07d8e3.jpg'),
(1, 'por do sol', 'jpg', '2461', 'Mais foto inserida por script', 'api/users/1/photo-1428452932365-4e7e1c4b0987.jpg'),
(1, 'jumping', 'jpg', '2217', 'Olha foto inserida por script', 'api/users/1/photo-1428954376791-d9ae785dfb2d.jpg'),
(1, 'road', 'jpg', '2506', 'Quer foto inserida por script', 'api/users/1/photo-1429042007245-890c9e2603af.jpg');

INSERT INTO br_image (customer_id, name, type, size, description, path) VALUES
(1, 'caminhos', 'jpg', '7211', 'Uma foto inserida por script', 'api/users/1/photo-1424746219973-8fe3bd07d8e3.jpg'),
(1, 'caminhos', 'jpg', '7211', 'Uma foto inserida por script', 'api/users/1/photo-1424746219973-8fe3bd07d8e3.jpg');

