<?php
/**
 * Registration Page
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

// Handle registration form submission
if ($_POST) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $data = [
            'name' => sanitize($_POST['name'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'role' => sanitize($_POST['role'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];
        
        // Validation
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            $error = 'Please fill in all required fields.';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $error = 'Passwords do not match.';
        } elseif (strlen($data['password']) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif (!in_array($data['role'], ['agent', 'user'])) {
            $error = 'Invalid role selected.';
        } else {
            $result = $auth->register($data);
            
            if ($result['success']) {
                flash('success', 'Registration successful! You can now login.');
                redirect(BASE_URL . '/login.php');
            } else {
                $error = $result['message'];
            }
        }
        
        // Store old input for form repopulation
        $_SESSION['old'] = $data;
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
    <title>Register - <?php echo APP_NAME; ?></title>
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

        <!-- Registration Form -->
        <div class="card shadow-lg">
            <div class="card-header text-center pb-4">
                <h2 class="card-title text-2xl font-semibold">Create Account</h2>
                <p class="card-description">
                    Join our courier management platform
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
                        <label for="name" class="text-sm font-medium">Full Name *</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            placeholder="Enter your full name"
                            value="<?php echo old('name'); ?>"
                            class="input"
                            data-validate="required|min:2"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">Email Address *</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="Enter your email"
                            value="<?php echo old('email'); ?>"
                            class="input"
                            data-validate="required|email"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-medium">Phone Number</label>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            placeholder="Enter your phone number"
                            value="<?php echo old('phone'); ?>"
                            class="input"
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="text-sm font-medium">Role *</label>
                        <select id="role" name="role" class="select" required>
                            <option value="">Select your role</option>
                            <option value="agent" <?php echo old('role') === 'agent' ? 'selected' : ''; ?>>
                                Agent - Manage courier operations
                            </option>
                            <option value="user" <?php echo old('role') === 'user' ? 'selected' : ''; ?>>
                                User - Track packages
                            </option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">Password *</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Create a password"
                            class="input"
                            data-validate="required|min:6"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_password" class="text-sm font-medium">Confirm Password *</label>
                        <input
                            id="confirm_password"
                            name="confirm_password"
                            type="password"
                            placeholder="Confirm your password"
                            class="input"
                            data-validate="required|min:6"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        Create Account
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-sm text-muted-foreground">
                        Already have an account?
                        <a href="login.php" class="text-primary hover:underline">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('border-red-500');
                showFieldError(this, 'Passwords do not match');
            } else {
                this.classList.remove('border-red-500');
                clearFieldError({ target: this });
            }
        });
        
        function showFieldError(field, message) {
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            
            if (message) {
                const errorElement = document.createElement('div');
                errorElement.className = 'field-error text-red-600 text-sm mt-1';
                errorElement.textContent = message;
                field.parentNode.appendChild(errorElement);
            }
        }
        
        function clearFieldError(event) {
            const field = event.target;
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
        }
    </script>
</body>
</html>

<?php
// Clear old input data
unset($_SESSION['old']);
?>