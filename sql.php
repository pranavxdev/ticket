<?php

$eventsTable = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    location VARCHAR(100),
    date DATE,
    time TIME,
    total_tickets INT,
    ticket_price DECIMAL(10,2),
    event_image VARCHAR(255),
    featured TINYINT(1) DEFAULT 0
)";

$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(45) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0
)";

$ticketsTable = "CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    quantity INT,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
)";

// SQL to add email column to existing users table if it doesn't exist
$addEmailColumn = "ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(100) NOT NULL AFTER username";

// SQL to add is_admin column to existing users table if it doesn't exist
$addAdminColumn = "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0 AFTER password";

?>