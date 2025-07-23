<?php
/**
 * Login Page
 */

require_once 'config/config.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    $user = $auth->user();
    redirect(BASE_URL . '/' . $user['role'] . '/dashboard.php');
}

$error = '';
$success = '';

// Handle login form submission
if ($_POST) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            if ($auth->login($email, $password)) {
                $user = $auth->user();
                redirect(BASE_URL . '/' . $user['role'] . '/dashboard.php');
            } else {
                $error = 'Invalid email or password.';
            }
        }
    }
}

// Get flash messages
$error = $error ?: flash('error');
$success = flash('success');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
</head>
<body class="min-h-screen bg-background flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-muted-foreground mb-2">
                Courier Management System
            </h1>
        </div>

        <!-- Login Form -->
        <div class="card shadow-lg">
            <div class="card-header text-center pb-4">
                <h2 class="card-title text-2xl font-semibold">Sign In</h2>
                <p class="card-description">
                    Enter your credentials to access your account
                </p>
            </div>
            <div class="card-content space-y-4">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4" data-loading>
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">Email</label>
                        <div class="relative">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                value="<?php echo old('email'); ?>"
                                class="input pl-10"
                                data-validate="required|email"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                class="input pl-10"
                                data-validate="required|min:6"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        Sign In
                    </button>
                </form>

                <div class="flex items-center justify-between text-sm">
                    <a href="forgot-password.php" class="text-primary hover:underline">
                        Forgot Password?
                    </a>
                    <div class="text-muted-foreground">
                        Don't have an account?
                        <a href="register.php" class="text-primary hover:underline">
                            Register here
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="card shadow-lg">
            <div class="card-header pb-4">
                <h3 class="card-title text-lg">Demo Credentials</h3>
                <p class="card-description">
                    Use these credentials to test the system
                </p>
            </div>
            <div class="card-content space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <div class="p-3 border rounded-lg">
                        <div class="font-medium">Admin Account</div>
                        <div class="text-sm text-muted-foreground">
                            Email: admin@courierpro.com<br>
                            Password: password
                        </div>
                    </div>
                    
                    <div class="p-3 border rounded-lg">
                        <div class="font-medium">Agent Account</div>
                        <div class="text-sm text-muted-foreground">
                            Email: agent@courierpro.com<br>
                            Password: password
                        </div>
                    </div>
                    
                    <div class="p-3 border rounded-lg">
                        <div class="font-medium">User Account</div>
                        <div class="text-sm text-muted-foreground">
                            Email: user@courierpro.com<br>
                            Password: password
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Contact Link -->
        <div class="card text-center">
            <div class="card-content pt-6">
                <div class="flex items-center justify-center gap-2 text-muted-foreground">
                    <span class="text-sm">Need help?</span>
                    <a href="support.php" class="text-primary hover:underline text-sm">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
</body>
</html>