<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Protect page
check_auth('auth/login.php');

$user_id   = $_SESSION['user_id'] ?? 1;
$username  = $_SESSION['username'] ?? 'Student';
$email     = $_SESSION['email'] ?? 'student@rusl.ac.lk';
$full_name = $_SESSION['full_name'] ?? $username;

$errors = [];
$success_msg = "";

// Handle item deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['item_id'])) {
    $del_id = intval($_GET['item_id']);
    if (isset($pdo) && $pdo !== null) {
        try {
            $del_stmt = $pdo->prepare("DELETE FROM products WHERE id = :id AND (user_id = :uid OR seller_name = :sname)");
            $del_stmt->execute(['id' => $del_id, 'uid' => $user_id, 'sname' => $username]);
            set_flash('success', "Item removed from marketplace successfully.");
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Error deleting listing: " . $e->getMessage();
        }
    }
}

// Handle new product submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
    $title       = sanitize_input($_POST['title'] ?? '');
    $category    = sanitize_input($_POST['category'] ?? '');
    $price       = floatval($_POST['price'] ?? 0);
    $condition   = sanitize_input($_POST['item_condition'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = "Please provide an item title.";
    }
    if (empty($category)) {
        $errors[] = "Please choose a valid category.";
    }
    if ($price < 0) {
        $errors[] = "Price cannot be negative.";
    }
    if (empty($condition)) {
        $errors[] = "Please specify item condition.";
    }
    if (empty($description) || strlen($description) < 10) {
        $errors[] = "Description must be at least 10 characters.";
    }
    if (empty($image_url)) {
        // Fallback default image placeholder based on category
        $image_url = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80';
    }

    if (empty($errors)) {
        if (isset($pdo) && $pdo !== null) {
            try {
                $insert = $pdo->prepare("INSERT INTO products (title, category, price, item_condition, description, image_url, seller_name, user_id, status) VALUES (:title, :cat, :price, :cond, :desc, :img, :seller, :uid, 'Available')");
                $insert->execute([
                    'title'  => $title,
                    'cat'    => $category,
                    'price'  => $price,
                    'cond'   => $condition,
                    'desc'   => $description,
                    'img'    => $image_url,
                    'seller' => $username,
                    'uid'    => $user_id
                ]);
                $success_msg = "Your item has been successfully published to CampusSwap!";
            } catch (PDOException $e) {
                $errors[] = "Database insertion error: " . $e->getMessage();
            }
        } else {
            $success_msg = "Item saved successfully in preview mode!";
        }
    }
}

// Fetch user's listings
$my_items = [];
if (isset($pdo) && $pdo !== null) {
    try {
        $item_stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = :uid OR seller_name = :sname ORDER BY id DESC");
        $item_stmt->execute(['uid' => $user_id, 'sname' => $username]);
        $my_items = $item_stmt->fetchAll();
    } catch (PDOException $e) {
        $my_items = [];
    }
}

// Fallback demo user items if none found
if (empty($my_items) && !isset($pdo)) {
    $my_items = [
        [
            'id' => 1,
            'title' => 'Casio FX-991EX Scientific ClassWiz Calculator',
            'category' => 'Electronics',
            'price' => '25.00',
            'item_condition' => 'Like New',
            'status' => 'Available',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
}

$page_title = "Student Dashboard";
require_once __DIR__ . '/includes/header.php';
$flash = get_flash();
?>

<div class="container py-4">
    <!-- Notification alerts -->
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo ($flash['type'] === 'success') ? 'success' : 'info'; ?> alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- User Profile Header -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center gap-3">
                <div class="brand-icon-box" style="width: 60px; height: 60px; font-size: 2rem;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($full_name); ?></h3>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-person me-1"></i> @<?php echo htmlspecialchars($username); ?> &bull; 
                        <i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($email); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-emerald" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-lg me-1"></i> List New Item
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Metrics Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="dashboard-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">My Active Listings</div>
                    <div class="fs-3 fw-bold text-success mt-1"><?php echo count($my_items); ?></div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="dashboard-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Campus Swaps</div>
                    <div class="fs-3 fw-bold text-primary mt-1">4</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="dashboard-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Buyer Inquiries</div>
                    <div class="fs-3 fw-bold text-info mt-1">7</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-chat-quote"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="dashboard-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Community Trust</div>
                    <div class="fs-3 fw-bold text-warning mt-1">100%</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings Management Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="bi bi-view-list me-2 text-success"></i>My Posted Items</h5>
            <span class="badge bg-light text-secondary border"><?php echo count($my_items); ?> items total</span>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($my_items)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Item Details</th>
                                <th>Category</th>
                                <th>Condition</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_items as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($item['title']); ?></div>
                                        <small class="text-muted">Listed: <?php echo date('M d, Y', strtotime($item['created_at'] ?? 'now')); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            <?php echo htmlspecialchars($item['category']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo get_condition_badge($item['item_condition'] ?? 'Good'); ?>">
                                            <?php echo htmlspecialchars($item['item_condition'] ?? 'Good'); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        <?php echo format_price($item['price']); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <?php echo htmlspecialchars($item['status'] ?? 'Available'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="dashboard.php?action=delete&item_id=<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to remove this item listing?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center p-5">
                    <i class="bi bi-inbox text-muted fs-1 mb-2 d-block"></i>
                    <h5 class="fw-bold">No active listings yet</h5>
                    <p class="text-muted small">You haven't listed any items for swap or sale yet.</p>
                    <button type="button" class="btn btn-emerald btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-lg me-1"></i> Post Your First Item
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal for Adding New Item -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addItemModalLabel">
                    <i class="bi bi-plus-circle text-success me-2"></i>Post New Item for Swap or Sale
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="dashboard.php" method="POST" class="needs-validation" novalidate id="addItemForm">
                <input type="hidden" name="action" value="add_item">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="title" class="form-label fw-semibold small">Item Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="e.g. Casio fx-991EX Calculator / Physics Textbook" required>
                            <div class="invalid-feedback">Please enter a descriptive item title.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold small">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Select Category</option>
                                <option value="Books">Books & Course Notes</option>
                                <option value="Electronics">Electronics & Hardware</option>
                                <option value="Stationery">Stationery & Lab Items</option>
                                <option value="Dorm Gear">Dorm & Hostel Gear</option>
                                <option value="Appliances">Hostel Appliances</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Furniture">Furniture</option>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold small">Asking Price (USD or 0 for Free Swap)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.50" min="0" class="form-control" id="price" name="price" placeholder="25.00" required>
                                <div class="invalid-feedback">Enter a valid price.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="item_condition" class="form-label fw-semibold small">Condition</label>
                            <select class="form-select" id="item_condition" name="item_condition" required>
                                <option value="Brand New">Brand New (Unopened)</option>
                                <option value="Like New" selected>Like New (Used few times)</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good (Working, minor marks)</option>
                                <option value="Fair">Fair (Noticeable wear)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="image_url" class="form-label fw-semibold small">Photo URL (Web Link)</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" 
                                   placeholder="https://images.unsplash.com/photo-..." 
                                   value="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80">
                            <small class="text-muted" style="font-size: 0.75rem;">Paste an image web URL or use default placeholder</small>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label fw-semibold small">Detailed Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Describe the condition, semester module, reason for swapping, etc." required minlength="10"></textarea>
                            <div class="invalid-feedback">Description must be at least 10 characters long.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-emerald px-4 fw-bold">Publish Listing</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
