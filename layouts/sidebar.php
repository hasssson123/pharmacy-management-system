<?php
/**
 * Sidebar Layout
 * الشريط الجانبي
 */
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-pills"></i>
            <span>الصيدليات</span>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li class="sidebar-menu-item">
            <a href="<?php echo APP_URL; ?>/dashboard.php" class="sidebar-menu-link <?php echo isset($currentPage) && $currentPage === 'dashboard' ? 'active' : ''; ?>">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-home"></i>
                </span>
                <span class="sidebar-menu-text">لوحة التحكم</span>
            </a>
        </li>
        
        <!-- Medicines -->
        <?php if (Auth::hasPermission('view_medicines')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-pills"></i>
                </span>
                <span class="sidebar-menu-text">الأدوية</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/medicines/list.php" class="sidebar-submenu-link">جميع الأدوية</a></li>
                <li><a href="<?php echo APP_URL; ?>/medicines/create.php" class="sidebar-submenu-link">إضافة دواء</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Stock -->
        <?php if (Auth::hasPermission('view_stock')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-boxes"></i>
                </span>
                <span class="sidebar-menu-text">المخزون</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/stock/list.php" class="sidebar-submenu-link">المخزون الحالي</a></li>
                <li><a href="<?php echo APP_URL; ?>/stock/movements.php" class="sidebar-submenu-link">حركة المخزون</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Sales -->
        <?php if (Auth::hasPermission('view_sales')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-cash-register"></i>
                </span>
                <span class="sidebar-menu-text">المبيعات</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/sales/pos.php" class="sidebar-submenu-link">نقطة البيع</a></li>
                <li><a href="<?php echo APP_URL; ?>/sales/invoices.php" class="sidebar-submenu-link">الفواتير</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Purchases -->
        <?php if (Auth::hasPermission('view_purchases')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                <span class="sidebar-menu-text">المشتريات</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/purchases/suppliers.php" class="sidebar-submenu-link">الموردين</a></li>
                <li><a href="<?php echo APP_URL; ?>/purchases/invoices.php" class="sidebar-submenu-link">فواتير الشراء</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Customers -->
        <?php if (Auth::hasPermission('view_customers')): ?>
        <li class="sidebar-menu-item">
            <a href="<?php echo APP_URL; ?>/customers/list.php" class="sidebar-menu-link <?php echo isset($currentPage) && $currentPage === 'customers' ? 'active' : ''; ?>">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-users"></i>
                </span>
                <span class="sidebar-menu-text">العملاء</span>
            </a>
        </li>
        <?php endif; ?>
        
        <!-- Users Management -->
        <?php if (Auth::hasPermission('manage_users')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-user-tie"></i>
                </span>
                <span class="sidebar-menu-text">المستخدمون</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/users/list.php" class="sidebar-submenu-link">المستخدمون</a></li>
                <li><a href="<?php echo APP_URL; ?>/roles/list.php" class="sidebar-submenu-link">الأدوار والصلاحيات</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Reports -->
        <?php if (Auth::hasPermission('view_reports')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <span class="sidebar-menu-text">التقارير</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/reports/sales.php" class="sidebar-submenu-link">تقارير المبيعات</a></li>
                <li><a href="<?php echo APP_URL; ?>/reports/stock.php" class="sidebar-submenu-link">تقارير المخزون</a></li>
                <li><a href="<?php echo APP_URL; ?>/reports/inventory.php" class="sidebar-submenu-link">تقارير الجرد</a></li>
            </ul>
        </li>
        <?php endif; ?>
        
        <!-- Settings -->
        <?php if (Auth::hasPermission('manage_settings')): ?>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link" data-toggle="submenu">
                <span class="sidebar-menu-icon">
                    <i class="fas fa-cog"></i>
                </span>
                <span class="sidebar-menu-text">الإعدادات</span>
                <span class="sidebar-menu-toggle">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?php echo APP_URL; ?>/settings/system.php" class="sidebar-submenu-link">إعدادات النظام</a></li>
                <li><a href="<?php echo APP_URL; ?>/settings/branches.php" class="sidebar-submenu-link">الفروع</a></li>
                <li><a href="<?php echo APP_URL; ?>/settings/companies.php" class="sidebar-submenu-link">الشركات</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</aside>

<script>
    // Sidebar Toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
    
    // Submenu Toggle
    document.querySelectorAll('[data-toggle="submenu"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.sidebar-menu-item');
            parent.classList.toggle('open');
        });
    });
</script>
