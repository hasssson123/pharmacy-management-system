<?php
/**
 * Permission Middleware
 * برمجية التحقق من الصلاحيات
 */

class PermissionMiddleware {
    
    private $requiredPermissions = [];

    public function __construct($permissions = []) {
        $this->requiredPermissions = is_array($permissions) ? $permissions : [$permissions];
    }

    public function handle($request) {
        if (empty($this->requiredPermissions)) {
            return true;
        }

        foreach ($this->requiredPermissions as $permission) {
            if (Auth::hasPermission($permission)) {
                return true;
            }
        }

        Response::flash('ليس لديك صلاحية للوصول إلى هذه الصفحة', 'danger');
        Response::redirect(APP_URL . '/dashboard.php');
        return false;
    }
}
