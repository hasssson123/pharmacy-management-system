<?php
/**
 * Sales Reports Page
 * تقارير المبيعات
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('view_reports')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'تقارير المبيعات';
$currentPage = 'reports';

Auth::logActivity('عرض تقارير المبيعات', '');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line me-2"></i>تقارير المبيعات
            </h1>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label>من التاريخ</label>
                        <input type="date" class="form-control" id="fromDate" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label>إلى التاريخ</label>
                        <input type="date" class="form-control" id="toDate" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary w-100" id="filterBtn">
                            <i class="fas fa-filter me-2"></i>تطبيق
                        </button>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button class="btn btn-secondary w-100" id="exportBtn">
                            <i class="fas fa-download me-2"></i>تصدير PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-top: 4px solid #10b981;">
                    <div class="card-body">
                        <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">إجمالي المبيعات</h6>
                        <h2 style="margin: 0; color: #10b981; font-weight: bold;" id="totalSales">0.00</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-top: 4px solid #667eea;">
                    <div class="card-body">
                        <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">عدد العمليات</h6>
                        <h2 style="margin: 0; color: #667eea; font-weight: bold;" id="transactionCount">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-top: 4px solid #f59e0b;">
                    <div class="card-body">
                        <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">متوسط البيعة</h6>
                        <h2 style="margin: 0; color: #f59e0b; font-weight: bold;" id="avgSale">0.00</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-top: 4px solid #3b82f6;">
                    <div class="card-body">
                        <h6 style="color: #999; font-size: 12px; margin: 0 0 10px 0; text-transform: uppercase;">أفضل منتج</h6>
                        <h2 style="margin: 0; color: #3b82f6; font-weight: bold; font-size: 18px;" id="topProduct">-</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Table -->
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;"><i class="fas fa-list me-2"></i>بيان المبيعات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="reportsTable">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>تاريخ البيع</th>
                                <th>رقم الفاتورة</th>
                                <th>عدد الأصناف</th>
                                <th>الإجمالي</th>
                                <th>الخصم</th>
                                <th>المبلغ النهائي</th>
                                <th>المبيع</th>
                            </tr>
                        </thead>
                        <tbody id="reportBody">
                            <tr>
                                <td colspan="7" style="text-align: center; color: #999; padding: 40px;">
                                    انقر على تطبيق لعرض البلاغات
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('filterBtn').addEventListener('click', function() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    makeRequest('<?php echo APP_URL; ?>/api/reports/sales.php?from=' + fromDate + '&to=' + toDate)
        .then(response => {
            if (response.success) {
                const data = response.data;
                document.getElementById('totalSales').textContent = data.total.toFixed(2);
                document.getElementById('transactionCount').textContent = data.count;
                document.getElementById('avgSale').textContent = (data.total / data.count).toFixed(2);
                document.getElementById('topProduct').textContent = data.topProduct || '-';
                
                const tbody = document.getElementById('reportBody');
                if (data.transactions.length > 0) {
                    tbody.innerHTML = data.transactions.map(tx => `
                        <tr>
                            <td>${tx.date}</td>
                            <td>#${tx.id}</td>
                            <td>${tx.items}</td>
                            <td>${tx.total}</td>
                            <td>${tx.discount}</td>
                            <td><strong>${tx.finalTotal}</strong></td>
                            <td>${tx.user}</td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #999;">لا توجد بيانات</td></tr>';
                }
            }
        })
        .catch(error => showToast('حدث خطأ', 'danger'));
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
