<?php
/**
 * CSRF Middleware
 * برمجية حماية CSRF
 */

class CSRFMiddleware {
    
    public function handle($request) {
        // Skip CSRF check for GET requests and API calls
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        // Check CSRF token for POST, PUT, DELETE requests
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!Security::verifyCSRFToken($token)) {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                Response::error('رمز الحماية غير صحيح', 403);
            } else {
                Response::flash('رمز الحماية غير صحيح. حاول مرة أخرى', 'danger');
                Response::redirect($_SERVER['HTTP_REFERER'] ?? APP_URL);
            }
            return false;
        }

        return true;
    }
}
