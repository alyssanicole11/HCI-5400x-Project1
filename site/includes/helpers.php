<?php
/**
 * SMALL HELPERS used on every page.
 */

// ---------------------------------------------------------------------
// Labels - ONE place for the wording, so every page says the same thing.
// (The JoyWatch spec says: same decision, same words, everywhere.)
// ---------------------------------------------------------------------
const WATCH_LABELS = [
    'not_yet'  => 'Not yet',
    'watching' => 'Watching',
    'watched'  => 'Watched',
];
const INTEREST_LABELS = [
    'yes'            => 'Interested',
    'unsure'         => 'Unsure',
    'not_interested' => 'Not interested',
];
const RATING_LABELS = [   // value => [label, emoji]
    2  => ['Love', '😍'],
    1  => ['Like', '🙂'],
    0  => ['Meh', '😐'],
    -1 => ['Dislike', '🙁'],
    -2 => ['Hate', '😖'],
];
const REWATCH_LABELS = [
    'yes'   => 'Yes',
    'maybe' => 'Maybe',
    'no'    => 'No',
];
const OWN_FORMATS = [     // database column => label
    'own_dvd'     => 'DVD',
    'own_bluray'  => 'Blu-ray',
    'own_4k'      => '4K UHD',
    'own_digital' => 'Digital',
];

/**
 * h() = "HTML-safe". Use it every time you print data that came from
 * a user or the database:   <?= h($title['title']) ?>
 * It stops people from injecting HTML/JavaScript into our pages (XSS).
 */
function h($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

/** Send the browser to another page and stop running this one. */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/** Read a value from the URL (?name=value) with a default. */
function get_param($name, $default = '') {
    return isset($_GET[$name]) ? trim((string) $_GET[$name]) : $default;
}

/** Read a value from a submitted form (method="post") with a default. */
function post_param($name, $default = '') {
    return isset($_POST[$name]) ? trim((string) $_POST[$name]) : $default;
}

/** True when the page was loaded by submitting a form with method="post". */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// ---------------------------------------------------------------------
// Flash messages: a one-time message shown on the NEXT page load,
// e.g. "Saved!" after a form redirects back.
// ---------------------------------------------------------------------
function flash($message, $type = 'success') {
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function show_flash() {
    if (empty($_SESSION['flash'])) {
        return;
    }
    foreach ($_SESSION['flash'] as $f) {
        echo '<div class="flash flash-' . h($f['type']) . '" role="status">' . h($f['message']) . '</div>';
    }
    unset($_SESSION['flash']);
}

// ---------------------------------------------------------------------
// CSRF protection: a secret token inside every POST form proves the
// form really came from our site. Put csrf_field() inside each
// <form method="post"> and call csrf_check() before saving anything.
// ---------------------------------------------------------------------
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_check() {
    if (!hash_equals(csrf_token(), post_param('csrf'))) {
        http_response_code(400);
        exit('Form expired. Please go back, refresh the page, and try again.');
    }
}

// ---------------------------------------------------------------------
// Display helpers
// ---------------------------------------------------------------------

/** "Movie" or "TV" */
function media_label($mediaType) {
    return $mediaType === 'tv' ? 'TV' : 'Movie';
}

/** "😍 Love" for a rating number, or '' when there is no rating. */
function rating_label($rating) {
    if ($rating === null || $rating === '') {   // NOT empty(): 0 (Meh) is a real rating!
        return '';
    }
    $r = RATING_LABELS[(int) $rating];
    return $r[1] . ' ' . $r[0];
}

/** Minutes -> "2h 15m" */
function format_runtime($minutes) {
    if (!$minutes) {
        return '';
    }
    $h = intdiv((int) $minutes, 60);
    $m = $minutes % 60;
    return ($h ? $h . 'h ' : '') . $m . 'm';
}

/**
 * Poster image. Uses the real TMDb poster when we have one, otherwise a
 * colorful placeholder with the title on it (so missing art still looks nice).
 */
function poster_html($title, $size = 'w342') {
    if (!empty($title['poster_path'])) {
        return '<img class="poster" src="https://image.tmdb.org/t/p/' . h($size) . h($title['poster_path'])
             . '" alt="Poster for ' . h($title['title']) . '" loading="lazy">';
    }
    $hue = ((int) $title['id'] * 47) % 360;   // same title = same color every time
    return '<div class="poster poster-placeholder" style="--hue:' . $hue . '" role="img" aria-label="No poster for '
         . h($title['title']) . '"><span>' . h($title['title']) . '</span></div>';
}

/** One title card (poster + name + year/type). Used by search, library, etc. */
function title_card($title, $extraHtml = '') {
    $url = 'title.php?id=' . (int) $title['id'];
    return '<article class="card">'
         . '<a href="' . $url . '" class="card-poster">' . poster_html($title) . '</a>'
         . '<div class="card-body">'
         . '<h3 class="card-title"><a href="' . $url . '">' . h($title['title']) . '</a></h3>'
         . '<p class="meta">' . h($title['year']) . ' · ' . media_label($title['media_type']) . '</p>'
         . $extraHtml
         . '</div></article>';
}
