SET FOREIGN_KEY_CHECKS = 0; 
DROP TABLE IF EXISTS booker_events;
DROP TABLE IF EXISTS booker_users;
DROP TABLE IF EXISTS booker_rooms;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE booker_users (
    id INT(12) NOT NULL AUTO_INCREMENT, 
    username VARCHAR(200) NOT NULL,
    password VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    is_admin BOOLEAN,
    is_active BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE booker_rooms (
    id INT(12) AUTO_INCREMENT, 
    name VARCHAR(100),
    PRIMARY KEY (id)
);

CREATE TABLE booker_events (
    id INT(12) NOT NULL AUTO_INCREMENT,
	idrec INT(12) NULL,
    description VARCHAR(200) NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	idroom INT(12) NOT NULL,
    iduser INT(12) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (iduser) REFERENCES booker_users(id),
	FOREIGN KEY (idroom) REFERENCES booker_rooms(id)
);




