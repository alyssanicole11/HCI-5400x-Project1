-- =====================================================================
-- JoyWatch - database schema (MySQL / MariaDB)
-- ---------------------------------------------------------------------
-- HOW TO USE
--   1. Open phpMyAdmin on Keymaker (or any MySQL tool) and select the
--      database the school gave you.
--   2. Import this file first, then 02_seed.sql.
--   Re-importing this file DELETES all tables and data (see DROP lines).
--
-- The big idea:
--   * `titles` + `genres` + `title_genres` = the movie/TV CATALOG.
--     Same for every user. Loaded from database/titles.csv (flat file)
--     and optionally filled in from the TMDb API (extra credit).
--   * `users`, `user_titles`, `user_genre_prefs` = PERSONAL data that
--     each user creates by filling out our forms.
--   Every personal row has a user_id, and every query we write must
--   say "WHERE user_id = <the logged-in user>".
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS user_genre_prefs;
DROP TABLE IF EXISTS user_titles;
DROP TABLE IF EXISTS title_genres;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS titles;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- users: one row per account (register.php creates these)
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username         VARCHAR(40)  NOT NULL,            -- used to log in
  display_name     VARCHAR(60)  NOT NULL,            -- shown in the header
  password_hash    VARCHAR(255) NOT NULL,            -- NEVER store the real password
  media_preference ENUM('both','movie','tv') NOT NULL DEFAULT 'both',
  created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- titles: the shared catalog of movies and TV shows
-- A title is uniquely identified by media_type + tmdb_id
-- (a movie and a TV show can share a name, e.g. "Fargo").
-- ---------------------------------------------------------------------
CREATE TABLE titles (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  media_type  ENUM('movie','tv') NOT NULL,
  tmdb_id     INT UNSIGNED NULL,                 -- The Movie Database ID (may be empty)
  title       VARCHAR(200) NOT NULL,
  year        SMALLINT UNSIGNED NULL,             -- release / first-air year
  runtime     SMALLINT UNSIGNED NULL,             -- minutes (TV = typical episode)
  overview    TEXT NULL,                          -- short synopsis
  poster_path VARCHAR(100) NULL,                  -- e.g. /abc123.jpg from TMDb (NULL = use placeholder)
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_titles_canonical (media_type, tmdb_id),
  KEY idx_titles_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- genres + title_genres: many-to-many (a title has many genres)
-- ---------------------------------------------------------------------
CREATE TABLE genres (
  id   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_genres_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE title_genres (
  title_id INT UNSIGNED NOT NULL,
  genre_id SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (title_id, genre_id),
  CONSTRAINT fk_tg_title FOREIGN KEY (title_id) REFERENCES titles(id) ON DELETE CASCADE,
  CONSTRAINT fk_tg_genre FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- user_titles: EVERYTHING a user has decided about one title.
-- One row per (user, title). A row exists once the user saves anything.
--
-- IMPORTANT MEANINGS (from the JoyWatch spec):
--   NULL means "not answered yet". It is different from every value.
--   rating 0 = "Meh" and is a REAL rating (never treat 0 as empty!).
--   interest 'not_interested' = hide this title from Trainer/Discover/Tonight.
--   Changing one field must never erase the other fields.
-- ---------------------------------------------------------------------
CREATE TABLE user_titles (
  user_id       INT UNSIGNED NOT NULL,
  title_id      INT UNSIGNED NOT NULL,
  watch_status  ENUM('not_yet','watching','watched') NULL,
  interest      ENUM('yes','unsure','not_interested') NULL,
  rating        TINYINT NULL,          -- 2 Love, 1 Like, 0 Meh, -1 Dislike, -2 Hate
  rewatch       ENUM('yes','maybe','no') NULL,
  own_dvd       TINYINT(1) NOT NULL DEFAULT 0,
  own_bluray    TINYINT(1) NOT NULL DEFAULT 0,
  own_4k        TINYINT(1) NOT NULL DEFAULT 0,
  own_digital   TINYINT(1) NOT NULL DEFAULT 0,
  notes         TEXT NULL,
  date_added    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_rated    DATETIME NULL,
  last_watched  DATE NULL,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, title_id),
  KEY idx_ut_title (title_id),
  CONSTRAINT fk_ut_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  CONSTRAINT fk_ut_title FOREIGN KEY (title_id) REFERENCES titles(id) ON DELETE CASCADE,
  CONSTRAINT chk_ut_rating CHECK (rating IS NULL OR rating BETWEEN -2 AND 2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- user_genre_prefs: "I love Comedy", "I hate Horror" (Taste page form)
-- ---------------------------------------------------------------------
CREATE TABLE user_genre_prefs (
  user_id    INT UNSIGNED NOT NULL,
  genre_id   SMALLINT UNSIGNED NOT NULL,
  rating     TINYINT NOT NULL,        -- same -2..2 scale as title ratings
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, genre_id),
  CONSTRAINT fk_ugp_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  CONSTRAINT fk_ugp_genre FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE,
  CONSTRAINT chk_ugp_rating CHECK (rating BETWEEN -2 AND 2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- STRETCH GOALS (not created yet - add only if we have time):
--   people(id, tmdb_person_id, name)
--   title_people(title_id, person_id, role)
--   user_people(user_id, person_id, rating)   -- "rate actors" on Taste page
-- ---------------------------------------------------------------------
