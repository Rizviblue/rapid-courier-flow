<?php
require_once 'includes/auth.php';

// Check if user is already logged in
if (Auth::isAuthenticated()) {
    $redirectUrl = Auth::getRoleRedirectUrl();
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
    if (!Auth::verifyCSRFToken($csrf_token)) {
        $error_message = 'Security token mismatch. Please try again.';
    } elseif (empty($email) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (Auth::login($email, $password)) {
        $role = Auth::getUserRole();
        $redirectUrl = Auth::getRoleRedirectUrl($role);
        
        Auth::setFlashMessage('success', 'Welcome back! Login successful.');
        header('Location: ' . $redirectUrl);
        exit();
    } else {
        $error_message = 'Invalid email or password. Please try again.';
    }
}

// Get flash message if any
$flash_message = Auth::getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rapid Courier Flow</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card fade-in">
            <!-- Header -->
            <div class="card-header text-center">
                <h1 class="card-title text-2xl font-bold">
                    <i class="fas fa-shipping-fast" style="color: var(--primary); margin-right: 0.5rem;"></i>
                    Rapid Courier Flow
                </h1>
                <p class="card-description">Sign in to your account to continue</p>
            </div>

            <!-- Form -->
            <div class="card-content">
                <!-- Flash Messages -->
                <?php if ($flash_message): ?>
                    <div class="alert alert-<?php echo $flash_message['type']; ?>">
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                        <?php echo htmlspecialchars($flash_message['message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="label">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="input" 
                            placeholder="Enter your email address"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            required
                            autocomplete="email"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="label">
                            <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>
                            Password
                        </label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input" 
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary w-full btn-lg">
                        <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
                        Sign In
                    </button>
                </form>

                <!-- Demo Login Section -->
                <div style="margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top: 1px solid var(--border);">
                    <h3 class="text-sm font-medium text-center mb-4" style="color: var(--muted-foreground);">
                        Demo Login Credentials
                    </h3>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="demo-login-card">
                            <button type="button" class="btn btn-outline w-full" onclick="fillDemoCredentials('admin')">
                                <i class="fas fa-user-shield" style="margin-right: 0.5rem; color: var(--primary);"></i>
                                Login as Admin
                            </button>
                            <small class="text-sm" style="color: var(--muted-foreground);">admin@courierpro.com</small>
                        </div>
                        <div class="demo-login-card">
                            <button type="button" class="btn btn-outline w-full" onclick="fillDemoCredentials('agent')">
                                <i class="fas fa-user-tie" style="margin-right: 0.5rem; color: var(--success);"></i>
                                Login as Agent
                            </button>
                            <small class="text-sm" style="color: var(--muted-foreground);">agent@courierpro.com</small>
                        </div>
                        <div class="demo-login-card">
                            <button type="button" class="btn btn-outline w-full" onclick="fillDemoCredentials('user')">
                                <i class="fas fa-user" style="margin-right: 0.5rem; color: var(--warning);"></i>
                                Login as User
                            </button>
                            <small class="text-sm" style="color: var(--muted-foreground);">user@courierpro.com</small>
                        </div>
                    </div>
                </div>

                <!-- Additional Links -->
                <div class="text-center" style="margin-top: var(--spacing-lg);">
                    <a href="forgot-password.php" class="text-sm" style="color: var(--primary); text-decoration: none;">
                        <i class="fas fa-key" style="margin-right: 0.5rem;"></i>
                        Forgot your password?
                    </a>
                </div>

                <div class="text-center" style="margin-top: var(--spacing-md);">
                    <span class="text-sm" style="color: var(--muted-foreground);">Don't have an account? </span>
                    <a href="register.php" class="text-sm" style="color: var(--primary); text-decoration: none;">
                        Sign up here
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Demo credentials fill function
        function fillDemoCredentials(role) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            const credentials = {
                admin: 'admin@courierpro.com',
                agent: 'agent@courierpro.com',
                user: 'user@courierpro.com'
            };
            
            emailInput.value = credentials[role];
            passwordInput.value = 'password';
            
            // Add visual feedback
            emailInput.style.backgroundColor = 'var(--accent)';
            passwordInput.style.backgroundColor = 'var(--accent)';
            
            setTimeout(() => {
                emailInput.style.backgroundColor = '';
                passwordInput.style.backgroundColor = '';
            }, 1000);
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
            
            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Signing in...';
            submitBtn.disabled = true;
            
            // Re-enable if form submission fails
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>

    <style>
        .demo-login-card {
            text-align: center;
            margin-bottom: var(--spacing-sm);
        }
        
        .demo-login-card button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .input-group {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: var(--spacing-sm);
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted-foreground);
            padding: var(--spacing-xs);
            border-radius: var(--radius-sm);
            transition: background-color 0.2s ease;
        }
        
        .password-toggle:hover {
            background: var(--accent);
        }
        
        /* Loading animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</body>
</html>