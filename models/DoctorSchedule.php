<?php
/**
 * DoctorSchedule Model
 * Handles doctor schedule/slot management
 */

class DoctorSchedule {
    private $db;
    private $table = 'doctor_schedules';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all schedules for a doctor
     */
    public function getByDoctor($doctorId) {
        $sql = "SELECT ds.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} ds 
                JOIN doctors d ON ds.doctor_id = d.id 
                WHERE ds.doctor_id = :doctor_id 
                ORDER BY FIELD(ds.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), 
                         ds.start_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctorId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all schedules
     */
    public function getAll() {
        $sql = "SELECT ds.*, d.full_name as doctor_name, d.specialization 
                FROM {$this->table} ds 
                JOIN doctors d ON ds.doctor_id = d.id 
                ORDER BY d.full_name, 
                         FIELD(ds.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), 
                         ds.start_time ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get available slots for a doctor on a specific date
     */
    public function getAvailableSlots($doctorId, $date) {
        $dayOfWeek = date('l', strtotime($date));
        
        // Get doctor's schedule for that day
        $sql = "SELECT * FROM {$this->table} 
                WHERE doctor_id = :doctor_id 
                AND day_of_week = :day_of_week 
                AND is_available = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':doctor_id'   => $doctorId,
            ':day_of_week' => $dayOfWeek
        ]);
        $schedules = $stmt->fetchAll();

        if (empty($schedules)) {
            return [];
        }

        $availableSlots = [];
        foreach ($schedules as $schedule) {
            // Count existing reservations for this schedule slot
            $countSql = "SELECT COUNT(*) as booked FROM reservations 
                         WHERE doctor_id = :doctor_id 
                         AND appointment_date = :date 
                         AND appointment_time >= :start_time 
                         AND appointment_time < :end_time 
                         AND status != 'Cancelled'";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute([
                ':doctor_id'  => $doctorId,
                ':date'       => $date,
                ':start_time' => $schedule['start_time'],
                ':end_time'   => $schedule['end_time']
            ]);
            $booked = $countStmt->fetch()['booked'];

            // Generate time slots (30 min intervals)
            $start = strtotime($schedule['start_time']);
            $end = strtotime($schedule['end_time']);
            $interval = 30 * 60; // 30 minutes

            for ($time = $start; $time < $end; $time += $interval) {
                $slotTime = date('H:i:s', $time);
                
                // Check if this specific slot is taken
                $slotSql = "SELECT COUNT(*) as taken FROM reservations 
                            WHERE doctor_id = :doctor_id 
                            AND appointment_date = :date 
                            AND appointment_time = :time 
                            AND status != 'Cancelled'";
                $slotStmt = $this->db->prepare($slotSql);
                $slotStmt->execute([
                    ':doctor_id' => $doctorId,
                    ':date'      => $date,
                    ':time'      => $slotTime
                ]);
                $taken = $slotStmt->fetch()['taken'];

                if ($taken == 0) {
                    $availableSlots[] = [
                        'time'      => $slotTime,
                        'formatted' => date('g:i A', $time)
                    ];
                }
            }
        }

        return $availableSlots;
    }

    /**
     * Create a new schedule
     */
    public function create($data) {
        // Check for overlap
        if ($this->hasOverlap($data['doctor_id'], $data['day_of_week'], $data['start_time'], $data['end_time'])) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (doctor_id, day_of_week, start_time, end_time, max_patients, is_available) 
                VALUES (:doctor_id, :day_of_week, :start_time, :end_time, :max_patients, :is_available)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':doctor_id'    => $data['doctor_id'],
            ':day_of_week'  => $data['day_of_week'],
            ':start_time'   => $data['start_time'],
            ':end_time'     => $data['end_time'],
            ':max_patients' => $data['max_patients'] ?? 10,
            ':is_available' => $data['is_available'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a schedule
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET day_of_week = :day_of_week, start_time = :start_time, 
                    end_time = :end_time, max_patients = :max_patients, is_available = :is_available 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':day_of_week'  => $data['day_of_week'],
            ':start_time'   => $data['start_time'],
            ':end_time'     => $data['end_time'],
            ':max_patients' => $data['max_patients'] ?? 10,
            ':is_available' => $data['is_available'] ?? 1
        ]);
    }

    /**
     * Delete a schedule
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Check for schedule overlap
     */
    private function hasOverlap($doctorId, $dayOfWeek, $startTime, $endTime, $excludeId = null) {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table} 
                WHERE doctor_id = :doctor_id 
                AND day_of_week = :day_of_week 
                AND ((start_time < :end_time AND end_time > :start_time))";
        $params = [
            ':doctor_id'   => $doctorId,
            ':day_of_week' => $dayOfWeek,
            ':start_time'  => $startTime,
            ':end_time'    => $endTime
        ];

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['cnt'] > 0;
    }

    /**
     * Get schedule by ID
     */
    public function getById($id) {
        $sql = "SELECT ds.*, d.full_name as doctor_name 
                FROM {$this->table} ds 
                JOIN doctors d ON ds.doctor_id = d.id 
                WHERE ds.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
