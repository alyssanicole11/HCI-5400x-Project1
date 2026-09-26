<?php
/**
 * SETTINGS  (FORM #9 profile, FORM #10 clear data)
 * STATUS: STARTER - forms displayed, saving is TODO
 * OWNER: Partner B  (see docs/TEAM_PLAN.md)
 *
 *   ⬜ TODO 1: save the profile form
 *        - read display_name + media_preference with post_param()
 *        - validate: name not empty, max 60 chars; preference in ('both','movie','tv')
 *        - UPDATE users SET display_name = ?, media_preference = ? WHERE id = ?
 *        - flash('Settings saved!'); redirect('settings.php');
 *        (copy the structure of register.php - it's the same pattern)
 *   ⬜ TODO 2: clear my data
 *        - only when the user typed CLEAR in the box (so it's not an accident)
 *        - DELETE FROM user_titles WHERE user_id = ?  (and user_genre_prefs)
 *   ⬜ STRETCH: streaming services, country, import a backup
 *
 * Tip: both forms post to this same page. Tell them apart with the
 * hidden input "form" (value "profile" or "clear").
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Settings';
$activeNav = 'settings';
$user = current_user();

if (is_post()) {
    csrf_check();
    $which = post_param('form');
    // TODO 1 + TODO 2 go here
    flash('🚧 Saving settings is not built yet.', 'info');
    redirect('settings.php');
}

require __DIR__ . '/includes/header.php';
?>

<h1>Settings</h1>

<section class="panel">
    <h2>Profile</h2>
    <form method="post" class="stack">
        <?= csrf_field() ?>
        <input type="hidden" name="form" value="profile">
        <label>Display name
            <input type="text" name="display_name" value="<?= h($user['display_name']) ?>" maxlength="60" required>
        </label>
        <fieldset class="choice-group">
            <legend>I usually want to see</legend>
            <?php foreach (['both' => 'Movies & TV', 'movie' => 'Movies', 'tv' => 'TV'] as $value => $label): ?>
                <label class="pill">
                    <input type="radio" name="media_preference" value="<?= $value ?>" <?= $user['media_preference'] === $value ? 'checked' : '' ?>>
                    <span><?= h($label) ?></span>
                </label>
            <?php endforeach; ?>
        </fieldset>
        <button class="btn btn-primary" type="submit">Save settings</button>
    </form>
</section>

<section class="panel">
    <h2>Backup</h2>
    <div class="button-row">
        <a class="btn" href="export.php">⬇️ Export my library (CSV)</a>
        <span class="btn btn-disabled" aria-disabled="true" title="Stretch goal">⬆️ Import (coming later)</span>
    </div>
</section>

<section class="panel panel-danger">
    <h2>Clear my data</h2>
    <p>Deletes every title, rating, and preference in your account. This cannot be undone. Export first!</p>
    <form method="post" class="toolbar">
        <?= csrf_field() ?>
        <input type="hidden" name="form" value="clear">
        <label>Type CLEAR to confirm <input type="text" name="confirm" autocomplete="off"></label>
        <button class="btn btn-danger" type="submit">Clear my data</button>
    </form>
</section>

<section class="panel">
    <h2>About JoyWatch</h2>
    <p><a href="about.php">What JoyWatch does and how we built it →</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
