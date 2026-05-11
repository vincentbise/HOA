<?php
/**
 * User Model
 * Handles user registration, authentication, and profile operations
 */

class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Register a new user
     */
    public function register($data) {
        try {
            $sql = "INSERT INTO {$this->table} (full_name, email, password, phone, role) 
                    VALUES (:full_name, :email, :password, :phone, :role)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':full_name' => $data['full_name'],
                ':email'     => $data['email'],
                ':password'  => password_hash($data['password'], PASSWORD_BCRYPT),
                ':phone'     => $data['phone'] ?? null,
                ':role'      => 'patient'
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return false; // Duplicate email
            }
            throw $e;
        }
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT id, full_name, email, phone, role, created_at FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Get all patients
     */
    public function getAllPatients() {
        $sql = "SELECT id, full_name, email, phone, created_at FROM {$this->table} WHERE role = 'patient' ORDER BY full_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Count users by role
     */
    public function countByRole($role = 'patient') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role' => $role]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
