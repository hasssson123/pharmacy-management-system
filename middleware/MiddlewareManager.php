<?php
/**
 * Middleware Manager
 * مدير البرمجيات الوسيطة
 */

class MiddlewareManager {
    
    private $middlewares = [];
    private $request;

    public function __construct() {
        $this->request = $_SERVER['REQUEST_URI'];
    }

    /**
     * Register Middleware
     * تسجيل البرمجية الوسيطة
     */
    public function register($middleware) {
        if (class_exists($middleware)) {
            $this->middlewares[] = new $middleware();
        }
        return $this;
    }

    /**
     * Execute Middlewares
     * تنفيذ البرمجيات الوسيطة
     */
    public function execute() {
        foreach ($this->middlewares as $middleware) {
            if (method_exists($middleware, 'handle')) {
                $result = $middleware->handle($this->request);
                if ($result !== true) {
                    return $result;
                }
            }
        }
        return true;
    }
}
