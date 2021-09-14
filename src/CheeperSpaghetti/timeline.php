<?php declare(strict_types=1);

/*
 * Execute first init.php
 */

error_reporting(E_ALL);

//snippet timeline

//ignore
$link = mysqli_connect('127.0.0.1', 'user', 'pass');

if (!$link) {
    die('Could not connect: ' . mysqli_error($link));
}

mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, 'db');
//end-ignore

if (!$timeline_username = htmlspecialchars($_GET['username'])) {
    die('Specify a username');
}

$timeline_query = mysqli_query($link, sprintf(<<<SQL
        SELECT
            username, message, date
        FROM cheeps
            JOIN authors ON cheeps.author_id = authors.id
            LEFT JOIN follows ON follows.followee_id = authors.id
        WHERE username = '%s' OR follows.followee_id = authors.id
        ORDER BY date DESC
    SQL, mysqli_real_escape_string($link, $timeline_username)));

?>
<html>
    <body>
        <table>
            <h1><?php echo $timeline_username ?> timeline</h1>
            <thead align="left">
                <tr>
                    <th>Message</th>
                    <th>Username</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($cheep = mysqli_fetch_assoc($timeline_query)): ?>
                <tr>
                    <td><?php echo $cheep['message']; ?></td>
                    <td><?php echo $cheep['username']; ?></td>
                    <td><?php echo $cheep['date']; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a href="post.php">Post a Cheep!</a>
    </body>
</html>
<?php mysqli_close($link); ?>
<!--end-snippet-->
