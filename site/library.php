<?php
/**
 * LIBRARY  (FORM #5 - a GET filter/sort form)
 * STATUS: PARTLY WORKING
 *   ✅ Lists the user's titles
 *   ✅ View tabs (All / Interested / Watching / Watched / Owned / Unrated / Not interested)
 *   ⬜ TODO: search box, Movie/TV filter, Sort dropdown  (Owner: see TEAM_PLAN.md)
 *   ⬜ STRETCH: grid/list toggle, quick status buttons on each card,
 *               select multiple + "Edit selected" (bulk edit form with checkboxes)
 *
 * HOW THE FILTERS WORK (read this before doing the TODOs):
 *   We start with a basic SQL query and a list of $where conditions.
 *   Each filter the user picked ADDS one condition + its value to $params.
 *   At the end we glue the conditions together with " AND ".
 *   The "view" filter below is already done - copy its pattern!
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Library';
$activeNav = 'library';

$userId = current_user_id();

// ---- read the filter form (from the URL) ----
$view = get_param('view', 'all');
$q    = get_param('q');
$type = get_param('type');
$sort = get_param('sort', 'recent');

// Each tab: key => [label, SQL condition]
$views = [
    'all'            => ['All',            "(ut.interest IS NULL OR ut.interest <> 'not_interested')"],
    'interested'     => ['Interested',     "ut.interest IN ('yes','unsure') AND (ut.watch_status IS NULL OR ut.watch_status = 'not_yet')"],
    'watching'       => ['Watching',       "ut.watch_status = 'watching'"],
    'watched'        => ['Watched',        "ut.watch_status = 'watched'"],
    'owned'          => ['Owned',          '(ut.own_dvd = 1 OR ut.own_bluray = 1 OR ut.own_4k = 1 OR ut.own_digital = 1)'],
    'unrated'        => ['Finish my ratings', "ut.watch_status IN ('watching','watched') AND ut.rating IS NULL"],
    'not_interested' => ['Not interested', "ut.interest = 'not_interested'"],
];
if (!isset($views[$view])) {
    $view = 'all';
}

// ---- build the query ----
$where  = ['ut.user_id = ?'];      // ALWAYS limit to the logged-in user
$params = [$userId];

$where[] = $views[$view][1];       // ✅ the view tab (done)

// TODO 1 (search box): if $q is not empty, add  "t.title LIKE ?"  to $where
//         and add  '%' . $q . '%'  to $params.
// TODO 2 (type): if $type is 'movie' or 'tv', add  "t.media_type = ?"  and $type.

// TODO 3 (sort): turn $sort into an ORDER BY. NEVER put $sort itself in the SQL -
//         look it up in a list of allowed options instead, like this:
//   $sorts = ['recent' => 'ut.date_added DESC', 'title' => 't.title ASC',
//             'year' => 't.year DESC', 'rating' => 'ut.rating DESC'];
$orderBy = 'ut.date_added DESC';

$titles = db_all(
    'SELECT t.*, ut.watch_status, ut.interest, ut.rating, ut.rewatch,
            ut.own_dvd, ut.own_bluray, ut.own_4k, ut.own_digital
     FROM user_titles ut
     JOIN titles t ON t.id = ut.title_id
     WHERE ' . implode(' AND ', $where) . '
     ORDER BY ' . $orderBy,
    $params
);

require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <h1>My Library <span class="count"><?= count($titles) ?></span></h1>
    <a href="search.php" class="btn btn-primary">＋ Add titles</a>
</div>

<nav class="tabs" aria-label="Library views">
    <?php foreach ($views as $key => $v): ?>
        <a href="library.php?view=<?= $key ?>" class="<?= $key === $view ? 'active' : '' ?>"
           <?= $key === $view ? 'aria-current="page"' : '' ?>><?= h($v[0]) ?></a>
    <?php endforeach; ?>
</nav>

<!-- The filter form. method="get" so filters show up in the URL. -->
<form method="get" class="toolbar">
    <input type="hidden" name="view" value="<?= h($view) ?>">
    <label class="grow">
        <span class="visually-hidden">Search your library</span>
        <input type="search" name="q" value="<?= h($q) ?>" placeholder="Search your library…">
    </label>
    <label>
        <span class="visually-hidden">Type</span>
        <select name="type">
            <option value="">Movies &amp; TV</option>
            <option value="movie" <?= $type === 'movie' ? 'selected' : '' ?>>Movies</option>
            <option value="tv" <?= $type === 'tv' ? 'selected' : '' ?>>TV</option>
        </select>
    </label>
    <label>Sort
        <select name="sort">
            <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Recently added</option>
            <option value="title"  <?= $sort === 'title'  ? 'selected' : '' ?>>Title A-Z</option>
            <option value="year"   <?= $sort === 'year'   ? 'selected' : '' ?>>Newest release</option>
            <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>My rating</option>
        </select>
    </label>
    <button type="submit" class="btn">Apply</button>
</form>

<?php if (!$titles): ?>
    <div class="empty">
        <h2>Nothing here yet</h2>
        <p>
            <a class="btn btn-primary" href="search.php">🔍 Add titles</a>
            <a class="btn" href="trainer.php">💜 Taste Trainer</a>
            <a class="btn" href="discover.php">✨ Discover</a>
        </p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($titles as $t): ?>
            <?php
            // Small badges under each card: status + rating + owned formats
            $badges = '';
            if ($t['watch_status'] === 'watching' || $t['watch_status'] === 'watched') {
                $badges .= '<span class="badge badge-' . $t['watch_status'] . '">' . WATCH_LABELS[$t['watch_status']] . '</span>';
                if ($t['rating'] !== null) {
                    $badges .= '<span class="badge">' . rating_label($t['rating']) . '</span>';
                }
            } elseif ($t['interest']) {
                $badges .= '<span class="badge badge-' . $t['interest'] . '">' . INTEREST_LABELS[$t['interest']] . '</span>';
            }
            foreach (OWN_FORMATS as $col => $label) {
                if ($t[$col]) {
                    $badges .= '<span class="badge badge-own">' . $label . '</span>';
                }
            }
            echo title_card($t, '<p class="badges">' . $badges . '</p>');
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
