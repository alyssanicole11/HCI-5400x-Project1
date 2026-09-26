<?php
/**
 * TMDb API HELPER  -  STRETCH / EXTRA CREDIT     OWNER: Partner A
 * ---------------------------------------------------------------
 * Not loaded by bootstrap.php yet. When you start this feature, add
 *     require __DIR__ . '/tmdb.php';
 * to bootstrap.php.
 *
 * FIRST run setup-check.php on Keymaker: if "Outbound internet to TMDb"
 * is ❌, the school server can't reach TMDb and we skip this feature.
 *
 * Ideas, smallest first:
 *   1. Posters: for titles with a tmdb_id and no poster_path, call
 *      tmdb_get("/movie/$id") or ("/tv/$id") and save poster_path.
 *   2. Search: in search.php, if our catalog has few results, call
 *      tmdb_get('/search/multi', ['query' => $q]) and show an "Add to JoyWatch"
 *      button that INSERTs the title + genres into our catalog.
 *   3. Trailers / cast / where to watch: /movie/{id}/videos, /credits, /watch/providers
 * Docs: https://developer.themoviedb.org/reference/intro/getting-started
 */

/** GET a TMDb endpoint and return the decoded JSON array (or null on failure). */
function tmdb_get($path, $query = []) {
    if (!defined('TMDB_TOKEN') || TMDB_TOKEN === '' || !function_exists('curl_init')) {
        return null;
    }
    $url = 'https://api.themoviedb.org/3' . $path . ($query ? '?' . http_build_query($query) : '');
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . TMDB_TOKEN, 'Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($body === false || $code !== 200) {
        return null;
    }
    return json_decode($body, true);
}
