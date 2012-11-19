<?php
    /*
        This file will create the users, unactivated_users, and harvardcourses tables
    */
    require_once("includes/functions.php");
    
    query("CREATE TABLE users
    (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    file VARCHAR(255),
    rating INT)");
    query("CREATE TABLE unactivated_users
    (
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    confcode VARCHAR(255),
    registration_date DATETIME)");
    
    query("CREATE TABLE harvardcourses
    (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    department VARCHAR(255)
    )");
?>
