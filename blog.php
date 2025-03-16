<?php

require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\Post;

session_start();

// Usage example
$post = new Post();

// checks if the button is clicked and the form is submitted. If true, run the createUser() function from our User class
// Then pass in an array of data to the createUser class since createUser accepts an array of data
if(isset($_POST['submit'])) {
    $post->addPost([
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'author_id' => $_POST['author_id']
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Blog Post</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <form method="POST" action="blog.php">
        <p>Hello, <?php echo $_SESSION['user']['first_name'] ?></p>
        <h1>Add a Blog Post</h1>
        <input type="text" name="title" placeholder="Title">
        <input type="hidden" name="author_id" value="<?php echo $_SESSION['user']['id']; ?>">
        <textarea name="content" id=""></textarea>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>