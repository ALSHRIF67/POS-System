{{-- resources/views/menu/menu-management.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MenuMaster') }} - إدارة القائمة</title>

    <!-- Google Fonts - Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: #f8fafc;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* 3D Card Effect */
        .card-3d {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-3d:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(108, 99, 255, 0.1), 0 10px 10px -5px rgba(108, 99, 255, 0.04);
        }
        
        /* Gradient animations */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        
        /* RTL specific adjustments */
        .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        /* Status badges */
        .status-badge {
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        
        .status-available {
            background: rgba(16, 185, 129, 0.15);
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .status-low-stock {
            background: rgba(245, 158, 11, 0.15);
            color: #92400E;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .status-out-of-stock {
            background: rgba(239, 68, 68, 0.15);
            color: #991B1B;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .status-unlimited {
            background: rgba(108, 99, 255, 0.15);
            color: #6C63FF;
            border: 1px solid rgba(108, 99, 255, 0.3);
        }

        /* Table styles */
        .table-header {
            background: rgba(108, 99, 255, 0.05);
            color: #6C63FF;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(108, 99, 255, 0.1);
        }
        
        .table-row:hover {
            background: rgba(108, 99, 255, 0.05);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #6C63FF 0%, #8f88ff 100%);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 18px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 15px 30px -10px rgba(108, 99, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 40px -12px rgba(108, 99, 255, 0.7);
        }
        
        .btn-icon {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-icon-edit {
            background: rgba(108, 99, 255, 0.15);
            color: #6C63FF;
        }
        
        .btn-icon-edit:hover {
            background: #6C63FF;
            color: white;
            transform: scale(1.1);
        }
        
        .btn-icon-delete {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444;
        }
        
        .btn-icon-delete:hover {
            background: #EF4444;
            color: white;
            transform: scale(1.1);
        }

        /* Filter chips */
        .filter-chip {
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            background: white;
            border: 1px solid rgba(108, 99, 255, 0.2);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            color: #4B5563;
            white-space: nowrap;
        }
        
        .filter-chip:hover, .filter-chip.active {
            background: #6C63FF;
            color: white;
            border-color: #6C63FF;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -8px rgba(108, 99, 255, 0.5);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
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
            border-radius: 40px;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 50px 70px -20px rgba(108, 99, 255, 0.5);
        }
        
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        /* Input fields */
        .input-field {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid #f1f5f9;
            border-radius: 16px;
            transition: all 0.2s ease;
            font-family: 'Cairo', sans-serif;
            background: white;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #6C63FF;
            box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.15);
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
            background-color: #E2E8F0;
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #6C63FF, #8f88ff);
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            padding: 1rem 2rem;
            border-radius: 50px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: none;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
        
        .notification.show {
            display: flex;
            animation: slideDown 0.3s ease;
        }
        
        .notification.success {
            background: #10b981;
            color: white;
        }
        
        .notification.error {
            background: #ef4444;
            color: white;
        }
        
        @keyframes slideDown {
            from {
                transform: translate(-50%, -100%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen">

<!-- ========== Notification ========== -->
<div id="notification" class="notification"></div>

<div class="flex min-h-screen" x-data="menuData()" x-init="init()">
    <!-- ================= SIDEBAR ================= -->
    <x-asidebar />

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 mr-72 p-8 lg:p-10 overflow-y-auto bg-[#f8fafc]">
        <!-- عنوان الصفحة مع الشخصية ثلاثية الأبعاد -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    إدارة القائمة 🍽️
                </h2>
                <p class="text-gray-500 mt-1 text-lg">
                    إدارة وتحديث عناصر القائمة والمخزون
                </p>
            </div>
            
            <!-- 3D Character -->
            <div class="relative float-animation">
                <div class="w-20 h-20 bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] rounded-3xl rotate-12 absolute -top-2 -right-2 opacity-20"></div>
                <div class="w-20 h-20 bg-[#6C63FF] rounded-2xl flex items-center justify-center text-white text-4xl shadow-xl relative z-10">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
        </div>

        <!-- ========== STATS CARDS ========== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg card-3d border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-utensils text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">إجمالي العناصر</p>
                <p class="text-3xl font-bold text-gray-800" id="totalItems">{{ $stats['total_items'] ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg card-3d border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">مخزون منخفض</p>
                <p class="text-3xl font-bold text-yellow-600" id="lowStock">{{ $stats['low_stock'] ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg card-3d border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-400 to-red-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">غير متوفر</p>
                <p class="text-3xl font-bold text-red-600" id="outOfStock">{{ $stats['out_of_stock'] ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg card-3d border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#6C63FF] to-[#8f88ff] flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-infinity text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">غير محدود</p>
                <p class="text-3xl font-bold text-[#6C63FF]" id="unlimited">{{ $stats['unlimited'] ?? 0 }}</p>
            </div>
        </div>

        <!-- ========== SEARCH AND FILTER ========== -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Search Bar -->
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute right-5 top-1/2 transform -translate-y-1/2 text-[#6C63FF]"></i>
                    <input type="text" 
                           id="searchInput"
                           placeholder="ابحث عن عنصر في القائمة..." 
                           class="input-field pr-12">
                </div>
                
                <!-- Category Filter -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0" id="categoryFilters">
                    <span class="filter-chip active" data-category="all">الكل</span>
                    <span class="filter-chip" data-category="مقبلات">🍢 مقبلات</span>
                    <span class="filter-chip" data-category="أطباق رئيسية">🍖 أطباق رئيسية</span>
                    <span class="filter-chip" data-category="مشروبات">🥤 مشروبات</span>
                    <span class="filter-chip" data-category="حلويات">🍰 حلويات</span>
                </div>

                <!-- Add Button -->
                <button onclick="openModal()" class="btn-primary whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    إضافة عنصر جديد
                </button>
            </div>
        </div>

        <!-- ========== MENU ITEMS TABLE ========== -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative" id="tableContainer">
            <div class="loading-overlay" id="tableLoading" style="display: none;">
                <div class="loading-spinner"></div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-5 text-right">اسم العنصر</th>
                            <th class="px-6 py-5 text-right">السعر</th>
                            <th class="px-6 py-5 text-right">الكمية</th>
                            <th class="px-6 py-5 text-right">الحالة</th>
                            <th class="px-6 py-5 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="menuItemsTableBody">
                        @forelse($menuItems as $item)
                        <tr class="table-row" data-id="{{ $item['id'] }}" data-category="{{ $item['category_arabic'] }}" data-name="{{ $item['name'] }}">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#6C63FF]/20 to-[#C084FC]/20 rounded-2xl flex items-center justify-center text-2xl">
                                        {{ $item['category_icon'] }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-xs text-[#6C63FF]">{{ $item['category_arabic'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 font-bold text-gray-900">${{ number_format($item['price'], 2) }}</td>
                            <td class="px-6 py-5">
                                <span class="status-badge {{ $item['status_class'] }}">
                                    @if($item['track_inventory'])
                                        <i class="fas {{ $item['quantity'] == 0 ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                                        {{ $item['quantity'] }}
                                    @else
                                        <i class="fas fa-infinity"></i>
                                        غير محدود
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="status-badge {{ $item['status_class'] }}">
                                    <i class="fas 
                                        @if($item['status'] == 'available') fa-circle
                                        @elseif($item['status'] == 'low_stock') fa-exclamation-circle
                                        @elseif($item['status'] == 'out_of_stock') fa-ban
                                        @else fa-check-circle
                                        @endif">
                                    </i>
                                    {{ $item['status_text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn-icon btn-icon-edit" onclick="openEditModal({{ $item['id'] }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-delete" onclick="confirmDelete({{ $item['id'] }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3"></i>
                                <p>لا توجد عناصر في القائمة</p>
                                <button onclick="openModal()" class="mt-4 text-[#6C63FF] hover:text-[#FF6B6B] transition-colors">
                                    <i class="fas fa-plus ml-1"></i>
                                    أضف أول عنصر
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#6C63FF]/5 to-transparent border-t border-[#6C63FF]/10 flex items-center justify-between">
                <div class="text-sm text-gray-500" id="tableInfo">
                    عرض {{ count($menuItems) }} من {{ $stats['total_items'] ?? 0 }} عنصر
                </div>
            </div>
        </div>

      
        
        <x-footer />
    </main>
</div>

<!-- ========== ITEM MODAL ========== -->
<div class="modal-overlay" id="itemModal">
    <div class="modal-content p-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold bg-gradient-to-l from-[#6C63FF] to-[#FF6B6B] bg-clip-text text-transparent" id="modalTitle">
                إضافة عنصر جديد
            </h2>
            <button onclick="closeModal()" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 hover:bg-[#6C63FF] hover:text-white transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form id="itemForm" onsubmit="event.preventDefault(); saveItem();">
            @csrf
            <input type="hidden" id="itemId" name="id">
            
            <!-- Item Name -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-utensils ml-2 text-[#6C63FF]"></i>
                    اسم العنصر
                    <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" id="itemName" name="name" class="input-field" placeholder="مثال: بيتزا مارجريتا" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-name"></div>
            </div>
            
            <!-- Price -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-tag ml-2 text-[#6C63FF]"></i>
                    السعر
                    <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="number" id="itemPrice" name="price" class="input-field" placeholder="0.00" min="0.01" step="0.01" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-price"></div>
            </div>
            
            <!-- Category -->
            <div class="mb-5">
                <label class="input-label">
                    <i class="fas fa-list ml-2 text-[#6C63FF]"></i>
                    التصنيف
                </label>
                <select id="itemCategory" name="category" class="input-field">
                    <option value="appetizer">🍢 مقبلات</option>
                    <option value="main">🍖 أطباق رئيسية</option>
                    <option value="drink">🥤 مشروبات</option>
                    <option value="dessert">🍰 حلويات</option>
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-category"></div>
            </div>
            
            <!-- Inventory Toggle -->
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
                        <input type="checkbox" id="trackInventory" name="track_inventory" checked onchange="toggleQuantityField()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            
            <!-- Quantity Field -->
            <div class="mb-5" id="quantityField">
                <label class="input-label">
                    <i class="fas fa-cubes ml-2 text-[#6C63FF]"></i>
                    الكمية المتوفرة
                </label>
                <input type="number" id="itemQuantity" name="quantity" class="input-field" placeholder="0" min="0" value="10">
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <i class="fas fa-info-circle text-[#6C63FF]"></i>
                    سيتم خصم الكمية تلقائياً عند كل عملية بيع
                </p>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-quantity"></div>
            </div>
            
            <!-- Form Buttons -->
            <div class="flex gap-3 mt-8">
                <button type="submit" class="flex-1 btn-primary justify-center" id="submitBtn">
                    <i class="fas fa-save"></i>
                    <span>حفظ العنصر</span>
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-white border-2 border-gray-200 text-gray-600 px-6 py-3 rounded-2xl font-bold hover:border-[#6C63FF] hover:text-[#6C63FF] transition-all">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========== SCRIPTS ========== -->
<script>
    function menuData() {
        return {
            activeMenu: 'إدارة القائمة',
            setActiveMenu(menu) {
                this.activeMenu = menu;
            },
            init() {
                // Initialize any Alpine.js data here
            }
        }
    }

    // Global variables
    let menuItems = @json($menuItems);
    let currentFilter = 'all';
    let searchTerm = '';

    // Modal functions
    function openModal() {
        document.getElementById('modalTitle').textContent = 'إضافة عنصر جديد';
        document.getElementById('itemId').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemPrice').value = '';
        document.getElementById('itemCategory').value = 'appetizer';
        document.getElementById('trackInventory').checked = true;
        document.getElementById('itemQuantity').value = '10';
        document.getElementById('quantityField').style.display = 'block';
        hideAllErrors();
        document.getElementById('itemModal').classList.add('active');
    }

    async function openEditModal(id) {
        document.getElementById('modalTitle').textContent = 'تعديل العنصر';
        document.getElementById('itemId').value = id;
        
        showTableLoading();
        
        try {
            const response = await fetch(`/menu-items/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'فشل في جلب بيانات العنصر');
            }
            
            const item = result.data;
            
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemPrice').value = item.price;
            document.getElementById('itemCategory').value = item.category;
            document.getElementById('trackInventory').checked = item.track_inventory;
            document.getElementById('itemQuantity').value = item.quantity || '';
            
            toggleQuantityField();
            hideAllErrors();
            document.getElementById('itemModal').classList.add('active');
        } catch (error) {
            showNotification('error', error.message || 'فشل في تحميل بيانات العنصر');
        } finally {
            hideTableLoading();
        }
    }

    function closeModal() {
        document.getElementById('itemModal').classList.remove('active');
        hideAllErrors();
    }

    function toggleQuantityField() {
        const isChecked = document.getElementById('trackInventory').checked;
        const quantityField = document.getElementById('quantityField');
        quantityField.style.display = isChecked ? 'block' : 'none';
    }

    async function saveItem() {
        hideAllErrors();
        
        const itemData = {
            name: document.getElementById('itemName').value.trim(),
            price: parseFloat(document.getElementById('itemPrice').value),
            category: document.getElementById('itemCategory').value,
            track_inventory: document.getElementById('trackInventory').checked
        };

        if (itemData.track_inventory) {
            itemData.quantity = parseInt(document.getElementById('itemQuantity').value);
            if (isNaN(itemData.quantity)) {
                showFieldError('quantity', 'الرجاء إدخال الكمية');
                return;
            }
        }

        const id = document.getElementById('itemId').value;
        const url = id ? `/menu-items/${id}` : '/menu-items';
        const method = id ? 'PUT' : 'POST';

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> جاري الحفظ...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(itemData)
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        showFieldError(field, result.errors[field][0]);
                    });
                    throw new Error('يرجى تصحيح الأخطاء في النموذج');
                }
                throw new Error(result.message || 'فشل في حفظ العنصر');
            }
            
            showNotification('success', result.message);
            await refreshTable();
            await refreshStats();
            closeModal();
        } catch (error) {
            showNotification('error', error.message || 'فشل في حفظ العنصر');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    function confirmDelete(id) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            deleteItem(id);
        }
    }

    async function deleteItem(id) {
        try {
            const response = await fetch(`/menu-items/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'فشل في حذف العنصر');
            }
            
            showNotification('success', result.message);
            await refreshTable();
            await refreshStats();
        } catch (error) {
            showNotification('error', error.message || 'فشل في حذف العنصر');
        }
    }

    async function refreshTable() {
        showTableLoading();
        
        try {
            const response = await fetch('/menu-items', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'فشل في تحديث الجدول');
            }
            
            menuItems = result.data;
            filterAndDisplayItems();
        } catch (error) {
            showNotification('error', error.message);
        } finally {
            hideTableLoading();
        }
    }

    async function refreshStats() {
        try {
            const response = await fetch('/menu-stats', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'فشل في تحديث الإحصائيات');
            }
            
            const stats = result.data;
            
            document.getElementById('totalItems').textContent = stats.total_items || 0;
            document.getElementById('lowStock').textContent = stats.low_stock || 0;
            document.getElementById('outOfStock').textContent = stats.out_of_stock || 0;
            document.getElementById('unlimited').textContent = stats.unlimited || 0;
        } catch (error) {
            console.error('Error refreshing stats:', error);
        }
    }

    function filterAndDisplayItems() {
        let filteredItems = menuItems;
        
        if (currentFilter !== 'all') {
            filteredItems = filteredItems.filter(item => item.category_arabic === currentFilter);
        }
        
        if (searchTerm) {
            filteredItems = filteredItems.filter(item => 
                item.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }
        
        const tbody = document.getElementById('menuItemsTableBody');
        
        if (filteredItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-3"></i>
                        <p>لا توجد عناصر تطابق البحث</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = filteredItems.map(item => `
                <tr class="table-row" data-id="${item.id}" data-category="${item.category_arabic}" data-name="${item.name}">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#6C63FF]/20 to-[#C084FC]/20 rounded-2xl flex items-center justify-center text-2xl">
                                ${item.category_icon}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">${item.name}</div>
                                <div class="text-xs text-[#6C63FF]">${item.category_arabic}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 font-bold text-gray-900">$${item.price.toFixed(2)}</td>
                    <td class="px-6 py-5">
                        <span class="status-badge ${item.status_class}">
                            ${item.track_inventory ? 
                                `<i class="fas ${item.quantity === 0 ? 'fa-times-circle' : 'fa-check-circle'}"></i> ${item.quantity}` : 
                                '<i class="fas fa-infinity"></i> غير محدود'}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="status-badge ${item.status_class}">
                            <i class="fas ${item.status === 'available' ? 'fa-circle' : (item.status === 'low_stock' ? 'fa-exclamation-circle' : (item.status === 'out_of_stock' ? 'fa-ban' : 'fa-check-circle'))}"></i>
                            ${item.status_text}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-center gap-2">
                            <button class="btn-icon btn-icon-edit" onclick="openEditModal(${item.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-icon-delete" onclick="confirmDelete(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        document.getElementById('tableInfo').textContent = 
            `عرض ${filteredItems.length} من ${menuItems.length} عنصر`;
    }

    function showFieldError(field, message) {
        const errorElement = document.getElementById(`error-${field}`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            const input = document.getElementById(`item${field.charAt(0).toUpperCase() + field.slice(1)}`);
            if (input) {
                input.style.borderColor = '#ef4444';
            }
        }
    }

    function hideAllErrors() {
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        document.querySelectorAll('.input-field').forEach(el => {
            el.style.borderColor = '';
        });
    }

    function showNotification(type, message) {
        const notification = document.getElementById('notification');
        notification.className = `notification ${type} show`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    function showTableLoading() {
        document.getElementById('tableLoading').style.display = 'flex';
    }

    function hideTableLoading() {
        document.getElementById('tableLoading').style.display = 'none';
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchTerm = e.target.value;
                    filterAndDisplayItems();
                }, 300);
            });
        }
        
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.addEventListener('click', (e) => {
                document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
                e.target.classList.add('active');
                currentFilter = e.target.dataset.category;
                filterAndDisplayItems();
            });
        });
        
        document.getElementById('itemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    });
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>