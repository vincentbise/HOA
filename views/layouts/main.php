<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clinic Online Reservation System - Book your medical appointments easily and efficiently.">
    <title><?php echo $pageTitle ?? 'Clinic Reservation System'; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <i class='bx bx-plus-medical'></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name">MediCare</span>
                        <span class="logo-sub">Clinic System</span>
                    </div>
                </div>
                <button class="sidebar-close" id="sidebar-close">
                    <i class='bx bx-x'></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <div class="nav-section">
                        <span class="nav-section-title">Main</span>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="nav-link <?php echo ($currentPage ?? '') === 'admin-dashboard' ? 'active' : ''; ?>" id="nav-admin-dashboard">
                            <i class='bx bxs-dashboard'></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/reservations" class="nav-link <?php echo ($currentPage ?? '') === 'admin-reservations' ? 'active' : ''; ?>" id="nav-admin-reservations">
                            <i class='bx bxs-calendar-check'></i>
                            <span>Reservations</span>
                        </a>
                    </div>
                    <div class="nav-section">
                        <span class="nav-section-title">Management</span>
                        <a href="<?php echo BASE_URL; ?>/admin/doctors" class="nav-link <?php echo ($currentPage ?? '') === 'admin-doctors' ? 'active' : ''; ?>" id="nav-admin-doctors">
                            <i class='bx bxs-user-detail'></i>
                            <span>Doctors & Schedules</span>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Patient Navigation -->
                    <div class="nav-section">
                        <span class="nav-section-title">Main</span>
                        <a href="<?php echo BASE_URL; ?>/patient/dashboard" class="nav-link <?php echo ($currentPage ?? '') === 'patient-dashboard' ? 'active' : ''; ?>" id="nav-patient-dashboard">
                            <i class='bx bxs-dashboard'></i>
                            <span>My Dashboard</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/patient/reserve" class="nav-link <?php echo ($currentPage ?? '') === 'patient-reserve' ? 'active' : ''; ?>" id="nav-patient-reserve">
                            <i class='bx bxs-calendar-plus'></i>
                            <span>Book Appointment</span>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="user-avatar">
                        <i class='bx bxs-user-circle'></i>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['user_role'] ?? 'patient'); ?></span>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="nav-link logout-link" id="nav-logout">
                    <i class='bx bx-log-out'></i>
                    <span>Log Out</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="menu-toggle" id="menu-toggle">
                        <i class='bx bx-menu'></i>
                    </button>
                    <div class="page-info">
                        <h1 class="page-title"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                        <p class="page-subtitle"><?php echo $pageSubtitle ?? ''; ?></p>
                    </div>
                </div>
                <div class="top-bar-right">
                    <div class="current-date">
                        <i class='bx bx-calendar'></i>
                        <span id="current-date"><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-area">
                <?php echo $content ?? ''; ?>
            </div>
        </main>
    </div>

    <!-- Notification Component -->
    <?php require_once BASE_PATH . '/views/components/notification.php'; ?>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modal-overlay"></div>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?php echo BASE_URL; ?>/assets/js/<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
