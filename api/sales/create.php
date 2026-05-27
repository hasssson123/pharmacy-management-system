<?php
/**
 * API: Create Sale
 * إنشاء بيع
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || !Auth::hasPermission('create_sale')) {
    Response::error('غير مرخص', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error('طلب غير صحيح', 400);
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    $discount = (float) ($data['discount'] ?? 0);
    
    if (empty($items)) {
        Response::error('لا يوجد أصناف للبيع', 400);
    }
    
    $user = Auth::user();
    $db = Database::getInstance()->getConnection();
    
    $db->beginTransaction();
    
    $total = 0;
    $stockModel = new Stock();
    
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
        
        // Reduce stock
        if (!$stockModel->reduceStock($user['branch_id'], $item['id'], $item['quantity'])) {
            throw new Exception('فشل تقليل المخزون');
        }
        
        // Record stock movement
        $stmt = $db->prepare(
            'INSERT INTO stock_movements (branch_id, medicine_id, movement_type, quantity, reference_type, created_by) 
             VALUES (?, ?, "out", ?, "sale", ?)'
        );
        $stmt->execute([$user['branch_id'], $item['id'], $item['quantity'], $user['id']]);
    }
    
    $db->commit();
    
    Auth::logActivity('بيع جديد', 'مبلغ: ' . $total);
    Response::success('تم إنماء البيع بنجاح');
    
} catch (Exception $e) {
    $db->rollBack();
    Response::error('خطأ: ' . $e->getMessage(), 500);
}
