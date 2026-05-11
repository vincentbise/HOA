<?php
$pageTitle = 'My Dashboard';
$pageSubtitle = 'Overview of your appointments';
$currentPage = 'patient-dashboard';
$scripts = ['reservation.js', 'search.js'];

ob_start();
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon">
            <i class='bx bxs-calendar'></i>
        </div>
        <div class="stat-details">
            <span class="stat-number" id="stat-total"><?php echo $stats['total']; ?></span>
            <span class="stat-label">Total Bookings</span>
        </div>
    </div>
    <div class="stat-card stat-pending">
        <div class="stat-icon">
            <i class='bx bxs-time-five'></i>
        </div>
        <div class="stat-details">
            <span class="stat-number" id="stat-pending"><?php echo $stats['pending']; ?></span>
            <span class="stat-label">Pending</span>
        </div>
    </div>
    <div class="stat-card stat-approved">
        <div class="stat-icon">
            <i class='bx bxs-check-circle'></i>
        </div>
        <div class="stat-details">
            <span class="stat-number" id="stat-approved"><?php echo $stats['approved']; ?></span>
            <span class="stat-label">Approved</span>
        </div>
    </div>
    <div class="stat-card stat-completed">
        <div class="stat-icon">
            <i class='bx bxs-badge-check'></i>
        </div>
        <div class="stat-details">
            <span class="stat-number" id="stat-completed"><?php echo $stats['completed']; ?></span>
            <span class="stat-label">Completed</span>
        </div>
    </div>
</div>

<!-- Action Bar -->
<div class="action-bar">
    <div class="search-box">
        <i class='bx bx-search'></i>
        <input type="text" id="search-input" class="search-input" 
               placeholder="Search by doctor, date, or status..." 
               data-search-url="<?php echo BASE_URL; ?>/patient/search"
               data-context="patient">
    </div>
    <a href="<?php echo BASE_URL; ?>/patient/reserve" class="btn btn-primary" id="btn-new-reservation">
        <i class='bx bx-plus'></i>
        <span>New Appointment</span>
    </a>
</div>

<!-- Reservations Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class='bx bxs-calendar-check'></i> My Reservations</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table" id="reservations-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="reservations-tbody">
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $index => $res): ?>
                        <tr id="reservation-row-<?php echo $res['id']; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="cell-doctor">
                                    <i class='bx bxs-user-detail'></i>
                                    <span><?php echo htmlspecialchars($res['doctor_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($res['specialization']); ?></td>
                            <td>
                                <div class="cell-date">
                                    <i class='bx bx-calendar'></i>
                                    <?php echo date('M j, Y', strtotime($res['appointment_date'])); ?>
                                </div>
                            </td>
                            <td>
                                <div class="cell-time">
                                    <i class='bx bx-time'></i>
                                    <?php echo date('g:i A', strtotime($res['appointment_time'])); ?>
                                </div>
                            </td>
                            <td>
                                <span class="cell-reason" title="<?php echo htmlspecialchars($res['reason']); ?>">
                                    <?php echo htmlspecialchars(substr($res['reason'], 0, 30)); ?><?php echo strlen($res['reason']) > 30 ? '...' : ''; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($res['status']); ?>">
                                    <?php echo $res['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($res['status'] === 'Pending' || $res['status'] === 'Approved'): ?>
                                <button class="btn btn-sm btn-danger" onclick="cancelReservation(<?php echo $res['id']; ?>)" 
                                        id="cancel-btn-<?php echo $res['id']; ?>">
                                    <i class='bx bx-x'></i> Cancel
                                </button>
                                <?php else: ?>
                                <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-reservations-row">
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-content">
                                    <i class='bx bx-calendar-x'></i>
                                    <p>No reservations yet</p>
                                    <a href="<?php echo BASE_URL; ?>/patient/reserve" class="btn btn-primary btn-sm">Book Your First Appointment</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php';
?>
