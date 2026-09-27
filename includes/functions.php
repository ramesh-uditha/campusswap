<?php
/**
 * CampusSwap - Helper Functions Library
 * ICT 2209 Web Technologies Project
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize and clean user input data against XSS
 *
 * @param mixed $data
 * @return string
 */
function sanitize_input($data) {
    if (is_null($data)) return '';
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if a student user is currently logged in
 *
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Enforce authentication on protected pages
 *
 * @param string $redirect_url
 */
function check_auth($redirect_url = 'auth/login.php') {
    if (!is_logged_in()) {
        header("Location: " . $redirect_url);
        exit();
    }
}

/**
 * Format numeric price with currency symbol
 *
 * @param float|string $amount
 * @return string
 */
function format_price($amount) {
    return '$' . number_format((float)$amount, 2);
}

/**
 * Get appropriate Bootstrap badge class according to condition
 *
 * @param string $condition
 * @return string
 */
function get_condition_badge($condition) {
    switch (strtolower(trim($condition))) {
        case 'like new':
        case 'brand new':
            return 'bg-success text-white';
        case 'excellent':
            return 'bg-teal text-white';
        case 'good':
            return 'bg-primary text-white';
        case 'fair':
            return 'bg-warning text-dark';
        default:
            return 'bg-secondary text-white';
    }
}

/**
 * Set flash message in session
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Retrieve and clear flash message
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
