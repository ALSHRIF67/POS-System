
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
                    نظرة عامة على أداء المطعم لهذا اليوم
                </p>
            </div>
            
            <!-- 3D Character - يمكن استبدالها بصورة SVG أو 3D -->
            <div class="relative float-animation">
                <div class="w-20 h-20 bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] rounded-3xl rotate-12 absolute -top-2 -right-2 opacity-20"></div>
                <div class="w-20 h-20 bg-[#6C63FF] rounded-2xl flex items-center justify-center text-white text-4xl shadow-xl relative z-10">
                    <i class="fas fa-robot"></i>
                </div>
            </div>
        </div>

        <!-- ================= STATS CARDS ================= -->
        @php
            $stats = [
                ['title' => 'مبيعات اليوم', 'value' => '5,240', 'icon' => 'fa-chart-line', 'color' => 'from-[#6C63FF] to-[#FF6B6B]', 'trend' => '+12%'],
                ['title' => 'صافي الربح', 'value' => '3,820', 'icon' => 'fa-wallet', 'color' => 'from-[#FF6B6B] to-[#FFA07A]', 'trend' => '+8%'],
                ['title' => 'الطلبات النشطة', 'value' => '7', 'icon' => 'fa-clock', 'color' => 'from-[#4ECDC4] to-[#45B7D1]', 'trend' => '-3%'],
                ['title' => 'الموظفين النشطين', 'value' => '5', 'icon' => 'fa-users', 'color' => 'from-[#FFD93D] to-[#FFB347]', 'trend' => '0%'],
            ];
        @endphp

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
                <p class="text-3xl font-bold text-gray-800">{{ $stat['value'] }} <span class="text-sm text-gray-500 font-normal">ج.م</span></p>
            </div>
            @endforeach
        </div>

        <!-- ================= CHARTS AND TOP SELLING ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- الرسم البياني للمبيعات -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">المبيعات خلال الأسبوع</h3>
                    <select class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-gray-600 focus:ring-2 focus:ring-[#6C63FF]/50 focus:border-[#6C63FF] outline-none">
                        <option>آخر 7 أيام</option>
                        <option>آخر 30 يوم</option>
                    </select>
                </div>
                <div class="h-72 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- الأصناف الأكثر مبيعاً -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">الأكثر مبيعاً اليوم</h3>
                @php
                    $topSellingItems = [
                        ['name' => 'بيتزا مارجريتا', 'quantity' => 8, 'revenue' => 960, 'color' => '#6C63FF'],
                        ['name' => 'برجر دجاج', 'quantity' => 6, 'revenue' => 900, 'color' => '#FF6B6B'],
                        ['name' => 'سلطة سيزر', 'quantity' => 5, 'revenue' => 400, 'color' => '#4ECDC4'],
                        ['name' => 'باستا ألفريدو', 'quantity' => 7, 'revenue' => 840, 'color' => '#FFD93D'],
                        ['name' => 'عصير طبيعي', 'quantity' => 12, 'revenue' => 360, 'color' => '#FFA07A'],
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach($topSellingItems as $item)
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
                    @endforeach
                </div>
                <button class="w-full mt-6 text-sm text-[#6C63FF] hover:text-[#FF6B6B] transition-colors text-center font-medium bg-gray-50 py-3 rounded-xl hover:bg-gray-100">
                    عرض التفاصيل كاملة
                    <i class="fas fa-arrow-left mr-2"></i>
                </button>
            </div>
        </div>

        <!-- ================= EMPLOYEE PERFORMANCE AND RECENT ORDERS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- أداء الموظفين -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">أداء الموظفين اليوم</h3>
                @php
                    $employees = [
                        ['name' => 'أحمد محمد', 'role' => 'كاشير', 'orders' => 12, 'revenue' => 3600, 'avatar' => 'أ', 'color' => '#6C63FF'],
                        ['name' => 'سارة أحمد', 'role' => 'ويتر', 'orders' => 15, 'revenue' => 4500, 'avatar' => 'س', 'color' => '#FF6B6B'],
                        ['name' => 'محمود علي', 'role' => 'ويتر', 'orders' => 9, 'revenue' => 2700, 'avatar' => 'م', 'color' => '#4ECDC4'],
                        ['name' => 'فاطمة عمر', 'role' => 'كاشير', 'orders' => 8, 'revenue' => 2400, 'avatar' => 'ف', 'color' => '#FFD93D'],
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach($employees as $employee)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-12 h-12 rounded-xl" style="background-color: {{ $employee['color'] }}20; color: {{ $employee['color'] }}; border: 2px solid {{ $employee['color'] }}30" class="flex items-center justify-center">
                                <span class="text-lg font-bold">{{ $employee['avatar'] }}</span>
                            </div>
                            <div>
                                <p class="text-base font-semibold text-gray-800">{{ $employee['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $employee['role'] }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-base font-semibold text-gray-800">{{ $employee['orders'] }} طلب</p>
                            <p class="text-sm text-[#6C63FF]">{{ number_format($employee['revenue']) }} ج.م</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- آخر الطلبات -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">آخر الطلبات</h3>
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
                            case 'completed': return 'bg-green-50 text-green-600 border border-green-200';
                            case 'in-progress': return 'bg-blue-50 text-blue-600 border border-blue-200';
                            case 'pending': return 'bg-yellow-50 text-yellow-600 border border-yellow-200';
                            case 'ready': return 'bg-purple-50 text-purple-600 border border-purple-200';
                            default: return 'bg-gray-50 text-gray-600 border border-gray-200';
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
                            <tr class="border-b border-gray-100">
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">رقم الطلب</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">العميل</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">الحالة</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">المبلغ</th>
                                <th class="pb-3 text-right text-sm font-medium text-gray-500">الوقت</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 text-sm font-semibold text-gray-800">{{ $order['number'] }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $order['customer'] }}</td>
                                <td class="py-4">
                                    <span class="px-3 py-1.5 text-xs rounded-xl font-medium {{ $getStatusClass($order['status']) }}">
                                        {{ $getStatusText($order['status']) }}
                                    </span>
                                </td>
                                <td class="py-4 text-sm font-semibold text-gray-800">{{ $order['amount'] }} ج.م</td>
                                <td class="py-4 text-sm text-gray-500">{{ $order['time'] }}</td>
                            </tr>
                            @endforeach
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
                            label: 'المبيعات',
                            data: [4200, 3800, 5100, 4500, 5800, 6200, 5240],
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
                                ticks: { color: '#64748b', stepSize: 1000 },
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