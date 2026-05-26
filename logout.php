<?php
/**
 * Logout Handler
 * معالج تسجيل الخروج
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/autoload.php';

// Logout
Auth::logout();

// Redirect to Login
Response::flash('تم تسجيل الخروج بنجاح', 'success');
Response::redirect(APP_URL . '/login.php');
