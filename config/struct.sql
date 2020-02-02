
CREATE DATABASE IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 ;

USE `camagru` ;

CREATE TABLE IF NOT EXISTS users
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    mail VARCHAR(100),
    name VARCHAR(100),
    passwd VARCHAR(255),
    date_created DATETIME,
    confirm_key VARCHAR(255),
    account_confirm INT(1),
    register_token VARCHAR(50),
    mail_com INT,
    token_connect VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS images
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100),
    src VARCHAR(100),
    id_user INT,
    date_created DATETIME
);

CREATE TABLE IF NOT EXISTS user_like
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_img INT,
    id_user INT,
    date_created DATETIME
);

CREATE TABLE IF NOT EXISTS comment
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    src_img VARCHAR(255),
    id_user INT,
    content VARCHAR(2000),
    date_created DATETIME,
    id_img INT
);