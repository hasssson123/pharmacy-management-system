<?php
/**
 * Dashboard Page
 * لوحة التحكم
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/autoload.php';

// Check Authentication
if (!Auth::isLoggedIn()) {
    Response::redirect(APP_URL . '/login.php');
}

// Get Current User
$user = Auth::user();
$pageTitle = 'لوحة التحكم';
$currentPage = 'dashboard';

// Log Activity
Auth::logActivity('زيارة لوحة التحكم', '');

// Get Statistics
$db = Database::getInstance()->getConnection();

try {
    // Total Medicines
    $stmt = $db->prepare('SELECT COUNT(*) FROM medicines WHERE company_id = ? AND status = "active"');
    $stmt->execute([$user['company_id']]);
    $totalMedicines = $stmt->fetchColumn();
    
    // Low Stock Medicines
    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM stock s 
         JOIN medicines m ON s.medicine_id = m.id 
         WHERE m.company_id = ? AND s.branch_id = ? AND s.available_quantity <= m.min_stock'
    );
    $stmt->execute([$user['company_id'], $user['branch_id']]);
    $lowStockCount = $stmt->fetchColumn();
    
    // Expired Medicines
    $expiredMeds = new Medicine();
    $expiredMedicines = $expiredMeds->getExpiredMedicines($user['company_id']);
    $expiredCount = count($expiredMedicines);
    
    // Total Users
    $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE company_id = ? AND status = "active"');
    $stmt->execute([$user['company_id']]);
    $totalUsers = $stmt->fetchColumn();
    
} catch (PDOException $e) {
    $totalMedicines = 0;
    $lowStockCount = 0;
    $expiredCount = 0;
    $totalUsers = 0;
}

require_once __DIR__ . '/layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/layouts/navbar.php'; ?>
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line me-2"></i>
                مرحباً بك <?php echo Security::escapeOutput($user['name']); ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">لوحة التحكم</li>
                </ol>
            </nav>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Medicines Card -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border-top: 4px solid #667eea;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">جميع الأدوية</h6>
                                <h2 style="margin: 0; color: #667eea; font-weight: bold;"><?php echo $totalMedicines; ?></h2>
                            </div>
                            <div style="font-size: 40px; color: #667eea; opacity: 0.2;">
                                <i class="fas fa-pills"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Low Stock Card -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border-top: 4px solid #f59e0b;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">أدوية ناقصة</h6>
                                <h2 style="margin: 0; color: #f59e0b; font-weight: bold;"><?php echo $lowStockCount; ?></h2>
                            </div>
                            <div style="font-size: 40px; color: #f59e0b; opacity: 0.2;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expired Medicines Card -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border-top: 4px solid #ef4444;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">أدوية منتهية</h6>
                                <h2 style="margin: 0; color: #ef4444; font-weight: bold;"><?php echo $expiredCount; ?></h2>
                            </div>
                            <div style="font-size: 40px; color: #ef4444; opacity: 0.2;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Users Card -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border-top: 4px solid #10b981;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">إجمالي المستخدمين</h6>
                                <h2 style="margin: 0; color: #10b981; font-weight: bold;"><?php echo $totalUsers; ?></h2>
                            </div>
                            <div style="font-size: 40px; color: #10b981; opacity: 0.2;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-lightning-bolt me-2"></i>إجراءات سريعة</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (Auth::hasPermission('create_medicine')): ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="<?php echo APP_URL; ?>/medicines/create.php" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>إضافة دواء جديد
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (Auth::hasPermission('create_sale')): ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="<?php echo APP_URL; ?>/sales/pos.php" class="btn btn-primary w-100">
                                    <i class="fas fa-cash-register me-2"></i>بدء بيع جديد
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (Auth::hasPermission('create_purchase')): ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="<?php echo APP_URL; ?>/purchases/create.php" class="btn btn-primary w-100">
                                    <i class="fas fa-shopping-cart me-2"></i>عملية شراء جديدة
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (Auth::hasPermission('manage_users')): ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="<?php echo APP_URL; ?>/users/create.php" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>إضافة مستخدم جديد
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-history me-2"></i>آخر الأنشطة</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" style="margin-bottom: 0;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="font-weight: 600;">العملية</th>
                                        <th style="font-weight: 600;">التفاصيل</th>
                                        <th style="font-weight: 600;">الاسم البريدي</th>
                                        <th style="font-weight: 600;">الوقت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge" style="background: #667eea;">تسجيل دخول</span></td>
                                        <td>تم تسجيل الدخول بنجاح</td>
                                        <td><?php echo Security::escapeOutput($user['email']); ?></td>
                                        <td>الآن</td>
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

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
