<x-templte/>

<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content min-h-screen bg-[#f8fafc]">
    <div class="content-container">
        <!-- Page Header with Back Button -->
        <div class="page-header mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user text-[#6C63FF]"></i>
                    بيانات الموظف
                </h2>
                <p class="text-sm text-gray-500 mt-1">عرض تفاصيل الموظف والمدفوعات والسلف</p>
            </div>
            <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-[#6C63FF] transition flex items-center gap-1">
                <i class="fas fa-arrow-right"></i>
                العودة إلى القائمة
            </a>
        </div>

        <!-- Employee Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">معلومات الموظف</h3>
                <a href="{{ route('employees.edit', $employee) }}" 
                   class="inline-flex items-center gap-1 px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition touch-button">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">الاسم</p>
                    <p class="font-medium text-gray-800">{{ $employee->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">رقم الهاتف</p>
                    <p class="font-medium text-gray-800">{{ $employee->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">الوظيفة</p>
                    <p class="font-medium text-gray-800">{{ $employee->role }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">نوع الراتب</p>
                    <p class="font-medium text-gray-800">{{ $employee->salary_type == 'daily' ? 'يومي' : 'شهري' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">الراتب اليومي</p>
                    <p class="font-medium text-gray-800">{{ $employee->daily_salary ? number_format($employee->daily_salary, 2) . ' ج.م' : '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">الراتب الشهري</p>
                    <p class="font-medium text-gray-800">{{ $employee->monthly_salary ? number_format($employee->monthly_salary, 2) . ' ج.م' : '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Payments -->
            <div class="bg-white rounded-2xl shadow p-5 border-r-4 border-green-500">
                <p class="text-sm text-gray-500">إجمالي المدفوعات</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($employee->totalPaid(), 2) }} ج.م</p>
            </div>
            <!-- Total Advances -->
            <div class="bg-white rounded-2xl shadow p-5 border-r-4 border-yellow-500">
                <p class="text-sm text-gray-500">إجمالي السلف</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($employee->totalAdvances(), 2) }} ج.م</p>
            </div>
            <!-- Net Balance -->
            @php $net = $employee->netBalance(); @endphp
            <div class="bg-white rounded-2xl shadow p-5 border-r-4 {{ $net >= 0 ? 'border-purple-500' : 'border-red-500' }}">
                <p class="text-sm text-gray-500">صافي الرصيد</p>
                <p class="text-2xl font-bold {{ $net >= 0 ? 'text-purple-600' : 'text-red-600' }}">
                    {{ number_format(abs($net), 2) }} ج.م
                </p>
                <p class="text-xs {{ $net >= 0 ? 'text-purple-500' : 'text-red-500' }}">
                    {{ $net >= 0 ? 'مستحق للموظف' : 'مستحق على الموظف' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('employees.payment.create', $employee) }}" 
               class="px-4 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 transition touch-button flex items-center gap-1">
                <i class="fas fa-money-bill-wave"></i>
                تسجيل دفعة
            </a>
            <a href="{{ route('employees.advance.create', $employee) }}" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition touch-button flex items-center gap-1">
                <i class="fas fa-hand-holding-usd"></i>
                تسجيل سلفة
            </a>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-green-500"></i>
                    سجل المدفوعات
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">المبلغ</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">ملاحظات</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employee->payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">{{ number_format($payment->amount, 2) }} ج.م</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->notes ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-money-bill-wave text-3xl mb-2 text-gray-300"></i>
                                <p>لا توجد مدفوعات مسجلة</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Advances Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-hand-holding-usd text-yellow-500"></i>
                    سجل السلف
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">المبلغ</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">ملاحظات</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-500">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employee->advances as $advance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-yellow-600">{{ number_format($advance->amount, 2) }} ج.م</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $advance->notes ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($advance->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-hand-holding-usd text-3xl mb-2 text-gray-300"></i>
                                <p>لا توجد سلف مسجلة</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<style>
    .touch-button {
        min-height: 44px;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }
    .touch-button:active {
        transform: scale(0.98);
    }
    .main-content {
        overflow-y: auto;
    }
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>