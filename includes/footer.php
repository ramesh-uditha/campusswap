</main>

<!-- Footer Section -->
<footer class="footer-swap mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-5 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="brand-icon-box" style="width: 34px; height: 34px; font-size: 1.1rem;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <span class="fs-4 fw-bold text-white">Campus<span class="text-success">Swap</span></span>
                </div>
                <p class="small text-secondary mb-3 pe-lg-4">
                    The student-driven peer-to-peer thrift and exchange platform. Reduce campus waste, save money on semester gear, and support fellow undergraduates through sustainable exchange.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1">
                        <i class="bi bi-shield-check me-1"></i> Verified Student Community
                    </span>
                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 px-2 py-1">
                        <i class="bi bi-tree me-1"></i> Zero Waste Initiative
                    </span>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3">Platform Navigation</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="<?php echo $base_path; ?>index.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Home</a></li>
                    <li><a href="<?php echo $base_path; ?>marketplace.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Browse Items</a></li>
                    <li><a href="<?php echo $base_path; ?>contact.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Help & Inquiries</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base_path; ?>dashboard.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Student Dashboard</a></li>
                    <?php else: ?>
                    <li><a href="<?php echo $base_path; ?>auth/login.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Member Sign In</a></li>
                    <li><a href="<?php echo $base_path; ?>auth/register.php" class="footer-link"><i class="bi bi-chevron-right small me-1"></i> Create Account</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-white fw-bold mb-3">Academic Project Details</h6>
                <ul class="list-unstyled small text-secondary mb-0 d-flex flex-column gap-2">
                    <li><i class="bi bi-mortarboard me-2 text-success"></i> <strong>Course:</strong> ICT 2209 - Web Technologies</li>
                    <li><i class="bi bi-building me-2 text-success"></i> <strong>Faculty:</strong> Faculty of Technology</li>
                    <li><i class="bi bi-geo-alt me-2 text-success"></i> Rajarata University of Sri Lanka</li>
                    <li><i class="bi bi-code-slash me-2 text-success"></i> PHP, MySQL, Bootstrap 5 & Vanilla JS</li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <div class="row align-items-center small text-secondary">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; <?php echo date('Y'); ?> CampusSwap. Designed for RUSL ICT 2209 Web Technologies.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-muted">Sustainable Student Economy &bull; All Rights Reserved</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Client Interactivity Script -->
<script src="<?php echo $base_path; ?>js/main.js"></script>
</body>
</html>
