<?php
/**
 * Navbar Layout
 * شريط القائمة العلوي
 */
?>
<nav class="navbar">
    <div class="navbar-content">
        <div class="navbar-title">
            <?php echo isset($pageTitle) ? Security::escapeOutput($pageTitle) : APP_NAME; ?>
        </div>
        
        <div class="navbar-right">
            <!-- Notifications -->
            <button class="navbar-btn" title="الإشعارات">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>
            
            <!-- Messages -->
            <button class="navbar-btn" title="الرسائل">
                <i class="fas fa-envelope"></i>
                <span class="badge">2</span>
            </button>
            
            <!-- User Menu -->
            <div class="dropdown">
                <button class="user-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <?php 
                        $user = Auth::user();
                        echo strtoupper(substr($user['name'] ?? 'U', 0, 1));
                        ?>
                    </div>
                    <span><?php echo Security::escapeOutput($user['name'] ?? 'المستخدم'); ?></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile.php">ملفي الشخصي</a></li>
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/settings/account.php">إعدادات الحساب</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/logout.php">تسجيل الخروج</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
