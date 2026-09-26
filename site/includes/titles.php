<?php
/**
 * TITLE + LIBRARY DATA FUNCTIONS
 * ---------------------------------------------------------------
 * All the SQL about titles and a user's personal choices lives here,
 * so pages stay short and easy to read.
 */

/** One catalog title by id (or null if it doesn't exist). */
function get_title($titleId) {
    return db_one('SELECT * FROM titles WHERE id = ?', [$titleId]);
}

/** Genre names for one title, e.g. ['Comedy', 'Drama'] */
function get_title_genres($titleId) {
    $rows = db_all(
        'SELECT g.name FROM genres g
         JOIN title_genres tg ON tg.genre_id = g.id
         WHERE tg.title_id = ? ORDER BY g.name',
        [$titleId]
    );
    return array_column($rows, 'name');
}

/** Every genre (id + name), alphabetical. */
function all_genres() {
    return db_all('SELECT id, name FROM genres ORDER BY name');
}

/** The logged-in user's saved choices for one title, or null if not in their library. */
function get_user_title($userId, $titleId) {
    return db_one('SELECT * FROM user_titles WHERE user_id = ? AND title_id = ?', [$userId, $titleId]);
}

/**
 * Search the catalog by name. Also returns the user's watch_status and
 * interest (NULL when the title isn't in their library yet).
 *
 * $mediaType: '' (both), 'movie', or 'tv'
 */
function search_titles($userId, $query, $mediaType = '', $limit = 40) {
    $sql = 'SELECT t.*, ut.watch_status, ut.interest, ut.rating,
                   (ut.title_id IS NOT NULL) AS in_library
            FROM titles t
            LEFT JOIN user_titles ut ON ut.title_id = t.id AND ut.user_id = ?
            WHERE t.title LIKE ?';
    $params = [$userId, '%' . $query . '%'];
    if ($mediaType === 'movie' || $mediaType === 'tv') {
        $sql .= ' AND t.media_type = ?';
        $params[] = $mediaType;
    }
    $sql .= ' ORDER BY (t.title LIKE ?) DESC, t.title LIMIT ' . (int) $limit;
    $params[] = $query . '%';          // titles that START with the search come first
    return db_all($sql, $params);
}

/**
 * SAVE CHOICES WITHOUT ERASING OTHER CHOICES  (a core JoyWatch rule)
 * ---------------------------------------------------------------
 * $fields contains ONLY the columns you want to change, e.g.
 *     save_user_title($uid, 12, ['watch_status' => 'watched']);
 * Every other column (rating, notes, ownership...) is left alone.
 * Creates the library row if it doesn't exist yet.
 */
function save_user_title($userId, $titleId, $fields) {
    $allowed = ['watch_status', 'interest', 'rating', 'rewatch', 'notes', 'last_watched',
                'own_dvd', 'own_bluray', 'own_4k', 'own_digital'];
    $fields = array_intersect_key($fields, array_flip($allowed));   // ignore anything else

    $existing = get_user_title($userId, $titleId);

    // Record WHEN the rating changed
    if (array_key_exists('rating', $fields)) {
        $old = $existing ? $existing['rating'] : null;
        $new = $fields['rating'];
        if ($new === null) {
            $fields['date_rated'] = null;
        } elseif ($old === null || (int) $old !== (int) $new) {
            $fields['date_rated'] = date('Y-m-d H:i:s');
        }
    }

    if ($existing) {
        if (!$fields) {
            return;
        }
        $sets = [];
        foreach (array_keys($fields) as $col) {
            $sets[] = "$col = ?";
        }
        $params   = array_values($fields);
        $params[] = $userId;
        $params[] = $titleId;
        db_run('UPDATE user_titles SET ' . implode(', ', $sets) . ' WHERE user_id = ? AND title_id = ?', $params);
    } else {
        $cols   = array_merge(['user_id', 'title_id'], array_keys($fields));
        $params = array_merge([$userId, $titleId], array_values($fields));
        $marks  = implode(', ', array_fill(0, count($cols), '?'));
        db_run('INSERT INTO user_titles (' . implode(', ', $cols) . ") VALUES ($marks)", $params);
    }
}

/** Remove a title from the user's library completely. */
function delete_user_title($userId, $titleId) {
    db_run('DELETE FROM user_titles WHERE user_id = ? AND title_id = ?', [$userId, $titleId]);
}

/**
 * "More like this": other titles that share the most genres with this one.
 * Skips titles the user marked Not interested.
 */
function more_like_this($userId, $titleId, $limit = 6) {
    return db_all(
        'SELECT t.*, COUNT(*) AS shared_genres
         FROM titles t
         JOIN title_genres tg ON tg.title_id = t.id
         LEFT JOIN user_titles ut ON ut.title_id = t.id AND ut.user_id = ?
         WHERE t.id <> ?
           AND tg.genre_id IN (SELECT genre_id FROM title_genres WHERE title_id = ?)
           AND (ut.interest IS NULL OR ut.interest <> \'not_interested\')
         GROUP BY t.id
         ORDER BY shared_genres DESC, t.year DESC
         LIMIT ' . (int) $limit,
        [$userId, $titleId, $titleId]
    );
}
