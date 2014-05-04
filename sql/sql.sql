# create database 
CREATE DATABASE library;

#use database 
USE DATABASE library;

# TABLE user
CREATE TABLE user (
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
	PRIMARY KEY (ID),
	UNIQUE(username),
    CHECK(sex = 0 OR sex = 1 OR sex = 2),
    CHECK (level = 0 OR level = 1 OR level = 2)

);

# TABLE Books

CREATE TABLE books (
    ID INT NOT NULL AUTO_INCREMENT,
    bookname VARCHAR(255) NOT NULL,
    
);