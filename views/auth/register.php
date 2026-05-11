<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for MediCare Clinic Reservation System">
    <title>Register — MediCare Clinic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body class="auth-body">
    <div class="auth-wrapper">
        <!-- Left Panel - Branding -->
        <div class="auth-brand-panel">
            <div class="auth-brand-content">
                <div class="brand-icon-large">
                    <i class='bx bx-plus-medical'></i>
                </div>
                <h1 class="brand-title">MediCare</h1>
                <p class="brand-subtitle">Clinic Reservation System</p>
                <div class="brand-features">
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Quick & easy registration</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Secure patient portal</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Track your appointments</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>24/7 online access</span>
                    </div>
                </div>
            </div>
            <div class="brand-decorative">
                <div class="deco-circle deco-circle-1"></div>
                <div class="deco-circle deco-circle-2"></div>
                <div class="deco-circle deco-circle-3"></div>
            </div>
        </div>

        <!-- Right Panel - Register Form -->
        <div class="auth-form-panel">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Fill in your details to get started</p>
                </div>

                <form id="register-form" class="auth-form" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="reg-fullname" class="form-label">
                                <i class='bx bx-user'></i>
                                Full Name
                            </label>
                            <input type="text" id="reg-fullname" name="full_name" class="form-input" 
                                   placeholder="Enter your full name" required>
                            <span class="form-error" id="reg-fullname-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="reg-phone" class="form-label">
                                <i class='bx bx-phone'></i>
                                Phone Number
                            </label>
                            <input type="tel" id="reg-phone" name="phone" class="form-input" 
                                   placeholder="e.g. 09171234567">
                            <span class="form-error" id="reg-phone-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reg-email" class="form-label">
                            <i class='bx bx-envelope'></i>
                            Email Address
                        </label>
                        <input type="email" id="reg-email" name="email" class="form-input" 
                               placeholder="Enter your email address" required autocomplete="email">
                        <span class="form-error" id="reg-email-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="reg-password" class="form-label">
                                <i class='bx bx-lock-alt'></i>
                                Password
                            </label>
                            <div class="input-password-wrapper">
                                <input type="password" id="reg-password" name="password" class="form-input" 
                                       placeholder="Min. 6 characters" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('reg-password', this)">
                                    <i class='bx bx-hide'></i>
                                </button>
                            </div>
                            <span class="form-error" id="reg-password-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="reg-confirm" class="form-label">
                                <i class='bx bx-lock-alt'></i>
                                Confirm Password
                            </label>
                            <div class="input-password-wrapper">
                                <input type="password" id="reg-confirm" name="confirm_password" class="form-input" 
                                       placeholder="Repeat password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('reg-confirm', this)">
                                    <i class='bx bx-hide'></i>
                                </button>
                            </div>
                            <span class="form-error" id="reg-confirm-error"></span>
                        </div>
                    </div>

                    <div class="form-message" id="register-message"></div>

                    <button type="submit" class="btn btn-primary btn-full" id="register-btn">
                        <span class="btn-text">Create Account</span>
                        <span class="btn-loader" style="display:none;"><i class='bx bx-loader-alt bx-spin'></i></span>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login" class="auth-link" id="go-to-login">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="toast-container"></div>

    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>
</html>
