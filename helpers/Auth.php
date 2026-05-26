<?php
/**
 * Authentication Helper
 * مساعد المصادقة
 */

class Auth {
    
    /**
     * Login User
     * تسجيل دخول المستخدم
     */
    public static function login($username, $password, $rememberMe = false) {
        $db = Database::getInstance()->getConnection();

        // Check Brute Force
        if (!Security::checkBruteForce($username)) {
            return [
                'success' => false,
                'message' => 'تم تجاوز الحد الأقصى لمحاولات تسجيل الدخول. حاول لاحقاً.'
            ];
        }

        try {
            $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND status = 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user || !Security::verifyPassword($password, $user['password'])) {
                Security::incrementLoginAttempts($username);
                return [
                    'success' => false,
                    'message' => 'بيانات المستخدم غير صحيحة'
                ];
            }

            // Reset Login Attempts
            Security::resetLoginAttempts($username);

            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['company_id'] = $user['company_id'];
            $_SESSION['branch_id'] = $user['branch_id'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();

            // Log Activity
            self::logActivity('تسجيل دخول', 'المستخدم تم تسجيل الدخول بنجاح');

            return [
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح'
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'خطأ في قاعدة البيانات'
            ];
        }
    }

    /**
     * Logout User
     * تسجيل خروج المستخدم
     */
    public static function logout() {
        self::logActivity('تسجيل خروج', 'المستخدم تم تسجيل الخروج');
        session_destroy();
    }

    /**
     * Check if Logged In
     * التحقق من تسجيل الدخول
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Get Current User
     * الحصول على المستخدم الحالي
     */
    public static function user() {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'name' => $_SESSION['name'] ?? null,
            'role_id' => $_SESSION['role_id'] ?? null,
            'company_id' => $_SESSION['company_id'] ?? null,
            'branch_id' => $_SESSION['branch_id'] ?? null
        ];
    }

    /**
     * Check Permission
     * التحقق من الصلاحيات
     */
    public static function hasPermission($permission) {
        if (!self::isLoggedIn()) {
            return false;
        }

        $db = Database::getInstance()->getConnection();
        
        try {
            $stmt = $db->prepare(
                'SELECT COUNT(*) FROM role_permissions 
                WHERE role_id = ? AND permission_id = (SELECT id FROM permissions WHERE slug = ?)'
            );
            $stmt->execute([$_SESSION['role_id'], $permission]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Check Role
     * التحقق من الدور
     */
    public static function hasRole($roleId) {
        return self::isLoggedIn() && $_SESSION['role_id'] == $roleId;
    }

    /**
     * Log Activity
     * تسجيل النشاط
     */
    public static function logActivity($action, $details = '') {
        if (!self::isLoggedIn()) {
            return false;
        }

        $db = Database::getInstance()->getConnection();
        
        try {
            $stmt = $db->prepare(
                'INSERT INTO activity_logs (user_id, company_id, branch_id, action, details, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            return $stmt->execute([
                $_SESSION['user_id'],
                $_SESSION['company_id'],
                $_SESSION['branch_id'],
                $action,
                $details,
                $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}