 <!-- ================= السايدبار ================= -->
    <aside class="w-72 bg-white/90 backdrop-blur-lg shadow-2xl p-8 fixed right-0 top-0 bottom-0 overflow-y-auto z-10 border-l border-[#6C63FF]/10">
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-[#6C63FF] to-[#C084FC] rounded-2xl flex items-center justify-center shadow-xl">
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
                    ['name' => 'لوحة التحكم', 'icon' => 'fa-chart-pie'],
                    ['name' => 'المبيعات', 'icon' => 'fa-chart-line'],
                    ['name' => 'الأصناف', 'icon' => 'fa-cubes'],
                    ['name' => 'الموظفين', 'icon' => 'fa-users'],
                    ['name' => 'الفواتير', 'icon' => 'fa-file-invoice'],
                    ['name' => 'التحليل اليومي', 'icon' => 'fa-calendar-alt'],
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
                    <button class="p-2 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
                        <i class="fas fa-moon text-sm"></i>
                    </button>
                    <button class="p-2 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
                        <i class="fas fa-bell text-sm"></i>
                    </button>
                    <button class="p-2 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>
