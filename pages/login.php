<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } else {
        if (login($email, $password)) {
            redirectToDashboard($_SESSION['user_role']);
        } else {
            $error = 'Invalid email or password';
        }
    }
}

if (isset($_GET['logout'])) {
    $success = 'You have been logged out successfully';
}
?>

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-truck text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-2"><?php echo APP_NAME; ?></h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <a href="<?php echo pageUrl('forgot-password'); ?>" class="text-decoration-none">
                                <i class="bi bi-question-circle"></i> Forgot Password?
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-2"><strong>Demo Accounts:</strong></p>
                            <div class="d-grid gap-2">
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="email" value="admin@courierpro.com">
                                    <input type="hidden" name="password" value="password">
                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-person-gear"></i> Login as Admin
                                    </button>
                                </form>
                                
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="email" value="agent@courierpro.com">
                                    <input type="hidden" name="password" value="password">
                                    <button type="submit" class="btn btn-outline-info btn-sm w-100">
                                        <i class="bi bi-people"></i> Login as Agent
                                    </button>
                                </form>
                                
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="email" value="user@courierpro.com">
                                    <input type="hidden" name="password" value="password">
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                        <i class="bi bi-person"></i> Login as User
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">
                                Don't have an account? 
                                <a href="<?php echo pageUrl('register'); ?>" class="text-decoration-none">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
}
</script>