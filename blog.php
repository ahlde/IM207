<?php

require_once 'vendor/autoload.php';

use Geonzon\Dbmodel\Models\Post;

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$postModel = new Post();
$userId = $_SESSION['user']['id'];
$editing = false;
$existingPost = null;
$title = '';
$content = '';

// If editing (post ID in the URL), fetch the post
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $existingPost = $postModel->getPostById($postId);

    // Ensure the logged-in user is the author of the post
    if ($existingPost && (int)$existingPost['author_id'] === (int)$userId) {
        $editing = true;
        $title = $existingPost['title'];
        $content = $existingPost['content'];
    } else {
        $_SESSION['message'] = "You are not authorized to edit this blog post.";
        header("Location: index.php");
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($editing) {
        $postModel->updatePost([
            'id' => $postId,
            'title' => $title,
            'content' => $content,
            'author_id' => $userId
        ]);
        $_SESSION['message'] = "Blog post updated successfully.";
    } else {
        $postModel->addPost([
            'title' => $title,
            'content' => $content,
            'author_id' => $userId
        ]);
        $_SESSION['message'] = "Blog post added successfully.";
    }

    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo $editing ? 'Edit' : 'Add'; ?> Blog</title>
<style>
    /* Reset */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f0f0f0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    form {
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .greeting {
        margin-bottom: 25px;
        font-size: 20px;
        color: #333;
    }

    input[type="text"],
    textarea {
        padding: 14px;
        margin-bottom: 20px;
        border: 1.5px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
        font-family: inherit;
    }

    input[type="text"]:focus,
    textarea:focus {
        border-color: #28a745;
        outline: none;
    }

    input[type="submit"] {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 14px 20px;
        font-size: 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
    }

    input[type="submit"]:hover {
        background-color: #218838;
    }

    @media (max-width: 640px) {
        form {
            padding: 20px 25px;
        }
    }
</style>
</head>
<body>

<form action="blog.php<?php echo $editing ? '?id=' . htmlspecialchars($postId) : ''; ?>" method="POST" novalidate>
    <div class="greeting">
        Hello, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?><br>
        <?php echo $editing ? 'Edit your blog post below:' : 'Add a blog post:'; ?>
    </div>

    <input type="text" name="title" placeholder="Blog Title" value="<?php echo htmlspecialchars($title); ?>" required />

    <textarea name="content" rows="8" placeholder="Your content here..." required><?php echo htmlspecialchars($content); ?></textarea>

    <input type="submit" value="<?php echo $editing ? 'Update Post' : 'Submit Post'; ?>" />
</form>

</body>
</html>
