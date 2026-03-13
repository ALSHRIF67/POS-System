<x-templte/>

<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content min-h-screen bg-[#f8fafc]">
    <div class="content-container">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <h2 class="text-2xl font-bold text-gray-800">قائمة الطلبات</h2>
            <p class="text-sm text-gray-500 mt-1">عرض وإدارة جميع الطلبات</p>
        </div>

        <!-- Date Filter Section (as a GET form) -->
        <form method="GET" action="{{ route('orders.index') }}" id="filterForm" class="date-filter">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-calendar-alt text-[#6C63FF]"></i>
                <span class="text-sm font-medium text-gray-700">من:</span>
                <input type="date" 
                       name="dateFrom" 
                       id="dateFrom" 
                       class="date-input" 
                       value="{{ $dateFrom ?? date('Y-m-d') }}">
            </div>
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-calendar-alt text-[#FF6B6B]"></i>
                <span class="text-sm font-medium text-gray-700">إلى:</span>
                <input type="date" 
                       name="dateTo" 
                       id="dateTo" 
                       class="date-input" 
                       value="{{ $dateTo ?? date('Y-m-d') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search ml-2"></i>
                    بحث
                </button>
                <button type="button" class="btn-secondary" onclick="resetToToday()">
                    <i class="fas fa-redo-alt ml-2"></i>
                    اليوم
                </button>
            </div>
        </form>

        {{-- ================= ORDER SUMMARY ================= --}}
        <div class="stats-grid mb-6">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الطلبات</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ $summary->total_orders }}</p>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-cubes text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+8%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الأصناف المباعة</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ $summary->total_items_sold }}</p>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+15%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الإيرادات</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-[#6C63FF]">{{ number_format($summary->total_money, 2) }} ج.م</p>
            </div>
        </div>

        {{-- ========== NEW: DAILY REVENUE REPORT SECTION ========== --}}
        <div class="daily-revenue-report mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-3">تقرير الإيراد اليومي</h3>
            
            <!-- Form to select a single date, preserving all existing query parameters -->
            <form method="GET" action="{{ route('orders.index') }}" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="reportDate" class="block text-sm font-medium text-gray-700 mb-1">اختر التاريخ</label>
                        <input type="date" 
                               name="reportDate" 
                               id="reportDate" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                               value="{{ request('reportDate') }}">
                    </div>
                    
                    <!-- Preserve all existing query parameters except 'reportDate' -->
                    @foreach(request()->except('reportDate') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $arrayValue)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    
                    <div>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#6C63FF] text-white rounded-xl hover:bg-[#5a52d5] transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i>
                            عرض التقرير
                        </button>
                    </div>
                </div>
            </form>

            <!-- Display the daily report only when a date is selected and data exists -->
            @if(request('reportDate') && isset($dailyReport))
                <div class="mt-4 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">تقرير يوم {{ \Carbon\Carbon::parse(request('reportDate'))->format('Y/m/d') }}</h4>
                        <span class="bg-[#6C63FF] bg-opacity-10 text-[#6C63FF] px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-calendar-day ml-1"></i>
                            تقرير مفصل
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Total Orders -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $dailyReport->total_orders }}</div>
                            <div class="text-sm text-gray-600">عدد الطلبات</div>
                        </div>
                        
                        <!-- Total Items Sold -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">{{ $dailyReport->total_items_sold }}</div>
                            <div class="text-sm text-gray-600">إجمالي الأصناف</div>
                        </div>
                        
                        <!-- Total Revenue -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                            <div class="text-3xl font-bold text-[#6C63FF] mb-1">{{ number_format($dailyReport->total_money, 2) }} ج.م</div>
                            <div class="text-sm text-gray-600">الإيرادات</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- ========== END DAILY REVENUE REPORT SECTION ========== --}}

       {{-- ================= ORDER TABLE ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Add horizontal scroll wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">رقم الطلب</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">عدد الأصناف</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الإجمالي</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">تاريخ الطلب</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الحالة</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $order->items_count }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#6C63FF]">{{ number_format($order->total, 2) }} ج.م</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 text-xs rounded-xl font-medium 
                                    @if($order->status === 'completed') bg-green-100 text-green-600
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-600
                                    @else bg-red-100 text-red-600
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 justify-start">
                                    {{-- View Order --}}
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition"
                                       title="عرض الطلب">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit Order --}}
                                    <a href="{{ route('orders.edit', $order->id) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-amber-600 hover:bg-amber-50 transition"
                                       title="تعديل الطلب">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete Order --}}
                                    <form action="{{ route('orders.destroy', $order->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف الطلب؟');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                     
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">لا توجد طلبات في هذا النطاق</p>
                                <p class="text-sm text-gray-400 mt-1">حاول تغيير تواريخ البحث</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination with query string preserved --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex justify-center">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<script>
     // Existing resetToToday function
    function resetToToday() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dateFrom').value = today;
        document.getElementById('dateTo').value = today;
        document.getElementById('filterForm').submit();
    }

    // Disable flatpickr on our date inputs (if flatpickr is loaded)
    document.addEventListener('DOMContentLoaded', function() {
        // Option A: Remove the class that triggers flatpickr
        document.querySelectorAll('#dateFrom, #dateTo').forEach(el => {
            el.classList.remove('date-input'); // remove the flatpickr trigger class
        });

        // Option B: If you need to keep the class but flatpickr is already initialized,
        // you can destroy existing instances:
        if (window.flatpickr) {
            flatpickr.find('#dateFrom')?.destroy();
            flatpickr.find('#dateTo')?.destroy();
        }
    });
</script>

<style>
    /* Ensure the table wrapper scrolls horizontally on small screens */
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Optional: keep table cells from shrinking too much */
    .overflow-x-auto table {
        min-width: 900px; /* Adjusted to accommodate new column */
    }

    /* Ensure main content can scroll vertically (default browser behavior) */
    .main-content {
        overflow-y: auto;
    }
</style>