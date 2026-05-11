<?php
$pageTitle = 'Book Appointment';
$pageSubtitle = 'Schedule a new consultation';
$currentPage = 'patient-reserve';
$scripts = ['reservation.js'];

ob_start();
?>

<div class="reserve-layout">
    <!-- Reservation Form -->
    <div class="card reserve-form-card">
        <div class="card-header">
            <h2 class="card-title"><i class='bx bxs-calendar-plus'></i> Appointment Details</h2>
        </div>
        <div class="card-body">
            <form id="reservation-form" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="res-fullname" class="form-label">
                            <i class='bx bx-user'></i> Full Name
                        </label>
                        <input type="text" id="res-fullname" name="full_name" class="form-input" 
                               value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>"
                               placeholder="Enter your full name" required>
                        <span class="form-error" id="res-fullname-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="res-contact" class="form-label">
                            <i class='bx bx-phone'></i> Contact Number
                        </label>
                        <input type="tel" id="res-contact" name="contact_number" class="form-input" 
                               placeholder="e.g. 09171234567" required>
                        <span class="form-error" id="res-contact-error"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="res-email" class="form-label">
                        <i class='bx bx-envelope'></i> Email Address
                    </label>
                    <input type="email" id="res-email" name="email" class="form-input" 
                           value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>"
                           placeholder="Enter your email" required>
                    <span class="form-error" id="res-email-error"></span>
                </div>

                <div class="form-group">
                    <label for="res-doctor" class="form-label">
                        <i class='bx bx-user-pin'></i> Preferred Doctor
                    </label>
                    <select id="res-doctor" name="doctor_id" class="form-input form-select" required>
                        <option value="">— Select a Doctor —</option>
                        <?php foreach ($doctors as $doc): ?>
                        <option value="<?php echo $doc['id']; ?>">
                            <?php echo htmlspecialchars($doc['full_name']); ?> — <?php echo htmlspecialchars($doc['specialization']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-error" id="res-doctor-error"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="res-date" class="form-label">
                            <i class='bx bx-calendar'></i> Appointment Date
                        </label>
                        <input type="date" id="res-date" name="appointment_date" class="form-input" 
                               min="<?php echo date('Y-m-d'); ?>" required>
                        <span class="form-error" id="res-date-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="res-time" class="form-label">
                            <i class='bx bx-time'></i> Appointment Time
                        </label>
                        <select id="res-time" name="appointment_time" class="form-input form-select" required disabled>
                            <option value="">— Select doctor & date first —</option>
                        </select>
                        <span class="form-error" id="res-time-error"></span>
                        <span class="form-hint" id="time-hint" style="display:none;">
                            <i class='bx bx-loader-alt bx-spin'></i> Loading available slots...
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="res-reason" class="form-label">
                        <i class='bx bx-notepad'></i> Reason for Consultation
                    </label>
                    <textarea id="res-reason" name="reason" class="form-input form-textarea" rows="4" 
                              placeholder="Briefly describe your concern or reason for visit..." required></textarea>
                    <span class="form-error" id="res-reason-error"></span>
                </div>

                <div class="form-message" id="reservation-message"></div>

                <div class="form-actions">
                    <a href="<?php echo BASE_URL; ?>/patient/dashboard" class="btn btn-outline" id="btn-back-dashboard">
                        <i class='bx bx-arrow-back'></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-submit-reservation">
                        <span class="btn-text"><i class='bx bx-check'></i> Submit Reservation</span>
                        <span class="btn-loader" style="display:none;"><i class='bx bx-loader-alt bx-spin'></i> Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="reserve-info-sidebar">
        <div class="card info-card">
            <div class="card-header">
                <h3 class="card-title"><i class='bx bx-info-circle'></i> How It Works</h3>
            </div>
            <div class="card-body">
                <div class="info-steps">
                    <div class="info-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <strong>Fill In Your Details</strong>
                            <p>Provide your name, contact info, and reason for visit.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <strong>Choose a Doctor</strong>
                            <p>Select your preferred doctor from the list.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <strong>Pick Date & Time</strong>
                            <p>Available slots will appear based on the doctor's schedule.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <strong>Confirm Booking</strong>
                            <p>Submit your reservation and wait for approval.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card info-card status-info-card">
            <div class="card-header">
                <h3 class="card-title"><i class='bx bx-flag'></i> Status Guide</h3>
            </div>
            <div class="card-body">
                <div class="status-guide-list">
                    <div class="status-guide-item">
                        <span class="status-badge status-pending">Pending</span>
                        <span>Awaiting clinic confirmation</span>
                    </div>
                    <div class="status-guide-item">
                        <span class="status-badge status-approved">Approved</span>
                        <span>Confirmed by the clinic</span>
                    </div>
                    <div class="status-guide-item">
                        <span class="status-badge status-completed">Completed</span>
                        <span>Consultation done</span>
                    </div>
                    <div class="status-guide-item">
                        <span class="status-badge status-cancelled">Cancelled</span>
                        <span>Booking cancelled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store BASE_URL for JS
    const BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php';
?>
