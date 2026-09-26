<?php
/**
 * SETUP CHECK - open this page first after uploading to Keymaker.
 * It tells you what is working and what isn't. It never shows passwords.
 * (Safe to leave in the final submission, or delete it.)
 */
$checks = [];

// $fixHint is shown only when the check fails; $info is shown when it passes
function check($label, $ok, $fixHint = '', $info = '') {
    global $checks;
    $checks[] = [$label, $ok, $ok ? $info : $fixHint];
    return $ok;
}

check('PHP version ' . PHP_VERSION, version_compare(PHP_VERSION, '7.4', '>='), 'Need 7.4 or newer');
check('PDO MySQL driver', extension_loaded('pdo_mysql'), 'Ask the Keymaker admin to enable pdo_mysql');
check('mbstring extension', extension_loaded('mbstring'), 'Used for trimming notes safely');
$hasConfig = check('includes/config.php exists', file_exists(__DIR__ . '/includes/config.php'),
                   'Copy includes/config.sample.php to includes/config.php');

if ($hasConfig) {
    require __DIR__ . '/includes/config.php';
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        check('Database connection', true, '', 'Server: ' . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION));
        foreach (['users', 'titles', 'genres', 'title_genres', 'user_titles', 'user_genre_prefs'] as $table) {
            try {
                $n = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                check("Table $table", true, '', "$n rows");
            } catch (PDOException $e) {
                check("Table $table", false, 'Missing - import database/01_schema.sql then 02_seed.sql');
            }
        }
    } catch (PDOException $e) {
        check('Database connection', false, 'Check DB_HOST / DB_NAME / DB_USER / DB_PASS in config.php');
    }

    // Extra credit feature: can this server reach the TMDb API?
    if (function_exists('curl_init')) {
        $ch = curl_init('https://api.themoviedb.org/3/configuration');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_NOBODY => true]);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        check('Outbound internet to TMDb', $code > 0, 'Blocked - TMDb features will not work on this server', "HTTP $code (reachable)");
        check('TMDB_TOKEN set', defined('TMDB_TOKEN') && TMDB_TOKEN !== '', 'Optional (extra credit)');
    } else {
        check('cURL extension', false, 'Optional - needed only for TMDb');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Setup check · JoyWatch</title><link rel="stylesheet" href="css/style.css"></head>
<body><main class="container">
<h1>JoyWatch setup check</h1>
<table class="table">
    <thead><tr><th>Check</th><th>Result</th><th>Details / how to fix</th></tr></thead>
    <tbody>
    <?php foreach ($checks as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c[0]) ?></td>
            <td><?= $c[1] ? '✅' : '❌' ?></td>
            <td><?= htmlspecialchars($c[2]) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p><a href="index.php">Go to JoyWatch →</a></p>
</main></body></html>
