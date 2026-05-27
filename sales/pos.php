<?php
/**
 * POS Page
 * نقطة البيع
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../autoload.php';

if (!Auth::isLoggedIn() || !Auth::hasPermission('create_sale')) {
    Response::redirect(APP_URL . '/dashboard.php');
}

$user = Auth::user();
$pageTitle = 'نقطة البيع';
$currentPage = 'sales';

Auth::logActivity('فتح نقطة البيع', '');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="content-wrapper">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cash-register me-2"></i>نقطة البيع (POS)
            </h1>
        </div>
        
        <div class="row">
            <!-- Products Panel -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; gap: 10px;">
                            <input type="text" class="form-control" id="barcodeInput" placeholder="امسح الباركود..." autofocus>
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="productsContainer" style="max-height: 400px; overflow-y: auto;">
                            <p style="text-align: center; color: #999;">ابدأ بالبحث عن منتجات</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cart Panel -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;"><i class="fas fa-shopping-cart me-2"></i>سلة الشراء</h5>
                    </div>
                    <div class="card-body">
                        <div id="cartContainer" style="max-height: 400px; overflow-y: auto;">
                            <p style="text-align: center; color: #999;">السلة فارغة</p>
                        </div>
                        
                        <hr>
                        
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>الإجمالي الضريبة:</span>
                                <strong id="subtotalAmount">0.00</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>الخصم:</span>
                                <input type="number" id="discountAmount" style="width: 80px; padding: 5px;" value="0" step="0.01">
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; font-size: 16px;">
                                <span>الإجمالي:</span>
                                <span id="totalAmount">0.00</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-success w-100" id="completeBtn">
                            <i class="fas fa-check me-2"></i>إتمام البيع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchProduct();
    }
});

document.getElementById('searchBtn').addEventListener('click', searchProduct);

function searchProduct() {
    const barcode = document.getElementById('barcodeInput').value.trim();
    if (!barcode) return;
    
    makeRequest('<?php echo APP_URL; ?>/api/medicines/search.php?barcode=' + encodeURIComponent(barcode))
        .then(response => {
            if (response.success && response.data) {
                addToCart(response.data);
                document.getElementById('barcodeInput').value = '';
                document.getElementById('barcodeInput').focus();
            } else {
                showToast('لم يتم العثور على المنتج بهذا الباركود', 'warning');
            }
        })
        .catch(error => showToast('حدث خطأ', 'danger'));
}

function addToCart(product) {
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({...product, quantity: 1});
    }
    updateCart();
}

function updateCart() {
    const cartContainer = document.getElementById('cartContainer');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p style="text-align: center; color: #999;">السلة فارغة</p>';
    } else {
        cartContainer.innerHTML = cart.map((item, index) => `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 10px; background: #f3f4f6; border-radius: 5px;">
                <div style="flex: 1;">
                    <small>${item.name}</small><br>
                    <small style="color: #999;">${item.quantity} x ${item.price}</small>
                </div>
                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
    }
    
    calculateTotals();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = subtotal - discount;
    
    document.getElementById('subtotalAmount').textContent = subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

document.getElementById('discountAmount').addEventListener('change', calculateTotals);

document.getElementById('completeBtn').addEventListener('click', function() {
    if (cart.length === 0) {
        showToast('السلة فارغة', 'warning');
        return;
    }
    
    const total = parseFloat(document.getElementById('totalAmount').textContent);
    if (confirm(`هل أنت متأكد من بيع بمبلغ ${total.toFixed(2)}؟`)) {
        makeRequest('<?php echo APP_URL; ?>/api/sales/create.php', 'POST', {
            items: cart,
            discount: parseFloat(document.getElementById('discountAmount').value) || 0
        })
        .then(response => {
            if (response.success) {
                showToast('تم إنماء البيع بنجاح', 'success');
                cart = [];
                updateCart();
                document.getElementById('discountAmount').value = '0';
            } else {
                showToast(response.message, 'danger');
            }
        })
        .catch(error => showToast('حدث خطأ', 'danger'));
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
