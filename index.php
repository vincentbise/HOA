<?php
/**
 * Front Controller / Router
 * Clinic Online Reservation System
 */

session_start();

// Base path constant
define('BASE_PATH', __DIR__);
define('BASE_URL', '/HOA');

// Load database config
require_once BASE_PATH . '/config/database.php';

// Load models
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Doctor.php';
require_once BASE_PATH . '/models/DoctorSchedule.php';
require_once BASE_PATH . '/models/Reservation.php';

// Load controllers
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/ReservationController.php';
require_once BASE_PATH . '/controllers/DoctorController.php';
require_once BASE_PATH . '/controllers/AdminController.php';

// Parse URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$segments = explode('/', $url);

$controller = !empty($segments[0]) ? $segments[0] : 'auth';
$action = !empty($segments[1]) ? $segments[1] : 'login';
$param = !empty($segments[2]) ? $segments[2] : null;

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Route requests
switch ($controller) {
    case 'auth':
        $ctrl = new AuthController();
        switch ($action) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ctrl->login();
                } else {
                    $ctrl->showLogin();
                }
                break;
            case 'register':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ctrl->register();
                } else {
                    $ctrl->showRegister();
                }
                break;
            case 'logout':
                $ctrl->logout();
                break;
            default:
                $ctrl->showLogin();
                break;
        }
        break;

    case 'patient':
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $ctrl = new ReservationController();
        switch ($action) {
            case 'dashboard':
                $ctrl->dashboard();
                break;
            case 'reserve':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ctrl->store();
                } else {
                    $ctrl->create();
                }
                break;
            case 'reservations':
                $ctrl->getByUser();
                break;
            case 'cancel':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ctrl->cancel();
                }
                break;
            case 'search':
                $ctrl->search();
                break;
            case 'available-slots':
                $ctrl->getAvailableSlots();
                break;
            default:
                $ctrl->dashboard();
                break;
        }
        break;

    case 'admin':
        // Check authentication and admin role
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $adminCtrl = new AdminController();
        $doctorCtrl = new DoctorController();
        $resCtrl = new ReservationController();
        switch ($action) {
            case 'dashboard':
                $adminCtrl->dashboard();
                break;
            case 'reservations':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'update-status') {
                    $resCtrl->updateStatus();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'cancel') {
                    $resCtrl->cancel();
                } else {
                    $adminCtrl->reservations();
                }
                break;
            case 'search-reservations':
                $resCtrl->search();
                break;
            case 'doctors':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'store') {
                    $doctorCtrl->store();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'update') {
                    $doctorCtrl->update();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'delete') {
                    $doctorCtrl->delete();
                } else {
                    $adminCtrl->doctors();
                }
                break;
            case 'schedules':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'store') {
                    $doctorCtrl->storeSchedule();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'update') {
                    $doctorCtrl->updateSchedule();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $param === 'delete') {
                    $doctorCtrl->deleteSchedule();
                } else {
                    $doctorCtrl->getSchedules();
                }
                break;
            case 'all-reservations':
                $resCtrl->getAll();
                break;
            default:
                $adminCtrl->dashboard();
                break;
        }
        break;

    default:
        // Default redirect to login
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
}
