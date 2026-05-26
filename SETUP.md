<?php
/**
 * Setup Instructions
 * تعليمات التركيب
 */

return <<<SETUP

# تعليمات تثبيت نظام إدارة الصيدليات

## متطلبات النظام

- PHP 7.4 أو أعلى
- MySQL 5.7 أو أعلى (MariaDB 10.3+)
- Apache/Nginx Web Server
- cURL مفعل
- JSON support مفعل

## الخطوات

### 1. إعداد قاعدة البيانات

```bash
# انسخ ملف الإعدادات
cp config/.env.example config/.env

# عدل البيانات التالية:
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=pharmacy_db
```

### 2. إنشاء قاعدة البيانات

```bash
# عبر المتصفح
# زيارة: http://localhost:8000/database/installer.php

# أو عبر Command Line:
mysql -u root -p pharmacy_db < database/schema.sql
```

### 3. تشغيل الخادم الموضعي

```bash
# باستخدام PHP
cd /path/to/project
php -S localhost:8000

# باستخدام Apache
# قم بعمل Virtual Host م��ازي للمشروع
```

### 4. المتابعة عبر المتصفح

1. اذهب إلى `http://localhost:8000/install.php`
2. ملء بيانات الشركة والمستخدم المالك
3. انقر على الزر "بدء التثبيت"
4. اذهب إلى الصفحة الرئيسية `http://localhost:8000/login.php`

## بيانات الدخول الافتراضية

بعد التثبيت، سيطلب منك النظام إدخال بياناتك.

## هيكل المشروع

```
pharmacy-management-system/
├── config/
│   ├── constants.php
│   ├── database.php
│   └── session.php
├── database/
│   ├── schema.sql
│   └── installer.php
├── helpers/
│   ├── Security.php
│   ├── Response.php
│   ├── Validator.php
└── models/
│   ├── BaseModel.php
│   ├── User.php
│   ├── Company.php
│   ├── Medicine.php
└── middleware/
│   ├── AuthMiddleware.php
│   ├── PermissionMiddleware.php
├── layouts/
│   ├── header.php
│   ├── sidebar.php
│   ├── navbar.php
└── assets/
│   ├── js/
│   └── css/
├── login.php
├── dashboard.php
├── install.php
└── README.md
```

SETUP;
