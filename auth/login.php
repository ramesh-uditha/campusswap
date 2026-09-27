<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already authenticated
if (is_logged_in()) {
    header("Location: ../dashboard.php");
    exit();
}

$page_title = "Student Sign In";
$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email)) {
        $errors[] = "Please provide your registered university email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The email address format is invalid.";
    }

    if (empty($password)) {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {
        if (isset($pdo) && $pdo !== null) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Successful login
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];

                    set_flash('success', "Welcome back, " . htmlspecialchars($_SESSION['username']) . "!");
                    header("Location: ../dashboard.php");
                    exit();
                } else {
                    $errors[] = "Invalid credentials. Please verify your email and password.";
                }
            } catch (PDOException $e) {
                $errors[] = "Database communication error: " . $e->getMessage();
            }
        } else {
            // Offline demo fallback login
            if (!empty($email)) {
                $user_handle = explode('@', $email)[0];
                $_SESSION['user_id'] = 101;
                $_SESSION['username'] = $user_handle;
                $_SESSION['email'] = $email;
                $_SESSION['full_name'] = ucfirst($user_handle);

                header("Location: ../dashboard.php");
                exit();
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="auth-box animate__animated animate__fadeInUp">
                <div class="text-center mb-4">
                    <div class="brand-icon-box mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.6rem;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Student Portal Sign In</h3>
                    <p class="text-muted small">Access your campus swap listings and direct messages</p>
                </div>

                <!-- Display Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="login.php" method="POST" class="needs-validation" novalidate id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">University Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" 
                                   placeholder="student@rusl.ac.lk" required value="<?php echo htmlspecialchars($email); ?>">
                            <div class="invalid-feedback">Please provide a valid university email.</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-semibold small mb-0">Account Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0" id="password" name="password" 
                                   placeholder="Enter your secret password" required>
                            <button class="btn btn-outline-secondary border-start-0 toggle-password-btn" type="button" data-target="password">
                                <i class="bi bi-eye"></i>
                            </button>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-emerald w-100 py-2 fs-6 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Account
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        New to CampusSwap? 
                        <a href="register.php" class="text-success fw-bold text-decoration-none ms-1">Create an Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
