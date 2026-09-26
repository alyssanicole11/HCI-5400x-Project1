# JoyWatch — Database Guide

MySQL/MariaDB. The schema is `database/01_schema.sql` and the data is `database/02_seed.sql`, which is generated from `database/titles.csv`.

```
users ──< user_titles >── titles ──< title_genres >── genres
  └────< user_genre_prefs >──────────────────────────────┘
```
`──<` means "one to many". For example, one user has many `user_titles` rows.

## Catalog tables (shared by everyone)

### `titles`: one row per movie or TV show
| column | example | notes |
|---|---|---|
| id | 13 | our own number |
| media_type | `movie` | `movie` or `tv` |
| tmdb_id | 862 | The Movie Database ID. media_type + tmdb_id is unique |
| title | Toy Story | |
| year | 1995 | |
| runtime | 81 | minutes (for TV, one episode) |
| overview | A cowboy doll feels threatened… | short synopsis |
| poster_path | NULL | filled from TMDb if we do that feature; otherwise the site draws a colored placeholder |

### `genres` and `title_genres`
`genres` has 17 rows (Action … War). `title_genres` links them: `(title_id 13, genre_id 3 = Animation)`, `(13, 4 = Comedy)`, …

## Personal tables (every row has a `user_id`)

### `users`
`id, username, display_name, password_hash, media_preference (both|movie|tv), created_at`
The password is stored only as a hash made with `password_hash()`.

### `user_titles`: everything one user decided about one title
| column | values | meaning |
|---|---|---|
| user_id, title_id | | together form the primary key: one row per user per title |
| watch_status | `not_yet` `watching` `watched` or NULL | |
| interest | `yes` `unsure` `not_interested` or NULL | `not_interested` hides the title from recommendations **forever** |
| rating | 2, 1, **0**, -1, -2 or NULL | Love, Like, **Meh**, Dislike, Hate. **0 is a real rating; NULL means not rated** |
| rewatch | `yes` `maybe` `no` or NULL | "Watch again?" |
| own_dvd, own_bluray, own_4k, own_digital | 0 or 1 | checkboxes |
| notes | text | |
| date_added / date_rated / last_watched | dates | |

Example rows (demo user):

| title | watch_status | interest | rating | rewatch | notes |
|---|---|---|---|---|---|
| Inception | watched | NULL | 2 | yes | Still thinking about the ending. |
| Barbie | watched | NULL | **0** | maybe | |
| Dune: Part Two | not_yet | yes | NULL | NULL | Watch on the big TV. |
| John Wick | not_yet | not_interested | NULL | NULL | |

### `user_genre_prefs`
`(user_id, genre_id, rating -2..2)`. For example, the demo user has Animation = 2, Comedy = 2 and Horror = -2.

## Handy queries (try them in phpMyAdmin → SQL tab)

```sql
-- Demo user's library with titles
SELECT t.title, ut.watch_status, ut.interest, ut.rating
FROM user_titles ut JOIN titles t ON t.id = ut.title_id
WHERE ut.user_id = 1;

-- Average rating per genre for user 1 (this is the core of "My Taste")
SELECT g.name, COUNT(*) AS rated, ROUND(AVG(ut.rating), 2) AS avg_rating
FROM user_titles ut
JOIN title_genres tg ON tg.title_id = ut.title_id
JOIN genres g ON g.id = tg.genre_id
WHERE ut.user_id = 1 AND ut.rating IS NOT NULL
GROUP BY g.name ORDER BY avg_rating DESC;

-- Tonight's "personal pool" for user 1
SELECT t.title FROM user_titles ut JOIN titles t ON t.id = ut.title_id
WHERE ut.user_id = 1
  AND ( ut.interest IN ('yes','unsure')
     OR (ut.watch_status = 'watched' AND ut.rating >= 0 AND ut.rewatch IN ('yes','maybe')) );
```

## Adding titles to the catalog
1. Edit the **JoyWatch titles** Google Sheet. It has the same columns as `titles.csv`: `media_type, tmdb_id, title, year, runtime, genres, overview`. Genres are separated by `|`, like `Comedy|Drama`. Use genre names from the list above. `tmdb_id` can be blank. To find one, search themoviedb.org; the number is in the URL, e.g. `themoviedb.org/movie/862-toy-story`.
2. File → Download → CSV. Replace `database/titles.csv` with it.
3. Run `php tools/build_seed.php`. Partner A can do this step.
4. Re-import **both** `01_schema.sql` and `02_seed.sql`. ⚠️ This resets all user data, so do it before real testing starts, or add new titles with plain INSERTs after that.
