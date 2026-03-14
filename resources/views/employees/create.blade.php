<x-templte/>

<!-- ================= MAIN CONTENT ================= -->
<main id="mainContent" class="main-content min-h-screen bg-[#f8fafc]">
    <div class="content-container">
        <!-- Page Header with Back Button -->
        <div class="page-header mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-[#6C63FF]"></i>
                    إضافة موظف جديد
                </h2>
                <p class="text-sm text-gray-500 mt-1">أدخل بيانات الموظف لإضافته إلى النظام</p>
            </div>
            <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-[#6C63FF] transition flex items-center gap-1">
                <i class="fas fa-arrow-right"></i>
                العودة إلى القائمة
            </a>
        </div>

        <!-- Employee Creation Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#6C63FF] focus:outline-none @error('name') border-red-500 @enderror"
                               placeholder="أدخل اسم الموظف"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#6C63FF] focus:outline-none @error('phone') border-red-500 @enderror"
                               placeholder="أدخل رقم الهاتف (اختياري)">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الوظيفة <span class="text-red-500">*</span></label>
                        <select name="role" 
                                id="role" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#6C63FF] focus:outline-none @error('role') border-red-500 @enderror"
                                required>
                            <option value="" disabled selected>-- اختر الوظيفة --</option>
                            <option value="Chef" {{ old('role') == 'Chef' ? 'selected' : '' }}>شيف</option>
                            <option value="Waiter" {{ old('role') == 'Waiter' ? 'selected' : '' }}>جارسون</option>
                            <option value="Cashier" {{ old('role') == 'Cashier' ? 'selected' : '' }}>كاشير</option>
                            <option value="Juice Maker" {{ old('role') == 'Juice Maker' ? 'selected' : '' }}>عصاراتي</option>
                            <option value="Cleaner" {{ old('role') == 'Cleaner' ? 'selected' : '' }}>عامل نظافة</option>
                            <option value="Accountant" {{ old('role') == 'Accountant' ? 'selected' : '' }}>محاسب</option>
                            <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>مدير</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salary Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع الراتب <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="salary_type" 
                                       value="daily" 
                                       class="w-5 h-5 text-[#6C63FF] focus:ring-[#6C63FF]"
                                       {{ old('salary_type') == 'daily' ? 'checked' : '' }}
                                       onchange="toggleSalaryFields()"
                                       required>
                                <span>يومي</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="salary_type" 
                                       value="monthly" 
                                       class="w-5 h-5 text-[#6C63FF] focus:ring-[#6C63FF]"
                                       {{ old('salary_type') == 'monthly' ? 'checked' : '' }}
                                       onchange="toggleSalaryFields()"
                                       required>
                                <span>شهري</span>
                            </label>
                        </div>
                        @error('salary_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Daily Salary (hidden by default) -->
                    <div id="dailySalaryField" class="{{ old('salary_type') == 'daily' ? '' : 'hidden' }}">
                        <label for="daily_salary" class="block text-sm font-medium text-gray-700 mb-2">الراتب اليومي <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">ج.م</span>
                            <input type="number" 
                                   id="daily_salary" 
                                   name="daily_salary" 
                                   value="{{ old('daily_salary') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#6C63FF] focus:outline-none @error('daily_salary') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('daily_salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Monthly Salary (hidden by default) -->
                    <div id="monthlySalaryField" class="{{ old('salary_type') == 'monthly' ? '' : 'hidden' }}">
                        <label for="monthly_salary" class="block text-sm font-medium text-gray-700 mb-2">الراتب الشهري <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">ج.م</span>
                            <input type="number" 
                                   id="monthly_salary" 
                                   name="monthly_salary" 
                                   value="{{ old('monthly_salary') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#6C63FF] focus:outline-none @error('monthly_salary') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('monthly_salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('employees.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition touch-button">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#6C63FF] text-white rounded-xl font-bold hover:bg-[#5a52d5] transition touch-button">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الموظف
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function toggleSalaryFields() {
        const salaryType = document.querySelector('input[name="salary_type"]:checked')?.value;
        const dailyField = document.getElementById('dailySalaryField');
        const monthlyField = document.getElementById('monthlySalaryField');
        
        if (salaryType === 'daily') {
            dailyField.classList.remove('hidden');
            monthlyField.classList.add('hidden');
            document.getElementById('daily_salary').required = true;
            document.getElementById('monthly_salary').required = false;
        } else if (salaryType === 'monthly') {
            dailyField.classList.add('hidden');
            monthlyField.classList.remove('hidden');
            document.getElementById('daily_salary').required = false;
            document.getElementById('monthly_salary').required = true;
        }
    }

    // Run on page load to set initial state based on old input
    document.addEventListener('DOMContentLoaded', function() {
        toggleSalaryFields();
    });
</script>

<style>
    /* Reuse styles from orders page */
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
</style>