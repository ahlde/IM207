<?php

require_once 'vendor/autoload.php';

use Geonzon\Dbmodel\Models\Post;

session_start();

$post = new Post();

// Show message after adding a post (if any)
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$isLoggedIn = isset($_SESSION['user']);

if ($isLoggedIn) {
    $userId = $_SESSION['user']['id'];
    $posts = $post->getPostsByLoggedInUser($userId);
} else {
    $posts = $post->getAllPostsWithAuthor(); // Show all posts with author name for guests
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        a.button { background: #007BFF; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
        li a.post-link {
            display: block;
            color: inherit;
            text-decoration: none;
            background: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }
        li a.post-link:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            background-color: #f9f9f9;
        }
        .message { color: green; margin-bottom: 10px; }
        small.author-name {
            display: block;
            margin-top: 10px;
            color: #555;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="topbar">
    <h1>Welcome to the Blog</h1>
    <div>
        <?php if ($isLoggedIn): ?>
            Hello, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?> |
            <a href="blog.php" class="button">Add Blog</a>
            <a href="logout.php" class="button">Logout</a>
        <?php else: ?>
            <a href="login.php" class="button">Login</a>
            <a href="register.php" class="button">Register</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($message): ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<h2><?php echo $isLoggedIn ? 'Your Blog Posts' : 'Recent Posts from All Users'; ?></h2>

<?php if (!empty($posts)): ?>
    <ul>
        <?php foreach ($posts as $p): ?>
            <?php if ($isLoggedIn): ?>
                <li>
                    <a href="blog.php?id=<?php echo $p['id']; ?>" class="post-link">
                        <strong><?php echo htmlspecialchars($p['title']); ?></strong><br>
                        <p><?php echo nl2br(htmlspecialchars($p['content'])); ?></p>
                    </a>
                </li>
            <?php else: ?>
                <li style="background: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <strong><?php echo htmlspecialchars($p['title']); ?></strong><br>
                    <p><?php echo nl2br(htmlspecialchars($p['content'])); ?></p>
                    <small class="author-name">by <?php echo htmlspecialchars($p['author_name']); ?></small>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No blog posts found.</p>
<?php endif; ?>

</body>
</html>
