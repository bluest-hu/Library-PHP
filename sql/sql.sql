# create database 
CREATE DATABASE library;

#use database 
USE DATABASE library;

# TABLE user
CREATE TABLE users (
	ID INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	register_time TIMESTAMP NOT NULL,
	unique_id VARCHAR(255) NOT null,
	email VARCHAR(255),
	avatar VARCHAR(255) NULL,
    loaction VARCHAR(255) NULL,
    sex INT NULL,
    level INT NOT NULL,
    cover_bg VARCHAR(255) NULL,
	PRIMARY KEY (ID),
	UNIQUE(username),
    CHECK(sex = 0 OR sex = 1 OR sex = 2),
    CHECK (level = 0 OR level = 1 OR level = 2)

);

# TABLE Books
CREATE TABLE books (
    ID INT NOT NULL AUTO_INCREMENT,
    book_name VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NULL,
    cover VARCHAR(255) NULL,
    author INT NULL,
    publish_date TIMESTAMP NULL,
    add_date TIMESTAMP NOT NULL,
    sum_count INT NOT NULL,
    borrowed_count INT DEFAULT 0,
    tags VARCHAR(255),
    category INT NULL,
    summary TEXT NULL,
    PRIMARY KEY (ID)
);

# Create Table invite
CREATE TABLE invite (
    ID INT NOT NULL AUTO_INCREMENT,
    sender_id INT NOT NULL, #发送者
    register_key VARCHAR(255) NOT NULL,
    receiver INT NULL,
    is_completed INT, #是否完成
    sender_time TIMESTAMP NULL,
    register_time TIMESTAMP NULL, #其实是冗余
    level INT NOT NULL,
    PRIMARY KEY (ID),
 );


CREATE TABLE cata