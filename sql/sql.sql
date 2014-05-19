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
    location VARCHAR(255) NULL,
    sex INT NULL,
    level INT NOT NULL DEFAULT 0,
    cover_bg VARCHAR(255) NULL,
	PRIMARY KEY (ID),
	UNIQUE(username),
    CHECK(sex = 0 OR sex = 1 OR sex = 2)
);

# TABLE Books
CREATE TABLE books (
    ID INT NOT NULL AUTO_INCREMENT,
    book_name VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NULL,
    cover VARCHAR(255) NULL,
    author VARCHAR(255) NULL,
    publish_date TIMESTAMP NULL,
    add_date TIMESTAMP NOT NULL,
    sum_count INT NOT NULL,
    borrowed_count INT DEFAULT 0,
    tags VARCHAR(255),
    category INT NULL,
    summary TEXT NULL,
    PRIMARY KEY (ID)
);

# 图书分类
CREATE TABLE category (
    ID INT NOT NULL AUTO_INCREMENT,
    cate_name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NULL,
    add_time TIMESTAMP NOT NULL,
    PRIMARY KEY (ID),
    UNIQUE(cate_name)
);


CREATE TABLE borrow (
    ID INT NOT NULL AUTO_INCREMENT,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    borrow_date TIMESTAMP NOT NULL,
    accepte_date TIMESTAMP NULL,
    return_date TIMESTAMP NULL,
    accepted INT NOT NULL DEFAULT 0,
    completed INT NOT NULL DEFAULT 0,
    PRIMARY KEY(ID),
    CHECK(completed = 0 OR completed = 1),
    CHECK(accepted = 0 OR accepted = 1)

);


CREATE TABLE author (
    ID INT NOT NULL AUTO_INCREMENT,
    author_name VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) NULL  DEFAULT NULL,
    add_time TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY(ID),
    UNIQUE(author_name)
)


CREATE TABLE message (


);
