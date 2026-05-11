<?php
/**
 * Reservation Controller
 * Handles reservation CRUD, search, and status operations via AJAX
 */

class ReservationController {
    private $reservationModel;
    private $doctorModel;
    private $scheduleModel;

    public function __construct() {
        $this->reservationModel = new Reservation();
        $this->doctorModel = new Doctor();
        $this->scheduleModel = new DoctorSchedule();
    }

    /**
     * Show patient dashboard
     */
    public function dashboard() {
        $userId = $_SESSION['user_id'];
        $reservations = $this->reservationModel->getByUser($userId);
        $stats = [
            'total'     => $this->reservationModel->countByUserAndStatus($userId),
            'pending'   => $this->reservationModel->countByUserAndStatus($userId, 'Pending'),
            'approved'  => $this->reservationModel->countByUserAndStatus($userId, 'Approved'),
            'completed' => $this->reservationModel->countByUserAndStatus($userId, 'Completed'),
            'cancelled' => $this->reservationModel->countByUserAndStatus($userId, 'Cancelled'),
        ];
        require_once BASE_PATH . '/views/patient/dashboard.php';
    }

    /**
     * Show reservation form
     */
    public function create() {
        $doctors = $this->doctorModel->getActive();
        require_once BASE_PATH . '/views/patient/reserve.php';
    }

    /**
     * Store a new reservation (AJAX)
     */
    public function store() {
        header('Content-Type: application/json');

        $data = [
            'user_id'          => $_SESSION['user_id'],
            'doctor_id'        => $_POST['doctor_id'] ?? '',
            'full_name'        => trim($_POST['full_name'] ?? ''),
            'contact_number'   => trim($_POST['contact_number'] ?? ''),
            'email'            => trim($_POST['email'] ?? ''),
            'appointment_date' => $_POST['appointment_date'] ?? '',
            'appointment_time' => $_POST['appointment_time'] ?? '',
            'reason'           => trim($_POST['reason'] ?? '')
        ];

        // Validation
        $errors = [];
        if (empty($data['full_name'])) $errors[] = 'Full name is required.';
        if (empty($data['contact_number'])) $errors[] = 'Contact number is required.';
        if (empty($data['email'])) $errors[] = 'Email is required.';
        if (empty($data['doctor_id'])) $errors[] = 'Please select a doctor.';
        if (empty($data['appointment_date'])) $errors[] = 'Appointment date is required.';
        if (empty($data['appointment_time'])) $errors[] = 'Appointment time is required.';
        if (empty($data['reason'])) $errors[] = 'Reason for consultation is required.';

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
            return;
        }

        // Verify the appointment date is in the future
        if (strtotime($data['appointment_date']) < strtotime('today')) {
            echo json_encode(['success' => false, 'message' => 'Appointment date must be today or in the future.']);
            return;
        }

        $result = $this->reservationModel->create($data);

        if ($result) {
            $reservation = $this->reservationModel->getById($result);
            echo json_encode([
                'success'     => true,
                'message'     => 'Reservation submitted successfully!',
                'reservation' => $reservation
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create reservation. Please try again.']);
        }
    }

    /**
     * Get reservations for current user (AJAX)
     */
    public function getByUser() {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'];
        $reservations = $this->reservationModel->getByUser($userId);
        echo json_encode(['success' => true, 'reservations' => $reservations]);
    }

    /**
     * Get all reservations (admin - AJAX)
     */
    public function getAll() {
        header('Content-Type: application/json');
        $reservations = $this->reservationModel->getAll();
        echo json_encode(['success' => true, 'reservations' => $reservations]);
    }

    /**
     * Update reservation status (admin - AJAX)
     */
    public function updateStatus() {
        header('Content-Type: application/json');

        $id = $_POST['reservation_id'] ?? '';
        $status = $_POST['status'] ?? '';

        if (empty($id) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
            return;
        }

        $result = $this->reservationModel->updateStatus($id, $status);

        if ($result) {
            $statusMessages = [
                'Pending'   => 'Reservation set to pending.',
                'Approved'  => 'Reservation approved successfully!',
                'Completed' => 'Reservation marked as completed.',
                'Cancelled' => 'Reservation has been cancelled.'
            ];
            echo json_encode([
                'success' => true,
                'message' => $statusMessages[$status] ?? 'Status updated successfully.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
        }
    }

    /**
     * Cancel reservation (AJAX)
     */
    public function cancel() {
        header('Content-Type: application/json');

        $id = $_POST['reservation_id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid reservation ID.']);
            return;
        }

        // If patient, verify they own the reservation
        if ($_SESSION['user_role'] !== 'admin') {
            $reservation = $this->reservationModel->getById($id);
            if (!$reservation || $reservation['user_id'] != $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                return;
            }
        }

        $result = $this->reservationModel->cancel($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reservation cancelled successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel reservation.']);
        }
    }

    /**
     * Search reservations (AJAX)
     */
    public function search() {
        header('Content-Type: application/json');

        $query = trim($_GET['q'] ?? '');
        $userId = ($_SESSION['user_role'] !== 'admin') ? $_SESSION['user_id'] : null;

        $reservations = $this->reservationModel->search($query, $userId);
        echo json_encode(['success' => true, 'reservations' => $reservations]);
    }

    /**
     * Get available time slots for a doctor on a date (AJAX)
     */
    public function getAvailableSlots() {
        header('Content-Type: application/json');

        $doctorId = $_GET['doctor_id'] ?? '';
        $date = $_GET['date'] ?? '';

        if (empty($doctorId) || empty($date)) {
            echo json_encode(['success' => false, 'message' => 'Please select a doctor and date.']);
            return;
        }

        $slots = $this->scheduleModel->getAvailableSlots($doctorId, $date);

        echo json_encode([
            'success' => true,
            'slots'   => $slots
        ]);
    }
}
