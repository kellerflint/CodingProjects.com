CREATE TABLE User
(
    user_id int NOT NULL AUTO_INCREMENT,
    user_name varchar(255) NOT NULL,
    user_nickname varchar(255) NOT NULL,
    user_password varchar(255) NOT NULL,
    user_is_admin tinyint NOT NULL,

    PRIMARY KEY (user_id)
);

CREATE TABLE Session
(
    session_id int NOT NULL AUTO_INCREMENT,
    session_title varchar(255) NOT NULL,
    session_description varchar(5000),

    PRIMARY KEY (session_id)
);

CREATE TABLE Project
(
    project_id int NOT NULL AUTO_INCREMENT,
    project_title varchar(255),
    project_image varchar(255),
    project_description varchar(5000),
    project_category varchar(255),

    PRIMARY KEY (project_id)
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

INSERT INTO Project VALUES (1, "Project 1", "test.png", "Project 1 Description", "Category 1");
INSERT INTO Project VALUES (2, "Project 2", "test2.png", "Project 2 Description", "Category 1");
INSERT INTO Project VALUES (3, "Project 3", "test3.png", "Project 3 Description", "Category 1");

INSERT INTO Video VALUE (1, 1, "Video 1", "youtube.com", 1);
INSERT INTO Video VALUE (2, 2, "Video 1", "youtube.com", 1);
INSERT INTO Video VALUE (3, 3, "Video 1", "youtube.com", 1);
