<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to MediCare Clinic Reservation System">
    <title>Login — MediCare Clinic</title>
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
                        <span>Book appointments online</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Real-time schedule updates</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Manage your health records</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class='bx bx-check-circle'></i>
                        <span>Instant confirmation</span>
                    </div>
                </div>
            </div>
            <div class="brand-decorative">
                <div class="deco-circle deco-circle-1"></div>
                <div class="deco-circle deco-circle-2"></div>
                <div class="deco-circle deco-circle-3"></div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="auth-form-panel">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="auth-subtitle">Sign in to your account to continue</p>
                </div>

                <form id="login-form" class="auth-form" novalidate>
                    <div class="form-group">
                        <label for="login-email" class="form-label">
                            <i class='bx bx-envelope'></i>
                            Email Address
                        </label>
                        <input type="email" id="login-email" name="email" class="form-input" 
                               placeholder="Enter your email address" required autocomplete="email">
                        <span class="form-error" id="login-email-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="login-password" class="form-label">
                            <i class='bx bx-lock-alt'></i>
                            Password
                        </label>
                        <div class="input-password-wrapper">
                            <input type="password" id="login-password" name="password" class="form-input" 
                                   placeholder="Enter your password" required autocomplete="current-password">
                            <button type="button" class="password-toggle" id="toggle-password">
                                <i class='bx bx-hide'></i>
                            </button>
                        </div>
                        <span class="form-error" id="login-password-error"></span>
                    </div>

                    <div class="form-message" id="login-message"></div>

                    <button type="submit" class="btn btn-primary btn-full" id="login-btn">
                        <span class="btn-text">Sign In</span>
                        <span class="btn-loader" style="display:none;"><i class='bx bx-loader-alt bx-spin'></i></span>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/auth/register" class="auth-link" id="go-to-register">Create Account</a></p>
                </div>

                <div class="auth-demo-info">
                    <p class="demo-title"><i class='bx bx-info-circle'></i> Demo Credentials</p>
                    <p><strong>Admin:</strong> admin@clinic.com / admin123</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>
</html>
