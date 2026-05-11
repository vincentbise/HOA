<?php
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'System overview and analytics';
$currentPage = 'admin-dashboard';
$scripts = ['admin.js'];

ob_start();
?>

<!-- Stats Grid -->
<div class="stats-grid stats-grid-large">
    <div class="stat-card stat-total">
        <div class="stat-icon"><i class='bx bxs-calendar'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['total_reservations']; ?></span>
            <span class="stat-label">Total Reservations</span>
        </div>
    </div>
    <div class="stat-card stat-pending">
        <div class="stat-icon"><i class='bx bxs-time-five'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['pending']; ?></span>
            <span class="stat-label">Pending</span>
        </div>
    </div>
    <div class="stat-card stat-approved">
        <div class="stat-icon"><i class='bx bxs-check-circle'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['approved']; ?></span>
            <span class="stat-label">Approved</span>
        </div>
    </div>
    <div class="stat-card stat-completed">
        <div class="stat-icon"><i class='bx bxs-badge-check'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['completed']; ?></span>
            <span class="stat-label">Completed</span>
        </div>
    </div>
    <div class="stat-card stat-cancelled-card">
        <div class="stat-icon"><i class='bx bxs-x-circle'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['cancelled']; ?></span>
            <span class="stat-label">Cancelled</span>
        </div>
    </div>
    <div class="stat-card stat-doctors">
        <div class="stat-icon"><i class='bx bxs-user-detail'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['active_doctors']; ?></span>
            <span class="stat-label">Active Doctors</span>
        </div>
    </div>
    <div class="stat-card stat-patients">
        <div class="stat-icon"><i class='bx bxs-group'></i></div>
        <div class="stat-details">
            <span class="stat-number"><?php echo $stats['total_patients']; ?></span>
            <span class="stat-label">Registered Patients</span>
        </div>
    </div>
</div>

<!-- Dashboard Content Grid -->
<div class="dashboard-grid">
    <!-- Today's Appointments -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class='bx bxs-calendar-event'></i> Today's Appointments</h2>
            <span class="card-badge"><?php echo count($todayReservations); ?> appointments</span>
        </div>
        <div class="card-body">
            <?php if (!empty($todayReservations)): ?>
            <div class="appointment-list">
                <?php foreach ($todayReservations as $res): ?>
                <div class="appointment-item">
                    <div class="appt-time">
                        <i class='bx bx-time'></i>
                        <?php echo date('g:i A', strtotime($res['appointment_time'])); ?>
                    </div>
                    <div class="appt-details">
                        <strong><?php echo htmlspecialchars($res['full_name']); ?></strong>
                        <span class="appt-doctor"><?php echo htmlspecialchars($res['doctor_name']); ?></span>
                    </div>
                    <span class="status-badge status-<?php echo strtolower($res['status']); ?>">
                        <?php echo $res['status']; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state-sm">
                <i class='bx bx-calendar-check'></i>
                <p>No appointments scheduled for today</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class='bx bxs-calendar-star'></i> Upcoming Appointments</h2>
            <a href="<?php echo BASE_URL; ?>/admin/reservations" class="card-link" id="link-view-all-reservations">View All</a>
        </div>
        <div class="card-body">
            <?php if (!empty($upcomingReservations)): ?>
            <div class="appointment-list">
                <?php foreach ($upcomingReservations as $res): ?>
                <div class="appointment-item">
                    <div class="appt-date-badge">
                        <span class="date-month"><?php echo date('M', strtotime($res['appointment_date'])); ?></span>
                        <span class="date-day"><?php echo date('j', strtotime($res['appointment_date'])); ?></span>
                    </div>
                    <div class="appt-details">
                        <strong><?php echo htmlspecialchars($res['full_name']); ?></strong>
                        <span class="appt-doctor"><?php echo htmlspecialchars($res['doctor_name']); ?> • <?php echo date('g:i A', strtotime($res['appointment_time'])); ?></span>
                    </div>
                    <span class="status-badge status-<?php echo strtolower($res['status']); ?>">
                        <?php echo $res['status']; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state-sm">
                <i class='bx bx-calendar-star'></i>
                <p>No upcoming appointments</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php';
?>
