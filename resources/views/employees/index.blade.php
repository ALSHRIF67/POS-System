<x-templte/>

<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content min-h-screen bg-[#f8fafc]">
    <div class="content-container">
        <!-- Page Header -->
        <div class="page-header mb-4 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-[#6C63FF]"></i>
                    إدارة الموظفين
                </h2>
                <p class="text-sm text-gray-500 mt-1">عرض وإدارة جميع الموظفين والرواتب والسلف</p>
            </div>
            <div class="mt-3 md:mt-0 flex gap-2">
                <a href="{{ route('employees.report.salary') }}" 
                   class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 transition flex items-center gap-1 touch-button">
                    <i class="fas fa-chart-bar"></i>
                    تقرير الرواتب
                </a>
                <a href="{{ route('employees.create') }}" 
                   class="bg-[#6C63FF] text-white px-4 py-2 rounded-xl hover:bg-[#5a52d5] transition flex items-center gap-1 touch-button">
                    <i class="fas fa-plus"></i>
                    إضافة موظف
                </a>
            </div>
        </div>

        {{-- ========== STATISTICS CARDS ========== --}}
        <div class="stats-grid mb-6">
            <!-- Total Employees -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+5%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الموظفين</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ $totalEmployees }}</p>
            </div>
            
            <!-- Total Salary Paid -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي الرواتب المدفوعة</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ number_format($totalSalaryPaid, 2) }} ج.م</p>
            </div>
            
            <!-- Total Advances -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-hand-holding-usd text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-yellow-500 bg-yellow-50 px-2 py-1 rounded-lg">+8%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">إجمالي السلف</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ number_format($totalAdvances, 2) }} ج.م</p>
            </div>
            
            <!-- Owed to Employees -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-arrow-up text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-purple-500 bg-purple-50 px-2 py-1 rounded-lg">+3%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">مستحق للموظفين</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ number_format($totalOwedToEmployees, 2) }} ج.م</p>
            </div>
            
            <!-- Owed by Employees -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-arrow-down text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-red-500 bg-red-50 px-2 py-1 rounded-lg">-2%</span>
                </div>
                <p class="stat-label text-xs text-gray-500 mb-1">مستحق على الموظفين</p>
                <p class="stat-value text-2xl lg:text-3xl font-bold text-gray-900">{{ number_format($totalOwedByEmployees, 2) }} ج.م</p>
            </div>
        </div>

        {{-- ========== EMPLOYEES TABLE ========== --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الاسم</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">رقم الهاتف</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الوظيفة</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">نوع الراتب</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الراتب اليومي</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الراتب الشهري</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $employee->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $employee->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $employee->role }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 capitalize">
                                @if($employee->salary_type == 'daily')
                                    يومي
                                @else
                                    شهري
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($employee->daily_salary)
                                    {{ number_format($employee->daily_salary, 2) }} ج.م
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($employee->monthly_salary)
                                    {{ number_format($employee->monthly_salary, 2) }} ج.م
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 justify-start">
                                    {{-- View Employee --}}
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition"
                                       title="عرض الموظف">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit Employee --}}
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-amber-600 hover:bg-amber-50 transition"
                                       title="تعديل الموظف">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Record Payment --}}
                                    <a href="{{ route('employees.payment.create', $employee) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-green-600 hover:bg-green-50 transition"
                                       title="تسجيل دفعة">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>

                                    {{-- Record Advance --}}
                                    <a href="{{ route('employees.advance.create', $employee) }}" 
                                       class="inline-flex items-center justify-center p-2 rounded-lg text-purple-600 hover:bg-purple-50 transition"
                                       title="تسجيل سلفة">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </a>

                                    {{-- Delete Employee --}}
                                    <form action="{{ route('employees.destroy', $employee) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center p-2 rounded-lg text-red-600 hover:bg-red-50 transition"
                                                title="حذف الموظف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">لا يوجد موظفون</p>
                                <p class="text-sm text-gray-400 mt-1">قم بإضافة موظف جديد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Optional: Pagination if needed --}}
        @if(method_exists($employees, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 flex justify-center">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</main>

<!-- (Optional) Include any scripts if needed – you can remove the resetToToday function as it's not relevant -->
<script>
    // No specific scripts required for this page.
    // If you have any global scripts, they can stay.
</script>

<style>
    /* Ensure the table wrapper scrolls horizontally on small screens */
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Optional: keep table cells from shrinking too much */
    .overflow-x-auto table {
        min-width: 1000px; /* Adjust based on number of columns */
    }

    /* Ensure main content can scroll vertically (default browser behavior) */
    .main-content {
        overflow-y: auto;
    }

    /* Reuse the same touch-button style from orders page */
    .touch-button {
        min-height: 44px;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }
    .touch-button:active {
        transform: scale(0.98);
    }
</style>