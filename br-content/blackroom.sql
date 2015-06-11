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
	PRIMARY KEY (customer_id)
);

CREATE TABLE IF NOT EXISTS br_album (
	album_id int(11) NOT NULL AUTO_INCREMENT,
    customer_id int(11),
    name varchar(50) NOT NULL,
    description varchar(250) NOT NULL,
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
	description varchar(250) NOT NULL,
	content BLOB NOT NULL,
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

INSERT INTO br_customer (name, email, password, created) VALUES
('Blackroom Admin', 'admin@admin.com', 	 '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 18:21:20'),
('Angular Admin', 	'angular@admin.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 21:00:26');

INSERT INTO br_image (customer_id, name, type, size, description, content) VALUES
(1, 'FOTO 01', 'JPG', '4', 'Uma foto inserida por script', 'bsdhsdhsdhsdhsdhsdh'),
(1, 'FOTO 02', 'JPG', '5', 'Mais foto inserida por script', 'bsdhsdhsdhsdhsdhsdh'),
(2, 'FOTO 03', 'JPG', '7', 'Olha foto inserida por script', 'bsdhsdhsdhsdhsdhsdh'),
(2, 'FOTO 04', 'JPG', '6', 'Quer foto inserida por script', 'bsdhsdhsdhsdhsdhsdh');

