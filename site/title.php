<?php
/**
 * TITLE DETAILS  (FORM #4 - the main "decision" form)
 * STATUS: working (starter code) - this is the REFERENCE page.
 *
 * Every place in the app that shows a title (search, library, discover,
 * tonight) links here: title.php?id=123
 *
 * The form saves: viewing status, interest, rating, watch again,
 * ownership, notes, and last-watched date - all into ONE user_titles row.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();

$userId  = current_user_id();
$titleId = (int) get_param('id');
$title   = get_title($titleId);
if (!$title) {
    http_response_code(404);
    $pageTitle = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="empty"><h1>Title not found</h1><p><a href="search.php">Search for a title</a></p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

// ---------------------------------------------------------------------
// 1. HANDLE THE FORM (only when it was submitted)
// ---------------------------------------------------------------------
if (is_post()) {
    csrf_check();
    $action = post_param('action', 'save');

    if ($action === 'remove') {
        delete_user_title($userId, $titleId);
        flash('Removed “' . $title['title'] . '” from your Library.');
        redirect('title.php?id=' . $titleId);
    }

    if ($action === 'watched_again') {
        // Only touch the fields this button is about. Rating, notes, etc. stay.
        save_user_title($userId, $titleId, ['watch_status' => 'watched', 'last_watched' => date('Y-m-d')]);
        flash('Marked as watched again today.');
        redirect('title.php?id=' . $titleId);
    }

    // action = save: read + validate every field. Anything unexpected -> NULL.
    $status   = post_param('watch_status');
    $interest = post_param('interest');
    $rating   = post_param('rating');
    $rewatch  = post_param('rewatch');
    $lastDate = post_param('last_watched');

    $fields = [
        'watch_status' => isset(WATCH_LABELS[$status]) ? $status : null,
        'interest'     => isset(INTEREST_LABELS[$interest]) ? $interest : null,
        // careful: '0' is a real rating (Meh), so check for '' instead of empty()
        'rating'       => ($rating !== '' && isset(RATING_LABELS[(int) $rating])) ? (int) $rating : null,
        'rewatch'      => isset(REWATCH_LABELS[$rewatch]) ? $rewatch : null,
        'notes'        => post_param('notes') === '' ? null : mb_substr(post_param('notes'), 0, 2000),
        'last_watched' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastDate) ? $lastDate : null,
    ];
    foreach (OWN_FORMATS as $col => $label) {
        $fields[$col] = isset($_POST[$col]) ? 1 : 0;       // checkboxes: present = checked
    }

    save_user_title($userId, $titleId, $fields);
    flash('Saved!');
    redirect('title.php?id=' . $titleId);   // "Post/Redirect/Get": refresh won't re-submit
}

// ---------------------------------------------------------------------
// 2. LOAD DATA FOR THE PAGE
// ---------------------------------------------------------------------
$genres  = get_title_genres($titleId);
$mine    = get_user_title($userId, $titleId);            // null = not in library
$similar = more_like_this($userId, $titleId);

// Current values for the form (so the right buttons appear selected)
$cur = function ($field) use ($mine) {
    return $mine ? $mine[$field] : null;
};

$pageTitle = $title['title'];
$activeNav = '';
require __DIR__ . '/includes/header.php';
?>

<article class="details">
    <div class="details-poster"><?= poster_html($title, 'w500') ?></div>

    <div class="details-info">
        <h1><?= h($title['title']) ?></h1>
        <p class="meta">
            <?= h($title['year']) ?> · <?= media_label($title['media_type']) ?>
            <?php if ($title['runtime']): ?> · <?= format_runtime($title['runtime']) ?><?= $title['media_type'] === 'tv' ? ' per episode' : '' ?><?php endif; ?>
        </p>
        <p class="chips">
            <?php foreach ($genres as $g): ?><span class="chip"><?= h($g) ?></span><?php endforeach; ?>
        </p>
        <?php if ($title['overview']): ?><p><?= h($title['overview']) ?></p><?php endif; ?>

        <?php if ($mine): ?>
            <p class="small muted">
                Added <?= date('M j, Y', strtotime($mine['date_added'])) ?>
                <?php if ($mine['date_rated']): ?> · Rated <?= date('M j, Y', strtotime($mine['date_rated'])) ?><?php endif; ?>
                <?php if ($mine['last_watched']): ?> · Last watched <?= date('M j, Y', strtotime($mine['last_watched'])) ?><?php endif; ?>
            </p>
        <?php else: ?>
            <p class="badge">Not in your Library yet - save any choice below to add it.</p>
        <?php endif; ?>
    </div>
</article>

<form method="post" class="decision-form" id="decision-form">
    <?= csrf_field() ?>

    <fieldset class="choice-group">
        <legend>Have you watched it?</legend>
        <?php foreach (WATCH_LABELS as $value => $label): ?>
            <label class="pill">
                <input type="radio" name="watch_status" value="<?= $value ?>" <?= $cur('watch_status') === $value ? 'checked' : '' ?>>
                <span><?= h($label) ?></span>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <!-- data-show-when: app.js hides this group unless the status matches -->
    <fieldset class="choice-group" data-show-when="not_yet,">
        <legend>Interested?</legend>
        <?php foreach (INTEREST_LABELS as $value => $label): ?>
            <label class="pill">
                <input type="radio" name="interest" value="<?= $value ?>" <?= $cur('interest') === $value ? 'checked' : '' ?>>
                <span><?= h($label) ?></span>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <fieldset class="choice-group" data-show-when="watching,watched">
        <legend>Your rating</legend>
        <?php foreach (RATING_LABELS as $value => $r): ?>
            <label class="pill">
                <input type="radio" name="rating" value="<?= $value ?>"
                    <?= ($cur('rating') !== null && (int) $cur('rating') === $value) ? 'checked' : '' ?>>
                <span><?= $r[1] ?> <?= h($r[0]) ?></span>
            </label>
        <?php endforeach; ?>
        <label class="pill pill-quiet">
            <input type="radio" name="rating" value="" <?= $cur('rating') === null ? 'checked' : '' ?>>
            <span>No rating</span>
        </label>
    </fieldset>

    <fieldset class="choice-group" data-show-when="watched">
        <legend>Watch again?</legend>
        <?php foreach (REWATCH_LABELS as $value => $label): ?>
            <label class="pill">
                <input type="radio" name="rewatch" value="<?= $value ?>" <?= $cur('rewatch') === $value ? 'checked' : '' ?>>
                <span><?= h($label) ?></span>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <fieldset class="choice-group">
        <legend>I own it on</legend>
        <?php foreach (OWN_FORMATS as $col => $label): ?>
            <label class="pill">
                <input type="checkbox" name="<?= $col ?>" value="1" <?= $cur($col) ? 'checked' : '' ?>>
                <span><?= h($label) ?></span>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <div class="form-row">
        <label>Notes
            <textarea name="notes" rows="3" maxlength="2000" placeholder="Anything you want to remember…"><?= h($cur('notes')) ?></textarea>
        </label>
        <label>Last watched
            <input type="date" name="last_watched" value="<?= h($cur('last_watched')) ?>">
        </label>
    </div>

    <div class="button-row">
        <button type="submit" name="action" value="save" class="btn btn-primary">Save</button>
        <?php if ($mine): ?>
            <button type="submit" name="action" value="watched_again" class="btn">🔁 Watched again today</button>
            <button type="submit" name="action" value="remove" class="btn btn-danger"
                    onclick="return confirm('Remove this title and all your notes/ratings for it?');">Remove from Library</button>
        <?php endif; ?>
    </div>
</form>

<?php if ($similar): ?>
    <section>
        <h2>More like this</h2>
        <div class="grid grid-small">
            <?php foreach ($similar as $s) { echo title_card($s); } ?>
        </div>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
