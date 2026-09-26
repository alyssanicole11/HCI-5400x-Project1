<?php
/**
 * EXPORT MY LIBRARY AS CSV  (a file download, opens in Excel/Google Sheets)
 * STATUS: STUB     OWNER: either of us (small, good first PHP task)
 *
 * TODO:
 *   1. $rows = db_all('SELECT t.title, t.media_type, t.year, ut.watch_status, ut.interest,
 *                             ut.rating, ut.rewatch, ut.own_dvd, ut.own_bluray, ut.own_4k,
 *                             ut.own_digital, ut.notes, ut.date_added, ut.last_watched
 *                      FROM user_titles ut JOIN titles t ON t.id = ut.title_id
 *                      WHERE ut.user_id = ? ORDER BY t.title', [current_user_id()]);
 *   2. Tell the browser a file is coming:
 *        header('Content-Type: text/csv; charset=utf-8');
 *        header('Content-Disposition: attachment; filename="joywatch-library.csv"');
 *   3. $out = fopen('php://output', 'w');
 *      fputcsv($out, array_keys($rows[0]));   // header row (check $rows isn't empty!)
 *      foreach ($rows as $row) { fputcsv($out, $row); }
 *   Do NOT include header.php here - the output must be only CSV.
 */
require __DIR__ . '/includes/bootstrap.php';
require_login();

flash('🚧 Export is not built yet.', 'info');
redirect('settings.php');
