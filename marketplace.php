<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Explore Items - Campus Thrift & Item Exchange";
require_once __DIR__ . '/includes/header.php';

// Fetch all available products from database
$products = [];
if (isset($pdo) && $pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM products WHERE status = 'Available' ORDER BY id DESC");
        $products = $stmt->fetchAll();
    } catch (PDOException $e) {
        $products = [];
    }
}

// Fallback seed items if DB offline
if (empty($products)) {
    $products = [
        [
            'id' => 1,
            'title' => 'Casio FX-991EX Scientific ClassWiz Calculator',
            'category' => 'Electronics',
            'price' => '25.00',
            'item_condition' => 'Like New',
            'description' => 'Original natural textbook display calculator. Approved for university engineering and math examinations. Includes protective slide-on case.',
            'image_url' => 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Kavindu P.'
        ],
        [
            'id' => 2,
            'title' => 'Data Structures and Algorithm Analysis in C++',
            'category' => 'Books',
            'price' => '28.00',
            'item_condition' => 'Good',
            'description' => 'Comprehensive reference book for 2nd year IT algorithms course. Clean pages, no torn edges, highlighted key sections for quick revision.',
            'image_url' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777a?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Dilshan R.'
        ],
        [
            'id' => 3,
            'title' => 'A2 Engineering Drawing Board & T-Square Ruler',
            'category' => 'Stationery',
            'price' => '38.00',
            'item_condition' => 'Excellent',
            'description' => 'Standard drafting board for technical drawing and engineering graphics. Comes with parallel motion ruler and carrying clips.',
            'image_url' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Nethmi F.'
        ],
        [
            'id' => 4,
            'title' => 'Adjustable LED Dorm Desk Lamp (USB Rechargeable)',
            'category' => 'Dorm Gear',
            'price' => '18.00',
            'item_condition' => 'Like New',
            'description' => 'Touch-control 3-level brightness study lamp with flexible neck. Saves hostel electricity and ideal for night study without glare.',
            'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Sahan D.'
        ],
        [
            'id' => 5,
            'title' => 'Stainless Steel Cordless Electric Kettle 1.8L',
            'category' => 'Appliances',
            'price' => '16.00',
            'item_condition' => 'Good',
            'description' => 'Rapid boil electric kettle with automatic shut-off and boil-dry protection. Great for quick hostel tea, coffee, or noodles.',
            'image_url' => 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Amaya K.'
        ],
        [
            'id' => 6,
            'title' => 'Ergonomic Laptop Stand & RGB Mechanical Keyboard',
            'category' => 'Electronics',
            'price' => '42.00',
            'item_condition' => 'Like New',
            'description' => 'Aluminium foldable laptop cooling stand plus compact mechanical keyboard. Boosts posture and typing speed during programming labs.',
            'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Kavindu P.'
        ],
        [
            'id' => 7,
            'title' => 'Waterproof Heavy-Duty Campus Backpack 30L',
            'category' => 'Accessories',
            'price' => '32.00',
            'item_condition' => 'Good',
            'description' => 'Spacious laptop compartment fits up to 15.6 inch laptops, padded straps, and breathable mesh back. Rain cover included.',
            'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Charith W.'
        ],
        [
            'id' => 8,
            'title' => 'Foldable Wooden Hostel Study Bed Table',
            'category' => 'Furniture',
            'price' => '20.00',
            'item_condition' => 'Fair',
            'description' => 'Multi-purpose bed tray desk with cup holder and tablet slot. Folds flat for compact hostel room storage.',
            'image_url' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=600&q=80',
            'seller_name' => 'Nethmi F.'
        ]
    ];
}

$selected_cat = $_GET['cat'] ?? 'all';
?>

<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2 font-monospace">CAMPUS CATALOGUE</span>
            <h1 class="fw-bold mb-1">Explore Marketplace Items</h1>
            <p class="text-muted mb-0">Discover verified listings from faculty students across all departments</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php" class="btn btn-emerald">
                    <i class="bi bi-plus-lg me-1"></i> Post New Item
                </a>
            <?php else: ?>
                <a href="auth/login.php" class="btn btn-outline-emerald">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to List Item
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter & Live Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <!-- Search input -->
            <div class="col-lg-5 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="marketplaceSearch" class="form-control border-start-0" 
                           placeholder="Search by keywords, title, or category...">
                    <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearchBtn" title="Clear">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Sort dropdown -->
            <div class="col-lg-3 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted small"><i class="bi bi-sort-numeric-down"></i></span>
                    <select id="sortSelect" class="form-select">
                        <option value="default">Sort by: Default</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="title-asc">Title: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Counter badge -->
            <div class="col-lg-4 text-lg-end text-muted small">
                Showing <strong id="itemCountDisplay" class="text-success"><?php echo count($products); ?></strong> active listings
            </div>
        </div>

        <!-- Category Pill Buttons -->
        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top" id="categoryFilterContainer">
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'all') ? 'active' : ''; ?>" data-category="all">
                All Items
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Books') ? 'active' : ''; ?>" data-category="Books">
                Books & Notes
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Electronics') ? 'active' : ''; ?>" data-category="Electronics">
                Electronics
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Stationery') ? 'active' : ''; ?>" data-category="Stationery">
                Stationery & Lab
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Dorm Gear') ? 'active' : ''; ?>" data-category="Dorm Gear">
                Dorm Gear
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Appliances') ? 'active' : ''; ?>" data-category="Appliances">
                Hostel Appliances
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Accessories') ? 'active' : ''; ?>" data-category="Accessories">
                Accessories
            </button>
            <button type="button" class="category-filter-btn <?php echo ($selected_cat === 'Furniture') ? 'active' : ''; ?>" data-category="Furniture">
                Furniture
            </button>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-4" id="productsGrid">
        <?php foreach ($products as $item): ?>
            <div class="col-lg-4 col-md-6 product-card-col" 
                 data-category="<?php echo htmlspecialchars($item['category']); ?>" 
                 data-title="<?php echo htmlspecialchars(strtolower($item['title'])); ?>"
                 data-description="<?php echo htmlspecialchars(strtolower($item['description'])); ?>"
                 data-price="<?php echo (float)$item['price']; ?>">
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
                        <p class="text-muted small mb-3" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo htmlspecialchars($item['description']); ?>
                        </p>
                        <div class="mt-auto pt-3 border-top d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1 view-details-btn"
                                    data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                    data-category="<?php echo htmlspecialchars($item['category']); ?>"
                                    data-price="<?php echo format_price($item['price']); ?>"
                                    data-condition="<?php echo htmlspecialchars($item['item_condition'] ?? 'Good'); ?>"
                                    data-desc="<?php echo htmlspecialchars($item['description']); ?>"
                                    data-image="<?php echo htmlspecialchars($item['image_url']); ?>"
                                    data-seller="<?php echo htmlspecialchars($item['seller_name']); ?>">
                                <i class="bi bi-eye me-1"></i> Details
                            </button>
                            <a href="contact.php?subject=<?php echo urlencode('Interested in: ' . $item['title']); ?>" class="btn btn-emerald btn-sm px-3">
                                <i class="bi bi-chat-dots me-1"></i> Inquire
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Items Matching Placeholder -->
    <div id="noResultsAlert" class="card border-0 shadow-sm rounded-4 p-5 text-center mt-4 d-none bg-white">
        <i class="bi bi-search text-muted mb-3" style="font-size: 3rem;"></i>
        <h4 class="fw-bold">No Matching Items Found</h4>
        <p class="text-muted mb-3">Try adjusting your keyword filter or switching categories.</p>
        <button type="button" class="btn btn-emerald mx-auto" id="resetFiltersBtn">Reset All Filters</button>
    </div>
</div>

<!-- Item Details Modal (Interactive JavaScript Modal) -->
<div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="itemDetailsModalLabel">Listing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <img id="modalItemImage" src="" alt="Item" class="img-fluid rounded-3 shadow-sm w-100" style="max-height: 280px; object-fit: cover;">
                    </div>
                    <div class="col-md-6 d-flex flex-direction-column flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span id="modalItemCategory" class="badge bg-light text-secondary border"></span>
                                <span id="modalItemCondition" class="badge bg-success"></span>
                            </div>
                            <h4 id="modalItemTitle" class="fw-bold mb-2"></h4>
                            <div class="fs-3 fw-bold text-success mb-3" id="modalItemPrice"></div>
                            <h6 class="fw-semibold text-muted small mb-1">Description:</h6>
                            <p id="modalItemDesc" class="text-secondary small mb-3"></p>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted mb-1"><i class="bi bi-person-circle me-1"></i> Listed by:</div>
                            <strong id="modalItemSeller" class="text-dark d-block mb-3"></strong>
                            <a id="modalContactLink" href="contact.php" class="btn btn-emerald w-100 py-2">
                                <i class="bi bi-envelope me-1"></i> Message Seller Regarding Item
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
