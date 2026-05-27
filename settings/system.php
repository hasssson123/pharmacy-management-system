<?php
/**
 * System Settings Page
 * إعدادات النظام
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('manage_settings')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'إعدادات النظام';
$currentPage = 'settings';

Auth::logActivity('عرض إعدادات النظام', '');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cog me-2"></i>إعدادات النظام
            </h1>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list" style="border: 1px solid #e0e0e0; border-radius: 8px 8px 0 0;">
                        <i class="fas fa-cogs me-2"></i>إعدادات عامة
                    </a>
                    <a href="#backup" class="list-group-item list-group-item-action" data-bs-toggle="list" style="border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0;">
                        <i class="fas fa-database me-2"></i>نسخ احتياطي
                    </a>
                    <a href="#logs" class="list-group-item list-group-item-action" data-bs-toggle="list" style="border: 1px solid #e0e0e0; border-radius: 0 0 8px 8px;">
                        <i class="fas fa-history me-2"></i>سجلات النشاط
                    </a>
                </div>
            </div>
            
            <div class="col-md-9">
                <!-- General Settings -->
                <div id="general" class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-cogs me-2"></i>الإعدادات العامة</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم التطبيق</label>
                                <input type="text" class="form-control" value="<?php echo APP_NAME; ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">إصدار التطبيق</label>
                                <input type="text" class="form-control" value="<?php echo APP_VERSION; ?>" disabled>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">عرض العناصر بكل صفحة</label>
                                <input type="number" class="form-control" value="<?php echo ITEMS_PER_PAGE; ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">منطقة زمنية</label>
                                <input type="text" class="form-control" value="<?php echo APP_TIMEZONE; ?>" disabled>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            لتغيير هذه الإعدادات، يجب تعديل ملف الإعدادات
                        </div>
                    </div>
                </div>
                
                <!-- Backup Settings -->
                <div id="backup" class="card" style="display: none;">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-database me-2"></i>نسخ احتياطي</h5>
                    </div>
                    <div class="card-body">
                        <p>النسخ الاحتياطي تلقائية من قاعدة البيانات</p>
                        <button class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>نزع نسخة احتياطية
                        </button>
                    </div>
                </div>
                
                <!-- Logs -->
                <div id="logs" class="card" style="display: none;">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-history me-2"></i>سجلات النشاط</h5>
                    </div>
                    <div class="card-body">
                        <p style="color: #999; margin-bottom: 20px;">آخر 100 نشاط</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th>المستخدم</th>
                                        <th>العملية</th>
                                        <th>الوقت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo Security::escapeOutput($user['name']); ?></td>
                                        <td>عرض مراقبة النظام</td>
                                        <td>برلائذ ما</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-bs-toggle="list"]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('href');
        
        document.querySelectorAll('[id^="general"], [id^="backup"], [id^="logs"]').forEach(el => {
            el.style.display = 'none';
        });
        
        document.querySelector(target).style.display = 'block';
        
        document.querySelectorAll('[data-bs-toggle="list"]').forEach(el => {
            el.classList.remove('active');
        });
        
        this.classList.add('active');
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
