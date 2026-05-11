<?php
$pageTitle = 'Manage Reservations';
$pageSubtitle = 'View and manage all appointment reservations';
$currentPage = 'admin-reservations';
$scripts = ['admin.js', 'search.js'];

ob_start();
?>

<!-- Action Bar -->
<div class="action-bar">
    <div class="search-box">
        <i class='bx bx-search'></i>
        <input type="text" id="search-input" class="search-input" 
               placeholder="Search by patient, doctor, date, or status..."
               data-search-url="<?php echo BASE_URL; ?>/admin/search-reservations"
               data-context="admin">
    </div>
    <div class="filter-group">
        <select id="status-filter" class="form-input form-select filter-select" onchange="filterByStatus(this.value)">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>
    </div>
</div>

<!-- Reservations Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class='bx bxs-calendar-check'></i> All Reservations</h2>
        <span class="card-badge" id="reservation-count"><?php echo count($reservations); ?> records</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table" id="admin-reservations-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="admin-reservations-tbody">
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $index => $res): ?>
                        <tr id="admin-res-row-<?php echo $res['id']; ?>" data-status="<?php echo $res['status']; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="cell-patient">
                                    <strong><?php echo htmlspecialchars($res['full_name']); ?></strong>
                                    <small><?php echo htmlspecialchars($res['email']); ?></small>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($res['contact_number']); ?></td>
                            <td>
                                <div class="cell-doctor">
                                    <i class='bx bxs-user-detail'></i>
                                    <div>
                                        <span><?php echo htmlspecialchars($res['doctor_name']); ?></span>
                                        <small><?php echo htmlspecialchars($res['specialization']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="cell-datetime">
                                    <span class="cell-date"><i class='bx bx-calendar'></i> <?php echo date('M j, Y', strtotime($res['appointment_date'])); ?></span>
                                    <span class="cell-time"><i class='bx bx-time'></i> <?php echo date('g:i A', strtotime($res['appointment_time'])); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="cell-reason" title="<?php echo htmlspecialchars($res['reason']); ?>">
                                    <?php echo htmlspecialchars(substr($res['reason'], 0, 25)); ?><?php echo strlen($res['reason']) > 25 ? '...' : ''; ?>
                                </span>
                            </td>
                            <td>
                                <select class="status-dropdown status-<?php echo strtolower($res['status']); ?>" 
                                        onchange="updateReservationStatus(<?php echo $res['id']; ?>, this.value)"
                                        id="status-select-<?php echo $res['id']; ?>">
                                    <option value="Pending" <?php echo $res['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo $res['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Completed" <?php echo $res['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Cancelled" <?php echo $res['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($res['status'] !== 'Cancelled'): ?>
                                    <button class="btn btn-sm btn-danger" onclick="adminCancelReservation(<?php echo $res['id']; ?>)" 
                                            id="admin-cancel-<?php echo $res['id']; ?>" title="Cancel Reservation">
                                        <i class='bx bx-x'></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-admin-reservations">
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-content">
                                    <i class='bx bx-calendar-x'></i>
                                    <p>No reservations found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php';
?>
