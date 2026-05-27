# 💊 نظام إدارة الصيدليات الاحترافي
## Professional Pharmacy Management System

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-brightgreen.svg)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-%3E%3D5.7-blue.svg)](https://www.mysql.com)
[![Status](https://img.shields.io/badge/Status-Active-success.svg)](#)

> نظام تطبيق ويب متقدم لإدارة الصيدليات بشكل احترافي مع دعم كامل للعربية والإنجليزية، يوفر حلاً شاملاً لإدارة المخزون والمبيعات والتقارير.

---

## ✨ المزايا الرئيسية

### 🔐 الأمان والمصادقة
- ✅ نظام مصادقة آمن مع تشفير Bcrypt
- ✅ حماية CSRF على جميع النماذج
- ✅ إدارة الصلاحيات والأدوار المتقدمة
- ✅ تسجيل جميع النشاطات والعمليات
- ✅ حماية من هجمات Brute Force
- ✅ جلسات آمنة مع انتهاء الصلاحية التلقائي

### 📦 إدارة المخزون
- ✅ تتبع كامل لكميات الأدوية
- ✅ تنبيهات تلقائية للأدوية الناقصة
- ✅ تحذيرات من الأدوية منتهية الصلاحية
- ✅ حساب متقدم للأرصدة المتاحة
- ✅ سجل كامل لحركات المخزون

### 💰 نظام البيع (POS)
- ✅ واجهة سهلة الاستخدام
- ✅ البحث السريع بالباركود
- ✅ إدارة سلة الشراء الديناميكية
- ✅ تطبيق الخصومات
- ✅ حفظ فوري للمبيعات
- ✅ طباعة الفواتير

### 📊 التقارير والتحليلات
- ✅ تقارير مبيعات شاملة
- ✅ تقارير المخزون والجرد
- ✅ تحليل الأداء والإحصائيات
- ✅ تقارير الأرباح والخسائر
- ✅ تصدير التقارير بصيغة PDF/Excel

### 👥 إدارة المستخدمين
- ✅ إنشاء حسابات متعددة
- ✅ تحديد الأدوار والصلاحيات
- ✅ إدارة المستخدمين والفروع
- ✅ تتبع نشاطات كل مستخدم
- ✅ التحكم في الوصول حسب الدور

### 🌐 واجهة المستخدم
- ✅ تصميم عصري وجميل
- ✅ دعم كامل للعربية RTL
- ✅ واجهة متجاوبة (Responsive)
- ✅ سهلة الاستخدام والتنقل
- ✅ تصميم مظلم/فاتح

---

## 📋 متطلبات النظام

### الحد الأدنى
- **PHP:** 7.4 أو أعلى
- **MySQL:** 5.7 أو أعلى (MariaDB 10.3+)
- **Apache/Nginx** Web Server
- **cURL:** مفعّل
- **JSON Support:** مفعّل
- **PDO:** مفعّل

### الموارد المطلوبة
- **RAM:** 512 MB على الأقل
- **Storage:** 100 MB
- **CPU:** معالج عادي

---

## 🚀 خطوات التثبيت

### 1️⃣ استنساخ المشروع

```bash
git clone https://github.com/hasssson123/pharmacy-management-system.git
cd pharmacy-management-system
```

### 2️⃣ إعداد قاعدة البيانات

```bash
# إنشاء قاعدة بيانات جديدة
mysql -u root -p -e "CREATE DATABASE pharmacy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# استيراد الجداول
mysql -u root -p pharmacy_db < database/schema.sql
```

### 3️⃣ تهيئة الإعدادات

```bash
# انسخ ملف الإعدادات
cp .env.example .env

# عدّل ملف الإعدادات بيانات قاعدة البيانات
nano .env
```

### 4️⃣ تشغيل الخادم

```bash
# استخدام PHP المدمج
php -S localhost:8000

# أو استخدام Apache/Nginx
# تأكد من إعدادات Virtual Host
```

### 5️⃣ التثبيت عبر المتصفح

1. افتح المتصفح وادخل: `http://localhost:8000/install.php`
2. أدخل بيانات الشركة والمسؤول
3. انقر على "بدء التثبيت"
4. انتظر حتى انتهاء التثبيت
5. سيتم إعادة التوجيه إلى صفحة تسجيل الدخول

---

## 🔑 بيانات الدخول الافتراضية

بعد التثبيت، استخدم البيانات التي أدخلتها أثناء الإعداد.

**الدور الافتراضي:** مسؤول الشركة 🔑

---

## 📁 هيكل المشروع

```
pharmacy-management-system/
├── config/                    # ملفات الإعدادات
│   ├── constants.php          # الثوابت العامة
│   ├── database.php           # إعدادات قاعدة البيانات
│   ├── session.php            # إعدادات الجلسات
│   └── .env.example           # مثال ملف البيئة
│
├── database/                  # ملفات قاعدة البيانات
│   ├── schema.sql             # هيكل الجداول
│   └── installer.php          # برنامج التثبيت
│
├── helpers/                   # فئات المساعدة
│   ├── Security.php           # الأمان والتشفير
│   ├── Response.php           # معالجة الردود
│   ├── Validator.php          # التحقق من البيانات
│   └── Auth.php               # المصادقة والتفويض
│
├── models/                    # نماذج قاعدة البيانات
│   ├── BaseModel.php          # النموذج الأساسي
│   ├── User.php               # نموذج المستخدمين
│   ├── Company.php            # نموذج الشركات
│   ├── Medicine.php           # نموذج الأدوية
│   ├── Stock.php              # نموذج المخزون
│   └── Branch.php             # نموذج الفروع
│
├── middleware/                # البرمجيات الوسيطة
│   ├── AuthMiddleware.php     # التحقق من المصادقة
│   ├── PermissionMiddleware.php # التحقق من الصلاحيات
│   ├── RoleMiddleware.php     # التحقق من الأدوار
│   └── CSRFMiddleware.php     # حماية CSRF
│
├── layouts/                   # تخطيطات الصفحات
│   ├── header.php             # الرأس
│   ├── sidebar.php            # الشريط الجانبي
│   ├── navbar.php             # شريط القائمة العلوي
│   └── footer.php             # الحاشية السفلية
│
├── assets/                    # الموارد الثابتة
│   ├── js/                    # ملفات JavaScript
│   ├── css/                   # ملفات CSS
│   └── images/                # الصور
│
├── medicines/                 # صفحات إدارة الأدوية
│   ├── list.php               # قائمة الأدوية
│   ├── create.php             # إضافة دواء
│   └── edit.php               # تعديل دواء
│
├── stock/                     # صفحات إدارة المخزون
│   ├── list.php               # قائمة المخزون
│   └── movements.php          # حركات المخزون
│
├── sales/                     # صفحات المبيعات
│   ├── pos.php                # نقطة البيع
│   └── invoices.php           # الفواتير
│
├── reports/                   # صفحات التقارير
│   ├── sales.php              # تقارير المبيعات
│   ├── stock.php              # تقارير المخزون
│   └── inventory.php          # تقارير الجرد
│
├── settings/                  # صفحات الإعدادات
│   ├── system.php             # إعدادات النظام
│   ├── branches.php           # إعدادات الفروع
│   └── companies.php          # إعدادات الشركات
│
├── api/                       # نقاط نهاية API
│   ├── medicines/             # API الأدوية
│   ├── sales/                 # API المبيعات
│   ├── stock/                 # API المخزون
│   └── reports/               # API التقارير
│
├── login.php                  # صفحة تسجيل الدخول
├── dashboard.php              # لوحة التحكم
├── profile.php                # الملف الشخصي
├── logout.php                 # تسجيل الخروج
├── install.php                # برنامج التثبيت
├── autoload.php               # تحميل الملفات تلقائياً
├── index.php                  # الصفحة الرئيسية
├── .htaccess                  # قواعد Apache
├── .env.example               # مثال البيئة
├── SETUP.md                   # تعليمات التثبيت
└── README.md                  # هذا الملف
```

---

## 🎯 الأدوار والصلاحيات

### 1. مسؤول النظام (Super Admin)
- إدارة كاملة للنظام
- إنشاء الشركات والفروع
- إدارة المستخدمين والأدوار
- الوصول الكامل للتقارير

### 2. مسؤول الشركة (Company Admin)
- إدارة الشركة بالكامل
- إدارة الفروع التابعة
- إدارة المستخدمين بالشركة
- تقارير الشركة

### 3. مدير الفرع (Branch Manager)
- إدارة الفرع
- إدارة الموظفين بالفرع
- تقارير الفرع
- مراقبة المخزون

### 4. الصيدلاني (Pharmacist)
- إدارة الأدوية
- مراقبة المخزون
- إدارة المبيعات
- عرض التقارير

### 5. أمين الصندوق (Cashier)
- تسجيل المبيعات
- معالجة الدفع
- طباعة الفواتير

---

## 🔧 الميزات التقنية

### الأمان
- ✅ تشفير Bcrypt للكلمات المرورية
- ✅ حماية CSRF على جميع النماذج
- ✅ التحقق من الصلاحيات على مستوى الخادم
- ✅ معالجة آمنة للمدخلات
- ✅ جلسات آمنة مع httpOnly و Secure flags
- ✅ حماية من SQL Injection
- ✅ حماية من XSS Attacks

### قاعدة البيانات
- ✅ استخدام Prepared Statements
- ✅ Transactions للعمليات الحرجة
- ✅ Indexes على الأعمدة الرئيسية
- ✅ دعم UTF-8 الكامل للعربية
- ✅ تصميم معياري وقابل للتوسع

### الواجهة
- ✅ Bootstrap 5 RTL
- ✅ Font Awesome Icons
- ✅ jQuery للتفاعل
- ✅ AJAX للعمليات الديناميكية
- ✅ Responsive Design

---

## 📱 واجهات API

### الأدوية
```bash
GET    /api/medicines/get.php          # الحصول على قائمة الأدوية
POST   /api/medicines/create.php       # إنشاء دواء جديد
GET    /api/medicines/search.php       # البحث عن دواء
POST   /api/medicines/delete.php       # حذف دواء
```

### المبيعات
```bash
POST   /api/sales/create.php           # إنشاء بيع جديد
GET    /api/sales/invoices.php         # الحصول على الفواتير
```

### التقارير
```bash
GET    /api/reports/sales.php          # تقارير المبيعات
GET    /api/reports/stock.php          # تقارير المخزون
```

---

## 🐛 استكشاف الأخطاء

### مشكلة: لا يعمل الدخول
**الحل:**
- تحقق من بيانات قاعدة البيانات في `config/database.php`
- تأكد من وجود جداول قاعدة البيانات
- تحقق من أن المستخدم موجود في قاعدة البيانات

### مشكلة: خطأ في الصلاحيات
**الحل:**
- تأكد من أن المستخدم له الأدوار الصحيحة
- تحقق من جدول `role_permissions`
- تأكد من تعيين الصلاحيات للدور

### مشكلة: مشاكل في الترميز العربي
**الحل:**
- تأكد من أن قاعدة البيانات تستخدم `utf8mb4`
- تحقق من صفحات HTML أن `charset=UTF-8`
- تأكد من أن الملفات مخزنة بصيغة UTF-8

---

## 🤝 المساهمة

نرحب بالمساهمات! يرجى:

1. Fork المشروع
2. أنشئ فرع جديد (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى الفرع (`git push origin feature/AmazingFeature`)
5. افتح Pull Request

---

## 📝 الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE)

---

## 👨‍💻 المطورون

- **مطور رئيسي:** hasssson123
- **البريد:** randov1299@yahoo.com
- **GitHub:** [hasssson123](https://github.com/hasssson123)

---

## 📞 الدعم والمساعدة

للمساعدة والدعم:
- 📧 أرسل بريداً إلى: randov1299@yahoo.com
- 🐛 أبلغ عن الأخطاء في [Issues](https://github.com/hasssson123/pharmacy-management-system/issues)
- 💬 شارك اقتراحاتك

---

## 📚 الموارد والمراجع

- [PHP Official Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [OWASP Security Guidelines](https://owasp.org/)

---

## 🎉 شكر وتقدير

شكراً للمكتبات والأدوات المستخدمة في هذا المشروع:
- Bootstrap Framework
- Font Awesome
- jQuery
- MySQL/MariaDB

---

## 📅 آخر تحديث

**الإصدار:** 1.0.0  
**تاريخ التحديث:** 2026-05-27  
**الحالة:** 🟢 نشط وجاهز للاستخدام

---

<div align="center">

### ⭐ إذا أعجبك المشروع، لا تنسى إضافة نجمة ⭐

**Made with ❤️ for Pharmacies**

</div>
