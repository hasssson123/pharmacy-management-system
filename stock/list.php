<?php
/**
 * Stock List Page
 * قائمة المخزون
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_stock')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'المخزون';
$currentPage = 'stock';

$stockModel = new Stock();
$branchStock = $stockModel->getBranchStock($user['branch_id'], ITEMS_PER_PAGE);
$lowStock = $stockModel->getLowStock($user['branch_id']);

Auth::logActivity('عرض المخزون', '');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-warehouse me-2"></i>المخزون الحالي
            </h1>
        </div>
        
        <!-- Low Stock Alert -->
        <?php if (!empty($lowStock)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>تنبيه!</strong> هنالك <?php echo count($lowStock); ?> أدوية ناقصة المخزون
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Stock Table -->
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;"><i class="fas fa-boxes me-2"></i>كميات المواد</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>المواد</th>
                                <th>الباركود</th>
                                <th>الكمية الموجودة</th>
                                <th>المحجوزة</th>
                                <th>المتاحة</th>
                                <th>الحد الأدنى</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($branchStock)): ?>
                                <?php foreach ($branchStock as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo Security::escapeOutput($item['name']); ?></strong><br>
                                        <small style="color: #999;"><?php echo Security::escapeOutput($item['ar_name']); ?></small>
                                    </td>
                                    <td><code><?php echo Security::escapeOutput($item['barcode']); ?></code></td>
                                    <td><?php echo (int) $item['quantity']; ?></td>
                                    <td><?php echo (int) $item['reserved_quantity']; ?></td>
                                    <td>
                                        <?php 
                                        $available = $item['available_quantity'];
                                        $status = '';
                                        if ($available <= 0) {
                                            $status = 'danger';
                                        } elseif ($available <= $item['min_stock']) {
                                            $status = 'warning';
                                        } else {
                                            $status = 'success';
                                        }
                                        ?>
                                        <span class="badge" style="background: <?php echo $status === 'danger' ? '#ef4444' : ($status === 'warning' ? '#f59e0b' : '#10b981'); ?>;"><strong><?php echo (int) $available; ?></strong></span>
                                    </td>
                                    <td><?php echo (int) $item['min_stock']; ?></td>
                                    <td><?php echo number_format($item['price'], 2); ?> <?php echo CURRENCY_SYMBOL; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #999; padding: 40px;">
                                        لا توجد مواد في المخزون
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
