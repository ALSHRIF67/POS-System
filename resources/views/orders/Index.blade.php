<x-templte/>

<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content min-h-screen bg-[#f8fafc]">
    <div class="content-container">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <h2 class="text-2xl font-bold text-gray-800">قائمة الطلبات</h2>
            <p class="text-sm text-gray-500 mt-1">عرض وإدارة جميع الطلبات</p>
        </div>

        <!-- Date Filter Section -->
        <div class="date-filter">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-calendar-alt text-[#6C63FF]"></i>
                <span class="text-sm font-medium text-gray-700">من:</span>
                <input type="text" 
                       id="dateFrom" 
                       class="date-input" 
                       placeholder="YYYY-MM-DD"
                       value="{{ date('Y-m-d') }}">
            </div>
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-calendar-alt text-[#FF6B6B]"></i>
                <span class="text-sm font-medium text-gray-700">إلى:</span>
                <input type="text" 
                       id="dateTo" 
                       class="date-input" 
                       placeholder="YYYY-MM-DD"
                       value="{{ date('Y-m-d') }}">
            </div>
            <div class="flex gap-2">
                <button class="btn-primary" onclick="filterByDate()">
                    <i class="fas fa-search ml-2"></i>
                    بحث
                </button>
                <button class="btn-secondary" onclick="resetToToday()">
                    <i class="fas fa-redo-alt ml-2"></i>
                    اليوم
                </button>
            </div>
        </div>

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
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ $summary->total_orders ?? 0 }}</p>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-cubes text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+8%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الأصناف المباعة</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ $summary->total_items_sold ?? 0 }}</p>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+15%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الإيرادات</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-[#6C63FF]">{{ number_format($summary->total_money ?? 0, 2) }} ج.م</p>
            </div>
        </div>

       {{-- ================= ORDER TABLE ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="responsive-table">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">رقم الطلب</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">عدد الأصناف</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الإجمالي</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">تاريخ الطلب</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders ?? [] as $order)
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">لا توجد طلبات حتى الآن</p>
                                <p class="text-sm text-gray-400 mt-1">ستظهر الطلبات هنا عند إضافتها</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
           
        </div>
        {{-- Pagination --}}
@if(isset($orders) && $orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex justify-center">
        {{ $orders->links() }}
    </div>
@endif
    </div>
</main>