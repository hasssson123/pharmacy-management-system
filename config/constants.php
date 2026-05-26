<?php
/**
 * Application Constants
 * الثوابت العامة للنظام
 */

// Application Settings
define('APP_NAME', 'نظام إدارة الصيدليات');
define('APP_VERSION', '1.0.0');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');
define('APP_TIMEZONE', 'Asia/Riyadh');

// Security Settings
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('CSRF_TOKEN_LENGTH', 32);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 900); // 15 minutes

// File Uploads
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// User Roles
define('ROLE_SUPER_ADMIN', 1);
define('ROLE_COMPANY_ADMIN', 2);
define('ROLE_BRANCH_MANAGER', 3);
define('ROLE_PHARMACIST', 4);
define('ROLE_CASHIER', 5);

// Date Format
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd/m/Y');
define('DISPLAY_DATETIME_FORMAT', 'd/m/Y H:i');

// Pagination
define('ITEMS_PER_PAGE', 15);

// Currency
define('CURRENCY', 'SR'); // Saudi Riyal
define('CURRENCY_SYMBOL', 'ر.س');
