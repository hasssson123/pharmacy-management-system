<?php
/**
 * Database Installer
 * منصب قاعدة البيانات
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

class DatabaseInstaller {
    
    public static function install() {
        try {
            $db = Database::getInstance()->getConnection();
            $schema = require __DIR__ . '/schema.sql';
            
            // Execute schema
            $statements = array_filter(array_map('trim', explode(';', $schema)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $db->exec($statement);
                }
            }

            return [
                'success' => true,
                'message' => 'تم تثبيت قاعدة البيانات بنجاح'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'خطأ في تثبيت قاعدة البيانات: ' . $e->getMessage()
            ];
        }
    }
}

// Run installer if called directly
if (php_sapi_name() === 'cli' || !empty($_GET['install'])) {
    $result = DatabaseInstaller::install();
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}
