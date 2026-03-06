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

        /* Sidebar styles */
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
            left: 1rem;
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
            
            .main-content {
                width: 100% !important;
                margin-right: 0 !important;
                padding: 1rem !important;
            }
            
            .stats-card {
                padding: 1rem;
            }
            
            .stats-icon {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            .stats-value {
                font-size: 1.5rem;
            }
            
            .stats-label {
                font-size: 0.75rem;
            }
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
                grid-template-columns: repeat(4, 1fr);
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

        /* Content area padding */
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
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen" x-data="dashboardData()" x-init="init(); initSidebar()" :class="{ 'sidebar-open': sidebarOpen }">

<!-- Hamburger Button - Only visible on mobile -->
<button id="hamburgerBtn" 
        class="hamburger-btn" 
        x-show="!sidebarOpen"
        @click="sidebarOpen = true"
        x-cloak>
    <i class="fas fa-bars text-xl"></i>
</button>


<!-- Sidebar -->    
<x-asidebar />
<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto bg-[#f8fafc] transition-all duration-300">
    <div class="content-container">
        <!-- عنوان الصفحة مع الشخصية ثلاثية الأبعاد -->
        <div class="page-header flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 lg:mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    مرحباً بك 👋
                </h2>
                <p class="text-sm sm:text-base text-gray-500 mt-1">
                    نظرة عامة على عناصر القائمة والمخزون
                </p>
            </div>
            
            <!-- 3D Character - Hidden on very small screens -->
            <div class="relative float-animation hidden sm:block">
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] rounded-2xl lg:rounded-3xl rotate-12 absolute -top-2 -right-2 opacity-20"></div>
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-[#6C63FF] rounded-xl lg:rounded-2xl flex items-center justify-center text-white text-2xl lg:text-4xl shadow-xl relative z-10">
                    <i class="fas fa-robot"></i>
                </div>
            </div>
        </div>

        <!-- ================= STATS CARDS ================= -->
        <div class="stats-grid mb-6 lg:mb-8">
            @foreach($stats as $index => $stat)
            <div class="bg-white rounded-xl lg:rounded-2xl p-4 lg:p-6 shadow-lg card-3d border border-gray-100 stats-card">
                <div class="flex items-center justify-between mb-3 lg:mb-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center text-white shadow-lg stats-icon">
                        <i class="fas {{ $stat['icon'] }} text-sm lg:text-xl"></i>
                    </div>
                    <span class="text-xs lg:text-sm font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <p class="text-xs lg:text-sm text-gray-500 mb-1 stats-label">{{ $stat['title'] }}</p>
                <p class="text-xl lg:text-3xl font-bold text-gray-800 stats-value">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- ================= CHARTS AND TOP ITEMS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- الرسم البياني للنشاط -->
            <div class="lg:col-span-2 bg-white rounded-xl lg:rounded-2xl p-4 lg:p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-semibold text-gray-800">نشاط القائمة خلال الأسبوع</h3>
                </div>
                <div class="chart-container w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- العناصر الأكثر توفراً -->
            <div class="bg-white rounded-xl lg:rounded-2xl p-4 lg:p-6 shadow-lg border border-gray-100">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4 lg:mb-6">أعلى العناصر مخزوناً</h3>
                <div class="space-y-3 lg:space-y-4">
                    @forelse($topItems as $item)
                    <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center space-x-2 lg:space-x-3 space-x-reverse">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-xs lg:text-sm text-gray-500">{{ $item['quantity'] }} وحدة</p>
                            </div>
                        </div>
                        <p class="text-base lg:text-lg font-bold text-[#6C63FF]">{{ number_format($item['revenue']) }} <span class="text-xs lg:text-sm text-gray-500">ج.م</span></p>
                    </div>
                    @empty
                    <div class="text-center py-6 lg:py-8 text-gray-500">
                        <i class="fas fa-box-open text-3xl lg:text-4xl mb-3"></i>
                        <p class="text-sm lg:text-base">لا توجد عناصر في القائمة</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('menu.management') }}" class="w-full mt-4 lg:mt-6 text-xs lg:text-sm text-[#6C63FF] hover:text-[#FF6B6B] transition-colors text-center font-medium bg-gray-50 py-2 lg:py-3 rounded-xl hover:bg-gray-100 block touch-button">
                    إدارة القائمة
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        <!-- ================= CATEGORY STATS AND RECENT ITEMS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
            <!-- توزيع التصنيفات -->
            <div class="bg-white rounded-xl lg:rounded-2xl p-4 lg:p-6 shadow-lg border border-gray-100">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4 lg:mb-6">توزيع التصنيفات</h3>
                <div class="space-y-3 lg:space-y-4">
                    @forelse($categoryStats as $category)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2 lg:space-x-3 space-x-reverse">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $category['color'] }}"></div>
                            <span class="text-sm lg:text-base text-gray-700">{{ $category['name'] }}</span>
                        </div>
                        <span class="text-sm lg:text-base font-semibold text-gray-900">{{ $category['count'] }} عنصر</span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p class="text-sm lg:text-base">لا توجد تصنيفات</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- آخر العناصر المضافة -->
            <div class="bg-white rounded-xl lg:rounded-2xl p-4 lg:p-6 shadow-lg border border-gray-100">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4 lg:mb-6">آخر العناصر المضافة</h3>
                <div class="responsive-table">
                    <table class="w-full min-w-[500px] lg:min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="pb-2 lg:pb-3 text-right text-xs lg:text-sm font-medium text-gray-500">العنصر</th>
                                <th class="pb-2 lg:pb-3 text-right text-xs lg:text-sm font-medium text-gray-500">التصنيف</th>
                                <th class="pb-2 lg:pb-3 text-right text-xs lg:text-sm font-medium text-gray-500">السعر</th>
                                <th class="pb-2 lg:pb-3 text-right text-xs lg:text-sm font-medium text-gray-500">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 lg:py-4 text-xs lg:text-sm font-semibold text-gray-800">{{ $item['name'] }}</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm text-gray-600">{{ $item['category'] }}</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm font-semibold text-gray-800">{{ number_format($item['price']) }} ج.م</td>
                                <td class="py-3 lg:py-4">
                                    <span class="px-2 lg:px-3 py-1 lg:py-1.5 text-xs rounded-xl font-medium {{ $item['status_class'] }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 lg:py-8 text-center text-gray-500">
                                    <i class="fas fa-box-open text-2xl lg:text-3xl mb-2"></i>
                                    <p class="text-sm lg:text-base">لا توجد عناصر مضافة</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <x-footer />
    </div>
</main>

<!-- ================= SCRIPTS ================= -->
<script>
    function dashboardData() {
        return {
            activeMenu: 'لوحة التحكم',
            sidebarOpen: false,
            weeklyData: {!! json_encode($weeklyData ?? [65, 78, 82, 95, 88, 110, 125]) !!},
            
            setActiveMenu(menu) {
                this.activeMenu = menu;
            },
            
            init() {
                this.initChart();
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
            
            initChart() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                const isMobile = window.innerWidth < 640;
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
                        datasets: [{
                            label: 'نشاط القائمة',
                            data: this.weeklyData,
                            borderColor: '#6C63FF',
                            backgroundColor: 'rgba(108, 99, 255, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#6C63FF',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: isMobile ? 3 : 5,
                            pointHoverRadius: isMobile ? 5 : 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#1e293b',
                                bodyColor: '#475569',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: isMobile ? 8 : 12,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#e2e8f0' },
                                ticks: { 
                                    color: '#64748b',
                                    font: { size: isMobile ? 10 : 12 }
                                },
                            },
                            x: {
                                grid: { display: false },
                                ticks: { 
                                    color: '#64748b',
                                    font: { size: isMobile ? 10 : 12 },
                                    maxRotation: isMobile ? 45 : 0,
                                    minRotation: isMobile ? 45 : 0
                                }
                            }
                        }
                    }
                });
            }
        }
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
        
        if (window.innerWidth < 1024 && alpineData && alpineData.sidebarOpen) {
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                alpineData.sidebarOpen = false;
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // Handle body scroll lock when sidebar opens/closes
    document.addEventListener('alpine:init', () => {
        document.addEventListener('alpine:initialized', () => {
            const alpineData = document.querySelector('[x-data]').__x.$data;
            if (alpineData) {
                alpineData.$watch('sidebarOpen', value => {
                    if (window.innerWidth < 1024) {
                        if (value) {
                            document.body.classList.add('sidebar-open');
                        } else {
                            document.body.classList.remove('sidebar-open');
                        }
                    }
                });
            }
        });
    });
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>