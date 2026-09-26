<?php
/**
 * SIGN UP  (FORM #1 - creates a row in `users`)
 * STATUS: working (starter code)
 *
 * The pattern used by every form page in this project:
 *   1. If the form was submitted (POST): read fields, validate, save, redirect.
 *   2. Otherwise (or if there were errors): show the form.
 */
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Sign up';
$activeNav = 'register';

$errors = [];
$username = '';
$displayName = '';

if (is_post()) {
    csrf_check();
    $username    = strtolower(post_param('username'));
    $displayName = post_param('display_name');
    $password    = post_param('password');
    $confirm     = post_param('confirm');

    // ---- validate ----
    if (!preg_match('/^[a-z0-9_]{3,40}$/', $username)) {
        $errors[] = 'Username must be 3-40 letters, numbers, or underscores.';
    } elseif (db_value('SELECT COUNT(*) FROM users WHERE username = ?', [$username]) > 0) {
        $errors[] = 'That username is taken.';
    }
    if ($displayName === '' || strlen($displayName) > 60) {
        $errors[] = 'Please enter a display name (up to 60 characters).';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // ---- save ----
    if (!$errors) {
        db_run(
            'INSERT INTO users (username, display_name, password_hash) VALUES (?, ?, ?)',
            [$username, $displayName, password_hash($password, PASSWORD_DEFAULT)]
        );
        login_user(db()->lastInsertId());
        flash('Welcome to JoyWatch! Tell us a few genres you love to get started.');
        redirect('taste.php#genres');
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="form-page">
    <h1>Create your account</h1>

    <?php if ($errors): ?>
        <div class="flash flash-error" role="alert">
            <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" class="stack">
        <?= csrf_field() ?>
        <label>Username
            <input type="text" name="username" value="<?= h($username) ?>" required
                   pattern="[a-zA-Z0-9_]{3,40}" autocomplete="username">
        </label>
        <label>Display name
            <input type="text" name="display_name" value="<?= h($displayName) ?>" required maxlength="60">
        </label>
        <label>Password <span class="hint">(8+ characters)</span>
            <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>
        <label>Confirm password
            <input type="password" name="confirm" required minlength="8" autocomplete="new-password">
        </label>
        <button type="submit" class="btn btn-primary">Sign up</button>
    </form>
    <p>Already have an account? <a href="login.php">Log in</a></p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
