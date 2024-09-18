

1. Create Database and Tables

database = chat_app

table 1 = messages
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    topic VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

table 2 = users 
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    user_id VARCHAR(100) NOT NULL



2. files
Frontend form
index.html 

PHP backend to handle the form submission
submit_message.php 

Database connection
connect.php

for handling the form submission
main.js



