<?php

/*
 * Execute first init.php
 */

error_reporting(E_ALL);

function is_valid(array $cheep): bool
{
    return true;
}

//snippet index
$link = mysqli_connect('127.0.0.1', 'user', 'pass');

if (!$link) {
    die('Could not connect: ' . mysqli_error($link));
}

mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, 'db');

$post_error = null;
if ($_POST && is_valid($_POST['cheep'])) {
    mysqli_query($link, 'START TRANSACTION');
    $sql = sprintf(
        "INSERT INTO cheeps (author_id, message) VALUES (%d, '%s')",
        $_POST['cheep']['author_id'],
        mysqli_real_escape_string($link, $_POST['cheep']['message'])
    );
    $result = mysqli_query($link, $sql);

    if ($result) {
        mysqli_query($link, 'COMMIT');
    } else {
        mysqli_query($link, 'ROLLBACK');
        $post_error = 'Cheep could not be published' . mysqli_error($link);
    }
}

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

$authors_query = mysqli_query($link, 'SELECT id, username FROM authors');

?>
<html>
    <body>
        <?php if ($_POST): ?>
            <?php if ($post_error): ?>
                <div class="alert error"><?php echo $post_error; ?></div>
            <?php else: ?>
                <div class="alert success">Cheep was published successfully!</div>
            <?php endif; ?>
        <?php endif; ?>
        <table>
            <h1>Timeline of <strong><?php echo $timeline_username ?></strong></h1>
            <thead>
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
        <form action="/?username=<?php echo $timeline_username; ?>" method="POST">
            <select name="cheep[author_id]">
                <?php while ($author = mysqli_fetch_assoc($authors_query)): ?>
                    <option value="<?php echo $author["id"] ?>">
                        <?php echo $author["username"] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <textarea name="cheep[message]"></textarea>
            <button type="submit">Publish!</button>
        </form>
    </body>
</html>
<?php mysqli_close($link); ?>
<!--end-snippet-->