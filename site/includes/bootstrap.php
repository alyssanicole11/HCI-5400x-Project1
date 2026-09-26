<?php
/**
 * BOOTSTRAP - the first line of EVERY page is:
 *
 *     require __DIR__ . '/includes/bootstrap.php';
 *
 * It loads the config, starts the login session, and makes all of our
 * helper functions available. Think of it as "turn the app on".
 */

if (!file_exists(__DIR__ . '/config.php')) {
    http_response_code(500);
    exit('<h1>Setup needed</h1><p>Copy <code>includes/config.sample.php</code> to '
       . '<code>includes/config.php</code> and fill in the database login.</p>');
}
require __DIR__ . '/config.php';

// Show errors while building; hide them in the final version (see config.php)
ini_set('display_errors', DEBUG ? '1' : '0');
error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');

// Sessions remember who is logged in between page loads
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/titles.php';
require __DIR__ . '/recommend.php';
