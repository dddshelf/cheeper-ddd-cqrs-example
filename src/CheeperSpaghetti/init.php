<?php declare(strict_types=1);

/**
 * The only purpose of this file is to make the spaghetti example work
 */

error_reporting(E_ALL);

$link = mysqli_connect('127.0.0.1', 'user', 'pass');

if (!$link) {
    die('Could not connect: ' . mysqli_error($link));
}

mysqli_select_db($link, 'db');

$sql = <<<SQL
    DROP TABLE IF EXISTS cheeps;
    DROP TABLE IF EXISTS follows;
    DROP TABLE IF EXISTS authors;
        
    -- snippet cheeper-schema
    CREATE TABLE authors (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        username VARCHAR(100) NOT NULL UNIQUE,
        website VARCHAR(255) NULL,
        bio VARCHAR(255) NULL,
        PRIMARY KEY (id)
    );

    CREATE TABLE follows (
        followee_id INT(11) UNSIGNED NOT NULL,
        follower_id INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (followee_id) REFERENCES authors(id) ON DELETE CASCADE,
        FOREIGN KEY (follower_id) REFERENCES authors(id) ON DELETE CASCADE,
        PRIMARY KEY (followee_id, follower_id)
    );

    CREATE TABLE cheeps (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        author_id INT(11) UNSIGNED NOT NULL,
        message VARCHAR(240) NOT NULL,
        date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
        PRIMARY KEY (id)
    );
    -- end-snippet
        
    INSERT INTO authors (id, username) VALUES
        (1, 'johndoe'),
        (2, 'rosemary');

    INSERT INTO follows (followee_id, follower_id) VALUES
        (1, 2);

    INSERT INTO cheeps (author_id, message, date) VALUES
        (1, "Hello, I'm John!", "2020-05-26 00:21:32"),
        (2, "Hello, I'm Rose!", "2020-05-26 00:18:29");
    SQL;

$result = mysqli_multi_query($link, $sql);
if (!$result) {
    echo mysqli_error($link);
}
mysqli_close($link);

echo "Done!";
