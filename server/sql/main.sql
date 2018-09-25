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
    is_recurring BOOLEAN,
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




INSERT INTO booker_rooms (id, name) VALUES
(1, 'main boardroom'),
(2, 'meeting room'),
(3, 'small meeting room');


INSERT INTO booker_users (id, username, password, email, fullname, is_admin, is_active) VALUES
(1, 'admin', '123', 'admin@localhost', 'John Smith', true, true),
(2, 'user1', '123', 'user1@localhost', 'Andrew Mirret', false, true),
(3, 'user2', '123', 'user2@localhost', 'Peter West', false, true);

INSERT INTO booker_events (id, is_recurring, idrec, description, start_time, end_time, idroom, iduser) VALUES
(0, false, 1, 'Expired event','2018-09-10 14:00:00', '2018-09-10 14:30:00', 1, 2),
(0, false, 2, 'The first event','2018-09-22 10:00:00', '2018-09-22 11:30:00', 1, 2),
(0, false, 3, 'The second event','2018-09-22 11:30:00', '2018-09-22 13:00:00', 1, 2),
(0, true, 4, 'The third event','2018-09-23 12:00:00', '2018-09-23 13:00:00', 1, 3),
(0, true, 4, 'The third event','2018-09-30 12:00:00', '2018-09-30 13:00:00', 1, 3),
(0, false, 5, 'The fourth event','2018-09-26 19:00:00', '2018-09-26 20:00:00', 2, 2);
