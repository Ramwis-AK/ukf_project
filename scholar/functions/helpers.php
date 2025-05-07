<?php
/**
 * Scholar - Helper Functions
 */

/**
 * Get the base URL of the site
 *
 * @return string Base URL
 */
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $path;
}

/**
 * Get the current page name
 *
 * @return string Current page name
 */
function get_current_page() {
    $page = basename($_SERVER['PHP_SELF']);
    return $page;
}

/**
 * Check if the current page is active
 *
 * @param string $page Page to check
 * @return string 'active' if current page, empty string otherwise
 */
function is_active($page) {
    $current_page = get_current_page();
    return ($current_page == $page) ? 'active' : '';
}

/**
 * Sanitize input data
 *
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format price with currency symbol
 *
 * @param float $price Price to format
 * @param string $currency Currency symbol
 * @return string Formatted price
 */
function format_price($price, $currency = '$') {
    return $currency . number_format($price, 2);
}

/**
 * Truncate text to a specific length
 *
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $append String to append if truncated
 * @return string Truncated text
 */
function truncate_text($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . $append;
    }
    return $text;
}

/**
 * Generate random string
 *
 * @param int $length Length of random string
 * @return string Random string
 */
function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_string;
}

/**
 * Check if a string starts with a specific substring
 *
 * @param string $haystack String to check
 * @param string $needle Substring to search for
 * @return bool True if string starts with substring, false otherwise
 */
function starts_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

/**
 * Check if a string ends with a specific substring
 *
 * @param string $haystack String to check
 * @param string $needle Substring to search for
 * @return bool True if string ends with substring, false otherwise
 */
function ends_with($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

/**
 * Get file extension
 *
 * @param string $filename Filename
 * @return string File extension
 */
function get_file_extension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

/**
 * Format date
 *
 * @param string $date Date string
 * @param string $format Date format
 * @return string Formatted date
 */
function format_date($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}