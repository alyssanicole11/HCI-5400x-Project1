<?php
/**
 * ADD TITLES - ADD A LIST  (FORM #11 - paste many titles at once)
 * STATUS: STRETCH GOAL / STUB     OWNER: Partner A (if time)
 *
 * Step 1 (this form): user pastes titles, one per line or separated by , or ;
 * Step 2 (TODO): PHP splits the text:
 *     $names = preg_split('/[\r\n,;]+/', $_POST['titles']);  trim each, drop blanks,
 *     strip surrounding quotes, remove duplicates (strtolower + array_unique)
 * Step 3 (TODO): for each name, search_titles() and sort into groups:
 *     Ready (exactly 1 match) | Needs confirmation (2+ matches) |
 *     Not found (0) | Already in Library
 * Step 4 (TODO): show a REVIEW form with a checkbox per Ready title and a
 *     <select> for each "Needs confirmation" title. Nothing is saved until
 *     the user presses "Add selected" (a second POST with action=confirm).
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Add a list';
$activeNav = 'search';

require __DIR__ . '/includes/header.php';
?>

<h1>Add titles</h1>
<p class="tabs">
    <a href="search.php">Search one title</a>
    <a href="add-list.php" class="active" aria-current="page">Add a list</a>
</p>

<p class="todo">🚧 Stretch goal - the form shows, but nothing happens yet.</p>
<form method="post" class="stack">
    <?= csrf_field() ?>
    <label>Type or paste titles - one per line, or separated by commas
        <textarea name="titles" rows="8" placeholder="Coco&#10;The Office&#10;Inception, Up; Frozen"></textarea>
    </label>
    <button class="btn btn-primary" type="submit">Review matches</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
