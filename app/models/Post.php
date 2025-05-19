<?php

namespace Geonzon\Dbmodel\Models;

use Geonzon\Dbmodel\Includes\Database;
use PDO;

class Post extends Database {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    public function getPostsByLoggedInUser($id): array {
        $stmt = $this->db->prepare("SELECT * FROM blog WHERE author_id = :id ORDER BY created_at DESC");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($postId): array|false {
        $stmt = $this->db->prepare("SELECT * FROM blog WHERE id = :id");
        $stmt->execute(['id' => $postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPostsWithAuthor(): array {
        $sql = "SELECT blog.*, user.first_name AS author_name
                FROM blog
                INNER JOIN user ON blog.author_id = user.id
                ORDER BY blog.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPost(array $data): bool {
        $stmt = $this->db->prepare("INSERT INTO blog (title, content, author_id, created_at, updated_at)
            VALUES (:title, :content, :author_id, NOW(), NOW())");
        return $stmt->execute([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'author_id' => $data['author_id']
        ]);
    }

    public function updatePost($data): bool {
        $sql = "UPDATE blog SET title = :title, content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $data['id'],
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    }

    public function deletePost(int $id, int $authorId): bool {
        $stmt = $this->db->prepare("DELETE FROM blog WHERE id = :id AND author_id = :author_id");
        return $stmt->execute(['id' => $id, 'author_id' => $authorId]);
    }
}
