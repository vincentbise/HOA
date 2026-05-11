<?php
$pageTitle = 'Doctors & Schedules';
$pageSubtitle = 'Manage doctor profiles and consultation schedules';
$currentPage = 'admin-doctors';
$scripts = ['admin.js'];

ob_start();
?>

<!-- Action Bar -->
<div class="action-bar">
    <div class="action-bar-left">
        <h2 class="section-title"><i class='bx bxs-user-detail'></i> Doctor Directory</h2>
    </div>
    <button class="btn btn-primary" onclick="openDoctorModal()" id="btn-add-doctor">
        <i class='bx bx-plus'></i> Add Doctor
    </button>
</div>

<!-- Doctors Grid -->
<div class="doctors-grid" id="doctors-grid">
    <?php if (!empty($doctors)): ?>
        <?php foreach ($doctors as $doc): ?>
        <div class="doctor-card" id="doctor-card-<?php echo $doc['id']; ?>">
            <div class="doctor-card-header">
                <div class="doctor-avatar">
                    <i class='bx bxs-user-detail'></i>
                </div>
                <span class="doctor-status-badge status-<?php echo $doc['status'] === 'active' ? 'approved' : 'cancelled'; ?>">
                    <?php echo ucfirst($doc['status']); ?>
                </span>
            </div>
            <div class="doctor-card-body">
                <h3 class="doctor-name"><?php echo htmlspecialchars($doc['full_name']); ?></h3>
                <p class="doctor-spec"><i class='bx bx-briefcase-alt-2'></i> <?php echo htmlspecialchars($doc['specialization']); ?></p>
                <?php if ($doc['email']): ?>
                <p class="doctor-contact"><i class='bx bx-envelope'></i> <?php echo htmlspecialchars($doc['email']); ?></p>
                <?php endif; ?>
                <?php if ($doc['phone']): ?>
                <p class="doctor-contact"><i class='bx bx-phone'></i> <?php echo htmlspecialchars($doc['phone']); ?></p>
                <?php endif; ?>
            </div>
            <div class="doctor-card-footer">
                <button class="btn btn-sm btn-outline" onclick="editDoctor(<?php echo $doc['id']; ?>, '<?php echo addslashes($doc['full_name']); ?>', '<?php echo addslashes($doc['specialization']); ?>', '<?php echo addslashes($doc['email'] ?? ''); ?>', '<?php echo addslashes($doc['phone'] ?? ''); ?>', '<?php echo $doc['status']; ?>')" id="edit-doc-<?php echo $doc['id']; ?>">
                    <i class='bx bx-edit'></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteDoctor(<?php echo $doc['id']; ?>)" id="del-doc-<?php echo $doc['id']; ?>">
                    <i class='bx bx-trash'></i>
                </button>
                <button class="btn btn-sm btn-primary" onclick="viewSchedules(<?php echo $doc['id']; ?>, '<?php echo addslashes($doc['full_name']); ?>')" id="sched-doc-<?php echo $doc['id']; ?>">
                    <i class='bx bx-calendar'></i> Schedules
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state-full" id="no-doctors">
            <i class='bx bx-user-plus'></i>
            <p>No doctors registered yet</p>
            <button class="btn btn-primary" onclick="openDoctorModal()">Add First Doctor</button>
        </div>
    <?php endif; ?>
</div>

<!-- Schedules Section -->
<div class="section-divider"></div>

<div class="action-bar">
    <div class="action-bar-left">
        <h2 class="section-title"><i class='bx bxs-calendar'></i> All Schedules</h2>
    </div>
    <button class="btn btn-primary" onclick="openScheduleModal()" id="btn-add-schedule">
        <i class='bx bx-plus'></i> Add Schedule
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table" id="schedules-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Patients</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="schedules-tbody">
                    <?php if (!empty($schedules)): ?>
                        <?php foreach ($schedules as $index => $sched): ?>
                        <tr id="schedule-row-<?php echo $sched['id']; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="cell-doctor">
                                    <i class='bx bxs-user-detail'></i>
                                    <span><?php echo htmlspecialchars($sched['doctor_name']); ?></span>
                                </div>
                            </td>
                            <td><span class="day-badge"><?php echo $sched['day_of_week']; ?></span></td>
                            <td><?php echo date('g:i A', strtotime($sched['start_time'])); ?></td>
                            <td><?php echo date('g:i A', strtotime($sched['end_time'])); ?></td>
                            <td><span class="count-badge"><?php echo $sched['max_patients']; ?></span></td>
                            <td>
                                <span class="status-badge status-<?php echo $sched['is_available'] ? 'approved' : 'cancelled'; ?>">
                                    <?php echo $sched['is_available'] ? 'Yes' : 'No'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline" onclick="editSchedule(<?php echo $sched['id']; ?>, <?php echo $sched['doctor_id']; ?>, '<?php echo $sched['day_of_week']; ?>', '<?php echo $sched['start_time']; ?>', '<?php echo $sched['end_time']; ?>', <?php echo $sched['max_patients']; ?>, <?php echo $sched['is_available']; ?>)" id="edit-sched-<?php echo $sched['id']; ?>">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSchedule(<?php echo $sched['id']; ?>)" id="del-sched-<?php echo $sched['id']; ?>">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-schedules">
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-content">
                                    <i class='bx bx-calendar-x'></i>
                                    <p>No schedules added yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Doctor Modal -->
<div class="modal" id="doctor-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="doctor-modal-title">Add Doctor</h3>
            <button class="modal-close" onclick="closeDoctorModal()" id="close-doctor-modal">
                <i class='bx bx-x'></i>
            </button>
        </div>
        <form id="doctor-form" novalidate>
            <input type="hidden" id="doc-edit-id" name="doctor_id" value="">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="doc-name" class="form-label">Full Name</label>
                        <input type="text" id="doc-name" name="full_name" class="form-input" placeholder="Dr. Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="doc-spec" class="form-label">Specialization</label>
                        <input type="text" id="doc-spec" name="specialization" class="form-input" placeholder="e.g. Cardiology" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="doc-email" class="form-label">Email</label>
                        <input type="email" id="doc-email" name="email" class="form-input" placeholder="doctor@clinic.com">
                    </div>
                    <div class="form-group">
                        <label for="doc-phone" class="form-label">Phone</label>
                        <input type="tel" id="doc-phone" name="phone" class="form-input" placeholder="09171234567">
                    </div>
                </div>
                <div class="form-group">
                    <label for="doc-status" class="form-label">Status</label>
                    <select id="doc-status" name="status" class="form-input form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeDoctorModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn-save-doctor">
                    <span class="btn-text">Save Doctor</span>
                    <span class="btn-loader" style="display:none;"><i class='bx bx-loader-alt bx-spin'></i></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal" id="schedule-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="schedule-modal-title">Add Schedule</h3>
            <button class="modal-close" onclick="closeScheduleModal()" id="close-schedule-modal">
                <i class='bx bx-x'></i>
            </button>
        </div>
        <form id="schedule-form" novalidate>
            <input type="hidden" id="sched-edit-id" name="schedule_id" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label for="sched-doctor" class="form-label">Doctor</label>
                    <select id="sched-doctor" name="doctor_id" class="form-input form-select" required>
                        <option value="">— Select Doctor —</option>
                        <?php foreach ($doctors as $doc): ?>
                        <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['full_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sched-day" class="form-label">Day of Week</label>
                    <select id="sched-day" name="day_of_week" class="form-input form-select" required>
                        <option value="">— Select Day —</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sched-start" class="form-label">Start Time</label>
                        <input type="time" id="sched-start" name="start_time" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="sched-end" class="form-label">End Time</label>
                        <input type="time" id="sched-end" name="end_time" class="form-input" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sched-max" class="form-label">Max Patients</label>
                        <input type="number" id="sched-max" name="max_patients" class="form-input" value="10" min="1" max="50">
                    </div>
                    <div class="form-group">
                        <label for="sched-available" class="form-label">Available</label>
                        <select id="sched-available" name="is_available" class="form-input form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeScheduleModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn-save-schedule">
                    <span class="btn-text">Save Schedule</span>
                    <span class="btn-loader" style="display:none;"><i class='bx bx-loader-alt bx-spin'></i></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php';
?>
