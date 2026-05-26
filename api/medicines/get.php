<?php
/**
 * API: Get Medicines
 * الحصول على الأدوية
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_medicines')) {
    Response::error('ليس لديك صلاحية للوصول إلى هذا الملف', 403);
}

try {
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $limit = min(100, (int) ($_GET['limit'] ?? ITEMS_PER_PAGE));
    $offset = ($page - 1) * $limit;
    $search = Security::sanitizeInput($_GET['search'] ?? '');
    $status = Security::sanitizeInput($_GET['status'] ?? 'active');
    
    $user = Auth::user();
    $db = Database::getInstance()->getConnection();
    
    $query = 'SELECT * FROM medicines WHERE company_id = ?';
    $params = [$user['company_id']];
    
    if (!empty($status)) {
        $query .= ' AND status = ?';
        $params[] = $status;
    }
    
    if (!empty($search)) {
        $query .= ' AND (name LIKE ? OR ar_name LIKE ? OR barcode LIKE ?)';
        $searchTerm = '%' . $search . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Get total count
    $countStmt = $db->prepare(str_replace('SELECT *', 'SELECT COUNT(*)', $query));
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();
    
    // Get paginated results
    $query .= ' ORDER BY name ASC LIMIT ? OFFSET ?';
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $medicines = $stmt->fetchAll();
    
    Response::success('تم الحصول على الأدوية بنجاح', [
        'medicines' => $medicines,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
    
} catch (Exception $e) {
    Response::error('خطأ في الخادم: ' . $e->getMessage(), 500);
}
