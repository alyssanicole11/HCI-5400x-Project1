<?php
/**
 * TASTE TRAINER  (FORM #7 - one quick question at a time)
 * STATUS: STUB     OWNER: Partner A (see docs/TEAM_PLAN.md)
 *
 * Shows ONE title the user hasn't answered yet and asks:
 *     "Watched?"  -> Yes: rating (Love..Hate) -> watch again (Yes/Maybe/No)
 *                 -> Not yet: Interested? (Yes / Unsure / No)
 *     or Skip.
 *
 * HOW WITHOUT JAVASCRIPT: each step is its own small POST form.
 *   The page reads ?id=<title id>&step=<watched|rate|rewatch|interest>
 *   Step "watched":  buttons  [Yes, I've seen it] [Not yet] [Skip]
 *   Yes      -> save watch_status='watched', go to step=rate for the same id
 *   Not yet  -> save watch_status='not_yet', go to step=interest
 *   rate     -> save rating, go to step=rewatch
 *   rewatch / interest -> save, go to the NEXT title (step=watched)
 *   Use save_user_title() so earlier answers are never erased.
 *
 * PICKING THE NEXT TITLE (TODO):
 *   SELECT t.* FROM titles t
 *   WHERE t.id NOT IN (SELECT title_id FROM user_titles WHERE user_id = ?)
 *     AND t.id NOT IN (<ids in $_SESSION['trainer_skipped']>)
 *   ORDER BY RAND() LIMIT 1
 *   - "Skip" adds the id to $_SESSION['trainer_skipped'] (not saved forever).
 *   - "Back" = keep a list of answered ids in $_SESSION['trainer_history'];
 *     Back opens the last one again so the user can change the answer.
 *   - If nothing is left: "You've gone through our whole catalog! 🎉"
 *
 * Spec rules: no "3 of 20" counter, no Undo button, Not interested titles never come back.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Taste Trainer';
$activeNav = 'taste';

require __DIR__ . '/includes/header.php';
?>

<h1>Taste Trainer</h1>
<p class="todo">🚧 Under construction. Below is the planned layout (buttons don't work yet).</p>

<!-- Planned layout - replace with real data + forms -->
<div class="carousel">
    <button class="icon-btn" type="button" aria-label="Back to previous title">‹</button>
    <div class="carousel-poster"><?= poster_html(['id' => 7, 'title' => 'Example title', 'poster_path' => null]) ?></div>
    <button class="icon-btn" type="button" aria-label="Skip this title">›</button>
</div>
<p class="carousel-caption"><strong>Example title</strong><br><span class="meta">2019 · Movie</span></p>
<h2 class="prompt">Watched?</h2>
<div class="button-row center">
    <button class="btn btn-primary" type="button">Yes, I've seen it</button>
    <button class="btn" type="button">Not yet</button>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
