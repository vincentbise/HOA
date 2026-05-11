<?php
/**
 * Doctor Model
 * Handles doctor CRUD operations
 */

class Doctor {
    private $db;
    private $table = 'doctors';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all doctors
     */
    public function getAll($status = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($status) {
            $sql .= " WHERE status = :status";
        }
        $sql .= " ORDER BY full_name ASC";
        $stmt = $this->db->prepare($sql);
        if ($status) {
            $stmt->execute([':status' => $status]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    /**
     * Get active doctors (for patient reservation form)
     */
    public function getActive() {
        return $this->getAll('active');
    }

    /**
     * Get doctor by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create a new doctor
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (full_name, specialization, email, phone, status) 
                VALUES (:full_name, :specialization, :email, :phone, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':full_name'       => $data['full_name'],
            ':specialization'  => $data['specialization'],
            ':email'           => $data['email'] ?? null,
            ':phone'           => $data['phone'] ?? null,
            ':status'          => $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update doctor details
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET full_name = :full_name, specialization = :specialization, 
                    email = :email, phone = :phone, status = :status 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'              => $id,
            ':full_name'       => $data['full_name'],
            ':specialization'  => $data['specialization'],
            ':email'           => $data['email'] ?? null,
            ':phone'           => $data['phone'] ?? null,
            ':status'          => $data['status'] ?? 'active'
        ]);
    }

    /**
     * Delete a doctor
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Count all doctors
     */
    public function count($status = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($status) {
            $sql .= " WHERE status = :status";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $this->db->query($sql);
        }
        $result = $stmt->fetch();
        return $result['total'];
    }
}
