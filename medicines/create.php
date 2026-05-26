<?php
/**
 * Create Medicine Page
 * إضافة دواء جديد
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('create_medicine')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'إضافة دواء جديد';
$currentPage = 'medicines';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'رمز الحماية غير صحيح';
    } else {
        // Validate input
        $validator = new Validator($_POST);
        $validator->required('name', 'اسم الدواء مطلوب')
                  ->required('ar_name', 'الاسم بالعربية مطلوب')
                  ->required('barcode', 'الباركود مطلوب')
                  ->required('price', 'السعر مطلوب')
                  ->numeric('price', 'السعر يجب أن يكون رقماً')
                  ->required('dosage', 'جرعة الدواء مطلوبة');
        
        if ($validator->fails()) {
            $errors = $validator->getErrors();
            $error = implode(', ', array_merge(...array_values($errors)));
        } else {
            try {
                $medicineModel = new Medicine();
                
                // Check barcode uniqueness
                $existingMedicine = $medicineModel->findByBarcode(
                    Security::sanitizeInput($_POST['barcode']),
                    $user['company_id']
                );
                
                if ($existingMedicine) {
                    $error = 'هذا الباركود موجود بالفعل';
                } else {
                    $medicineId = $medicineModel->create([
                        'company_id' => $user['company_id'],
                        'name' => Security::sanitizeInput($_POST['name']),
                        'ar_name' => Security::sanitizeInput($_POST['ar_name']),
                        'barcode' => Security::sanitizeInput($_POST['barcode']),
                        'generic_name' => Security::sanitizeInput($_POST['generic_name'] ?? ''),
                        'manufacturer' => Security::sanitizeInput($_POST['manufacturer'] ?? ''),
                        'manufacturing_date' => $_POST['manufacturing_date'] ?? null,
                        'expiry_date' => $_POST['expiry_date'] ?? null,
                        'dosage' => Security::sanitizeInput($_POST['dosage']),
                        'unit' => Security::sanitizeInput($_POST['unit'] ?? ''),
                        'price' => (float) $_POST['price'],
                        'cost_price' => (float) ($_POST['cost_price'] ?? 0),
                        'min_stock' => (int) ($_POST['min_stock'] ?? 10),
                        'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                        'status' => 'active'
                    ]);
                    
                    if ($medicineId) {
                        Auth::logActivity('إضافة دواء جديد', 'تم إضافة الدواء: ' . $_POST['name']);
                        Response::flash('تم إضافة الدواء بنجاح', 'success');
                        Response::redirect(APP_URL . '/medicines/list.php');
                    } else {
                        $error = 'حدث خطأ في إضافة الدواء';
                    }
                }
            } catch (Exception $e) {
                $error = 'حدث خطأ: ' . $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle me-2"></i>إضافة دواء جديد
            </h1>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-pills me-2"></i>معلومات الدواء</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">اسم الدواء (English)</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="ar_name" class="form-label">اسم الدواء (العربية)</label>
                                    <input type="text" class="form-control" id="ar_name" name="ar_name" required value="<?php echo htmlspecialchars($_POST['ar_name'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="barcode" class="form-label">الباركود</label>
                                    <input type="text" class="form-control" id="barcode" name="barcode" required value="<?php echo htmlspecialchars($_POST['barcode'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="generic_name" class="form-label">الاسم العام</label>
                                    <input type="text" class="form-control" id="generic_name" name="generic_name" value="<?php echo htmlspecialchars($_POST['generic_name'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="manufacturer" class="form-label">الشركة المصنعة</label>
                                    <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($_POST['manufacturer'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="dosage" class="form-label">الجرعة</label>
                                    <input type="text" class="form-control" id="dosage" name="dosage" required value="<?php echo htmlspecialchars($_POST['dosage'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="unit" class="form-label">الوحدة</label>
                                    <input type="text" class="form-control" id="unit" name="unit" value="<?php echo htmlspecialchars($_POST['unit'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">السعر</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="price" name="price" required step="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                                        <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cost_price" class="form-label">سعر التكلفة</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" value="<?php echo htmlspecialchars($_POST['cost_price'] ?? ''); ?>">
                                        <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="min_stock" class="form-label">الحد الأدنى للمخزون</label>
                                    <input type="number" class="form-control" id="min_stock" name="min_stock" value="<?php echo htmlspecialchars($_POST['min_stock'] ?? '10'); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="manufacturing_date" class="form-label">تاريخ التصنيع</label>
                                    <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date" value="<?php echo htmlspecialchars($_POST['manufacturing_date'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">تاريخ الانتهاء</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($_POST['expiry_date'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>حفظ الدواء
                                </button>
                                <a href="<?php echo APP_URL; ?>/medicines/list.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Help Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-info-circle me-2"></i>معلومات مهمة</h5>
                    </div>
                    <div class="card-body" style="font-size: 13px;">
                        <div class="mb-3">
                            <h6 style="font-weight: bold; color: #667eea;">الباركود</h6>
                            <p style="color: #666; margin: 0;">يجب أن يكون الباركود فريداً لكل دواء في الشركة</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 style="font-weight: bold; color: #667eea;">السعر</h6>
                            <p style="color: #666; margin: 0;">هذا هو السعر الذي سيتم عرضه للعملاء</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 style="font-weight: bold; color: #667eea;">الحد الأدنى للمخزون</h6>
                            <p style="color: #666; margin: 0;">سيتم التنبيه عند نقصان المخزون عن هذا الحد</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 style="font-weight: bold; color: #667eea;">تاريخ الانتهاء</h6>
                            <p style="color: #666; margin: 0;">سيتم التنبيه تلقائياً عند اقتراب تاريخ الانتهاء</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
