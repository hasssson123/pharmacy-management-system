<?php
/**
 * Installation Page
 * صفحة التثبيت
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/autoload.php';

$message = '';
$error = '';
$installed = false;

// Check if already installed
$checkFile = __DIR__ . '/.installed';
if (file_exists($checkFile)) {
    $installed = true;
}

if (!$installed && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form
    $validator = new Validator($_POST);
    $validator->required('company_name', 'اسم الشركة مطلوب')
              ->required('admin_username', 'اسم المستخدم مطلوب')
              ->required('admin_email', 'البريد الإلكتروني مطلوب')
              ->email('admin_email', 'البريد الإلكتروني غير صحيح')
              ->required('admin_password', 'كلمة المرور مطلوبة')
              ->minLength('admin_password', 6, 'كلمة المرور يجب أن لا تقل عن 6 أحرف');
    
    if ($validator->passes()) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Create company
            $stmt = $db->prepare(
                'INSERT INTO companies (name, ar_name, email, status) VALUES (?, ?, ?, "active")'
            );
            $stmt->execute([
                Security::sanitizeInput($_POST['company_name']),
                Security::sanitizeInput($_POST['company_name']),
                Security::sanitizeInput($_POST['admin_email'])
            ]);
            $companyId = $db->lastInsertId();
            
            // Create branch
            $stmt = $db->prepare(
                'INSERT INTO branches (company_id, name, ar_name, status) VALUES (?, ?, ?, "active")'
            );
            $stmt->execute([
                $companyId,
                'الفرع الرئيسي',
                'الفرع الرئيسي'
            ]);
            $branchId = $db->lastInsertId();
            
            // Create admin user
            $stmt = $db->prepare(
                'INSERT INTO users (company_id, branch_id, role_id, username, email, password, full_name, status) 
                 VALUES (?, ?, 2, ?, ?, ?, ?, "active")'
            );
            $stmt->execute([
                $companyId,
                $branchId,
                Security::sanitizeInput($_POST['admin_username']),
                Security::sanitizeInput($_POST['admin_email']),
                Security::hashPassword($_POST['admin_password']),
                Security::sanitizeInput($_POST['admin_name'] ?? $_POST['admin_username'])
            ]);
            
            // Insert default permissions
            $permissions = [
                ['view_medicines', 'عرض الأدوية'],
                ['create_medicine', 'إضافة دواء'],
                ['edit_medicine', 'تعديل الأدوية'],
                ['delete_medicine', 'حذف الأدوية'],
                ['view_stock', 'عرض المخزون'],
                ['manage_stock', 'إدارة المخزون'],
                ['view_sales', 'عرض المبيعات'],
                ['create_sale', 'إنشاء بيع جديد'],
                ['view_purchases', 'عرض المشتريات'],
                ['create_purchase', 'إنشاء مشتريات'],
                ['view_customers', 'عرض العملاء'],
                ['manage_users', 'إدارة المستخدمين'],
                ['view_reports', 'عرض التقارير'],
                ['manage_settings', 'إدارة الإعدادات']
            ];
            
            foreach ($permissions as $perm) {
                $stmt = $db->prepare(
                    'INSERT IGNORE INTO permissions (name, slug, description) VALUES (?, ?, ?)'
                );
                $stmt->execute([$perm[1], $perm[0], $perm[1]]);
            }
            
            // Grant all permissions to Company Admin role
            $stmt = $db->prepare('SELECT id FROM permissions');
            $stmt->execute();
            $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $stmt = $db->prepare('INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)');
            foreach ($perms as $permId) {
                $stmt->execute([2, $permId]);
            }
            
            // Create .installed file
            file_put_contents($checkFile, date('Y-m-d H:i:s'));
            
            $message = 'تم تثبيت النظام بنجاح! يمكنك الآن تسجيل الدخول.';
            $installed = true;
        } catch (Exception $e) {
            $error = 'خطأ في التثبيت: ' . $e->getMessage();
        }
    } else {
        $errors = $validator->getErrors();
        $error = implode(', ', array_merge(...array_values($errors)));
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .setup-container {
            width: 100%;
            max-width: 600px;
        }
        
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .setup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .setup-body {
            padding: 40px;
        }
        
        .success-message {
            text-align: center;
            padding: 40px;
        }
        
        .success-icon {
            font-size: 80px;
            color: #10b981;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <?php if ($installed && $message): ?>
                <div class="setup-header">
                    <h1><i class="fas fa-check-circle"></i> نجح التثبيت</h1>
                </div>
                <div class="success-message">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3><?php echo $message; ?></h3>
                    <p style="color: #999; margin: 20px 0;">بيانات العبور:</p>
                    <p style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: right;">
                        <strong>اسم المستخدم:</strong> 
                        <?php echo Security::escapeOutput($_POST['admin_username'] ?? ''); ?><br>
                        <strong>البريد الإلكتروني:</strong> 
                        <?php echo Security::escapeOutput($_POST['admin_email'] ?? ''); ?>
                    </p>
                    <a href="<?php echo APP_URL; ?>/login.php" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <i class="fas fa-sign-in-alt me-2"></i>دخول الآن
                    </a>
                </div>
            <?php elseif ($installed): ?>
                <div class="setup-header">
                    <h1><i class="fas fa-check"></i> تم التثبيت بالفعل</h1>
                </div>
                <div class="setup-body" style="text-align: center;">
                    <p style="color: #999;">Nالنظام مثبت بالفعل. يمكنك الآن تسجيل الدخول.</p>
                    <a href="<?php echo APP_URL; ?>/login.php" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; margin-top: 20px;">
                        اذهب لتسجيل الدخول
                    </a>
                </div>
            <?php else: ?>
                <div class="setup-header">
                    <h1><i class="fas fa-cogs"></i> تثبيت النظام</h1>
                    <p>قم بإرمال بيانات العبور التالية</p>
                </div>
                <div class="setup-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">اسم الشركة</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_name" class="form-label">اسم المالك</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                            <i class="fas fa-check me-2"></i>بدء التثبيت
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
