<?php
/**
 * Login Page
 * صفحة تسجيل الدخول
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/autoload.php';

// If already logged in, redirect to dashboard
if (Auth::isLoggedIn()) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$error = '';
$success = '';

// Handle Login Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator($_POST);
    $validator->required('username', 'اسم المستخدم مطلوب')
              ->required('password', 'كلمة المرور مطلوبة');

    if ($validator->fails()) {
        $errors = $validator->getErrors();
        $error = implode(', ', $errors[array_key_first($errors)]);
    } else {
        $username = Security::sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        $result = Auth::login($username, $password);
        
        if ($result['success']) {
            Response::flash('تم تسجيل الدخول بنجاح', 'success');
            Response::redirect(APP_URL . '/dashboard.php');
        } else {
            $error = $result['message'];
        }
    }
}

// Get Flash Message
$flash = Response::getFlash();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - <?php echo APP_NAME; ?></title>
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
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            height: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            pointer-events: none;
        }
        
        .form-control-with-icon {
            padding-right: 45px;
        }
        
        .btn-login {
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            font-size: 13px;
        }
        
        .form-check {
            margin: 0;
        }
        
        .form-check-input {
            cursor: pointer;
            border: 2px solid #e0e0e0;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #666;
        }
        
        .loading-spinner {
            display: none;
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>
                    <i class="fas fa-pills"></i>
                    نظام الصيدليات
                </h1>
                <p>نظام إدارة صيدليات احترافي</p>
            </div>
            
            <div class="login-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo Security::escapeOutput($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($flash['message']) && $flash['type'] === 'warning'): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo Security::escapeOutput($flash['message']); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm">
                    <div class="form-group">
                        <label for="username" class="form-label mb-2">اسم المستخدم</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-with-icon" 
                                   id="username" name="username" placeholder="أدخل اسم المستخدم" 
                                   required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                            <span class="input-icon">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label mb-2">كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-with-icon" 
                                   id="password" name="password" placeholder="أدخل كلمة المرور" required>
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>
                        <a href="#" class="forgot-link">هل نسيت كلمة المرور؟</a>
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="loginBtn">
                        <span id="btnText">دخول</span>
                        <span class="loading-spinner" id="spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </form>
            </div>
            
            <div class="login-footer">
                <p style="margin: 0;">
                    جميع الحقوق محفوظة © 2024
                    <strong><?php echo APP_NAME; ?></strong>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');
            
            btn.disabled = true;
            btnText.style.display = 'none';
            spinner.style.display = 'inline';
        });
    </script>
</body>
</html>
