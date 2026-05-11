<?php
/**
 * Auth Controller
 * Handles login, registration, and logout
 */

class AuthController {

    /**
     * Show login page
     */
    public function showLogin() {
        // If already logged in, redirect to appropriate dashboard
        if (isset($_SESSION['user_id'])) {
            $redirect = $_SESSION['user_role'] === 'admin' ? '/admin/dashboard' : '/patient/dashboard';
            header('Location: ' . BASE_URL . $redirect);
            exit;
        }
        require_once BASE_PATH . '/views/auth/login.php';
    }

    /**
     * Show registration page
     */
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            $redirect = $_SESSION['user_role'] === 'admin' ? '/admin/dashboard' : '/patient/dashboard';
            header('Location: ' . BASE_URL . $redirect);
            exit;
        }
        require_once BASE_PATH . '/views/auth/register.php';
    }

    /**
     * Handle login (AJAX)
     */
    public function login() {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            return;
        }

        // Set session variables
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        $redirect = $user['role'] === 'admin' 
            ? BASE_URL . '/admin/dashboard' 
            : BASE_URL . '/patient/dashboard';

        echo json_encode([
            'success'  => true,
            'message'  => 'Login successful! Redirecting...',
            'redirect' => $redirect
        ]);
    }

    /**
     * Handle registration (AJAX)
     */
    public function register() {
        header('Content-Type: application/json');

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        if (empty($fullName)) $errors[] = 'Full name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (empty($password)) $errors[] = 'Password is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
            return;
        }

        $userModel = new User();

        // Check if email exists
        if ($userModel->findByEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
            return;
        }

        $result = $userModel->register([
            'full_name' => $fullName,
            'email'     => $email,
            'phone'     => $phone,
            'password'  => $password
        ]);

        if ($result) {
            echo json_encode([
                'success'  => true,
                'message'  => 'Registration successful! Please log in.',
                'redirect' => BASE_URL . '/auth/login'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Handle logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
