<?php
/**
 * Autoloader
 * تحميل الملفات تلقائياً
 */

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/helpers/',
        __DIR__ . '/models/',
        __DIR__ . '/middleware/',
        __DIR__ . '/controllers/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }

    return false;
});
