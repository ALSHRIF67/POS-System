{{-- resources/views/menu-management.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MenuMaster - إدارة القائمة</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
 <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    
</head>
<body>

<div class="flex min-h-screen" x-data="{ activeMenu: 'الأصناف', setActiveMenu(menu) { this.activeMenu = menu } }">
    
       <x-asidebar/>

   
</div>

<!-- ========== النافذة المنبثقة لإضافة/تعديل عنصر ========== -->
<div class="modal-overlay" id="itemModal">
    <div class="modal-content p-8">
        <!-- رأس النافذة -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold bg-gradient-to-l from-[#6C63FF] to-[#FF6B6B] bg-clip-text text-transparent" id="modalTitle">
                إضافة عنصر جديد
            </h2>
            <button onclick="closeModal()" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 hover:bg-[#6C63FF] hover:text-white transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- محتوى النافذة -->
        <form onsubmit="event.preventDefault(); saveItem();">
            <!-- اسم العنصر -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-utensils ml-2 text-[#6C63FF]"></i>
                    اسم العنصر
                    <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" id="itemName" class="input-field" placeholder="مثال: بيتزا مارجريتا" required>
            </div>
            
            <!-- السعر -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-tag ml-2 text-[#6C63FF]"></i>
                    السعر
                    <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="number" id="itemPrice" class="input-field" placeholder="0.00" min="0.01" step="0.01" required>
            </div>
            
            <!-- التصنيف -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-list ml-2 text-[#6C63FF]"></i>
                    التصنيف
                </label>
                <select id="itemCategory" class="input-field">
                    <option value="appetizer">🍢 مقبلات</option>
                    <option value="main">🍖 أطباق رئيسية</option>
                    <option value="drink">🥤 مشروبات</option>
                    <option value="dessert">🍰 حلويات</option>
                </select>
            </div>
            
            <!-- مفتاح تتبع المخزون -->
            <div class="mb-5 p-5 bg-gradient-to-r from-[#6C63FF]/5 to-[#FF6B6B]/5 rounded-2xl border border-[#6C63FF]/10">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-boxes text-[#6C63FF]"></i>
                            تتبع المخزون
                        </label>
                        <p class="text-sm text-gray-500 mt-1">تفعيل لإدارة الكمية تلقائياً</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="trackInventory" checked onchange="toggleQuantityField()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            
            <!-- حقل الكمية (يظهر عند تفعيل المخزون) -->
            <div class="mb-5" id="quantityField">
                <label class="input-label">
                    <i class="fas fa-cubes ml-2 text-[#6C63FF]"></i>
                    الكمية المتوفرة
                </label>
                <input type="number" id="itemQuantity" class="input-field" placeholder="0" min="0" value="10">
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <i class="fas fa-info-circle text-[#6C63FF]"></i>
                    سيتم خصم الكمية تلقائياً عند كل عملية بيع
                </p>
            </div>
            
            <!-- أزرار الحفظ والإلغاء -->
            <div class="flex gap-3 mt-8">
                <button type="submit" class="flex-1 btn-primary justify-center">
                    <i class="fas fa-save"></i>
                    حفظ العنصر
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-white border-2 border-gray-200 text-gray-600 px-6 py-3 rounded-2xl font-bold hover:border-[#6C63FF] hover:text-[#6C63FF] transition-all">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========== JavaScript ========== -->
<script>
    // فتح النافذة المنبثقة
    function openModal() {
        document.getElementById('modalTitle').textContent = 'إضافة عنصر جديد';
        document.getElementById('itemName').value = '';
        document.getElementById('itemPrice').value = '';
        document.getElementById('itemCategory').value = 'appetizer';
        document.getElementById('trackInventory').checked = true;
        document.getElementById('itemQuantity').value = '10';
        document.getElementById('quantityField').style.display = 'block';
        document.getElementById('itemModal').classList.add('active');
    }
    
    // فتح نافذة التعديل
    function openEditModal(itemId) {
        document.getElementById('modalTitle').textContent = 'تعديل العنصر';
        // في التطبيق الفعلي، هنا تجلب بيانات العنصر
        document.getElementById('itemName').value = 'بيتزا مارجريتا';
        document.getElementById('itemPrice').value = '18.99';
        document.getElementById('itemCategory').value = 'appetizer';
        document.getElementById('trackInventory').checked = true;
        document.getElementById('itemQuantity').value = '25';
        document.getElementById('quantityField').style.display = 'block';
        document.getElementById('itemModal').classList.add('active');
    }
    
    // إغلاق النافذة المنبثقة
    function closeModal() {
        document.getElementById('itemModal').classList.remove('active');
    }
    
    // إظهار/إخفاء حقل الكمية بناءً على حالة التبديل
    function toggleQuantityField() {
        const isChecked = document.getElementById('trackInventory').checked;
        const quantityField = document.getElementById('quantityField');
        quantityField.style.display = isChecked ? 'block' : 'none';
    }
    
    // حفظ العنصر
    function saveItem() {
        // التحقق من صحة البيانات
        const name = document.getElementById('itemName').value.trim();
        const price = parseFloat(document.getElementById('itemPrice').value);
        const quantity = parseInt(document.getElementById('itemQuantity').value);
        
        if (!name) {
            alert('يرجى إدخال اسم العنصر');
            return;
        }
        
        if (isNaN(price) || price <= 0) {
            alert('يرجى إدخال سعر صحيح أكبر من صفر');
            return;
        }
        
        if (document.getElementById('trackInventory').checked && (isNaN(quantity) || quantity < 0)) {
            alert('الكمية يجب أن تكون 0 أو أكثر');
            return;
        }
        
        alert('تم حفظ العنصر بنجاح!');
        closeModal();
    }
    
    // تأكيد الحذف
    function confirmDelete(itemId) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            alert('تم حذف العنصر');
        }
    }
    
    // إغلاق النافذة عند النقر خارجها
    document.getElementById('itemModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

</body>
</html>