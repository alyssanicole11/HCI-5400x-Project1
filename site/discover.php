<?php
/**
 * DISCOVER  (one recommended title at a time, with "Why this")
 * STATUS: STUB     OWNER: Partner A (see docs/TEAM_PLAN.md)
 *
 * Needs includes/recommend.php finished first:
 *   $candidates = discover_candidates($userId);   // best score first
 *   $genreScores = genre_scores($userId);
 *   $pick = the first candidate not in $_SESSION['discover_skipped']
 *   $why  = score_title($pick, $genreScores)['reasons'];
 *
 * Card shows: ‹ Back | poster (links to title.php) | Skip ›
 *             title, year, Movie/TV
 *             Confidence: confidence_label($userId)   (never "100%" with little data)
 *             Why this: "You love Comedy" / "Similar to Coco, which you loved"
 *             [Seen it] [Interested] [Unsure] [Not interested]   <- POST, save_user_title()
 *             More like this -> title.php?id=...#more
 *
 * "Not interested" removes it right away and it never comes back.
 * If there are no candidates left, say so honestly and link to Library/Taste.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Discover';
$activeNav = 'discover';

require __DIR__ . '/includes/header.php';
?>

<h1>Discover</h1>
<p class="todo">🚧 Under construction - needs the recommendation functions in includes/recommend.php.</p>
<p class="muted">Confidence: <strong><?= h(confidence_label(current_user_id())) ?></strong></p>

<?php require __DIR__ . '/includes/footer.php'; ?>
