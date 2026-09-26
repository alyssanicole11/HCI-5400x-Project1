<?php
/**
 * TONIGHT  (FORM #8 - filter form -> a short list of picks)
 * STATUS: STUB (form is built, results are TODO)     OWNER: Partner A
 *
 *   ⬜ TODO: $picks = tonight_picks($userId, $filters);  (write it in includes/recommend.php)
 *   ⬜ TODO: show "From your list" cards (personal pool) first, then "New for you"
 *   ⬜ TODO: "Not tonight" on personal cards = hide for this session only
 *            ($_SESSION['not_tonight'][] = id)
 *            "Not interested" on suggestions = save_user_title(..., ['interest' => 'not_interested'])
 *            These two buttons must LOOK different.
 *   ⬜ TODO: "🎲 Random pick" = choose one with array_rand(), show it big at the top
 *            with a "Re-roll" button (just submit the form again with random=1)
 *   ⬜ If filters give no results, say which filter to loosen. Never show
 *      Not interested / Hate / Watch again = No titles to fill space.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Tonight';
$activeNav = 'tonight';
$userId = current_user_id();

// Read the filter form (GET, so the URL remembers the filters)
$filters = [
    'type'    => get_param('type'),            // '', 'movie', 'tv'
    'mood'    => get_param('mood'),            // key from MOODS in recommend.php
    'runtime' => (int) get_param('runtime'),   // max minutes, 0 = any
];
$activeCount = count(array_filter($filters));  // "Filters (2)"
$picks = tonight_picks($userId, $filters);

require __DIR__ . '/includes/header.php';
?>

<h1>What should we watch tonight?</h1>

<form method="get" class="filters">
    <fieldset class="choice-group">
        <legend>Mood</legend>
        <label class="pill"><input type="radio" name="mood" value="" <?= $filters['mood'] === '' ? 'checked' : '' ?>><span>Any</span></label>
        <?php foreach (MOODS as $key => $m): ?>
            <label class="pill">
                <input type="radio" name="mood" value="<?= $key ?>" <?= $filters['mood'] === $key ? 'checked' : '' ?>>
                <span><?= h($m['label']) ?></span>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <div class="toolbar">
        <label>Type
            <select name="type">
                <option value="">Movies &amp; TV</option>
                <option value="movie" <?= $filters['type'] === 'movie' ? 'selected' : '' ?>>Movie</option>
                <option value="tv" <?= $filters['type'] === 'tv' ? 'selected' : '' ?>>TV episode</option>
            </select>
        </label>
        <label>Time I have
            <select name="runtime">
                <option value="0">Any length</option>
                <?php foreach ([30 => 'Under 30 min', 60 => 'Under 1 hour', 100 => 'Under 1h 40m', 130 => 'Under 2h 10m'] as $mins => $label): ?>
                    <option value="<?= $mins ?>" <?= $filters['runtime'] === $mins ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Show picks<?= $activeCount ? " ($activeCount filters)" : '' ?></button>
        <button type="submit" name="random" value="1" class="btn">🎲 Random pick</button>
    </div>
</form>

<?php if (!$picks['personal'] && !$picks['suggested']): ?>
    <p class="todo">🚧 Results will appear here once tonight_picks() is written in includes/recommend.php.</p>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
