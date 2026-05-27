<?php
/**
 * API: Get Sales Reports
 * الحصول على تقارير المبيعات
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_reports')) {
    Response::error('غير مرخص', 403);
}

try {
    $from = Security::sanitizeInput($_GET['from'] ?? date('Y-m-01'));
    $to = Security::sanitizeInput($_GET['to'] ?? date('Y-m-d'));
    
    $user = Auth::user();
    $db = Database::getInstance()->getConnection();
    
    // Get sales data (placeholder - would need actual sales table)
    $stmt = $db->prepare(
        'SELECT COUNT(*) as count, SUM(CAST(0 as DECIMAL)) as total 
         FROM stock_movements 
         WHERE branch_id = ? AND movement_type = "out" 
         AND DATE(created_at) BETWEEN ? AND ?'
    );
    $stmt->execute([$user['branch_id'], $from, $to]);
    $result = $stmt->fetch();
    
    Response::success('تم الحصول على البيانات بنجاح', [
        'total' => (float) ($result['total'] ?? 0),
        'count' => (int) ($result['count'] ?? 0),
        'topProduct' => 'لم يتم التحديث بعد',
        'transactions' => []
    ]);
    
} catch (Exception $e) {
    Response::error('خطأ: ' . $e->getMessage(), 500);
}
