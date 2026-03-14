<!-- ================= السايدبار ================= -->
<aside id="sidebar"
class="sidebar w-72 bg-white/90 backdrop-blur-lg shadow-2xl p-8 fixed right-0 top-0 bottom-0 overflow-y-auto border-l border-[#6C63FF]/10">

    <!-- Close button (mobile only) -->
    <button id="closeSidebarBtn" class="close-btn">
        <i class="fas fa-times"></i>
    </button>

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

<!-- ================= Navigation ================= -->
<nav class="space-y-2">

@php
$menuItems = [
    ['name' => 'لوحة التحكم', 'icon' => 'fa-chart-pie', 'route' => 'dashboard'],
    ['name' => 'إدارة القائمة', 'icon' => 'fa-cubes', 'route' => 'menu.management'], 
    ['name' => 'الطلبات', 'icon' => 'fa-utensils', 'route' => 'orders.create'],
    ['name' => 'المبيعات', 'icon' => 'fa-chart-line', 'route' => 'orders.index'],
    ['name' => 'الموظفون', 'icon' => 'fa-users', 'route' => 'employees.index'], // Added Employee
];
@endphp

@foreach($menuItems as $menu)

<a href="{{ route($menu['route']) }}"
class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 font-medium text-gray-600 hover:bg-[#6C63FF]/10 hover:text-[#6C63FF] group">

    <i class="fas {{ $menu['icon'] }} ml-3 text-lg group-hover:scale-110 transition-transform"></i>
    <span>{{ $menu['name'] }}</span>

</a>

@endforeach

</nav>

<!-- ================= User Info ================= -->
<div class="absolute bottom-8 right-8 left-8">
<div class="border-t border-[#6C63FF]/20 pt-6">

<div class="flex items-center space-x-3 space-x-reverse">

<div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#6C63FF] to-[#FF6B6B] flex items-center justify-center shadow-xl">
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

<!-- Quick buttons -->
<div class="mt-4 grid grid-cols-3 gap-2">

<button class="p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
<i class="fas fa-moon text-sm"></i>
<span class="text-xs block mt-1">ليلي</span>
</button>

<button class="p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
<i class="fas fa-bell text-sm"></i>
<span class="text-xs block mt-1">الإشعارات</span>
</button>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit"
class="w-full p-3 rounded-xl bg-[#6C63FF]/10 text-[#6C63FF] hover:bg-[#6C63FF] hover:text-white transition-all text-center">
<i class="fas fa-sign-out-alt text-sm"></i>
<span class="text-xs block mt-1">خروج</span>
</button>
</form>

</div>
</div>
</div>

</aside>