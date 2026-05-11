<?php
/**
 * Doctor Controller
 * Handles doctor and schedule management (admin)
 */

class DoctorController {
    private $doctorModel;
    private $scheduleModel;

    public function __construct() {
        $this->doctorModel = new Doctor();
        $this->scheduleModel = new DoctorSchedule();
    }

    /**
     * Get all doctors (AJAX)
     */
    public function index() {
        header('Content-Type: application/json');
        $doctors = $this->doctorModel->getAll();
        echo json_encode(['success' => true, 'doctors' => $doctors]);
    }

    /**
     * Store a new doctor (AJAX)
     */
    public function store() {
        header('Content-Type: application/json');

        $data = [
            'full_name'      => trim($_POST['full_name'] ?? ''),
            'specialization' => trim($_POST['specialization'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'phone'          => trim($_POST['phone'] ?? ''),
            'status'         => $_POST['status'] ?? 'active'
        ];

        // Validation
        if (empty($data['full_name']) || empty($data['specialization'])) {
            echo json_encode(['success' => false, 'message' => 'Doctor name and specialization are required.']);
            return;
        }

        $id = $this->doctorModel->create($data);

        if ($id) {
            $doctor = $this->doctorModel->getById($id);
            echo json_encode([
                'success' => true,
                'message' => 'Doctor added successfully!',
                'doctor'  => $doctor
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add doctor.']);
        }
    }

    /**
     * Update doctor (AJAX)
     */
    public function update() {
        header('Content-Type: application/json');

        $id = $_POST['doctor_id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid doctor ID.']);
            return;
        }

        $data = [
            'full_name'      => trim($_POST['full_name'] ?? ''),
            'specialization' => trim($_POST['specialization'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'phone'          => trim($_POST['phone'] ?? ''),
            'status'         => $_POST['status'] ?? 'active'
        ];

        if (empty($data['full_name']) || empty($data['specialization'])) {
            echo json_encode(['success' => false, 'message' => 'Doctor name and specialization are required.']);
            return;
        }

        $result = $this->doctorModel->update($id, $data);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update doctor.']);
        }
    }

    /**
     * Delete doctor (AJAX)
     */
    public function delete() {
        header('Content-Type: application/json');

        $id = $_POST['doctor_id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid doctor ID.']);
            return;
        }

        $result = $this->doctorModel->delete($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor removed successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove doctor.']);
        }
    }

    /**
     * Get schedules for a doctor (AJAX)
     */
    public function getSchedules() {
        header('Content-Type: application/json');

        $doctorId = $_GET['doctor_id'] ?? '';
        if (!empty($doctorId)) {
            $schedules = $this->scheduleModel->getByDoctor($doctorId);
        } else {
            $schedules = $this->scheduleModel->getAll();
        }

        echo json_encode(['success' => true, 'schedules' => $schedules]);
    }

    /**
     * Store a new schedule (AJAX)
     */
    public function storeSchedule() {
        header('Content-Type: application/json');

        $data = [
            'doctor_id'    => $_POST['doctor_id'] ?? '',
            'day_of_week'  => $_POST['day_of_week'] ?? '',
            'start_time'   => $_POST['start_time'] ?? '',
            'end_time'     => $_POST['end_time'] ?? '',
            'max_patients' => $_POST['max_patients'] ?? 10,
            'is_available' => $_POST['is_available'] ?? 1
        ];

        // Validation
        if (empty($data['doctor_id']) || empty($data['day_of_week']) || 
            empty($data['start_time']) || empty($data['end_time'])) {
            echo json_encode(['success' => false, 'message' => 'All schedule fields are required.']);
            return;
        }

        if ($data['start_time'] >= $data['end_time']) {
            echo json_encode(['success' => false, 'message' => 'End time must be after start time.']);
            return;
        }

        $id = $this->scheduleModel->create($data);

        if ($id) {
            $schedule = $this->scheduleModel->getById($id);
            echo json_encode([
                'success'  => true,
                'message'  => 'Schedule added successfully!',
                'schedule' => $schedule
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Schedule overlaps with an existing one. Please choose a different time.']);
        }
    }

    /**
     * Update schedule (AJAX)
     */
    public function updateSchedule() {
        header('Content-Type: application/json');

        $id = $_POST['schedule_id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid schedule ID.']);
            return;
        }

        $data = [
            'day_of_week'  => $_POST['day_of_week'] ?? '',
            'start_time'   => $_POST['start_time'] ?? '',
            'end_time'     => $_POST['end_time'] ?? '',
            'max_patients' => $_POST['max_patients'] ?? 10,
            'is_available' => $_POST['is_available'] ?? 1
        ];

        $result = $this->scheduleModel->update($id, $data);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Schedule updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update schedule.']);
        }
    }

    /**
     * Delete schedule (AJAX)
     */
    public function deleteSchedule() {
        header('Content-Type: application/json');

        $id = $_POST['schedule_id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid schedule ID.']);
            return;
        }

        $result = $this->scheduleModel->delete($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Schedule deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete schedule.']);
        }
    }
}
