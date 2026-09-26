<?php
/**
 * LOGIN / SESSION HELPERS
 * ---------------------------------------------------------------
 * When someone logs in we store their user id in $_SESSION. PHP keeps
 * $_SESSION between page loads (using a cookie), so every page can ask
 * "who is this?" with current_user_id().
 *
 * Put  require_login();  at the top of any page that needs an account.
 */

function current_user_id() {
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function current_user() {
    static $user = null;
    if ($user === null && current_user_id()) {
        $user = db_one('SELECT id, username, display_name, media_preference FROM users WHERE id = ?', [current_user_id()]);
    }
    return $user;
}

function require_login() {
    if (!current_user_id()) {
        flash('Please log in first.', 'info');
        redirect('login.php');
    }
}

function login_user($userId) {
    session_regenerate_id(true);          // security: new session id after login
    $_SESSION['user_id'] = (int) $userId;
}

function logout_user() {
    $_SESSION = [];
    session_destroy();
}
