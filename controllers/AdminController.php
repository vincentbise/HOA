<?php
/**
 * Admin Controller
 * Handles admin dashboard and page rendering
 */

class AdminController {
    private $reservationModel;
    private $doctorModel;
    private $userModel;

    public function __construct() {
        $this->reservationModel = new Reservation();
        $this->doctorModel = new Doctor();
        $this->userModel = new User();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard() {
        $stats = [
            'total_reservations' => $this->reservationModel->countByStatus(),
            'pending'            => $this->reservationModel->countByStatus('Pending'),
            'approved'           => $this->reservationModel->countByStatus('Approved'),
            'completed'          => $this->reservationModel->countByStatus('Completed'),
            'cancelled'          => $this->reservationModel->countByStatus('Cancelled'),
            'total_doctors'      => $this->doctorModel->count(),
            'active_doctors'     => $this->doctorModel->count('active'),
            'total_patients'     => $this->userModel->countByRole('patient'),
        ];
        $todayReservations = $this->reservationModel->getToday();
        $upcomingReservations = $this->reservationModel->getUpcoming(8);
        
        require_once BASE_PATH . '/views/admin/dashboard.php';
    }

    /**
     * All Reservations page
     */
    public function reservations() {
        $reservations = $this->reservationModel->getAll();
        require_once BASE_PATH . '/views/admin/reservations.php';
    }

    /**
     * Doctor Management page
     */
    public function doctors() {
        $doctors = $this->doctorModel->getAll();
        $scheduleModel = new DoctorSchedule();
        $schedules = $scheduleModel->getAll();
        require_once BASE_PATH . '/views/admin/doctors.php';
    }
}
