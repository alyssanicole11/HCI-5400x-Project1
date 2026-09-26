<?php
/**
 * HOME
 *   Logged out -> welcome page with Sign up / Log in.
 *   Logged in  -> dashboard: counts from the library + shortcuts.
 * STATUS: working (starter code)
 */
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Home';
$activeNav = 'home';

$userId = current_user_id();
if ($userId) {
    // One query that counts several things at once (SUM of true/false = count)
    $stats = db_one(
        "SELECT COUNT(*) AS total,
                COALESCE(SUM(watch_status = 'watched'), 0)  AS watched,
                COALESCE(SUM(watch_status = 'watching'), 0) AS watching,
                COALESCE(SUM(interest = 'yes'), 0)          AS interested,
                COALESCE(SUM(watch_status IN ('watching','watched') AND rating IS NULL), 0) AS unrated
         FROM user_titles WHERE user_id = ?",
        [$userId]
    );
}

require __DIR__ . '/includes/header.php';
?>

<?php if (!$userId): ?>
    <section class="hero">
        <h1>What should we watch tonight?</h1>
        <p class="lead">JoyWatch remembers what you've watched, what you want to see, and what you
            never want suggested again - then helps you pick something you'll actually enjoy.</p>
        <p>
            <a href="register.php" class="btn btn-primary">Create a free account</a>
            <a href="login.php" class="btn">Log in</a>
        </p>
        <p class="small">Just looking? Log in as <strong>demo</strong> / <strong>joywatch</strong>.</p>
    </section>

    <section class="feature-grid">
        <div class="panel"><h2>📚 Library</h2><p>Track Watching, Watched, Interested, ratings, and the DVDs on your shelf.</p></div>
        <div class="panel"><h2>💜 Taste Trainer</h2><p>Answer quick questions about titles and JoyWatch learns your taste.</p></div>
        <div class="panel"><h2>✨ Discover</h2><p>One new suggestion at a time, with a plain-English reason why.</p></div>
        <div class="panel"><h2>🌙 Tonight</h2><p>Filter by mood and time and get a short list - or a random pick.</p></div>
    </section>

<?php else: ?>
    <h1>Hi, <?= h(current_user()['display_name']) ?> 👋</h1>

    <section class="stat-row" aria-label="Your library at a glance">
        <a class="stat" href="library.php"><strong><?= (int) $stats['total'] ?></strong><span>in Library</span></a>
        <a class="stat" href="library.php?view=watching"><strong><?= (int) $stats['watching'] ?></strong><span>Watching</span></a>
        <a class="stat" href="library.php?view=watched"><strong><?= (int) $stats['watched'] ?></strong><span>Watched</span></a>
        <a class="stat" href="library.php?view=interested"><strong><?= (int) $stats['interested'] ?></strong><span>Interested</span></a>
    </section>

    <?php if ($stats['unrated'] > 0): ?>
        <p class="notice">You have <?= (int) $stats['unrated'] ?> watched or in-progress title(s) without a rating.
            <a href="library.php?view=unrated">Finish my ratings →</a></p>
    <?php endif; ?>

    <?php if ((int) $stats['total'] === 0): ?>
        <div class="empty">
            <h2>Your Library is empty</h2>
            <p>Start by adding a few titles you've seen or want to see.</p>
            <p>
                <a class="btn btn-primary" href="search.php">🔍 Add titles</a>
                <a class="btn" href="trainer.php">💜 Taste Trainer</a>
                <a class="btn" href="discover.php">✨ Discover</a>
            </p>
        </div>
    <?php endif; ?>

    <section class="feature-grid">
        <a class="panel panel-link" href="tonight.php"><h2>🌙 Tonight</h2><p>Pick something to watch now.</p></a>
        <a class="panel panel-link" href="trainer.php"><h2>💜 Taste Trainer</h2><p>Rate a few titles quickly.</p></a>
        <a class="panel panel-link" href="discover.php"><h2>✨ Discover</h2><p>See something new, with reasons.</p></a>
        <a class="panel panel-link" href="taste.php"><h2>📊 My Taste</h2><p>Your stats and favorite genres.</p></a>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
