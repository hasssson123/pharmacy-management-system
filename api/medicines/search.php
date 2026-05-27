<?php
/**
 * API: Search Medicine by Barcode
 * بحث عن منتج بالباركود
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_medicines')) {
    Response::error('غير مرخص', 403);
}

try {
    $barcode = Security::sanitizeInput($_GET['barcode'] ?? '');
    
    if (empty($barcode)) {
        Response::error('الباركود مطلوب', 400);
    }
    
    $user = Auth::user();
    $medicineModel = new Medicine();
    $medicine = $medicineModel->findByBarcode($barcode, $user['company_id']);
    
    if (!$medicine) {
        Response::error('لم يتم العثور على المنتج', 404);
    }
    
    // Check stock
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare(
        'SELECT available_quantity FROM stock WHERE branch_id = ? AND medicine_id = ?'
    );
    $stmt->execute([$user['branch_id'], $medicine['id']]);
    $stock = $stmt->fetch();
    
    if (!$stock || $stock['available_quantity'] <= 0) {
        Response::error('المنتج غير متوفر', 400);
    }
    
    Response::success('تم العثور على المنتج', [
        'id' => $medicine['id'],
        'name' => $medicine['name'],
        'ar_name' => $medicine['ar_name'],
        'price' => (float) $medicine['price'],
        'available' => (int) $stock['available_quantity']
    ]);
    
} catch (Exception $e) {
    Response::error('خطأ: ' . $e->getMessage(), 500);
}
