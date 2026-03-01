{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ديكورهوم') }} - لوحة التحكم</title>

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
            font-family: 'Cairo', sans-serif; 
            background-color: #1a2634; /* Fallback */
        }
        .rtl-flip { transform: scaleX(-1); }
        .transition-all { transition: all 0.3s ease; }
        
        /* Custom scrollbar for dark mode */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #2a3745;
        }
        ::-webkit-scrollbar-thumb {
            background: #4a5a6e;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #5a6e86;
        }
        
        /* Chart.js dark mode adjustments */
        .chart-container {
            filter: brightness(0.9) contrast(1.1);
        }
    </style>
</head>
<body class="bg-[#1a2634] min-h-screen">

<div class="flex min-h-screen" x-data="dashboardData()" x-init="initChart()">
    <!-- ================= SIDEBAR ================= -->
    <aside class="w-72 bg-[#232f3e] shadow-xl p-8 border-l border-[#3a4a5a] fixed right-0 top-0 bottom-0 overflow-y-auto">
        <div class="mb-10">
            <h1 class="text-2xl font-light tracking-wide text-white">
                ديكور<span class="font-medium text-amber-400">هوم</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1">لوحة التحكم</p>
        </div>

        <!-- قائمة التنقل -->
        <nav class="space-y-2">
            @php
                $menuItems = ['لوحة التحكم', 'المبيعات', 'الأصناف', 'الموظفين', 'الفواتير', 'التحليل اليومي'];
                $activeMenu = 'لوحة التحكم';
            @endphp
            
            @foreach($menuItems as $menu)
            <a href="#" 
               @click="setActiveMenu('{{ $menu }}')"
               :class="{ 
                   'bg-amber-500/20 text-amber-300 border-r-4 border-amber-500': activeMenu === '{{ $menu }}', 
                   'text-gray-300 hover:bg-[#2f3f50] hover:text-white': activeMenu !== '{{ $menu }}' 
               }"
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 font-medium">
                <span>{{ $menu }}</span>
            </a>
            @endforeach
        </nav>

        <!-- معلومات المستخدم -->
        <div class="absolute bottom-8 right-8 left-8">
            <div class="border-t border-[#3a4a5a] pt-6">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="w-10 h-10 rounded-full bg-amber-500/30 flex items-center justify-center border border-amber-500/50">
                        <span class="text-amber-300 font-bold">
                            {{ auth()->user()->name[0] ?? 'أ' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">
                            {{ auth()->user()->name ?? 'أحمد محمد' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ auth()->user()->role === 'admin' ? 'مدير المطعم' : 'موظف' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 mr-72 p-8 lg:p-10 overflow-y-auto bg-[#1a2634]">
        <!-- عنوان الصفحة -->
        <div class="mb-8">
            <h2 class="text-3xl font-light text-white">
                مرحباً بك 👋
            </h2>
            <p class="text-gray-400 mt-1 text-lg">
                نظرة عامة على أداء المطعم لهذا اليوم
            </p>
        </div>

        <!-- ================= STATS CARDS ================= -->
        @php
            $stats = [
                ['title' => 'مبيعات اليوم', 'value' => '5,240 ج.م', 'extra' => 'عدد الفواتير: 18', 'color' => 'blue'],
                ['title' => 'صافي الربح', 'value' => '3,820 ج.م', 'extra' => 'المصروفات: 1,420 ج.م', 'color' => 'emerald'],
                ['title' => 'الطلبات النشطة', 'value' => '7', 'extra' => 'مكتملة اليوم: 11', 'color' => 'amber'],
                ['title' => 'الموظفين النشطين', 'value' => '5', 'extra' => 'الأكثر نشاطاً: أحمد', 'color' => 'purple'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $index => $stat)
            <div class="bg-[#232f3e] rounded-2xl p-6 shadow-lg border border-[#3a4a5a] hover:border-{{ $stat['color'] }}-500/50 hover:shadow-{{ $stat['color'] }}-500/10 transition-all duration-300">
                <p class="text-sm text-gray-400 mb-2 font-medium">{{ $stat['title'] }}</p>
                <p class="text-3xl font-bold text-white mb-3">{{ $stat['value'] }}</p>
                <div class="flex items-center justify-between pt-2 border-t border-[#3a4a5a]">
                    <span class="text-xs text-gray-400">{{ $stat['extra'] }}</span>
                    <span class="w-2 h-2 rounded-full bg-{{ $stat['color'] }}-500"></span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ================= CHARTS AND TOP SELLING ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- الرسم البياني للمبيعات -->
            <div class="lg:col-span-2 bg-[#232f3e] rounded-2xl p-6 shadow-lg border border-[#3a4a5a]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">المبيعات خلال الأسبوع</h3>
                    <select class="text-sm bg-[#2f3f50] border border-[#4a5a6e] rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none">
                        <option class="bg-[#232f3e]">آخر 7 أيام</option>
                        <option class="bg-[#232f3e]">آخر 30 يوم</option>
                    </select>
                </div>
                <div class="h-72 w-full chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- الأصناف الأكثر مبيعاً -->
            <div class="bg-[#232f3e] rounded-2xl p-6 shadow-lg border border-[#3a4a5a]">
                <h3 class="text-lg font-semibold text-white mb-6">الأكثر مبيعاً اليوم</h3>
                @php
                    $topSellingItems = [
                        ['name' => 'بيتزا مارجريتا', 'quantity' => 8, 'revenue' => 960],
                        ['name' => 'برجر دجاج', 'quantity' => 6, 'revenue' => 900],
                        ['name' => 'سلطة سيزر', 'quantity' => 5, 'revenue' => 400],
                        ['name' => 'باستا ألفريدو', 'quantity' => 7, 'revenue' => 840],
                        ['name' => 'عصير طبيعي', 'quantity' => 12, 'revenue' => 360],
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach($topSellingItems as $item)
                    <div class="flex items-center justify-between py-3 border-b border-[#3a4a5a] last:border-0">
                        <div>
                            <p class="text-base font-semibold text-white">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-400">{{ $item['quantity'] }} وحدة</p>
                        </div>
                        <p class="text-lg font-bold text-amber-400">{{ number_format($item['revenue']) }} <span class="text-sm text-gray-400">ج.م</span></p>
                    </div>
                    @endforeach
                </div>
                <button class="w-full mt-6 text-sm text-amber-400 hover:text-amber-300 transition-colors text-center font-medium bg-[#2f3f50] py-3 rounded-lg border border-[#4a5a6e] hover:border-amber-500/50">
                    عرض التفاصيل كاملة ←
                </button>
            </div>
        </div>

        <!-- ================= EMPLOYEE PERFORMANCE AND RECENT ORDERS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- أداء الموظفين -->
            <div class="bg-[#232f3e] rounded-2xl p-6 shadow-lg border border-[#3a4a5a]">
                <h3 class="text-lg font-semibold text-white mb-6">أداء الموظفين اليوم</h3>
                @php
                    $employees = [
                        ['name' => 'أحمد محمد', 'role' => 'كاشير', 'orders' => 12, 'revenue' => 3600, 'avatar' => 'أ', 'color' => 'amber'],
                        ['name' => 'سارة أحمد', 'role' => 'ويتر', 'orders' => 15, 'revenue' => 4500, 'avatar' => 'س', 'color' => 'emerald'],
                        ['name' => 'محمود علي', 'role' => 'ويتر', 'orders' => 9, 'revenue' => 2700, 'avatar' => 'م', 'color' => 'blue'],
                        ['name' => 'فاطمة عمر', 'role' => 'كاشير', 'orders' => 8, 'revenue' => 2400, 'avatar' => 'ف', 'color' => 'purple'],
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach($employees as $employee)
                    <div class="flex items-center justify-between py-3 border-b border-[#3a4a5a] last:border-0">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 rounded-full bg-{{ $employee['color'] }}-500/20 flex items-center justify-center border border-{{ $employee['color'] }}-500/30">
                                <span class="text-sm font-bold text-{{ $employee['color'] }}-400">{{ $employee['avatar'] }}</span>
                            </div>
                            <div>
                                <p class="text-base font-semibold text-white">{{ $employee['name'] }}</p>
                                <p class="text-sm text-gray-400">{{ $employee['role'] }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-base font-semibold text-white">{{ $employee['orders'] }} طلب</p>
                            <p class="text-sm text-amber-400">{{ number_format($employee['revenue']) }} ج.م</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- آخر الطلبات -->
            <div class="bg-[#232f3e] rounded-2xl p-6 shadow-lg border border-[#3a4a5a]">
                <h3 class="text-lg font-semibold text-white mb-6">آخر الطلبات</h3>
                @php
                    $recentOrders = [
                        ['number' => '#1024', 'customer' => 'طاولة 5', 'status' => 'completed', 'amount' => 320, 'time' => 'منذ 5 دقائق'],
                        ['number' => '#1023', 'customer' => 'طاولة 2', 'status' => 'in-progress', 'amount' => 450, 'time' => 'منذ 12 دقيقة'],
                        ['number' => '#1022', 'customer' => 'طاولة 7', 'status' => 'pending', 'amount' => 280, 'time' => 'منذ 18 دقيقة'],
                        ['number' => '#1021', 'customer' => 'طاولة 3', 'status' => 'completed', 'amount' => 560, 'time' => 'منذ 25 دقيقة'],
                        ['number' => '#1020', 'customer' => 'طاولة 1', 'status' => 'ready', 'amount' => 190, 'time' => 'منذ 32 دقيقة'],
                    ];
                    
                    $getStatusClass = function($status) {
                        switch($status) {
                            case 'completed': return 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                            case 'in-progress': return 'bg-blue-500/20 text-blue-300 border border-blue-500/30';
                            case 'pending': return 'bg-amber-500/20 text-amber-300 border border-amber-500/30';
                            case 'ready': return 'bg-purple-500/20 text-purple-300 border border-purple-500/30';
                            default: return 'bg-gray-500/20 text-gray-300 border border-gray-500/30';
                        }
                    };
                    
                    $getStatusText = function($status) {
                        switch($status) {
                            case 'completed': return 'مكتمل';
                            case 'in-progress': return 'قيد التحضير';
                            case 'pending': return 'قيد الانتظار';
                            case 'ready': return 'جاهز';
                            default: return $status;
                        }
                    };
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#3a4a5a]">
                                <th class="pb-3 text-right text-sm font-medium text-gray-400">رقم الطلب</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-400">العميل</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-400">الحالة</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-400">المبلغ</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-400">الوقت</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a4a5a]">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-[#2a3745] transition-colors">
                                <td class="py-4 text-sm font-semibold text-white">{{ $order['number'] }}</td>
                                <td class="py-4 text-sm text-gray-300">{{ $order['customer'] }}</td>
                                <td class="py-4">
                                    <span class="px-3 py-1.5 text-xs rounded-full font-medium {{ $getStatusClass($order['status']) }}">
                                        {{ $getStatusText($order['status']) }}
                                    </span>
                                </td>
                                <td class="py-4 text-sm font-semibold text-white">{{ $order['amount'] }} ج.م</td>
                                <td class="py-4 text-sm text-gray-400">{{ $order['time'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                
                // Dark mode chart colors
                const gridColor = '#3a4a5a';
                const textColor = '#9ca3af';
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
                        datasets: [{
                            label: 'المبيعات',
                            data: [4200, 3800, 5100, 4500, 5800, 6200, 5240],
                            borderColor: '#f59e0b', // Amber-500
                            backgroundColor: 'rgba(245, 158, 11, 0.15)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#1a2634',
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
                                backgroundColor: '#232f3e',
                                titleColor: '#fff',
                                bodyColor: '#d1d5db',
                                borderColor: '#3a4a5a',
                                borderWidth: 1,
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { color: textColor, stepSize: 1000 },
                                title: { display: true, text: 'القيمة (ج.م)', color: textColor }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: textColor }
                            }
                        }
                    }
                });
            }
        }
    }
</script>

</body>
</html>