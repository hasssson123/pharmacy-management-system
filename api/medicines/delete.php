<?php
/**
 * API: Delete Medicine
 * حذف دواء
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || !Auth::hasPermission('delete_medicine')) {
    Response::error('ليس لديك صلاحية للقيام بهذه العملية', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error('الطلب غير صحيح', 400);
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $medicineId = (int) ($data['id'] ?? 0);
    
    if (empty($medicineId)) {
        Response::error('معرف الدواء مطلوب', 400);
    }
    
    $medicineModel = new Medicine();
    $medicine = $medicineModel->find($medicineId);
    
    if (!$medicine || $medicine['company_id'] != Auth::user()['company_id']) {
        Response::error('الدواء غير موجود', 404);
    }
    
    if ($medicineModel->delete($medicineId)) {
        Auth::logActivity('حذف دواء', 'تم حذف الدواء: ' . $medicine['name']);
        Response::success('تم حذف الدواء بنجاح');
    } else {
        Response::error('حدث خطأ في حذف الدواء', 500);
    }
    
} catch (Exception $e) {
    Response::error('خطأ في الخادم: ' . $e->getMessage(), 500);
}
