<?php
/**
 * Authentication Middleware
 * برمجية التحقق من المصادقة
 */

class AuthMiddleware {
    
    public function handle($request) {
        // Allow login and register pages
        if (strpos($request, '/login') !== false || 
            strpos($request, '/register') !== false ||
            strpos($request, '/api/login') !== false) {
            return true;
        }

        // Check if user is logged in
        if (!Auth::isLoggedIn()) {
            Response::flash('يرجى تسجيل الدخول أولاً', 'warning');
            Response::redirect(APP_URL . '/login.php');
            return false;
        }

        return true;
    }
}
