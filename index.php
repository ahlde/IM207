<?php

require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\Post;

session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$post = new Post();
$userId = $_SESSION['user']['id'];
$message = '';

// Handle new blog post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $created = $post->createPost([
        'user_id' => $userId,
        'title' => $_POST['title'],
        'content' => $_POST['content'],
    ]);

    if ($created) {
        $message = 'Blog post added successfully!';
    } else {
        $message = 'Failed to add blog post. Please try again.';
    }
}

// Fetch user's posts only
$posts = $post->getPostsByUserId($userId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #0077ff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #005fcc;
        }
        ul {
            padding: 0;
        }
        li {
            list-style-type: none;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 12px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>!</h1>

    <form method="POST" action="">
        <h2>Add a New Blog Post</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" rows="5" placeholder="Write your content here..." required></textarea>
        <input type="submit" value="Add Post">
    </form>

    <h2>Your Blog Posts</h2>
    <?php if (!empty($posts)): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <strong><?php echo htmlspecialchars($post['title']); ?></strong><br>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No blog posts found.</p>
    <?php endif; ?>

</body>
</html>
