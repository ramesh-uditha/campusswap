<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Home - Campus Thrift & Item Exchange";

// Fetch featured products from database
$featured_products = [];
if (isset($pdo) && $pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM products WHERE status = 'Available' ORDER BY id DESC LIMIT 6");
        $featured_products = $stmt->fetchAll();
    } catch (PDOException $e) {
        $featured_products = [];
    }
}

// Fallback seed items if DB is offline or empty
if (empty($featured_products)) {
    $featured_products = [
        [
            'id' => 1,
            'title' => 'Casio FX-991EX Scientific ClassWiz Calculator',
            'category' => 'Electronics',
            'price' => '25.00',
            'item_condition' => 'Like New',
            'description' => 'Original natural textbook display calculator. Approved for university engineering and math exams.',
            'image_url' => 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Kavindu P.'
        ],
        [
            'id' => 2,
            'title' => 'Data Structures and Algorithm Analysis in C++',
            'category' => 'Books',
            'price' => '28.00',
            'item_condition' => 'Good',
            'description' => 'Comprehensive reference book for 2nd year IT algorithms course. Clean pages with no markings.',
            'image_url' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777a?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Dilshan R.'
        ],
        [
            'id' => 3,
            'title' => 'A2 Engineering Drawing Board & T-Square Ruler',
            'category' => 'Stationery',
            'price' => '38.00',
            'item_condition' => 'Excellent',
            'description' => 'Standard drafting board for technical drawing and engineering graphics. Comes with parallel motion ruler.',
            'image_url' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Nethmi F.'
        ],
        [
            'id' => 4,
            'title' => 'Adjustable LED Dorm Desk Lamp (USB Rechargeable)',
            'category' => 'Dorm Gear',
            'price' => '18.00',
            'item_condition' => 'Like New',
            'description' => 'Touch-control 3-level brightness study lamp with flexible neck. Saves hostel electricity.',
            'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Sahan D.'
        ],
        [
            'id' => 5,
            'title' => 'Stainless Steel Cordless Electric Kettle 1.8L',
            'category' => 'Appliances',
            'price' => '16.00',
            'item_condition' => 'Good',
            'description' => 'Rapid boil electric kettle with automatic shut-off and boil-dry protection. Great for quick hostel tea.',
            'image_url' => 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Amaya K.'
        ],
        [
            'id' => 6,
            'title' => 'Ergonomic Laptop Stand & RGB Mechanical Keyboard',
            'category' => 'Electronics',
            'price' => '42.00',
            'item_condition' => 'Like New',
            'description' => 'Aluminium foldable laptop cooling stand plus compact mechanical keyboard for coding labs.',
            'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Kavindu P.'
        ]
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Banner Section -->
<section class="hero-swap-section">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 animate__animated animate__fadeInLeft">
                <div class="hero-highlight-badge mb-3">
                    <i class="bi bi-recycle"></i> Rajarata Student Circular Economy
                </div>
                <h1 class="display-5 fw-bold mb-3">
                    Smart, Sustainable Campus Item Exchange.
                </h1>
                <p class="lead text-light opacity-90 mb-4 pe-lg-4">
                    Why buy brand new textbooks, electronics, and hostel equipment at full price? Connect with campus seniors and peers to swap, buy, or pass forward semester gear.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="marketplace.php" class="btn btn-emerald btn-lg px-4 py-3">
                        <i class="bi bi-search me-2"></i> Explore Marketplace
                    </a>
                    <?php if (is_logged_in()): ?>
                        <a href="dashboard.php" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="bi bi-plus-circle me-2"></i> Post an Item
                        </a>
                    <?php else: ?>
                        <a href="auth/register.php" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="bi bi-person-plus me-2"></i> Join Community
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Stats counters -->
                <div class="row g-3">
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="number">100%</div>
                            <div class="label">Student Verified</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="number">500+</div>
                            <div class="label">Items Exchanged</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="number">60%</div>
                            <div class="label">Average Savings</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block animate__animated animate__fadeInRight">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white text-dark p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Quick Campus Deals
                        </span>
                        <small class="text-muted"><i class="bi bi-geo-alt"></i> Faculty Campus</small>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center gap-3 px-0 py-2 border-0">
                            <div class="rounded-circle bg-light p-2 text-success">
                                <i class="bi bi-book fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Academic Textbooks</h6>
                                <small class="text-muted">Save up to 70% on curriculum modules</small>
                            </div>
                            <span class="badge bg-light text-dark fw-bold">$20 - $35</span>
                        </div>

                        <div class="list-group-item d-flex align-items-center gap-3 px-0 py-2 border-0">
                            <div class="rounded-circle bg-light p-2 text-success">
                                <i class="bi bi-laptop fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Lab & Tech Gear</h6>
                                <small class="text-muted">Calculators, keyboards, sensors</small>
                            </div>
                            <span class="badge bg-light text-dark fw-bold">Fair Rates</span>
                        </div>

                        <div class="list-group-item d-flex align-items-center gap-3 px-0 py-2 border-0">
                            <div class="rounded-circle bg-light p-2 text-success">
                                <i class="bi bi-cup-hot fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Hostel Living Essentials</h6>
                                <small class="text-muted">Kettles, lamps, study tables</small>
                            </div>
                            <span class="badge bg-light text-dark fw-bold">Affordable</span>
                        </div>
                    </div>

                    <a href="marketplace.php" class="btn btn-emerald w-100 mt-3 py-2 fw-semibold">
                        View All Categories <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Highlights -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2 font-monospace">POPULAR GEAR</span>
            <h2 class="fw-bold">Browse By Department & Category</h2>
            <p class="text-muted">Find exactly what you need for this semester without breaking your budget</p>
        </div>

        <div class="row g-3 justify-content-center text-center">
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Books" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-journal-bookmark-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Course Books</h6>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Electronics" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-cpu-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Electronics</h6>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Stationery" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-pen-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Stationery</h6>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Dorm Gear" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-lamp-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Dorm Gear</h6>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Appliances" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-plug-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Appliances</h6>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="marketplace.php?cat=Accessories" class="card h-100 p-3 text-decoration-none border rounded-3 hover-shadow transition">
                    <i class="bi bi-backpack-fill text-success fs-2 mb-2"></i>
                    <h6 class="fw-bold text-dark mb-0">Accessories</h6>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Recent Listings -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2 font-monospace">RECENT ADDITIONS</span>
                <h2 class="fw-bold mb-1">Featured Campus Listings</h2>
                <p class="text-muted mb-0">Quality checked student items ready for on-campus handover</p>
            </div>
            <a href="marketplace.php" class="btn btn-outline-emerald d-none d-md-inline-block">
                View All Items <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($featured_products as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="swap-card">
                        <div class="swap-card-img-wrap">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                            <span class="swap-condition-badge <?php echo get_condition_badge($item['item_condition'] ?? 'Good'); ?>">
                                <?php echo htmlspecialchars($item['item_condition'] ?? 'Good'); ?>
                            </span>
                            <div class="swap-price-pill">
                                <?php echo format_price($item['price']); ?>
                            </div>
                        </div>
                        <div class="swap-card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-secondary border">
                                    <i class="bi bi-tag me-1"></i> <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i> <?php echo htmlspecialchars($item['seller_name']); ?>
                                </small>
                            </div>
                            <h5 class="fw-bold text-truncate mb-2" title="<?php echo htmlspecialchars($item['title']); ?>">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h5>
                            <p class="text-muted small text-truncate-2 mb-3">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="text-success small fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Campus Verified
                                </span>
                                <a href="contact.php?subject=<?php echo urlencode('Inquiry regarding: ' . $item['title']); ?>" class="btn btn-sm btn-outline-emerald">
                                    <i class="bi bi-chat-dots me-1"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="marketplace.php" class="btn btn-outline-emerald w-100">
                View All Items <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Sustainability & Platform Benefits Section -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2 font-monospace">WHY CHOOSE US</span>
                <h2 class="fw-bold mb-3">Promoting Eco-Friendly & Affordable Student Life</h2>
                <p class="text-muted mb-4">
                    CampusSwap is designed specifically for Rajarata university students to minimize unnecessary expenses and cut down campus plastic & paper waste.
                </p>

                <div class="d-flex gap-3 mb-3">
                    <div class="brand-icon-box flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Zero Intermediary Fees</h6>
                        <p class="text-muted small mb-0">Direct peer-to-peer exchange between students. No commission charges or shipping fees.</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="brand-icon-box flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Trusted Faculty Network</h6>
                        <p class="text-muted small mb-0">Meet fellow students safely on campus at faculty libraries or canteen lounges for handovers.</p>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="brand-icon-box flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="bi bi-tree"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Eco-Friendly Environmental Impact</h6>
                        <p class="text-muted small mb-0">Extend the lifecycle of academic materials and hardware across succeeding batches.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid #a7f3d0;">
                    <div class="text-center mb-4">
                        <i class="bi bi-box-seam text-success" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">Ready to Declutter Your Dorm?</h3>
                        <p class="text-muted small">List your unused books or appliances in under two minutes</p>
                    </div>
                    <div class="d-grid gap-2">
                        <?php if (is_logged_in()): ?>
                            <a href="dashboard.php" class="btn btn-emerald btn-lg py-3 fw-bold">
                                <i class="bi bi-plus-circle me-2"></i> Add Item to Marketplace
                            </a>
                        <?php else: ?>
                            <a href="auth/register.php" class="btn btn-emerald btn-lg py-3 fw-bold">
                                <i class="bi bi-person-plus me-2"></i> Register Free Account
                            </a>
                            <a href="auth/login.php" class="btn btn-outline-secondary py-2">
                                Already registered? Log in
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
