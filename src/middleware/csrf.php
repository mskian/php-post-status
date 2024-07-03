<?php

session_start();

/**
 * Generate a CSRF token and store it in the session if not already set.
 * 
 * @return string The CSRF token.
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        // Generate a new CSRF token and store it in the session
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify if the provided CSRF token matches the one stored in the session.
 * 
 * @param string $token The CSRF token to verify.
 * @return bool True if the token is valid and matches, false otherwise.
 */
function verifyCsrfToken($token) {
    // Ensure the CSRF token is set in the session and matches the provided token
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>