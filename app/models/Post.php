<?php

// session_start();

// === Database Connection ===
class Database {
    private $host = "localhost";
    private $db = "blogpost"; 
    private $user = "root";
    private $pass = "";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// === Post Model ===
class Post {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getPostsByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM blog WHERE author_id = :author_id ORDER BY created_at DESC");
        $stmt->execute(['author_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPost($data) {
        $stmt = $this->db->prepare("INSERT INTO blog (title, content, author_id, created_at, updated_at) VALUES (:title, :content, :author_id, NOW(), NOW())");
        return $stmt->execute($data);
    }

    public function updatePost($data) {
        $stmt = $this->db->prepare("UPDATE blog SET title = :title, content = :content, updated_at = NOW() WHERE id = :id AND author_id = :author_id");
        return $stmt->execute($data);
    }

    public function deletePost($id, $author_id) {
        $stmt = $this->db->prepare("DELETE FROM blog WHERE id = :id AND author_id = :author_id");
        return $stmt->execute(['id' => $id, 'author_id' => $author_id]);
    }
}

// === Session/User Check ===
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$postModel = new Post();
$userId = $_SESSION['user']['id'];
$message = "";

// === Handle Create ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create') {
    $result = $postModel->addPost([
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'author_id' => $userId
    ]);
    $message = $result ? "Post added!" : "Failed to add post.";
}

// === Handle Update ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $result = $postModel->updatePost([
        'id' => $_POST['post_id'],
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'author_id' => $userId
    ]);
    $message = $result ? "Post updated!" : "Failed to update post.";
}

// === Handle Delete ===
if (isset($_GET['delete'])) {
    $result = $postModel->deletePost($_GET['delete'], $userId);
    $message = $result ? "Post deleted!" : "Failed to delete post.";
}

// === Fetch Posts ===
$posts = $postModel->getPostsByUser($userId);

?>

<!-- === HTML OUTPUT === -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #0077ff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005fcc;
        }
        .message {
            color: green;
            margin-bottom: 10px;
        }
        li {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>!</h1>

<?php if ($message): ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<!-- Add New Post -->
<form method="POST">
    <h2>Add New Post</h2>
    <input type="hidden" name="action" value="create">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="content" placeholder="Content" rows="5" required></textarea>
    <input type="submit" value="Add Post">
</form>

<!-- Posts List -->
<h2>Your Blog Posts</h2>
<ul>
<?php foreach ($posts as $p): ?>
    <li>
        <strong><?php echo htmlspecialchars($p['title']); ?></strong>
        <p><?php echo nl2br(htmlspecialchars($p['content'])); ?></p>

        <!-- Update Form -->
        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="post_id" value="<?php echo $p['id']; ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($p['title']); ?>" required>
            <textarea name="content" rows="3" required><?php echo htmlspecialchars($p['content']); ?></textarea>
            <input type="submit" value="Update Post">
        </form>

        <!-- Delete Button -->
        <form method="GET" style="margin-top:5px;">
            <input type="hidden" name="delete" value="<?php echo $p['id']; ?>">
            <input type="submit" value="Delete Post" style="background:red;color:white;">
        </form>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
