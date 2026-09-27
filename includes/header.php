<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$is_auth_dir = (strpos($_SERVER['PHP_SELF'], '/auth/') !== false);
$base_path = $is_auth_dir ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | CampusSwap' : 'CampusSwap - University Student Thrift & Item Exchange'; ?></title>

    <!-- Google Font: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Custom Theme Stylesheet -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body>

<!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-campusswap sticky-top">
    <div class="container">
        <a class="navbar-brand navbar-brand-swap" href="<?php echo $base_path; ?>index.php">
            <div class="brand-icon-box">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <span>Campus<strong class="text-success">Swap</strong></span>
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#swapNavbar" aria-controls="swapNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="swapNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                       href="<?php echo $base_path; ?>index.php">
                       <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($current_page == 'marketplace.php') ? 'active' : ''; ?>" 
                       href="<?php echo $base_path; ?>marketplace.php">
                       <i class="bi bi-collection me-1"></i> Explore Items
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" 
                       href="<?php echo $base_path; ?>dashboard.php">
                       <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" 
                       href="<?php echo $base_path; ?>contact.php">
                       <i class="bi bi-chat-left-dots me-1"></i> Contact & Help
                    </a>
                </li>
            </ul>

            <!-- Right Profile / Auth Nav Controls -->
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-emerald btn-sm rounded-pill px-3 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-6"></i>
                            <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'Student'); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 py-2">
                            <li class="px-3 py-1">
                                <small class="text-muted d-block">Signed in as</small>
                                <strong><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></strong>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo $base_path; ?>dashboard.php">
                                    <i class="bi bi-grid-fill text-success"></i> My Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="<?php echo $base_path; ?>auth/logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Log Out
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>auth/login.php" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </a>
                    <a href="<?php echo $base_path; ?>auth/register.php" class="btn btn-emerald btn-sm px-3 rounded-pill fw-semibold">
                        <i class="bi bi-person-plus me-1"></i> Get Started
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Live Notification Bar if MySQL DB is not yet imported -->
<?php if (isset($pdo) && $pdo === null): ?>
    <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0 border-0 text-center py-2" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Database Notice:</strong> MySQL server is offline. Previewing sample products in demo mode. Please import <code>database.sql</code> into your local MySQL server (XAMPP/WAMP).
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<main>
