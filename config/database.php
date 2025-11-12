<?php

/**
 * Database Configuration
 *
 * This file returns an array of database connection settings.
 * It's kept separate from the application logic for security and flexibility.
 *
 * host: The IP address or hostname of your database server (usually 'localhost' or '127.0.0.1' for local dev).
 * port: The port your database server is listening on (default for MySQL is 3306).
 * dbname: The name of your specific database (e.g., 'slubazaar_db').
 * charset: The character set for the connection. 'utf8mb4' is the modern standard, supporting emojis and most languages.
 * user: The database username.
 * password: The password for that database user.
 */

 // All of these should be replaced with the actual values if changed later (which is guaranteed)
return [
    'host'      => '127.0.0.1',
    'port'      => 3306,
    'dbname'    => 'SLUBazaar',
    'charset'   => 'utf8mb4',
    'user'      => 'root',
    'password'  => '', 
];