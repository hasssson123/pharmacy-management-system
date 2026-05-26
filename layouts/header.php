<?php
/**
 * Header Layout
 * شريط العنوان
 */
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? Security::escapeOutput($pageTitle) . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- AdminLTE Style -->
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f3f4f6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: #333;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--dark);
            color: white;
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            display: none;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu-item {
            margin-bottom: 5px;
        }
        
        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            gap: 12px;
        }
        
        .sidebar-menu-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar-menu-link.active {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            border-right: 4px solid white;
        }
        
        .sidebar-menu-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        
        .sidebar-menu-text {
            flex: 1;
        }
        
        .sidebar-submenu {
            list-style: none;
            padding: 0;
            background-color: rgba(0, 0, 0, 0.2);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .sidebar-menu-item.open .sidebar-submenu {
            max-height: 500px;
        }
        
        .sidebar-submenu-link {
            display: flex;
            align-items: center;
            padding: 10px 20px 10px 52px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        
        .sidebar-submenu-link:hover {
            color: white;
            padding-left: 56px;
        }
        
        .sidebar-submenu-link.active {
            color: white;
            padding-left: 56px;
        }
        
        .sidebar-menu-toggle {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .sidebar-menu-item.open .sidebar-menu-toggle {
            transform: rotate(180deg);
        }
        
        /* Main Content */
        .content-wrapper {
            margin-right: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }
        
        .navbar-title {
            font-size: 20px;
            font-weight: bold;
            color: var(--dark);
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .navbar-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: var(--dark);
            position: relative;
        }
        
        .navbar-btn:hover {
            color: var(--primary);
        }
        
        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 10px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .page-breadcrumb {
            font-size: 13px;
            color: #999;
        }
        
        .breadcrumb-item {
            color: #999;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 20px;
            font-weight: bold;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3, #68408f);
            color: white;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 13px;
            margin-right: 280px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                right: -250px;
            }
            
            .sidebar.active {
                right: 0;
            }
            
            .content-wrapper {
                margin-right: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .navbar-content {
                padding: 12px 15px;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .footer {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
