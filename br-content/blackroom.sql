DROP DATABASE IF EXISTS blackroom;

CREATE DATABASE IF NOT EXISTS blackroom;

USE blackroom;

CREATE TABLE IF NOT EXISTS customer_auth (
	customer_id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	password varchar(200) NOT NULL,
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (customer_id)
);

CREATE TABLE IF NOT EXISTS customers_image (
	image_id int(11) NOT NULL AUTO_INCREMENT,
    customer_id int(11),
	name varchar(50) NOT NULL,
	type varchar(30) NOT NULL,
	size int NOT NULL,
	description varchar(250) NOT NULL,
	content BLOB NOT NULL,
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (image_id),
	FOREIGN KEY (customer_id) REFERENCES customer_auth(customer_id)
    ON DELETE CASCADE
);

INSERT INTO customer_auth (name, email, password, created) VALUES
('Blackroom Admin', 'admin@admin.com', 	 '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 18:21:20'),
('Angular Admin', 	'angular@admin.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 21:00:26');