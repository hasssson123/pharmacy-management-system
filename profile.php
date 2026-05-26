<?php
/**
 * Profile Page
 * الملف الشخصي
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/autoload.php';

if (!Auth::isLoggedIn()) {
    Response::redirect(APP_URL . '/login.php');
}

$user = Auth::user();
$userModel = new User();
$userData = $userModel->find($user['id']);
$pageTitle = 'ملفي الشخصي';
$currentPage = 'profile';

require_once __DIR__ . '/layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user me-2"></i>ملفي الشخصي
            </h1>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card" style="text-align: center;">
                    <div class="card-body">
                        <div style="width: 120px; height: 120px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 50px; font-weight: bold;">
                            <?php echo strtoupper(substr($userData['full_name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <h4><?php echo Security::escapeOutput($userData['full_name']); ?></h4>
                        <p style="color: #999; margin-bottom: 20px;"><?php echo Security::escapeOutput($userData['username']); ?></p>
                        <p style="font-size: 13px; color: #666;">
                            <strong>البريد الإلكتروني:</strong><br>
                            <?php echo Security::escapeOutput($userData['email']); ?>
                        </p>
                        <p style="font-size: 13px; color: #666;">
                            <strong>رقم الهاتف:</strong><br>
                            <?php echo Security::escapeOutput($userData['phone']); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;">معلومات الحساب</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">الاسم بالكامل</p>
                                <p style="margin: 0;"><?php echo Security::escapeOutput($userData['full_name']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">اسم المستخدم</p>
                                <p style="margin: 0;"><?php echo Security::escapeOutput($userData['username']); ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">البريد الإلكتروني</p>
                                <p style="margin: 0;"><?php echo Security::escapeOutput($userData['email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">رقم الهاتف</p>
                                <p style="margin: 0;"><?php echo Security::escapeOutput($userData['phone']); ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">تاريخ الانضمام</p>
                                <p style="margin: 0;"><?php echo date(DISPLAY_DATE_FORMAT, strtotime($userData['created_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p style="color: #999; font-size: 12px; margin-bottom: 5px;">آخر تسجيل دخول</p>
                                <p style="margin: 0;"><?php echo $userData['last_login'] ? date(DISPLAY_DATETIME_FORMAT, strtotime($userData['last_login'])) : 'لم يتم'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
