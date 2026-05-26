<?php
/**
 * Role Middleware
 * برمجية التحقق من الأدوار
 */

class RoleMiddleware {
    
    private $requiredRoles = [];

    public function __construct($roles = []) {
        $this->requiredRoles = is_array($roles) ? $roles : [$roles];
    }

    public function handle($request) {
        if (empty($this->requiredRoles)) {
            return true;
        }

        $userRole = Auth::user()['role_id'] ?? null;

        if (in_array($userRole, $this->requiredRoles)) {
            return true;
        }

        Response::flash('ليس لديك صلاحية للوصول إلى هذه الصفحة', 'danger');
        Response::redirect(APP_URL . '/dashboard.php');
        return false;
    }
}
