<?php

namespace Aries\Dbmodel\Models;

use Aries\Dbmodel\Includes\Database;


class User extends Database {
    private $db;

    public function __construct() {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function login($data) {
        $sql = "SELECT id, first_name, password FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $data['username'],
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function register($data) {
        $sql = "INSERT INTO users (first_name, last_name, username, password) VALUES (:first_name, :last_name, :username, :password)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $this->db->lastInsertId();
    }

    public function update($data) {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        return "Record UPDATED successfully";
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return "Record DELETED successfully";
    }   
}
