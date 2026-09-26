<?php
/**
 * ADD TITLES - SEARCH  (FORM #3 - a GET search form)
 * STATUS: working (starter code)
 *
 * GET vs POST: search forms use method="get" so the search ends up in the
 * URL (search.php?q=toy&type=movie) and can be bookmarked or shared.
 * Forms that CHANGE data (title.php) use method="post".
 *
 * STRETCH (extra credit): when TMDB_TOKEN is set in config.php, also show
 * results from The Movie Database and let the user add them to our catalog.
 * See includes/tmdb.php.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Add titles';
$activeNav = 'search';

$q    = get_param('q');
$type = get_param('type');
$userId = current_user_id();

if ($q !== '') {
    $results = search_titles($userId, $q, $type);
} else {
    // No search yet: show some titles that aren't in the library, to browse
    $results = db_all(
        'SELECT t.* FROM titles t
         WHERE t.id NOT IN (SELECT title_id FROM user_titles WHERE user_id = ?)
         ORDER BY RAND() LIMIT 12',
        [$userId]
    );
}

require __DIR__ . '/includes/header.php';
?>

<h1>Add titles</h1>
<p class="tabs">
    <a href="search.php" class="active" aria-current="page">Search one title</a>
    <a href="add-list.php">Add a list</a>
</p>

<form method="get" class="toolbar" role="search">
    <label class="grow">
        <span class="visually-hidden">Search movies and TV</span>
        <input type="search" name="q" value="<?= h($q) ?>" placeholder="Search movies and TV…" autofocus>
    </label>
    <label>
        <span class="visually-hidden">Type</span>
        <select name="type">
            <option value="">Movies &amp; TV</option>
            <option value="movie" <?= $type === 'movie' ? 'selected' : '' ?>>Movies</option>
            <option value="tv" <?= $type === 'tv' ? 'selected' : '' ?>>TV</option>
        </select>
    </label>
    <button class="btn btn-primary" type="submit">Search</button>
</form>

<?php if ($q !== ''): ?>
    <h2><?= count($results) ?> result<?= count($results) === 1 ? '' : 's' ?> for “<?= h($q) ?>”</h2>
<?php else: ?>
    <h2>Need ideas? Browse a few</h2>
<?php endif; ?>

<?php if (!$results): ?>
    <div class="empty">
        <p>No titles found. Check the spelling or try fewer words.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($results as $t): ?>
            <?php
            $badge = '';
            if (!empty($t['in_library'])) {
                $status = $t['watch_status'] ? WATCH_LABELS[$t['watch_status']]
                        : ($t['interest'] ? INTEREST_LABELS[$t['interest']] : 'Saved');
                $badge = '<span class="badge">In Library · ' . h($status) . '</span>';
            }
            echo title_card($t, $badge);
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
