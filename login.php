<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';
$success_message = '';

// Handle form submission
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Demo login credentials
    $demo_users = [
        'admin@courierpro.com' => [
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'admin@courierpro.com',
            'role' => 'admin',
            'password' => 'password'
        ],
        'agent@courierpro.com' => [
            'id' => 2,
            'name' => 'Sarah Johnson',
            'email' => 'agent@courierpro.com',
            'role' => 'agent',
            'city' => 'New York',
            'password' => 'password'
        ],
        'user@courierpro.com' => [
            'id' => 3,
            'name' => 'Mike Wilson',
            'email' => 'user@courierpro.com',
            'role' => 'user',
            'phone' => '+1 234 567 8900',
            'password' => 'password'
        ]
    ];
    
    if (isset($demo_users[$email]) && $demo_users[$email]['password'] === $password) {
        // Set session variables
        $_SESSION['user_id'] = $demo_users[$email]['id'];
        $_SESSION['user_name'] = $demo_users[$email]['name'];
        $_SESSION['user_email'] = $demo_users[$email]['email'];
        $_SESSION['user_role'] = $demo_users[$email]['role'];
        $_SESSION['is_authenticated'] = true;
        
        // Redirect based on role
        $redirect_url = match($demo_users[$email]['role']) {
            'admin' => 'admin/dashboard.php',
            'agent' => 'agent/dashboard.php',
            'user' => 'user/dashboard.php',
            default => 'dashboard.php'
        };
        
        header('Location: ' . $redirect_url);
        exit();
    } else {
        $error_message = 'Invalid email or password. Use demo credentials below.';
    }
}

// Handle demo login
if (isset($_GET['demo'])) {
    $role = $_GET['demo'];
    $demo_users = [
        'admin' => [
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'admin@courierpro.com',
            'role' => 'admin'
        ],
        'agent' => [
            'id' => 2,
            'name' => 'Sarah Johnson',
            'email' => 'agent@courierpro.com',
            'role' => 'agent'
        ],
        'user' => [
            'id' => 3,
            'name' => 'Mike Wilson',
            'email' => 'user@courierpro.com',
            'role' => 'user'
        ]
    ];
    
    if (isset($demo_users[$role])) {
        $_SESSION['user_id'] = $demo_users[$role]['id'];
        $_SESSION['user_name'] = $demo_users[$role]['name'];
        $_SESSION['user_email'] = $demo_users[$role]['email'];
        $_SESSION['user_role'] = $demo_users[$role]['role'];
        $_SESSION['is_authenticated'] = true;
        
        $redirect_url = match($role) {
            'admin' => 'admin/dashboard.php',
            'agent' => 'agent/dashboard.php',
            'user' => 'user/dashboard.php',
            default => 'dashboard.php'
        };
        
        header('Location: ' . $redirect_url);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Courier Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js" rel="stylesheet">
    <style>
        :root {
            --background: hsl(250, 100%, 98%);
            --foreground: hsl(222.2, 84%, 4.9%);
            --card: hsl(0, 0%, 100%);
            --card-foreground: hsl(222.2, 84%, 4.9%);
            --primary: hsl(210, 100%, 38%);
            --primary-foreground: hsl(0, 0%, 100%);
            --primary-dark: hsl(210, 100%, 28%);
            --secondary: hsl(210, 40%, 96.1%);
            --secondary-foreground: hsl(222.2, 47.4%, 11.2%);
            --muted: hsl(220, 13%, 91%);
            --muted-foreground: hsl(215.4, 16.3%, 46.9%);
            --accent: hsl(210, 40%, 96.1%);
            --accent-foreground: hsl(222.2, 47.4%, 11.2%);
            --destructive: hsl(0, 84.2%, 60.2%);
            --destructive-foreground: hsl(210, 40%, 98%);
            --border: hsl(214.3, 31.8%, 91.4%);
            --input: hsl(214.3, 31.8%, 91.4%);
            --ring: hsl(210, 100%, 38%);
            --radius: 0.75rem;
        }

        body {
            background-color: var(--background);
            color: var(--foreground);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .card {
            background-color: var(--card);
            color: var(--card-foreground);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .card-shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--primary-foreground);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--foreground);
            border: 1px solid var(--border);
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .btn-outline:hover {
            background-color: var(--accent);
            color: var(--accent-foreground);
        }

        .btn-ghost {
            background-color: transparent;
            color: var(--foreground);
            border: none;
            padding: 0.25rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-ghost:hover {
            background-color: var(--accent);
            color: var(--accent-foreground);
        }

        .input {
            flex: 1;
            height: 2.5rem;
            width: 100%;
            border-radius: 0.375rem;
            border: 1px solid var(--input);
            background-color: var(--background);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .input:focus {
            outline: none;
            ring: 2px;
            ring-color: var(--ring);
            ring-offset: 2px;
        }

        .input::placeholder {
            color: var(--muted-foreground);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-muted-foreground {
            color: var(--muted-foreground);
        }

        .text-destructive {
            color: var(--destructive);
        }

        .bg-orange-50 {
            background-color: #fef7ed;
        }

        .text-orange-500 {
            color: #f97316;
        }

        .link {
            color: var(--primary);
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .icon {
            width: 1rem;
            height: 1rem;
            display: inline-block;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .icon-left {
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-foreground);
        }

        .icon-right {
            right: 0.25rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .pl-10 {
            padding-left: 2.5rem;
        }

        .pr-10 {
            padding-right: 2.5rem;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-muted-foreground mb-2">
                Courier Management System
            </h1>
        </div>

        <!-- Error/Success Messages -->
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle" class="icon"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
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
                <form method="POST" class="space-y-4" id="loginForm">
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="icon icon-left"></i>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                class="input pl-10"
                                required
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            />
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="icon icon-left"></i>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                class="input pl-10 pr-10"
                                required
                            />
                            <button
                                type="button"
                                class="btn-ghost absolute icon-right"
                                onclick="togglePassword()"
                                id="passwordToggle"
                            >
                                <i data-lucide="eye" class="icon" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="btn-primary w-full" 
                        id="submitBtn"
                    >
                        <span id="btnText">Sign In</span>
                        <div id="loading" class="loading icon hidden">
                            <i data-lucide="loader" class="icon"></i>
                        </div>
                    </button>
                </form>

                <div class="flex items-center justify-between text-sm">
                    <button
                        type="button"
                        onclick="showForgotPassword()"
                        class="link"
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
                    Click any button below to auto-fill login credentials
                </p>
            </div>
            <div class="p-6 pt-0 space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <a href="?demo=admin" class="btn-outline">
                        <span class="font-medium">Admin</span>
                        <span class="text-xs text-muted-foreground">Full system access</span>
                    </a>
                    
                    <a href="?demo=agent" class="btn-outline">
                        <span class="font-medium">Agent</span>
                        <span class="text-xs text-muted-foreground">Courier management</span>
                    </a>
                    
                    <a href="?demo=user" class="btn-outline">
                        <span class="font-medium">User</span>
                        <span class="text-xs text-muted-foreground">Track packages</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-muted-foreground bg-orange-50 p-3 rounded-lg">
                    <i data-lucide="alert-circle" class="icon text-orange-500"></i>
                    <span>Password for all demo accounts is <strong>password</strong></span>
                </div>
            </div>
        </div>

        <!-- Support Contact Link -->
        <div class="card text-center">
            <div class="p-6">
                <div class="flex items-center justify-center gap-2 text-muted-foreground">
                    <i data-lucide="help-circle" class="icon"></i>
                    <span class="text-sm">Need help?</span>
                    <a href="support.php" class="link text-sm">Contact Support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="card max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <button onclick="hideForgotPassword()" class="btn-ghost">
                        <i data-lucide="arrow-left" class="icon"></i>
                    </button>
                    <h3 class="text-lg font-semibold">Forgot Password</h3>
                </div>
                
                <form onsubmit="handleForgotPassword(event)" class="space-y-4">
                    <div class="space-y-2">
                        <label for="reset-email" class="text-sm font-medium">Email Address</label>
                        <div class="relative">
                            <i data-lucide="mail" class="icon icon-left"></i>
                            <input
                                id="reset-email"
                                type="email"
                                placeholder="Enter your email address"
                                class="input pl-10"
                                required
                            />
                        </div>
                        <p class="text-sm text-muted-foreground">
                            We'll send you a link to reset your password.
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
                        >
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

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
        }

        function hideForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }

        function handleForgotPassword(event) {
            event.preventDefault();
            const email = document.getElementById('reset-email').value;
            
            // Simulate API call
            setTimeout(() => {
                alert(`Password reset link sent to ${email}`);
                hideForgotPassword();
            }, 1000);
        }

        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loading = document.getElementById('loading');
            
            btnText.textContent = 'Signing In...';
            loading.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>