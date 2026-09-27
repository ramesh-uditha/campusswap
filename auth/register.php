<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already authenticated
if (is_logged_in()) {
    header("Location: ../dashboard.php");
    exit();
}

$page_title = "Student Registration";
$errors = [];
$username = '';
$email = '';
$full_name = '';
$faculty = 'Faculty of Technology';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $faculty   = sanitize_input($_POST['faculty'] ?? '');
    $username  = sanitize_input($_POST['username'] ?? '');
    $email     = sanitize_input($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($full_name)) {
        $errors[] = "Please provide your full student name.";
    }

    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid university email address.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters in length.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Password confirmation does not match.";
    }

    if (empty($errors)) {
        if (isset($pdo) && $pdo !== null) {
            try {
                // Check if username or email already taken
                $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1");
                $check_stmt->execute(['username' => $username, 'email' => $email]);
                
                if ($check_stmt->fetch()) {
                    $errors[] = "A student profile with this username or email already exists.";
                } else {
                    // Hash password securely as required by ICT 2209 specs
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, faculty) VALUES (:username, :email, :password, :full_name, :faculty)");
                    $insert_stmt->execute([
                        'username'  => $username,
                        'email'     => $email,
                        'password'  => $hashed_password,
                        'full_name' => $full_name,
                        'faculty'   => $faculty
                    ]);

                    $new_id = $pdo->lastInsertId();

                    // Automatic session startup after registration
                    $_SESSION['user_id'] = $new_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $full_name;

                    set_flash('success', "Welcome to CampusSwap! Your student account is active.");
                    header("Location: ../dashboard.php");
                    exit();
                }
            } catch (PDOException $e) {
                $errors[] = "Registration database error: " . $e->getMessage();
            }
        } else {
            // Offline demo fallback
            $_SESSION['user_id'] = 102;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['full_name'] = $full_name;

            header("Location: ../dashboard.php");
            exit();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-9">
            <div class="auth-box animate__animated animate__fadeInUp">
                <div class="text-center mb-4">
                    <div class="brand-icon-box mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.6rem;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Create Student Profile</h3>
                    <p class="text-muted small">Join CampusSwap to list books, gear, and connect with peers</p>
                </div>

                <!-- Display Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="register.php" method="POST" class="needs-validation" novalidate id="registerForm">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold small">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   placeholder="e.g. Kasun Perera" required value="<?php echo htmlspecialchars($full_name); ?>">
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold small">Student Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="e.g. kasun_ict" required value="<?php echo htmlspecialchars($username); ?>">
                            <div class="invalid-feedback">Choose a unique username.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">University Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" 
                                   placeholder="student@rusl.ac.lk" required value="<?php echo htmlspecialchars($email); ?>">
                            <div class="invalid-feedback">Valid university email address required.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="faculty" class="form-label fw-semibold small">Faculty / Department</label>
                        <select class="form-select" id="faculty" name="faculty">
                            <option value="Faculty of Technology" <?php echo ($faculty == 'Faculty of Technology') ? 'selected' : ''; ?>>Faculty of Technology (Technology)</option>
                            <option value="Faculty of Applied Sciences" <?php echo ($faculty == 'Faculty of Applied Sciences') ? 'selected' : ''; ?>>Faculty of Applied Sciences</option>
                            <option value="Faculty of Management Studies" <?php echo ($faculty == 'Faculty of Management Studies') ? 'selected' : ''; ?>>Faculty of Management Studies</option>
                            <option value="Faculty of Agriculture" <?php echo ($faculty == 'Faculty of Agriculture') ? 'selected' : ''; ?>>Faculty of Agriculture</option>
                            <option value="Faculty of Medicine" <?php echo ($faculty == 'Faculty of Medicine') ? 'selected' : ''; ?>>Faculty of Medicine</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold small">Password (Min 6 chars)</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0" id="password" name="password" 
                                       placeholder="Create password" required minlength="6">
                                <button class="btn btn-outline-secondary border-start-0 toggle-password-btn" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Minimum 6 characters.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label fw-semibold small">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0" id="confirm_password" name="confirm_password" 
                                       placeholder="Repeat password" required>
                                <button class="btn btn-outline-secondary border-start-0 toggle-password-btn" type="button" data-target="confirm_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Passwords must match.</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-emerald w-100 py-2 fs-6 fw-bold">
                        <i class="bi bi-check2-circle me-2"></i> Register Account
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">
                        Already have an account? 
                        <a href="login.php" class="text-success fw-bold text-decoration-none ms-1">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
