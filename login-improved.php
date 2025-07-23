<?php
require_once 'includes/auth.php';

// Check if user is already logged in
if (isAuthenticated()) {
    $role = getUserRole();
    $redirectUrl = getRoleRedirectUrl($role);
    header('Location: ' . $redirectUrl);
    exit();
}

$error_message = '';
$success_message = '';

// Handle form submission
if ($_POST) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Verify CSRF token
    if (!verifyCSRFToken($csrf_token)) {
        $error_message = 'Security token mismatch. Please try again.';
    } elseif (empty($email) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (authenticateUser($email, $password)) {
        $role = getUserRole();
        $redirectUrl = getRoleRedirectUrl($role);
        setFlashMessage('success', 'Login successful! Welcome back.');
        header('Location: ' . $redirectUrl);
        exit();
    } else {
        $error_message = 'Invalid email or password. Use demo credentials below.';
    }
}

// Handle demo login
if (isset($_GET['demo'])) {
    $role = $_GET['demo'];
    
    if (in_array($role, ['admin', 'agent', 'user'])) {
        if (demoLogin($role)) {
            $redirectUrl = getRoleRedirectUrl($role);
            setFlashMessage('success', 'Demo login successful! Logged in as ' . ucfirst($role));
            header('Location: ' . $redirectUrl);
            exit();
        }
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Courier Management System</title>
    <meta name="description" content="Secure login to the Courier Management System">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- External Stylesheets -->
    <link href="assets/css/login-styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-muted-foreground mb-2">
                Courier Management System
            </h1>
            <p class="text-sm text-muted-foreground">
                Professional package tracking and management
            </p>
        </div>

        <!-- Flash Messages -->
        <?php 
        $flash_messages = getFlashMessage();
        foreach ($flash_messages as $type => $message): 
        ?>
            <div class="alert alert-<?php echo $type; ?>" id="flashMessage">
                <i data-lucide="<?php echo $type === 'error' ? 'alert-circle' : ($type === 'success' ? 'check-circle' : 'info'); ?>" class="icon"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>

        <!-- Error/Success Messages from Form -->
        <?php if ($error_message): ?>
            <div class="alert alert-error" id="errorMessage">
                <i data-lucide="alert-circle" class="icon"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success" id="successMessage">
                <i data-lucide="check-circle" class="icon"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="card card-shadow-lg">
            <div class="text-center p-6 pb-4">
                <h2 class="text-2xl font-semibold mb-2">Sign In</h2>
                <p class="text-sm text-muted-foreground">
                    Enter your credentials to access your account
                </p>
            </div>
            <div class="p-6 pt-0 space-y-4">
                <form method="POST" class="space-y-4" id="loginForm" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">
                            Email Address <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="mail" class="icon icon-left"></i>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                class="input pl-10"
                                required
                                autocomplete="email"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                aria-describedby="email-error"
                            />
                        </div>
                        <div id="email-error" class="text-destructive text-xs hidden"></div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">
                            Password <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="lock" class="icon icon-left"></i>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                class="input pl-10 pr-10"
                                required
                                autocomplete="current-password"
                                aria-describedby="password-error"
                            />
                            <button
                                type="button"
                                class="btn-ghost absolute icon-right"
                                onclick="togglePassword()"
                                id="passwordToggle"
                                aria-label="Toggle password visibility"
                            >
                                <i data-lucide="eye" class="icon" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div id="password-error" class="text-destructive text-xs hidden"></div>
                    </div>

                    <button 
                        type="submit" 
                        class="btn-primary w-full" 
                        id="submitBtn"
                        aria-describedby="login-help"
                    >
                        <span id="btnText">Sign In</span>
                        <div id="loading" class="loading icon hidden">
                            <i data-lucide="loader" class="icon"></i>
                        </div>
                    </button>
                    
                    <div id="login-help" class="text-xs text-muted-foreground text-center">
                        Use demo credentials below or enter your login details
                    </div>
                </form>

                <div class="flex items-center justify-between text-sm">
                    <button
                        type="button"
                        onclick="showForgotPassword()"
                        class="link"
                        aria-label="Reset your password"
                    >
                        Forgot Password?
                    </button>
                    <div class="text-muted-foreground">
                        Don't have an account?
                        <a href="register.php" class="link">Register here</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="card card-shadow-lg">
            <div class="p-6 pb-4">
                <h3 class="text-lg font-semibold mb-2">Demo Credentials</h3>
                <p class="text-sm text-muted-foreground">
                    Click any button below for instant access
                </p>
            </div>
            <div class="p-6 pt-0 space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <a href="?demo=admin" class="btn-outline" aria-label="Login as Admin">
                        <span class="font-medium">Admin</span>
                        <span class="text-xs text-muted-foreground">Full system access</span>
                    </a>
                    
                    <a href="?demo=agent" class="btn-outline" aria-label="Login as Agent">
                        <span class="font-medium">Agent</span>
                        <span class="text-xs text-muted-foreground">Courier management</span>
                    </a>
                    
                    <a href="?demo=user" class="btn-outline" aria-label="Login as User">
                        <span class="font-medium">User</span>
                        <span class="text-xs text-muted-foreground">Track packages</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-muted-foreground bg-orange-50 p-3 rounded-lg">
                    <i data-lucide="alert-circle" class="icon text-orange-500"></i>
                    <span>All demo accounts use password: <strong>password</strong></span>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="card text-center">
            <div class="p-6">
                <div class="flex items-center justify-center gap-2 text-muted-foreground mb-4">
                    <i data-lucide="help-circle" class="icon"></i>
                    <span class="text-sm">Need assistance?</span>
                </div>
                <div class="flex justify-center gap-4 text-sm">
                    <a href="support.php" class="link">Contact Support</a>
                    <a href="docs.php" class="link">Documentation</a>
                    <a href="about.php" class="link">About System</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="modal-overlay hidden" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <button onclick="hideForgotPassword()" class="btn-ghost" aria-label="Close modal">
                        <i data-lucide="arrow-left" class="icon"></i>
                    </button>
                    <h3 id="modal-title" class="text-lg font-semibold">Reset Password</h3>
                </div>
                
                <form onsubmit="handleForgotPassword(event)" class="space-y-4">
                    <div class="space-y-2">
                        <label for="reset-email" class="text-sm font-medium">
                            Email Address <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="mail" class="icon icon-left"></i>
                            <input
                                id="reset-email"
                                type="email"
                                placeholder="Enter your email address"
                                class="input pl-10"
                                required
                                autocomplete="email"
                            />
                        </div>
                        <p class="text-sm text-muted-foreground">
                            We'll send you a secure link to reset your password.
                        </p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button
                            type="button"
                            onclick="hideForgotPassword()"
                            class="btn-outline flex-1"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn-primary flex-1"
                            id="resetBtn"
                        >
                            <span id="resetBtnText">Send Reset Link</span>
                            <div id="resetLoading" class="loading icon hidden">
                                <i data-lucide="loader" class="icon"></i>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Form validation
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;

            // Clear previous errors
            document.getElementById('email-error').classList.add('hidden');
            document.getElementById('password-error').classList.add('hidden');

            // Email validation
            if (!email.value.trim()) {
                showFieldError('email', 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showFieldError('email', 'Please enter a valid email address');
                isValid = false;
            }

            // Password validation
            if (!password.value) {
                showFieldError('password', 'Password is required');
                isValid = false;
            }

            return isValid;
        }

        function showFieldError(fieldId, message) {
            const errorElement = document.getElementById(fieldId + '-error');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            document.getElementById(fieldId).focus();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            
            lucide.createIcons();
        }

        function showForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
            document.getElementById('reset-email').focus();
        }

        function hideForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
            document.getElementById('reset-email').value = '';
        }

        function handleForgotPassword(event) {
            event.preventDefault();
            const email = document.getElementById('reset-email').value;
            const resetBtn = document.getElementById('resetBtn');
            const resetBtnText = document.getElementById('resetBtnText');
            const resetLoading = document.getElementById('resetLoading');
            
            if (!email || !isValidEmail(email)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Show loading state
            resetBtnText.textContent = 'Sending...';
            resetLoading.classList.remove('hidden');
            resetBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert(`Password reset link sent to ${email}`);
                hideForgotPassword();
                
                // Reset button state
                resetBtnText.textContent = 'Send Reset Link';
                resetLoading.classList.add('hidden');
                resetBtn.disabled = false;
            }, 2000);
        }

        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loading = document.getElementById('loading');
            
            btnText.textContent = 'Signing In...';
            loading.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        // Auto-dismiss alerts after 7 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            });
        }, 7000);

        // Keyboard navigation for demo buttons
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('forgotPasswordModal').classList.contains('hidden')) {
                hideForgotPassword();
            }
        });

        // Real-time validation feedback
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                showFieldError('email', 'Please enter a valid email address');
            } else {
                document.getElementById('email-error').classList.add('hidden');
            }
        });

        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>