<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen">

<div class="flex min-h-screen" x-data="dashboardData()" x-init="initChart()">
    <!-- ================= SIDEBAR ================= -->
    <x-asidebar />

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 mr-72 p-8 lg:p-10 overflow-y-auto bg-[#f8fafc]">
        <!-- عنوان الصفحة مع الشخصية ثلاثية الأبعاد -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    مرحباً بك 👋
                </h2>
                <p class="text-gray-500 mt-1 text-lg">
                    نظرة عامة على عناصر القائمة والمخزون
                </p>
            </div>
            
            <!-- 3D Character -->
            <div class="relative float-animation">
                <div class="w-20 h-20 bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] rounded-3xl rotate-12 absolute -top-2 -right-2 opacity-20"></div>
                <div class="w-20 h-20 bg-[#6C63FF] rounded-2xl flex items-center justify-center text-white text-4xl shadow-xl relative z-10">
                    <i class="fas fa-robot"></i>
                </div>
            </div>
        </div>

        <!-- ================= STATS CARDS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $index => $stat)
            <div class="bg-white rounded-2xl p-6 shadow-lg card-3d border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center text-white shadow-lg">
                        <i class="fas {{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-1">{{ $stat['title'] }}</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- ================= CHARTS AND TOP ITEMS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- الرسم البياني للنشاط -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">نشاط القائمة خلال الأسبوع</h3>
                </div>
                <div class="h-72 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- العناصر الأكثر توفراً -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">أعلى العناصر مخزوناً</h3>
                <div class="space-y-4">
                    @forelse($topItems as $item)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                            <div>
                                <p class="text-base font-semibold text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['quantity'] }} وحدة</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-[#6C63FF]">{{ number_format($item['revenue']) }} <span class="text-sm text-gray-500">ج.م</span></p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-3"></i>
                        <p>لا توجد عناصر في القائمة</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('menu.management') }}" class="w-full mt-6 text-sm text-[#6C63FF] hover:text-[#FF6B6B] transition-colors text-center font-medium bg-gray-50 py-3 rounded-xl hover:bg-gray-100 block">
                    إدارة القائمة
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        <!-- ================= CATEGORY STATS AND RECENT ITEMS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- توزيع التصنيفات -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">توزيع التصنيفات</h3>
                <div class="space-y-4">
                    @forelse($categoryStats as $category)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $category['color'] }}"></div>
                            <span class="text-gray-700">{{ $category['name'] }}</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ $category['count'] }} عنصر</span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p>لا توجد تصنيفات</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- آخر العناصر المضافة -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">آخر العناصر المضافة</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">العنصر</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">التصنيف</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">السعر</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 text-sm font-semibold text-gray-800">{{ $item['name'] }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $item['category'] }}</td>
                                <td class="py-4 text-sm font-semibold text-gray-800">{{ number_format($item['price']) }} ج.م</td>
                                <td class="py-4">
                                    <span class="px-3 py-1.5 text-xs rounded-xl font-medium {{ $item['status_class'] }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">
                                    <i class="fas fa-box-open text-3xl mb-2"></i>
                                    <p>لا توجد عناصر مضافة</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <x-footer />
     
    </main>
</div>

<!-- ================= SCRIPTS ================= -->
<script>
    function dashboardData() {
        return {
            activeMenu: 'لوحة التحكم',
            setActiveMenu(menu) {
                this.activeMenu = menu;
            },
            initChart() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
                        datasets: [{
                            label: 'نشاط القائمة',
                            data: @json($weeklyData),
                            borderColor: '#6C63FF',
                            backgroundColor: 'rgba(108, 99, 255, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#6C63FF',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
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
                                padding: 12,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#e2e8f0' },
                                ticks: { color: '#64748b' },
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748b' }
                            }
                        }
                    }
                });
            }
        }
    }
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>