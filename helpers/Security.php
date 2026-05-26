<?php
/**
 * Security Helper
 * فئة الحماية الأمنية
 */

class Security {
    
    /**
     * Generate CSRF Token
     * إنشاء رمز CSRF
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF Token
     * التحقق من رمز CSRF
     */
    public static function verifyCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Hash Password
     * تشفير كلمة المرور
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify Password
     * التحقق من كلمة المرور
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Sanitize Input
     * تنظيف المدخلات
     */
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape Output
     * الحماية من XSS
     */
    public static function escapeOutput($output) {
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate Email
     * التحقق من البريد الإلكتروني
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate Phone Number
     * التحقق من رقم الهاتف
     */
    public static function validatePhone($phone) {
        return preg_match('/^[0-9]{10,15}$/', preg_replace('/[^0-9]/', '', $phone));
    }

    /**
     * Generate Random String
     * إنشاء نص عشوائي
     */
    public static function generateRandomString($length = 20) {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Check Brute Force
     * التحقق من محاولات تسجيل الدخول المتكررة
     */
    public static function checkBruteForce($username) {
        $db = Database::getInstance()->getConnection();
        $key = 'login_attempts_' . $username;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = 0;
            $_SESSION[$key . '_time'] = time();
            return true;
        }

        if (time() - $_SESSION[$key . '_time'] > LOGIN_ATTEMPT_TIMEOUT) {
            $_SESSION[$key] = 0;
            $_SESSION[$key . '_time'] = time();
            return true;
        }

        if ($_SESSION[$key] >= MAX_LOGIN_ATTEMPTS) {
            return false;
        }

        return true;
    }

    /**
     * Increment Login Attempts
     * زيادة محاولات تسجيل الدخول
     */
    public static function incrementLoginAttempts($username) {
        $key = 'login_attempts_' . $username;
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
        $_SESSION[$key . '_time'] = time();
    }

    /**
     * Reset Login Attempts
     * إعادة تعيين محاولات تسجيل الدخول
     */
    public static function resetLoginAttempts($username) {
        $key = 'login_attempts_' . $username;
        unset($_SESSION[$key]);
        unset($_SESSION[$key . '_time']);
    }
}