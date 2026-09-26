<?php
/**
 * CONFIG TEMPLATE
 * ---------------------------------------------------------------
 * 1. Make a copy of this file named  config.php  (same folder).
 * 2. Fill in the database login that Keymaker gives us.
 * 3. NEVER put config.php on GitHub or in a public Google Drive link -
 *    it contains our database password. (.gitignore already skips it.)
 */

// ---- MySQL database connection -------------------------------------
define('DB_HOST', 'localhost');     // Keymaker will tell us this (often "localhost")
define('DB_NAME', 'joywatch');      // our database name
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

// ---- The Movie Database (TMDb) - OPTIONAL, extra credit ------------
// Get a free "API Read Access Token" at https://www.themoviedb.org/settings/api
// Leave it '' and the site uses only our own MySQL catalog.
define('TMDB_TOKEN', '');

// ---- Debug mode ------------------------------------------------------
// true  = show detailed PHP/database errors (use while building)
// false = show a friendly message only (use for the final submission)
define('DEBUG', true);
