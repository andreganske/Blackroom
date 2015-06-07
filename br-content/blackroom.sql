DROP DATABASE IF EXISTS blackroom;

CREATE DATABASE IF NOT EXISTS blackroom;

USE blackroom;

CREATE TABLE IF NOT EXISTS customer (
	customer_id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(50) NOT NULL,
	document varchar(50),
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (customer_id)
);

CREATE TABLE IF NOT EXISTS customers_auth (
	customers_auth_id int(11) NOT NULL AUTO_INCREMENT,
    customer_id int(11),
	email varchar(50) NOT NULL,
	password varchar(200) NOT NULL,
	created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (customers_auth_id),
	FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
	ON DELETE CASCADE
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
	FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
    ON DELETE CASCADE
);

INSERT INTO customer (customer_id, name, created) VALUES
(0001, 'Blackroom Admin', '2015-06-06 18:21:20'),
(0002, 'Angular Admin',	'2015-06-06 21:00:26');

INSERT INTO customers_auth (customer_id, email, password, created) VALUES
(0001, 'blackroom@admin.com', '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 18:21:20'),
(0002, 'angular@admin.com',   '$2a$10$72442f3d7ad44bcf1432cuAAZAURj9dtXhEMBQXMn9C8SpnZjmK1S', '2015-06-06 21:00:26');