<?php

namespace Geonzon\Dbmodel\Models;

use Geonzon\Dbmodel\Includes\Database;

class User extends Database {
    private $db;

    public function __construct() {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function login($data) {
        $sql = "SELECT id, first_name, password FROM user WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $data['username'],
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function register($data) {
        $sql = "INSERT INTO user (first_name, last_name, username, password) 
                VALUES (:first_name, :last_name, :username, :password)";
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
        $sql = "UPDATE user SET first_name = :first_name, last_name = :last_name, username = :username, password = :password 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
        return "Record UPDATED successfully";
    }

    public function delete($id) {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return "Record DELETED successfully";
    }
}
