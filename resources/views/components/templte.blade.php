<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ديكورهوم') }} - لوحة التحكم</title>

    <!-- Google Fonts - Poppins & Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Flatpickr for date picking -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        body { 
            font-family: 'Cairo', 'Poppins', sans-serif; 
            background-color: #f8fafc;
            overflow-x: hidden;
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
        
        /* Floating animation for 3D character */
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

        /* Sidebar styles - FIXED for mobile */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            right: -320px;
            width: 280px;
            background: white;
            box-shadow: -2px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 50;
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
        }

        .sidebar.open {
            right: 0;
        }

        @media (min-width: 1024px) {
            .sidebar {
                right: 0;
                width: 280px;
            }
            
            .main-content {
                margin-right: 280px;
                transition: margin-right 0.3s ease;
                width: calc(100% - 280px);
            }
            
            .hamburger-btn {
                display: none !important;
            }
            
            .sidebar .close-btn {
                display: none !important;
            }
        }

        /* Mobile overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 1024px) {
            .sidebar-overlay {
                display: none;
            }
        }

        /* Hamburger button */
        .hamburger-btn {
            position: fixed;
            top: 1rem;
            left: 1rem;
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 45;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }

        .hamburger-btn:hover {
            background: #6C63FF;
            color: white;
            transform: scale(1.05);
        }

        .hamburger-btn:active {
            transform: scale(0.95);
        }

        /* Close button inside sidebar */
        .sidebar .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            z-index: 51;
        }

        .sidebar .close-btn:hover {
            background: #e5e7eb;
            transform: rotate(90deg);
        }

        /* Mobile body scroll lock */
        body.sidebar-open {
            overflow: hidden;
        }

        /* Responsive tables */
        .responsive-table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 1rem;
        }

        /* Stats cards grid */
        .stats-grid {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Mobile card improvements */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
        }

        @media (max-width: 639px) {
            .stat-card {
                padding: 1rem;
            }
            
            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.75rem;
            }
        }

        /* Chart container */
        .chart-container {
            position: relative;
            width: 100%;
            height: 250px;
        }

        @media (min-width: 768px) {
            .chart-container {
                height: 300px;
            }
        }

        /* Touch-friendly buttons */
        .touch-button {
            min-height: 44px;
            min-width: 44px;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .touch-button:active {
            transform: scale(0.98);
        }

        /* Active menu item */
        .menu-item-active {
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.1) 0%, rgba(108, 99, 255, 0.05) 100%);
            border-right: 3px solid #6C63FF;
        }

        /* Mobile header spacing */
        @media (max-width: 639px) {
            .page-header {
                margin-right: 3.5rem;
                margin-left: 0.5rem;
                margin-top: 0.5rem;
            }
            
            .hamburger-btn {
                left: 0.5rem;
                top: 0.5rem;
            }
            
            .content-container {
                padding: 0.75rem;
                width: 100%;
            }
            
            .date-filter {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.75rem;
            }
            
            .date-filter .btn {
                width: 100%;
            }

            /* Fix for main content width on mobile */
            .main-content {
                width: 100% !important;
                margin-right: 0 !important;
            }
        }

        /* Container padding */
        .content-container {
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .content-container {
                padding: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .content-container {
                padding: 2rem;
            }
        }

        /* Date filter styles */
        .date-filter {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f1f5f9;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }

        .date-input {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            flex: 1;
            min-width: 200px;
        }

        .date-input:focus {
            border-color: #6C63FF;
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6C63FF, #FF6B6B);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(108, 99, 255, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Mobile table improvements */
        @media (max-width: 639px) {
            .responsive-table table {
                min-width: 500px;
            }
            
            .responsive-table th,
            .responsive-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #6C63FF;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Desktop layout fixes */
        @media (min-width: 1024px) {
            .main-content {
                margin-right: 280px;
                width: calc(100% - 280px);
            }
            
            .content-container {
                max-width: 1400px;
                margin: 0 auto;
            }
            
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .date-filter {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen" x-data="dashboardData()" x-init="initSidebar(); initDatePicker()" :class="{ 'sidebar-open': sidebarOpen }">

<!-- Hamburger Button -->
<button id="hamburgerBtn" 
        class="hamburger-btn touch-button" 
        x-show="!sidebarOpen"
        @click="sidebarOpen = true"
        x-cloak>
    <i class="fas fa-bars text-xl"></i>
</button>

<!-- Sidebar Overlay -->
<div id="sidebarOverlay" 
     class="sidebar-overlay" 
     :class="{ 'active': sidebarOpen }" 
     @click="sidebarOpen = false">
</div>

<!-- ================= SIDEBAR ================= -->
<aside id="sidebar" 
       class="sidebar bg-white/90 backdrop-blur-lg shadow-2xl p-8 border-l border-[#6C63FF]/10" 
       :class="{ 'open': sidebarOpen }">
    
    <!-- Close Button -->
    <button id="closeSidebarBtn" 
            class="close-btn" 
            @click="sidebarOpen = false">
        <i class="fas fa-times"></i>
    </button>

    <div class="mb-10">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-[#6C63FF] to-[#C084FC] rounded-2xl flex items-center justify-center shadow-xl">
                <i class="fas fa-utensils text-white text-lg"></i>
            </div>
            <h1 class="text-3xl font-bold">
                <span class="bg-gradient-to-l from-[#6C63FF] to-[#FF6B6B] bg-clip-text text-transparent">Menu</span>
                <span class="text-gray-800">Master</span>
            </h1>
        </div>
        <p class="text-sm text-gray-500 mt-1">نظام إدارة المطعم</p>
    </div>

    <!-- قائمة التنقل -->
    <nav class="space-y-2">
        @php
            $menuItems = [
                ['name' => 'لوحة التحكم', 'icon' => 'fa-chart-pie', 'route' => 'dashboard'],
                ['name' => 'المبيعات', 'icon' => 'fa-chart-line', 'route' => 'sales'],
                ['name' => 'الأصناف', 'icon' => 'fa-cubes', 'route' => 'products'],
                ['name' => 'الموظفين', 'icon' => 'fa-users', 'route' => 'employees'],
                ['name' => 'الفواتير', 'icon' => 'fa-file-invoice', 'route' => 'invoices'],
                ['name' => 'التحليل اليومي', 'icon' => 'fa-calendar-alt', 'route' => 'daily-analysis'],
            ];
        @endphp
        
        @foreach($menuItems as $menu)
        <a href="#" 
           @click="setActiveMenu('{{ $menu['name'] }}')"
           :class="{ 
               'bg-gradient-to-r from-[#6C63FF]/20 to-[#C084FC]/20 text-[#6C63FF] border-r-4 border-[#6C63FF]': activeMenu === '{{ $menu['name'] }}', 
               'text-gray-600 hover:bg-[#6C63FF]/10 hover:text-[#6C63FF]': activeMenu !== '{{ $menu['name'] }}' 
           }"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 font-medium group">
            <i class="fas {{ $menu['icon'] }} ml-3 text-lg group-hover:scale-110 transition-transform"></i>
            <span>{{ $menu['name'] }}</span>
        </a>
        @endforeach
    </nav>

    <!-- معلومات المستخدم -->
    <div class="absolute bottom-8 right-8 left-8">
        <div class="border-t border-[#6C63FF]/20 pt-6">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] flex items-center justify-center shadow-xl transform rotate-3 hover:rotate-0 transition-transform">
                    <span class="text-white font-bold text-xl">
                        {{ auth()->user()->name[0] ?? 'أ' }}
                    </span>
                </div>
                <div>
                    <p class="text-base font-bold text-gray-800">
                        {{ auth()->user()->name ?? 'أحمد محمد' }}
                    </p>
                    <p class="text-sm text-[#6C63FF] flex items-center gap-1">
                        <i class="fas fa-crown text-xs"></i>
                        {{ auth()->user()->role === 'admin' ? 'مدير المطعم' : 'موظف' }}
                    </p>
                </div>
            </div>
            
            <!-- أزرار سريعة -->
            <div class="mt-4 grid grid-cols-3 gap-2">
                <button class="p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center group">
                    <i class="fas fa-moon text-sm group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs block mt-1">ليلي</span>
                </button>
                <button class="p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center group">
                    <i class="fas fa-bell text-sm group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs block mt-1">الإشعارات</span>
                </button>
                <button class="p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center group">
                    <i class="fas fa-sign-out-alt text-sm group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs block mt-1">خروج</span>
                </button>
            </div>
        </div>
    </div>
</aside>





<!-- ================= SCRIPTS ================= -->
<script>
    function dashboardData() {
        return {
            activeMenu: 'لوحة التحكم',
            sidebarOpen: false,
            setActiveMenu(menu) {
                this.activeMenu = menu;
            },
            initSidebar() {
                // Initialize based on screen size
                if (window.innerWidth >= 1024) {
                    this.sidebarOpen = true;
                    document.getElementById('mainContent').style.marginRight = '280px';
                    document.getElementById('mainContent').style.width = 'calc(100% - 280px)';
                } else {
                    this.sidebarOpen = false;
                    document.getElementById('mainContent').style.marginRight = '0';
                    document.getElementById('mainContent').style.width = '100%';
                }
            },
            initDatePicker() {
                // Initialize flatpickr for date inputs
                flatpickr("#dateFrom", {
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    locale: "ar"
                });
                
                flatpickr("#dateTo", {
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    locale: "ar"
                });
            }
        }
    }

    // Filter orders by date
    function filterByDate() {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        if (!dateFrom || !dateTo) {
            alert('الرجاء اختيار تاريخ البداية والنهاية');
            return;
        }
        
        // Show loading state
        const tbody = document.getElementById('ordersTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="py-8 text-center">
                    <div class="spinner mx-auto mb-2"></div>
                    <p class="text-gray-500">جاري تحميل البيانات...</p>
                </td>
            </tr>
        `;
        
        // Make AJAX request
        fetch(`/orders/filter?from=${dateFrom}&to=${dateTo}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update summary stats
            document.getElementById('totalOrders').textContent = data.summary.total_orders;
            document.getElementById('totalItems').textContent = data.summary.total_items_sold;
            document.getElementById('totalRevenue').textContent = data.summary.total_money + ' ج.م';
            
            if (data.orders.length > 0) {
                let html = '';
                data.orders.forEach(order => {
                    let statusClass = '';
                    let statusText = '';
                    
                    if (order.status === 'completed') {
                        statusClass = 'bg-green-100 text-green-600';
                        statusText = 'مكتمل';
                    } else if (order.status === 'pending') {
                        statusClass = 'bg-yellow-100 text-yellow-600';
                        statusText = 'قيد الانتظار';
                    } else {
                        statusClass = 'bg-red-100 text-red-600';
                        statusText = 'ملغي';
                    }
                    
                    html += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">${order.order_number}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">${order.items_count}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#6C63FF]">${parseFloat(order.total).toFixed(2)} ج.م</td>
                            <td class="px-6 py-4 text-sm text-gray-700">${order.created_at}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 text-xs rounded-xl font-medium ${statusClass}">
                                    ${statusText}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500">
                            <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg">لا توجد طلبات في هذا التاريخ</p>
                            <p class="text-sm text-gray-400 mt-1">جرب تاريخ آخر</p>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-8 text-center text-red-500">
                        <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                        <p>حدث خطأ في تحميل البيانات</p>
                    </td>
                </tr>
            `;
        });
    }

    // Reset to today's date
    function resetToToday() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dateFrom').value = today;
        document.getElementById('dateTo').value = today;
        filterByDate();
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const alpineData = document.querySelector('[x-data]').__x.$data;
        const mainContent = document.getElementById('mainContent');
        
        if (window.innerWidth >= 1024) {
            alpineData.sidebarOpen = true;
            mainContent.style.marginRight = '280px';
            mainContent.style.width = 'calc(100% - 280px)';
            document.body.style.overflow = '';
        } else {
            alpineData.sidebarOpen = false;
            mainContent.style.marginRight = '0';
            mainContent.style.width = '100%';
            document.body.style.overflow = '';
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburgerBtn');
        const alpineData = document.querySelector('[x-data]').__x.$data;
        
        if (window.innerWidth < 1024 && alpineData.sidebarOpen) {
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                alpineData.sidebarOpen = false;
            }
        }
    });

    // Load today's orders by default
    document.addEventListener('DOMContentLoaded', function() {
        // Show only today's orders by default
        filterByDate();
    });
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>