<?php

declare(strict_types=1);

/*
 * Execute first init.php
 */

error_reporting(E_ALL);

function is_valid(array $cheep): bool
{
    return true;
}

//snippet post
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

$authors_query = mysqli_query($link, 'SELECT id, username FROM authors');

?>
<html>
    <body>
        <h1>Post a Cheep as Author</h1>
        <?php if ($_POST): ?>
            <?php if ($post_error): ?>
                <div class="alert error"><?php echo $post_error; ?></div>
            <?php else: ?>
                <div class="alert success">Cheep was published successfully!</div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="post.php" method="POST">
            <div>
                <label for="cheep[author_id]">Author</label><br>
                <select name="cheep[author_id]">
                    <?php while ($author = mysqli_fetch_assoc($authors_query)): ?>
                        <option value="<?php echo $author["id"] ?>">
                            <?php echo $author["username"] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="cheep[message]">Cheep</label><br>
                <textarea name="cheep[message]"></textarea>
            </div>
            <button type="submit">Publish!</button>
        </form>
    </body>
</html>
<?php mysqli_close($link); ?>
<!--end-snippet-->
