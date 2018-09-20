
/*SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE TABLE booker_rooms;
TRUNCATE TABLE booker_users;
TRUNCATE TABLE booker_events;
SET FOREIGN_KEY_CHECKS=1;
*/

INSERT INTO booker_rooms (id, name) VALUES
(1, 'Boardroom 1'),
(2, 'Boardroom 2'),
(3, 'Boardroom 3');


INSERT INTO booker_users (id, username, password, email, fullname, is_admin, is_active) VALUES
(1, 'admin', '123', 'admin@localhost', 'John Smith', true, true),
(2, 'user1', '123', 'user1@localhost', 'Andrew Mirret', false, true),
(3, 'user2', '123', 'user2@localhost', 'Peter West', false, true);

INSERT INTO booker_events (id, idrec, description, start_time, end_time, idroom, iduser) VALUES
(0, 1, 'Expired event','2018-09-10 14:00:00', '2018-09-10 14:30:00', 1, 2),
(0, 2, 'The first event','2018-09-22 10:00:00', '2018-09-22 11:30:00', 1, 2),
(0, 3, 'The second event','2018-09-22 11:30:00', '2018-09-22 13:00:00', 1, 2),
(0, 4, 'The third event','2018-09-23 12:00:00', '2018-09-23 13:00:00', 1, 3),
(0, 4, 'The third event','2018-09-30 12:00:00', '2018-09-30 13:00:00', 1, 3),
(0, 5, 'The fourth event','2018-09-26 19:00:00', '2018-09-26 20:00:00', 2, 2);
