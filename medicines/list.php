<?php
/**
 * Medicines List Page
 * قائمة الأدوية
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_medicines')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'الأدوية';
$currentPage = 'medicines';

// Get medicines
$medicineModel = new Medicine();
$medicines = $medicineModel->getCompanyMedicines($user['company_id'], ITEMS_PER_PAGE);

Auth::logActivity('عرض قائمة الأدوية', '');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-pills me-2"></i>جميع الأدوية
                    </h1>
                </div>
                <?php if (Auth::hasPermission('create_medicine')): ?>
                <a href="<?php echo APP_URL; ?>/medicines/create.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة دواء جديد
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchInput" placeholder="ابحث بالاسم أو الباركود...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="statusFilter">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary w-100" id="resetFilter">
                            <i class="fas fa-redo me-2"></i>إعادة تعيين
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medicines Table -->
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;"><i class="fas fa-list me-2"></i>قائمة الأدوية</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="medicinesTable">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="font-weight: 600;">الاسم</th>
                                <th style="font-weight: 600;">الباركود</th>
                                <th style="font-weight: 600;">السعر</th>
                                <th style="font-weight: 600;">الشركة المصنعة</th>
                                <th style="font-weight: 600;">تاريخ الانتهاء</th>
                                <th style="font-weight: 600;">الحالة</th>
                                <th style="font-weight: 600;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($medicines)): ?>
                                <?php foreach ($medicines as $medicine): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo Security::escapeOutput($medicine['name']); ?></strong><br>
                                        <small style="color: #999;"><?php echo Security::escapeOutput($medicine['ar_name']); ?></small>
                                    </td>
                                    <td><code><?php echo Security::escapeOutput($medicine['barcode']); ?></code></td>
                                    <td><?php echo number_format($medicine['price'], 2); ?> <?php echo CURRENCY_SYMBOL; ?></td>
                                    <td><?php echo Security::escapeOutput($medicine['manufacturer']); ?></td>
                                    <td>
                                        <?php 
                                        if ($medicine['expiry_date']) {
                                            $expiryDate = new DateTime($medicine['expiry_date']);
                                            $today = new DateTime();
                                            if ($expiryDate < $today) {
                                                echo '<span class="badge" style="background: #ef4444;">منتهي</span>';
                                            } else if ($expiryDate->diff($today)->days <= 30) {
                                                echo '<span class="badge" style="background: #f59e0b;">قريب</span>';
                                            } else {
                                                echo date(DISPLAY_DATE_FORMAT, strtotime($medicine['expiry_date']));
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($medicine['status'] === 'active'): ?>
                                            <span class="badge" style="background: #10b981;">نشط</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: #999;">غير نشط</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (Auth::hasPermission('edit_medicine')): ?>
                                        <a href="<?php echo APP_URL; ?>/medicines/edit.php?id=<?php echo $medicine['id']; ?>" class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if (Auth::hasPermission('delete_medicine')): ?>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $medicine['id']; ?>" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #999; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 20px; display: block; opacity: 0.5;"></i>
                                        لا توجد أدوية بعد
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

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذا الدواء؟')) {
            const id = this.dataset.id;
            makeRequest('<?php echo APP_URL; ?>/api/medicines/delete.php', 'POST', {id})
                .then(response => {
                    if (response.success) {
                        showToast('تم حذف الدواء بنجاح', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(response.message, 'danger');
                    }
                })
                .catch(error => showToast('حدث خطأ', 'danger'));
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
