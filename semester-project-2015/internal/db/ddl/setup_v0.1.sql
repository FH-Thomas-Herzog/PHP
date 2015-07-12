-- delete all existing data --
DELETE FROM fh_2015_scm4_s1310307011.THREAD;
DELETE FROM fh_2015_scm4_s1310307011.CHANNEL;
DELETE FROM fh_2015_scm4_s1310307011.USER;
DELETE FROM fh_2015_scm4_s1310307011.LOCALE;
DELETE FROM fh_2015_scm4_s1310307011.USER_TYPE;

-- reset auto increment back to one --
ALTER TABLE fh_2015_scm4_s1310307011.USER AUTO_INCREMENT = 1;
ALTER TABLE fh_2015_scm4_s1310307011.CHANNEL AUTO_INCREMENT = 1;
ALTER TABLE fh_2015_scm4_s1310307011.THREAD AUTO_INCREMENT = 1;

-- craete locale entries --
INSERT INTO fh_2015_scm4_s1310307011.LOCALE (id, resource_key)
VALUES ('de_DE', 'german');
INSERT INTO fh_2015_scm4_s1310307011.LOCALE (id, resource_key)
VALUES ('en_US', 'english');

-- create user types --
INSERT INTO fh_2015_scm4_s1310307011.USER_TYPE (id) 
VALUES ('EXTERNAL_USER');
INSERT INTO fh_2015_scm4_s1310307011.USER_TYPE (id) 
VALUES ('INTERNAL_USER');
INSERT INTO fh_2015_scm4_s1310307011.USER_TYPE (id) 
VALUES ('ADMIN_USER');

-- create predefined channels --
INSERT INTO fh_2015_scm4_s1310307011.CHANNEL (title, description) 
VALUES ('PHP Development', 'This channel holds threads regarding php development');
INSERT INTO fh_2015_scm4_s1310307011.CHANNEL (title, description) 
VALUES ('Java Development', 'This channel holds threads regarding java development');
INSERT INTO fh_2015_scm4_s1310307011.CHANNEL (title, description) 
VALUES ('Common', 'This channel holds threads with common thread. These threads are not bound to any specific topic');

-- create one admin, internal, external user --
INSERT INTO fh_2015_scm4_s1310307011.USER (firstname, lastname, email, username, password, locale_id, user_type_id)
VALUES('Thomas', 'Herzog', 'thomas.herzog@students.fh-hagenberg.at', 'het', '1234', 'de_DE', 'ADMIN_USER');
INSERT INTO fh_2015_scm4_s1310307011.USER (firstname, lastname, email, username, password, locale_id, user_type_id)
VALUES('Max', 'Mustermann', 'max.mustermann@students.fh-hagenberg.at', 'max', '1234', 'en_US', 'EXTERNAL_USER');
INSERT INTO fh_2015_scm4_s1310307011.USER (firstname, lastname, email, username, password, locale_id, user_type_id)
VALUES('Franz', 'Mustermann', 'franz.mustermann@students.fh-hagenberg.at', 'franz', '1234', 'en_US', 'INTERNAL_USER');

-- create one thread for each channel --
INSERT INTO fh_2015_scm4_s1310307011.THREAD (owner_user_id, channel_id, title, description)
VALUES (3, 1, 'PHP ORM mapper', 'This thread is about php orm mappers');
INSERT INTO fh_2015_scm4_s1310307011.THREAD (owner_user_id, channel_id, title, description)
VALUES (3, 2, 'Java ORM mapper', 'This thread is about java orm mappers');
INSERT INTO fh_2015_scm4_s1310307011.THREAD (owner_user_id, channel_id, title, description)
VALUES (3, 3, 'Forum best pratice', 'This thread is about best pratices in this forum');