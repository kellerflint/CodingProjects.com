CREATE TABLE User
(
    user_id int NOT NULL AUTO_INCREMENT,
    user_name varchar(255) UNIQUE NOT NULL,
    user_nickname varchar(255) NOT NULL,
    user_password varchar(255) NOT NULL,
    user_hashed_password varchar(255) NULL,
    user_is_admin tinyint NOT NULL,

    PRIMARY KEY (user_id)
);

CREATE TABLE Session
(
    session_id int NOT NULL AUTO_INCREMENT,
    session_title varchar(255) NOT NULL,
    session_description varchar(5000) NOT NULL,

    PRIMARY KEY (session_id)
);

CREATE TABLE Category
(
    category_id int NOT NULL AUTO_INCREMENT,
    category_title varchar(255) NOT NULL,
    category_description varchar(5000) NULL,
    category_order int NOT NULL,

    PRIMARY KEY (category_id)
);

CREATE TABLE Project
(
    project_id int NOT NULL AUTO_INCREMENT,
    project_title varchar(255) NOT NULL,
    project_image varchar(255) NULL,
    project_description varchar(5000) NOT NULL,
    category_id int,

    PRIMARY KEY (project_id),
    FOREIGN KEY (category_id) REFERENCES Category (category_id) ON UPDATE CASCADE
);

CREATE TABLE Video
(
    video_id int NOT NULL AUTO_INCREMENT,
    project_id int NOT NULL,
    video_title varchar(255) NOT NULL,
    video_url varchar(255) NOT NULL,
    video_order int NOT NULL,

    PRIMARY KEY (video_id),
    FOREIGN KEY (project_id) REFERENCES Project (project_id) ON UPDATE CASCADE
);

CREATE TABLE User_Session
(
    user_id int NOT NULL,
    session_id int NOT NULL,
    user_session_date_joined datetime NOT NULL,
    user_session_last_login datetime NULL,
    user_session_permission varchar(255) NOT NULL,

    PRIMARY KEY (user_id, session_id),
    FOREIGN KEY (user_id) REFERENCES User (user_id) ON UPDATE CASCADE,
    FOREIGN KEY (session_id) REFERENCES Session (session_id) ON UPDATE CASCADE
);

CREATE TABLE User_Project
(
    user_id int NOT NULL,
    project_id int NOT NULL,
    user_project_bookmark int NULL,
    user_project_date_complete datetime NULL,

    PRIMARY KEY (user_id, project_id),
    FOREIGN KEY (user_id) REFERENCES User (user_id) ON UPDATE CASCADE,
    FOREIGN KEY (project_id) REFERENCES Project (project_id) ON UPDATE CASCADE,
    FOREIGN KEY (user_project_bookmark) REFERENCES Video (video_id) ON UPDATE CASCADE
);

/* Test Data */

INSERT INTO Category VALUES (1, "Scratch", "Description of Scratch", 1);
INSERT INTO Category VALUES (2, "Web Dev", "Description of Web Dev", 2);

INSERT INTO Project VALUES (1, "Project 1", "test.png", "Project 1 Description", 1);
INSERT INTO Project VALUES (2, "Project 2", "test2.png", "Project 2 Description", 1);
INSERT INTO Project VALUES (3, "Project 3", "test3.png", "Project 3 Description", 2);

INSERT INTO Video VALUE (1, 1, "Project 1 Video", "https://www.youtube.com/embed/393W6KnRGSs", 1);
INSERT INTO Video VALUE (2, 2, "Project 2 Video", "https://www.youtube.com/embed/393W6KnRGSs", 1);
INSERT INTO Video VALUE (3, 3, "Project 3 Video", "https://www.youtube.com/embed/393W6KnRGSs", 1);

INSERT INTO User VALUES (1, "user1", "User 1", "1234", NULL, 0);
INSERT INTO User VALUES (2, "user2", "User 2", "1234", NULL, 0);
INSERT INTO User VALUES (3, "user3", "User 3", "1234", NULL, 0);
INSERT INTO User VALUES (4, "user4", "User 4", "1234", NULL, 0);
INSERT INTO User VALUES (5, "user5", "User 5", "1234", NULL, 0);

INSERT INTO Session VALUES (1, "Session 1", "Session 1 description");
INSERT INTO Session VALUES (2, "Session 2", "Session 2 description");
INSERT INTO Session VALUES (3, "Session 3", "Session 3 description");

INSERT INTO User_Session VALUES (1, 1, NOW(), NULL, "admin");
INSERT INTO User_Session VALUES (2, 1, NOW(), NULL, "user");
INSERT INTO User_Session VALUES (3, 1, NOW(), NULL, "user");
INSERT INTO User_Session VALUES (4, 1, NOW(), NULL, "user");
INSERT INTO User_Session VALUES (5, 1, NOW(), NULL, "user");


INSERT INTO User_Session VALUES (1, 2, NOW(), NULL, "user");
INSERT INTO User_Session VALUES (2, 3, NOW(), NULL, "admin");