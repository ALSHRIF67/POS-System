{{-- resources/views/menu-management.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة القائمة - Menu Management</title>
    
    <!-- Google Fonts - Cairo للعربية و Inter للأرقام -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <x-asidebar/>
    <style>
        body {
            font-family: 'Cairo', 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1e293b;
        }
        
        /* Custom soft shadows */
        .soft-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        }
        
        .soft-shadow-hover:hover {
            box-shadow: 0 20px 30px -10px rgba(34, 197, 94, 0.15);
        }
        
        /* Card styles */
        .premium-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }
        
        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 40px -12px rgba(34, 197, 94, 0.2);
        }
        
        /* Table styles */
        .table-header {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table-row {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
        
        /* Status badges */
        .status-badge-available {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .status-badge-out-of-stock {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .status-badge-low-stock {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: #22c55e;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 10px 20px -8px rgba(34, 197, 94, 0.3);
        }
        
        .btn-primary:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -10px rgba(34, 197, 94, 0.4);
        }
        
        .btn-secondary {
            background-color: white;
            color: #64748b;
            padding: 0.75rem 1.5rem;
            border-radius: 14px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #22c55e;
            color: #22c55e;
        }
        
        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .btn-icon-edit {
            color: #22c55e;
            background-color: #dcfce7;
        }
        
        .btn-icon-edit:hover {
            background-color: #22c55e;
            color: white;
        }
        
        .btn-icon-delete {
            color: #ef4444;
            background-color: #fee2e2;
        }
        
        .btn-icon-delete:hover {
            background-color: #ef4444;
            color: white;
        }
        
        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background: white;
            border-radius: 32px;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
        
        /* Input styles */
        .input-field {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: all 0.2s ease;
            font-family: 'Cairo', sans-serif;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .input-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }
        
        /* Toggle switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e2e8f0;
            transition: 0.3s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: #22c55e;
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        /* Stats cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            border-color: #22c55e;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Filter chips */
        .filter-chip {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            background-color: white;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .filter-chip:hover, .filter-chip.active {
            background-color: #22c55e;
            color: white;
            border-color: #22c55e;
        }
    </style>
</head>
<body class="bg-[#f9fafb] ">

    <div class="max-w-7xl mx-auto">
        <!-- ================= PAGE HEADER ================= -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة القائمة</h1>
                <p class="text-gray-500">إدارة وتحديث عناصر القائمة والمخزون</p>
            </div>
            <button class="btn-primary" onclick="openModal()">
                <i class="fas fa-plus"></i>
                إضافة عنصر جديد
            </button>
        </div>

        <!-- ================= STATS CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Items -->
            <div class="stat-card soft-shadow">
                <div>
                    <p class="text-sm text-gray-500 mb-1">إجمالي العناصر</p>
                    <p class="text-3xl font-bold text-gray-900">156</p>
                    <p class="text-xs text-green-600 mt-2">+12 هذا الشهر</p>
                </div>
                <div class="stat-icon bg-green-100 text-green-600">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
            
            <!-- Low Stock -->
            <div class="stat-card soft-shadow">
                <div>
                    <p class="text-sm text-gray-500 mb-1">مخزون منخفض</p>
                    <p class="text-3xl font-bold text-yellow-600">8</p>
                    <p class="text-xs text-gray-500 mt-2">أقل من 5 وحدات</p>
                </div>
                <div class="stat-icon bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            
            <!-- Out of Stock -->
            <div class="stat-card soft-shadow">
                <div>
                    <p class="text-sm text-gray-500 mb-1">غير متوفر</p>
                    <p class="text-3xl font-bold text-red-600">3</p>
                    <p class="text-xs text-gray-500 mt-2">بحاجة لإعادة تموين</p>
                </div>
                <div class="stat-icon bg-red-100 text-red-600">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- ================= SEARCH AND FILTERS ================= -->
        <div class="premium-card p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Search Bar -->
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           placeholder="بحث عن عنصر..." 
                           class="input-field pr-12">
                </div>
                
                <!-- Category Filter -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">
                    <span class="filter-chip active">الكل</span>
                    <span class="filter-chip">مقبلات</span>
                    <span class="filter-chip">أطباق رئيسية</span>
                    <span class="filter-chip">مشروبات</span>
                    <span class="filter-chip">حلويات</span>
                </div>
            </div>
        </div>

        <!-- ================= ITEMS TABLE ================= -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-right">اسم العنصر</th>
                            <th class="px-6 py-4 text-right">السعر</th>
                            <th class="px-6 py-4 text-right">الكمية</th>
                            <th class="px-6 py-4 text-right">الحالة</th>
                            <th class="px-6 py-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Item 1 -->
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">بيتزا مارجريتا</div>
                                <div class="text-xs text-gray-500">مقبلات</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">$18.99</td>
                            <td class="px-6 py-4">
                                <span class="status-badge-available">
                                    <i class="fas fa-check-circle text-xs"></i>
                                    25
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge-available">
                                    <i class="fas fa-circle text-xs"></i>
                                    متوفر
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn-icon btn-icon-edit" onclick="openEditModal(1)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-delete" onclick="confirmDelete(1)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Item 2 - Low Stock -->
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">برجر دجاج</div>
                                <div class="text-xs text-gray-500">أطباق رئيسية</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">$14.99</td>
                            <td class="px-6 py-4">
                                <span class="status-badge-low-stock">
                                    <i class="fas fa-exclamation-triangle text-xs"></i>
                                    3
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge-low-stock">
                                    <i class="fas fa-exclamation-circle text-xs"></i>
                                    مخزون منخفض
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn-icon btn-icon-edit" onclick="openEditModal(2)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-delete" onclick="confirmDelete(2)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Item 3 - Out of Stock -->
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">سلطة سيزر</div>
                                <div class="text-xs text-gray-500">مقبلات</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">$11.99</td>
                            <td class="px-6 py-4">
                                <span class="status-badge-out-of-stock">
                                    <i class="fas fa-times-circle text-xs"></i>
                                    0
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge-out-of-stock">
                                    <i class="fas fa-ban text-xs"></i>
                                    غير متوفر
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn-icon btn-icon-edit" onclick="openEditModal(3)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-delete" onclick="confirmDelete(3)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Item 4 - Inventory OFF -->
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">مياه معدنية</div>
                                <div class="text-xs text-gray-500">مشروبات</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">$1.99</td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">—</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge-available">
                                    <i class="fas fa-infinity text-xs"></i>
                                    غير محدود
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn-icon btn-icon-edit" onclick="openEditModal(4)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-delete" onclick="confirmDelete(4)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer with Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    عرض 1-4 من 156 عنصر
                </div>
                <div class="flex items-center gap-2">
                    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600 transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-green-600 text-white">1</button>
                    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600 transition-all">2</button>
                    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600 transition-all">3</button>
                    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600 transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= ADD/EDIT MODAL ================= -->
        <div class="modal-overlay" id="itemModal">
            <div class="modal-content p-8">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900" id="modalTitle">إضافة عنصر جديد</h2>
                    <button onclick="closeModal()" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <form onsubmit="event.preventDefault(); saveItem();">
                    <!-- Item Name -->
                    <div class="mb-5">
                        <label class="input-label">
                            اسم العنصر
                            <span class="text-red-500 mr-1">*</span>
                        </label>
                        <input type="text" id="itemName" class="input-field" placeholder="مثال: بيتزا مارجريتا" required>
                    </div>
                    
                    <!-- Price -->
                    <div class="mb-5">
                        <label class="input-label">
                            السعر
                            <span class="text-red-500 mr-1">*</span>
                        </label>
                        <input type="number" id="itemPrice" class="input-field" placeholder="0.00" min="0.01" step="0.01" required>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-5">
                        <label class="input-label">التصنيف</label>
                        <select id="itemCategory" class="input-field">
                            <option value="appetizer">مقبلات</option>
                            <option value="main">أطباق رئيسية</option>
                            <option value="drink">مشروبات</option>
                            <option value="dessert">حلويات</option>
                        </select>
                    </div>
                    
                    <!-- Track Inventory Toggle -->
                    <div class="mb-5 flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div>
                            <label class="font-semibold text-gray-900">تتبع المخزون</label>
                            <p class="text-xs text-gray-500">تفعيل لإدارة الكمية تلقائياً</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="trackInventory" checked onchange="toggleQuantityField()">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <!-- Quantity (visible when inventory is on) -->
                    <div class="mb-5" id="quantityField">
                        <label class="input-label">الكمية المتوفرة</label>
                        <input type="number" id="itemQuantity" class="input-field" placeholder="0" min="0" value="10">
                        <p class="text-xs text-gray-500 mt-1">سيتم خصم الكمية تلقائياً عند كل عملية بيع</p>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex gap-3 mt-8">
                        <button type="submit" class="flex-1 btn-primary justify-center">
                            <i class="fas fa-save"></i>
                            حفظ
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 btn-secondary">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal and Interactions -->
    <script>
        // Modal functions
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
        
        function openEditModal(itemId) {
            document.getElementById('modalTitle').textContent = 'تعديل العنصر';
            // In real app, you would fetch item data here
            document.getElementById('itemName').value = 'بيتزا مارجريتا';
            document.getElementById('itemPrice').value = '18.99';
            document.getElementById('itemCategory').value = 'appetizer';
            document.getElementById('trackInventory').checked = true;
            document.getElementById('itemQuantity').value = '25';
            document.getElementById('quantityField').style.display = 'block';
            document.getElementById('itemModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('itemModal').classList.remove('active');
        }
        
        function toggleQuantityField() {
            const isChecked = document.getElementById('trackInventory').checked;
            const quantityField = document.getElementById('quantityField');
            quantityField.style.display = isChecked ? 'block' : 'none';
        }
        
        function saveItem() {
            // Validation
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
        
        function confirmDelete(itemId) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                alert('تم حذف العنصر');
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('itemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

</body>
</html>