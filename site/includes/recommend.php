<?php
/**
 * RECOMMENDATION + TASTE ANALYSIS  (the "computation" part of the project)
 * ---------------------------------------------------------------
 * STATUS: STUBS. The function names, inputs, and outputs are decided;
 * the insides are TODO. Owner: see docs/TEAM_PLAN.md.
 *
 * Rules from the JoyWatch spec that EVERY function here must follow:
 *   1. Never recommend a title marked interest = 'not_interested'.
 *   2. Explicit choices beat guesses: a title rating or genre preference
 *      the user gave us outweighs anything we infer.
 *   3. Rating 0 (Meh) is a real rating. Use `=== null`, never empty().
 *   4. Be honest: with little data, say "Still learning".
 */

// Tonight's "mood" filter maps to genres. No extra database table needed.
const MOODS = [
    'funny'      => ['label' => 'Funny',      'genres' => ['Comedy']],
    'cozy'       => ['label' => 'Cozy',       'genres' => ['Family', 'Animation', 'Romance']],
    'tense'      => ['label' => 'Tense',      'genres' => ['Thriller', 'Horror', 'Mystery', 'Crime']],
    'thoughtful' => ['label' => 'Thoughtful', 'genres' => ['Drama', 'Documentary', 'History']],
    'epic'       => ['label' => 'Epic',       'genres' => ['Adventure', 'Fantasy', 'Science Fiction', 'Action', 'War']],
    'romantic'   => ['label' => 'Romantic',   'genres' => ['Romance']],
];

/**
 * How much do we know about this user?  -> 'Still learning' | 'Low' | 'Developing' | 'Established'
 *
 * TODO:
 *   $evidence = (# of user_titles rows with a rating OR an interest)
 *             + (# of user_genre_prefs rows)
 *   < 5  -> 'Still learning',  < 15 -> 'Low',  < 40 -> 'Developing',  else 'Established'
 */
function confidence_label($userId) {
    return 'Still learning';
}

/**
 * The user's score for every genre, e.g. ['Comedy' => 3.5, 'Horror' => -4.0]
 *
 * TODO (one simple, explainable formula):
 *   score[genre]  = 2 * (their explicit genre preference, -2..2)      // explicit = stronger
 *                 + average rating of titles they rated in that genre  // inferred
 *   (Only count genres that have some evidence.)
 *   Tip: SQL can do most of this:
 *     SELECT g.name, AVG(ut.rating) AS avg_rating, COUNT(*) AS n
 *     FROM user_titles ut
 *     JOIN title_genres tg ON tg.title_id = ut.title_id
 *     JOIN genres g ON g.id = tg.genre_id
 *     WHERE ut.user_id = ? AND ut.rating IS NOT NULL
 *     GROUP BY g.name
 */
function genre_scores($userId) {
    return [];
}

/**
 * Score one catalog title for this user and explain why.
 * Returns ['score' => float, 'reasons' => ['You love Comedy', ...]]
 *
 * TODO:
 *   score = sum of genre_scores for the title's genres
 *   reasons = the 1-2 genres that added the most (in plain English)
 *   If the user has no data yet: score 0, reason 'Popular pick while JoyWatch learns your taste'
 */
function score_title($title, $genreScores) {
    return ['score' => 0, 'reasons' => []];
}

/**
 * Discover: titles NOT in the user's library, best score first.
 * TODO: SELECT titles with no user_titles row for this user, score each
 *       with score_title(), usort() by score, return the top $limit.
 */
function discover_candidates($userId, $limit = 20) {
    return [];
}

/**
 * Tonight: returns ['personal' => [...], 'suggested' => [...]]
 *
 * TODO:
 *   personal pool (from the user's own library) =
 *       interest IN ('yes','unsure')
 *    OR (watch_status = 'watched' AND rating >= 0 AND rewatch IN ('yes','maybe'))
 *   suggested = discover_candidates(), used only to fill empty spots
 *   Apply $filters to both: media_type, max runtime, mood (via MOODS)
 *   Never include not_interested, rating -2 (Hate) or rewatch 'no'.
 */
function tonight_picks($userId, $filters, $limit = 8) {
    return ['personal' => [], 'suggested' => []];
}
