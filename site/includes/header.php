<?php
/**
 * PAGE HEADER + NAVIGATION  (included at the top of every page)
 * ---------------------------------------------------------------
 * Before including this file, a page sets:
 *     $pageTitle = 'Library';     // shown in the browser tab
 *     $activeNav = 'library';     // which nav link is highlighted
 */
$pageTitle = isset($pageTitle) ? $pageTitle : 'JoyWatch';
$activeNav = isset($activeNav) ? $activeNav : '';
$user      = current_user();

// Main app sections: key => [link, emoji, label]
$mainNav = [
    'tonight'  => ['tonight.php',  '🌙', 'Tonight'],
    'discover' => ['discover.php', '✨', 'Discover'],
    'library'  => ['library.php',  '📚', 'Library'],
    'taste'    => ['taste.php',    '💜', 'Taste'],
    'search'   => ['search.php',   '🔍', 'Add titles'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?> · JoyWatch</title>
    <link rel="icon" href="images/logo.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/app.js" defer></script>
</head>
<body class="<?= $user ? 'logged-in' : 'logged-out' ?>">
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="brand">
            <img src="images/logo.svg" alt="" width="32" height="32">
            <span>JoyWatch</span>
        </a>

        <?php if ($user): ?>
            <nav class="main-nav" aria-label="Main">
                <?php foreach ($mainNav as $key => $item): ?>
                    <a href="<?= $item[0] ?>" class="<?= $key === $activeNav ? 'active' : '' ?>"
                       <?= $key === $activeNav ? 'aria-current="page"' : '' ?>>
                        <span class="nav-icon" aria-hidden="true"><?= $item[1] ?></span>
                        <span class="nav-label"><?= $item[2] ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <nav class="util-nav" aria-label="Account">
            <a href="about.php" class="<?= $activeNav === 'about' ? 'active' : '' ?>">About</a>
            <?php if ($user): ?>
                <a href="settings.php" class="<?= $activeNav === 'settings' ? 'active' : '' ?>">Settings</a>
                <a href="logout.php">Log out</a>
            <?php else: ?>
                <a href="login.php" class="<?= $activeNav === 'login' ? 'active' : '' ?>">Log in</a>
                <a href="register.php" class="btn btn-small">Sign up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main id="main" class="container">
<?php show_flash(); ?>
