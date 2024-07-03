<?php

/**
 * Sanitize input data to ensure it is safe for use in different contexts.
 * 
 * @param string $data The input data to be sanitized.
 * @return string The sanitized input data.
 */
function sanitizeInput($data) {
    // Remove HTML and PHP tags
    $data = strip_tags($data);
    
    // Convert special characters to HTML entities
    // ENT_QUOTES to handle both double and single quotes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    // Trim whitespace from both ends
    $data = trim($data);

    // Optionally, you can also limit the length of the input
    // $data = substr($data, 0, 255); // Limit to 255 characters (example)

    return $data;
}

?>