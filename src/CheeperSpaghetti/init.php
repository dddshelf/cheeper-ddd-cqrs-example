<?php

/**
 * The only purpose of this file is to make the spaghetti example work
 */

error_reporting(E_ALL);

$link = mysqli_connect('127.0.0.1', 'user', 'pass');
mysqli_select_db($link, 'db');
$sql = <<<SQL
    DROP TABLE cheeps;
    DROP TABLE authors;

    -- snippet cheeper-schema
    CREATE TABLE authors (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        username VARCHAR(100) NOT NULL UNIQUE,
        website VARCHAR(255) NULL,
        bio VARCHAR(255) NULL,
        PRIMARY KEY (id)
    );

    CREATE TABLE cheeps (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        author_id INT(11) UNSIGNED NOT NULL,
        message VARCHAR(240) NOT NULL,
        date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES authors(id),
        PRIMARY KEY (id)
    );
    -- end-snippet

    INSERT INTO authors (id, username) VALUES (1, 'johndoe');
    INSERT INTO cheeps (author_id, message) VALUES (1, 'Hello world!');
SQL;
$result = mysqli_multi_query($link, $sql);
if (!$result) {
    echo mysqli_error($link);
}
mysqli_close($link);

echo "Done!";
