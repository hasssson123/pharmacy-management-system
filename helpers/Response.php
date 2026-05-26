<?php
/**
 * Response Helper
 * مساعد الرد على الطلبات
 */

class Response {
    
    /**
     * JSON Response
     * الرد بصيغة JSON
     */
    public static function json($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Success Response
     * رد النجاح
     */
    public static function success($message, $data = null) {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Error Response
     * رد الخطأ
     */
    public static function error($message, $statusCode = 400, $errors = null) {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Redirect
     * إعادة التوجيه
     */
    public static function redirect($url) {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Flash Message
     * رسالة مؤقتة
     */
    public static function flash($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    /**
     * Get Flash Message
     * الحصول على الرسالة المؤقتة
     */
    public static function getFlash() {
        $message = $_SESSION['flash_message'] ?? null;
        $type = $_SESSION['flash_type'] ?? 'info';
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        return ['message' => $message, 'type' => $type];
    }
}