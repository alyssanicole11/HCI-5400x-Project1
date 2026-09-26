<?php
/**
 * LOG IN  (FORM #2 - checks username + password against `users`)
 * STATUS: working (starter code)
 */
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Log in';
$activeNav = 'login';

$error = '';
$username = '';

if (is_post()) {
    csrf_check();
    $username = strtolower(post_param('username'));
    $user = db_one('SELECT id, password_hash FROM users WHERE username = ?', [$username]);

    // password_verify() compares the typed password with the stored hash
    if ($user && password_verify(post_param('password'), $user['password_hash'])) {
        login_user($user['id']);
        redirect('index.php');
    }
    $error = 'Username or password is incorrect.';   // don't say which one (security)
}

require __DIR__ . '/includes/header.php';
?>

<div class="form-page">
    <h1>Log in</h1>
    <?php if ($error): ?><div class="flash flash-error" role="alert"><?= h($error) ?></div><?php endif; ?>

    <form method="post" class="stack">
        <?= csrf_field() ?>
        <label>Username
            <input type="text" name="username" value="<?= h($username) ?>" required autocomplete="username">
        </label>
        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit" class="btn btn-primary">Log in</button>
    </form>
    <p>New here? <a href="register.php">Create an account</a></p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
