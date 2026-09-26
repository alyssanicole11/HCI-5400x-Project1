<?php
/**
 * MY TASTE  (FORM #6 genre preferences + the ANALYSIS page)
 * STATUS: STARTER - form displays; saving + stats are TODO
 * OWNER: Partner B (pair with Partner A for TODO 1) - see docs/TEAM_PLAN.md
 *
 * This page is where the project's "data analysis / computation" is most
 * visible to the professor: we turn the user's saved choices into stats.
 *
 *   ✅ Genre preference form is displayed with the user's saved choices
 *   ⬜ TODO 1: save the genre preference form
 *   ⬜ TODO 2: "At a glance" numbers
 *   ⬜ TODO 3: "Your genres" table (average rating per genre + a bar)
 *   ⬜ STRETCH: rate people/actors, mood traits, "Insights" sentences
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'My Taste';
$activeNav = 'taste';
$userId = current_user_id();

// ---------------------------------------------------------------------
// TODO 1: SAVE THE GENRE FORM
//   The form sends $_POST['genre'] as an array like  [3 => '2', 11 => '-2', 4 => '']
//   (genre id => rating, '' = no opinion).
//   if (is_post()) {
//       csrf_check();
//       foreach ($_POST['genre'] as $genreId => $rating) {
//           if ($rating === '')  -> DELETE FROM user_genre_prefs WHERE user_id = ? AND genre_id = ?
//           else                 -> INSERT INTO user_genre_prefs (user_id, genre_id, rating) VALUES (?, ?, ?)
//                                   ON DUPLICATE KEY UPDATE rating = VALUES(rating)
//       }
//       flash('Taste saved!');  redirect('taste.php');
//   }
//   Remember: (int) the ids, and only accept ratings -2, -1, 0, 1, 2.
// ---------------------------------------------------------------------

// Load genres + this user's saved preference for each (NULL = no opinion)
$genres = db_all(
    'SELECT g.id, g.name, p.rating
     FROM genres g
     LEFT JOIN user_genre_prefs p ON p.genre_id = g.id AND p.user_id = ?
     ORDER BY g.name',
    [$userId]
);

// ---------------------------------------------------------------------
// TODO 2: AT A GLANCE - fill these in with db_value() queries, e.g.
//   $rated = db_value('SELECT COUNT(*) FROM user_titles WHERE user_id = ? AND rating IS NOT NULL', [$userId]);
//   Also: total watched, average rating (AVG(rating)), total runtime of
//   watched MOVIES in hours (SUM(t.runtime) needs a JOIN to titles).
// ---------------------------------------------------------------------
$rated = 0;
$watched = 0;
$avgRating = null;
$hoursWatched = 0;

// ---------------------------------------------------------------------
// TODO 3: YOUR GENRES - one row per genre with:
//   name, how many titles you rated in it, your average rating.
//   Hint: see the SQL in includes/recommend.php -> genre_scores().
//   Then in the HTML below, loop over $genreStats and draw a bar whose
//   width depends on the average (e.g. (avg + 2) / 4 * 100 %).
// ---------------------------------------------------------------------
$genreStats = [];

require __DIR__ . '/includes/header.php';
?>

<h1>My Taste</h1>
<p class="muted">JoyWatch confidence: <strong><?= h(confidence_label($userId)) ?></strong></p>

<section>
    <h2>At a glance</h2>
    <div class="stat-row">
        <div class="stat"><strong><?= (int) $watched ?></strong><span>Watched</span></div>
        <div class="stat"><strong><?= (int) $rated ?></strong><span>Rated</span></div>
        <div class="stat"><strong><?= $avgRating === null ? '–' : h(round($avgRating, 1)) ?></strong><span>Avg rating (-2 to 2)</span></div>
        <div class="stat"><strong><?= (int) $hoursWatched ?></strong><span>Hours of movies</span></div>
    </div>
</section>

<section>
    <h2>Your genres</h2>
    <?php if (!$genreStats): ?>
        <p class="todo">🚧 TODO 3: average rating per genre goes here, with a bar chart made of &lt;div&gt;s.</p>
    <?php endif; ?>
</section>

<section id="genres">
    <h2>How do you feel about these genres?</h2>
    <form method="post" class="genre-form">
        <?= csrf_field() ?>
        <?php foreach ($genres as $g): ?>
            <fieldset class="choice-group compact">
                <legend><?= h($g['name']) ?></legend>
                <?php foreach (RATING_LABELS as $value => $r): ?>
                    <label class="pill" title="<?= h($r[0]) ?>">
                        <input type="radio" name="genre[<?= (int) $g['id'] ?>]" value="<?= $value ?>"
                            <?= ($g['rating'] !== null && (int) $g['rating'] === $value) ? 'checked' : '' ?>>
                        <span><?= $r[1] ?><span class="visually-hidden"> <?= h($r[0]) ?></span></span>
                    </label>
                <?php endforeach; ?>
                <label class="pill pill-quiet">
                    <input type="radio" name="genre[<?= (int) $g['id'] ?>]" value="" <?= $g['rating'] === null ? 'checked' : '' ?>>
                    <span>No opinion</span>
                </label>
            </fieldset>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Save my taste</button>
    </form>
</section>

<section>
    <h2>Teach JoyWatch faster</h2>
    <p><a href="trainer.php" class="btn">💜 Open Taste Trainer</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
