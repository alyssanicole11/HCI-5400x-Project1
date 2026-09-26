<?php
/** LOG OUT - forget the session, go home. STATUS: working */
require __DIR__ . '/includes/bootstrap.php';
logout_user();
session_start();
flash('You have been logged out.', 'info');
redirect('index.php');
