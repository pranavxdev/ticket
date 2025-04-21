<?php

$eventsTable = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    location VARCHAR(100),
    date DATE,
    time TIME,
    total_tickets INT,
    ticket_price DECIMAL(10,2)
)";

$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45) NOT NULL,
    email varchar(45) NOT NULL,
    password varchar(45) NOT NULL
)";

?>