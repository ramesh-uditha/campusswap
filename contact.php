<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Contact Support & Campus Help";
$errors = [];
$success_msg = "";

$prefill_subject = sanitize_input($_GET['subject'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize_input($_POST['name'] ?? '');
    $email   = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    // Server-side validation
    if (empty($name)) {
        $errors[] = "Please provide your full student name.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid university email address.";
    }

    if (empty($subject)) {
        $errors[] = "Please specify a subject for your inquiry.";
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = "Message inquiry must be at least 10 characters long.";
    }

    // Save to database
    if (empty($errors)) {
        if (isset($pdo) && $pdo !== null) {
            try {
                $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
                $stmt->execute([
                    'name'    => $name,
                    'email'   => $email,
                    'subject' => $subject,
                    'message' => $message
                ]);
                $success_msg = "Thank you, $name! Your message has been sent successfully. We will reply to your university email.";
                $prefill_subject = '';
            } catch (PDOException $e) {
                $errors[] = "Database communication error: " . $e->getMessage();
            }
        } else {
            $success_msg = "Thank you, $name! Your message was submitted successfully (Demo Mode).";
            $prefill_subject = '';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center mb-5 text-center">
        <div class="col-lg-7">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2 font-monospace">CAMPUS HELPDESK</span>
            <h1 class="fw-bold">Contact & Support</h1>
            <p class="text-muted">Have questions regarding a swap, reported listing, or platform issue? Send us a message or review student FAQs below.</p>
        </div>
    </div>

    <div class="row g-5">
        <!-- Inquiry Form Column -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h4 class="fw-bold mb-3">Send Inquiry to Student Support</h4>
                
                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success_msg); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="needs-validation" novalidate id="contactForm">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold small">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g. Kasun Perera" required 
                                   value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ($_POST['name'] ?? '')); ?>">
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">University Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="student@rusl.ac.lk" required 
                                   value="<?php echo htmlspecialchars($_SESSION['email'] ?? ($_POST['email'] ?? '')); ?>">
                            <div class="invalid-feedback">Valid email required.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label fw-semibold small">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               placeholder="e.g. Question about Casio Calculator Listing" required
                               value="<?php echo htmlspecialchars($prefill_subject ?: ($_POST['subject'] ?? '')); ?>">
                        <div class="invalid-feedback">Subject is required.</div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="message" class="form-label fw-semibold small mb-0">Message / Inquiry</label>
                            <small class="text-muted" id="charCount">0 / 500 characters</small>
                        </div>
                        <textarea class="form-control" id="message" name="message" rows="5" 
                                  placeholder="Write your message here (minimum 10 characters)..." 
                                  required minlength="10" maxlength="500"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        <div class="invalid-feedback">Please provide at least 10 characters.</div>
                    </div>

                    <button type="submit" class="btn btn-emerald w-100 py-3 fw-bold">
                        <i class="bi bi-send-fill me-2"></i> Submit Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Student FAQ & Safety Guidelines Column -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i>Campus Safety Tips</h5>
                <ul class="list-unstyled small text-secondary d-flex flex-column gap-3 mb-0">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle text-success fs-5"></i>
                        <span><strong>Public Handovers:</strong> Always schedule item inspections in public campus areas (Faculty canteen, main library lobby, or IT building).</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle text-success fs-5"></i>
                        <span><strong>Inspect Before Buying:</strong> Test electronic items and check textbook conditions in person before finalizing payments.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle text-success fs-5"></i>
                        <span><strong>Cash or Direct Bank Transfer:</strong> Settle payment directly with your fellow undergraduate upon receiving the item.</span>
                    </li>
                </ul>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3"><i class="bi bi-question-circle text-success me-2"></i>Frequently Asked Questions</h5>
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
                                Is it completely free to list items?
                            </button>
                        </h2>
                        <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small">
                                Yes! CampusSwap is an academic, non-commercial initiative built for Rajarata students. There are zero listing fees or commissions.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                                How can I edit or delete my listing?
                            </button>
                        </h2>
                        <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small">
                                Simply navigate to your <strong>Student Dashboard</strong> after logging in. You can remove items once they have been swapped or sold.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed px-0 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                                Can I swap items without paying cash?
                            </button>
                        </h2>
                        <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small">
                                Absolutely. Set the price to $0.00 and mention in the description what item or course book you are looking to trade in return.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
