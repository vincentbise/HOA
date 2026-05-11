<?php
/**
 * Reservation Model
 * Handles reservation CRUD, search, and status operations
 */

class Reservation {
    private $db;
    private $table = 'reservations';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new reservation
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (user_id, doctor_id, full_name, contact_number, email, appointment_date, appointment_time, reason, status) 
                VALUES (:user_id, :doctor_id, :full_name, :contact_number, :email, :appointment_date, :appointment_time, :reason, 'Pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'          => $data['user_id'],
            ':doctor_id'        => $data['doctor_id'],
            ':full_name'        => $data['full_name'],
            ':contact_number'   => $data['contact_number'],
            ':email'            => $data['email'],
            ':appointment_date' => $data['appointment_date'],
            ':appointment_time' => $data['appointment_time'],
            ':reason'           => $data['reason']
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Get all reservations with doctor info
     */
    public function getAll() {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get reservations by user ID
     */
    public function getByUser($userId) {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                WHERE r.user_id = :user_id 
                ORDER BY r.appointment_date DESC, r.appointment_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Get reservation by ID
     */
    public function getById($id) {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                WHERE r.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Update reservation status
     */
    public function updateStatus($id, $status) {
        $validStatuses = ['Pending', 'Approved', 'Completed', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    /**
     * Cancel a reservation
     */
    public function cancel($id) {
        return $this->updateStatus($id, 'Cancelled');
    }

    /**
     * Search reservations
     */
    public function search($query, $userId = null) {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                WHERE (r.full_name LIKE :query 
                    OR d.full_name LIKE :query2 
                    OR r.appointment_date LIKE :query3 
                    OR r.status LIKE :query4)";
        
        $params = [
            ':query'  => "%{$query}%",
            ':query2' => "%{$query}%",
            ':query3' => "%{$query}%",
            ':query4' => "%{$query}%"
        ];

        if ($userId) {
            $sql .= " AND r.user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $sql .= " ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count reservations by status
     */
    public function countByStatus($status = null) {
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

    /**
     * Count user's reservations by status
     */
    public function countByUserAndStatus($userId, $status = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $params = [':user_id' => $userId];
        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Get today's reservations
     */
    public function getToday() {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                WHERE r.appointment_date = CURDATE() 
                ORDER BY r.appointment_time ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get upcoming reservations (future dates)
     */
    public function getUpcoming($limit = 10) {
        $sql = "SELECT r.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} r 
                JOIN doctors d ON r.doctor_id = d.id 
                WHERE r.appointment_date >= CURDATE() 
                AND r.status IN ('Pending', 'Approved') 
                ORDER BY r.appointment_date ASC, r.appointment_time ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
