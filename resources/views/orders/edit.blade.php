<style>
    body {
        font-family: 'Cairo', sans-serif;
        background: #f3f4f6;
    }
    
    /* Touch-friendly styles */
    .touch-button {
        min-height: 60px;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }
    
    .touch-button:active {
        transform: scale(0.98);
    }
    
    /* Product card */
    .product-card {
        transition: all 0.2s;
        border: 2px solid transparent;
        user-select: none;
        min-height: 160px;
    }
    
    .product-card:active {
        border-color: #6C63FF;
        background: #f5f3ff;
    }
    
    .product-card.out-of-stock {
        opacity: 0.5;
        pointer-events: none;
    }
    
    /* Quantity buttons */
    .qty-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
        font-weight: bold;
        transition: all 0.2s;
    }
    
    .qty-btn:active {
        transform: scale(0.9);
    }
    
    /* Remove button */
    .remove-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #ef4444;
        transition: all 0.2s;
    }
    
    .remove-btn:active {
        background: #fee2e2;
    }
    
    /* Scrollbar */
    .scrollbar-custom::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-custom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .scrollbar-custom::-webkit-scrollbar-thumb {
        background: #c7c7c7;
        border-radius: 10px;
    }
    
    .scrollbar-custom::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }
    
    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: white;
        padding: 16px 24px;
        border-radius: 50px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1000;
        transition: transform 0.3s;
        border-right: 4px solid;
    }
    
    .notification.show {
        transform: translateX(-50%) translateY(0);
    }
    
    .notification.success {
        border-right-color: #10b981;
    }
    
    .notification.error {
        border-right-color: #ef4444;
    }
    
    /* Loading spinner */
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #6C63FF;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Sidebar styles */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        right: -100%;
        width: 280px;
        background: white;
        box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        z-index: 50;
        overflow-y: auto;
        transition: right 0.3s ease-in-out;
    }

    .sidebar.open {
        right: 0;
    }

    @media (min-width: 1024px) {
        .sidebar {
            right: 0;
        }
        
        .main-content {
            margin-right: 280px;
            transition: margin-right 0.3s ease;
        }
        
        .sidebar .close-btn {
            display: none;
        }
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 45;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        pointer-events: none;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    @media (min-width: 1024px) {
        .sidebar-overlay {
            display: none;
        }
    }

    .hamburger-btn {
        position: fixed;
        top: 1rem;
        right: 1rem;
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 46;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
    }

    .hamburger-btn:hover {
        background: #6C63FF;
        color: white;
    }

    .hamburger-btn:active {
        transform: scale(0.95);
    }

    @media (min-width: 1024px) {
        .hamburger-btn {
            display: none;
        }
    }

    .sidebar .close-btn {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 51;
        border: none;
    }

    .sidebar .close-btn:hover {
        background: #e5e7eb;
    }

    .sidebar .close-btn i {
        color: #4b5563;
        font-size: 1.1rem;
    }

    body.sidebar-open {
        overflow: hidden;
    }
    
    .main-content {
        height: 100vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .product-card {
        position: relative;
        z-index: 1;
    }
</style>

<x-templte/>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[60]">
    <div class="bg-white p-8 rounded-2xl text-center">
        <div class="spinner mx-auto mb-4"></div>
        <p class="text-gray-600">جاري تحديث الطلب...</p>
    </div>
</div>

<!-- Main Content -->
<main id="mainContent" class="main-content">
    <div class="flex flex-col lg:flex-row gap-4 p-4 min-h-full">

        {{-- ========== PRODUCTS SECTION (60%) ========== --}}
        <div class="lg:w-3/5 bg-white rounded-2xl shadow-lg p-4 flex flex-col h-full overflow-hidden">
            <!-- Header with back button -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit ml-2 text-amber-500"></i>
                    تعديل الطلب #{{ $order->order_number }}
                </h2>
                <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-[#6C63FF] transition flex items-center gap-1">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
            </div>

            <!-- Search -->
            <div class="mb-4">
                <div class="relative">
                    <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           id="searchInput"
                           placeholder="ابحث عن صنف..." 
                           class="w-full pr-12 pl-4 py-4 border-2 border-gray-200 rounded-2xl focus:border-[#6C63FF] focus:outline-none text-lg">
                </div>
            </div>

            <!-- Categories -->
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2 scrollbar-custom">
                <button class="category-btn active px-6 py-3 bg-[#6C63FF] text-white rounded-xl font-bold whitespace-nowrap touch-button" data-category="all">
                    الكل
                </button>
                @php
                    $categories = $products->pluck('category')->unique();
                @endphp
                @foreach($categories as $category)
                <button class="category-btn px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-[#6C63FF] hover:text-white transition whitespace-nowrap touch-button" 
                        data-category="{{ $category }}">
                    {{ $category }}
                </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 overflow-y-auto scrollbar-custom p-1 flex-1" id="productsGrid">
                @foreach($products as $product)
                <div class="product-card bg-gradient-to-br from-white to-gray-50 border-2 border-gray-100 rounded-2xl p-4 cursor-pointer touch-button hover:shadow-lg
                            {{ (!$product->track_inventory || $product->quantity > 0) ? '' : 'out-of-stock' }}"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-category="{{ $product->category }}"
                     data-stock="{{ $product->quantity }}"
                     data-track="{{ $product->track_inventory }}"
                     data-available="{{ (!$product->track_inventory || $product->quantity > 0) ? 'true' : 'false' }}">

                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-[#6C63FF]/20 to-[#FF6B6B]/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-utensils text-3xl text-[#6C63FF]"></i>
                    </div>

                    <h3 class="font-bold text-gray-800 text-center text-lg mb-1">{{ $product->name }}</h3>
                    <p class="text-[#6C63FF] font-bold text-center text-xl">
                        {{ number_format($product->price, 2) }} <span class="text-sm">ر.س</span>
                    </p>

                    @if($product->track_inventory)
                        <div class="text-center mt-2">
                            <span class="inline-block px-3 py-1 text-xs rounded-full 
                                @if($product->quantity > 10) bg-green-100 text-green-600
                                @elseif($product->quantity > 0) bg-yellow-100 text-yellow-600
                                @else bg-red-100 text-red-600 @endif">
                                <i class="fas 
                                    @if($product->quantity > 10) fa-check-circle
                                    @elseif($product->quantity > 0) fa-exclamation-circle
                                    @else fa-times-circle @endif ml-1">
                                </i>
                                {{ $product->quantity > 0 ? $product->quantity . ' متبقي' : 'غير متوفر' }}
                            </span>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- ========== ORDER EDIT SECTION (40%) ========== --}}
        <div class="lg:w-2/5 bg-white rounded-2xl shadow-lg flex flex-col h-full overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-l from-amber-500 to-orange-500 p-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i>
                    تعديل الطلب
                    <span class="bg-white text-amber-600 text-sm px-3 py-1 rounded-full mr-auto" id="itemCount">0</span>
                </h2>
            </div>

            <!-- Order Items Table -->
            <div class="flex-1 overflow-y-auto scrollbar-custom p-4" id="orderItemsContainer">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="p-3 text-right">الصنف</th>
                            <th class="p-3 text-center">السعر</th>
                            <th class="p-3 text-center">الكمية</th>
                            <th class="p-3 text-center">الإجمالي</th>
                            <th class="p-3 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsList"></tbody>
                </table>

                <div id="emptyOrder" class="text-center py-12 hidden">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-basket text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 text-lg">لم يتم إضافة أي أصناف</p>
                    <p class="text-gray-400">اضغط على الأصناف لإضافتها</p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600">المجموع الفرعي:</span>
                    <span class="font-bold text-xl" id="subtotal">0.00 ر.س</span>
                </div>

                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600">الضريبة (%):</span>
                    <div class="flex items-center gap-2">
                        <input type="number" 
                               id="taxRate" 
                               value="{{ $order->tax ? ($order->subtotal > 0 ? round(($order->tax / $order->subtotal) * 100, 2) : 0) : 0 }}" 
                               min="0" 
                               max="100" 
                               step="0.1"
                               class="w-20 p-2 border-2 border-gray-200 rounded-lg text-left focus:border-[#6C63FF] focus:outline-none">
                        <span>%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-3 text-sm text-gray-500">
                    <span>قيمة الضريبة:</span>
                    <span id="taxAmount">{{ number_format($order->tax, 2) }} ر.س</span>
                </div>

                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600">الخصم:</span>
                    <input type="number" 
                           id="discount" 
                           value="{{ $order->discount }}" 
                           min="0" 
                           step="0.5"
                           class="w-24 p-2 border-2 border-gray-200 rounded-lg text-left focus:border-[#6C63FF] focus:outline-none">
                </div>

                <div class="flex justify-between items-center mb-4 pt-3 border-t-2 border-gray-200">
                    <span class="font-bold text-lg">الإجمالي النهائي:</span>
                    <span class="font-bold text-2xl text-amber-600" id="grandTotal">{{ number_format($order->total, 2) }} ر.س</span>
                </div>

                <!-- Notes -->
                <textarea id="orderNotes" 
                          placeholder="ملاحظات إضافية (اختياري)..."
                          class="w-full p-3 border-2 border-gray-200 rounded-xl mb-3 focus:border-[#6C63FF] focus:outline-none"
                          rows="2">{{ $order->notes }}</textarea>

                <!-- Order Type (values match database: local, takeaway, delivery) -->
                <select id="orderType" class="w-full p-3 border-2 border-gray-200 rounded-xl mb-3 focus:border-[#6C63FF] focus:outline-none text-gray-700">
                    <option value="local" {{ $order->order_type == 'local' ? 'selected' : '' }}>داخل المطعم</option>
                    <option value="takeaway" {{ $order->order_type == 'takeaway' ? 'selected' : '' }}>سفري</option>
                    <option value="delivery" {{ $order->order_type == 'delivery' ? 'selected' : '' }}>توصيل</option>
                </select>

                <!-- Payment Method -->
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <button class="payment-method-btn {{ $order->payment_method == 'cash' ? 'active bg-[#6C63FF] text-white' : 'bg-gray-100 text-gray-700' }} p-3 rounded-xl font-bold touch-button" data-method="cash">
                        <i class="fas fa-money-bill-wave ml-1"></i>
                        نقدي
                    </button>
                    <button class="payment-method-btn {{ $order->payment_method == 'card' ? 'active bg-[#6C63FF] text-white' : 'bg-gray-100 text-gray-700' }} p-3 rounded-xl font-bold touch-button" data-method="card">
                        <i class="fas fa-credit-card ml-1"></i>
                        بطاقة
                    </button>
                    <button class="payment-method-btn {{ $order->payment_method == 'wallet' ? 'active bg-[#6C63FF] text-white' : 'bg-gray-100 text-gray-700' }} p-3 rounded-xl font-bold touch-button" data-method="wallet">
                        <i class="fas fa-wallet ml-1"></i>
                        محفظة
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="updateOrder()" 
                            class="bg-amber-500 text-white p-4 rounded-xl font-bold text-lg hover:bg-amber-600 transition-all touch-button flex items-center justify-center gap-2"
                            id="updateBtn">
                        <i class="fas fa-save"></i>
                        تحديث الطلب
                    </button>
                    
                    <button onclick="clearOrder()" 
                            class="bg-gray-200 text-gray-700 p-4 rounded-xl font-bold text-lg hover:bg-gray-300 transition-all touch-button flex items-center justify-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        تفريغ
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const body = document.body;
        
        if (sidebar) {
            function openSidebar() {
                sidebar.classList.add('open');
                if (overlay) overlay.classList.add('active');
                body.classList.add('sidebar-open');
            }
            
            function closeSidebar() {
                sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
                body.classList.remove('sidebar-open');
            }
            
            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openSidebar();
                });
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebar();
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebar();
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
            
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.add('open');
                    if (overlay) overlay.classList.remove('active');
                    body.classList.remove('sidebar-open');
                } else {
                    sidebar.classList.remove('open');
                    if (overlay) overlay.classList.remove('active');
                    body.classList.remove('sidebar-open');
                }
            });

            if (window.innerWidth >= 1024) {
                sidebar.classList.add('open');
            }
        }

        // ==================== ORDER EDIT JAVASCRIPT ====================
        // Prepare order items array in PHP and pass to JavaScript
        @php
            $orderItemsArray = $order->items->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product_name,
                    'price' => (float) $item->price,
                    'quantity' => (int) $item->quantity,
                    'total' => (float) $item->total
                ];
            })->values();
        @endphp

        // Initialize order items from PHP
        let orderItems = @json($orderItemsArray);
        let selectedPaymentMethod = '{{ $order->payment_method }}';

        // DOM Elements
        const productsGrid = document.getElementById('productsGrid');
        const orderItemsList = document.getElementById('orderItemsList');
        const emptyOrder = document.getElementById('emptyOrder');
        const itemCount = document.getElementById('itemCount');
        const subtotalEl = document.getElementById('subtotal');
        const taxRate = document.getElementById('taxRate');
        const taxAmount = document.getElementById('taxAmount');
        const discount = document.getElementById('discount');
        const grandTotal = document.getElementById('grandTotal');
        const orderNotes = document.getElementById('orderNotes');
        const orderType = document.getElementById('orderType');
        const updateBtn = document.getElementById('updateBtn');

        // Initialize
        renderOrderTable();
        updateTotals();

        // Category filters
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('active', 'bg-[#6C63FF]', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                this.classList.add('active', 'bg-[#6C63FF]', 'text-white');
                this.classList.remove('bg-gray-100', 'text-gray-700');
                
                filterByCategory(this.dataset.category);
            });
        });

        // Search input
        document.getElementById('searchInput').addEventListener('input', function() {
            searchProducts(this.value);
        });

        // Payment method buttons
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.payment-method-btn').forEach(b => {
                    b.classList.remove('active', 'bg-[#6C63FF]', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                this.classList.add('active', 'bg-[#6C63FF]', 'text-white');
                this.classList.remove('bg-gray-100', 'text-gray-700');
                
                selectedPaymentMethod = this.dataset.method;
            });
        });

        // Tax and discount listeners
        taxRate.addEventListener('input', updateTotals);
        discount.addEventListener('input', updateTotals);

        // Product card click
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                addToOrder(this);
            });
        });

        // ==================== FUNCTIONS ====================
        function filterByCategory(category) {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchProducts(term) {
            term = term.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                card.style.display = name.includes(term) ? 'block' : 'none';
            });
        }

        function addToOrder(productCard) {
            if (productCard.dataset.available === 'false') {
                showNotification('error', 'هذا الصنف غير متوفر حالياً');
                return;
            }

            const productId = parseInt(productCard.dataset.id);
            const productName = productCard.dataset.name;
            const productPrice = parseFloat(productCard.dataset.price);
            const trackStock = productCard.dataset.track === '1';
            const currentStock = parseInt(productCard.dataset.stock);

            if (trackStock && currentStock <= 0) {
                showNotification('error', 'هذا الصنف غير متوفر حالياً');
                return;
            }

            const existingItem = orderItems.find(item => item.id === productId);

            if (existingItem) {
                if (trackStock && existingItem.quantity >= currentStock) {
                    showNotification('error', 'لا يمكن إضافة المزيد - الكمية غير متوفرة');
                    return;
                }
                existingItem.quantity++;
                existingItem.total = existingItem.price * existingItem.quantity;
            } else {
                orderItems.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    total: productPrice
                });
            }

            renderOrderTable();
            updateTotals();

            productCard.classList.add('border-amber-500');
            setTimeout(() => {
                productCard.classList.remove('border-amber-500');
            }, 200);
        }

        function renderOrderTable() {
            if (orderItems.length === 0) {
                emptyOrder.classList.remove('hidden');
                orderItemsList.innerHTML = '';
                itemCount.textContent = '0';
                return;
            }

            emptyOrder.classList.add('hidden');

            let html = '';
            orderItems.forEach((item, index) => {
                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium">${item.name}</td>
                        <td class="p-3 text-center">${item.price.toFixed(2)}</td>
                        <td class="p-3">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="decreaseQuantity(${index})" 
                                        class="qty-btn bg-red-100 text-red-600 hover:bg-red-200 touch-button">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="font-bold w-8 text-center">${item.quantity}</span>
                                <button onclick="increaseQuantity(${index})" 
                                        class="qty-btn bg-green-100 text-green-600 hover:bg-green-200 touch-button">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="p-3 text-center font-bold">${item.total.toFixed(2)}</td>
                        <td class="p-3">
                            <button onclick="removeItem(${index})" 
                                    class="remove-btn hover:bg-red-50 touch-button">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            orderItemsList.innerHTML = html;
            itemCount.textContent = orderItems.reduce((sum, item) => sum + item.quantity, 0);
        }

        window.increaseQuantity = function(index) {
            const item = orderItems[index];
            const productCard = document.querySelector(`.product-card[data-id="${item.id}"]`);

            if (productCard && productCard.dataset.track === '1') {
                const currentStock = parseInt(productCard.dataset.stock);
                if (item.quantity >= currentStock) {
                    showNotification('error', 'لا يمكن إضافة المزيد - الكمية غير متوفرة');
                    return;
                }
            }

            item.quantity++;
            item.total = item.price * item.quantity;
            renderOrderTable();
            updateTotals();
        };

        window.decreaseQuantity = function(index) {
            const item = orderItems[index];
            if (item.quantity > 1) {
                item.quantity--;
                item.total = item.price * item.quantity;
                renderOrderTable();
            } else {
                removeItem(index);
            }
            updateTotals();
        };

        window.removeItem = function(index) {
            orderItems.splice(index, 1);
            renderOrderTable();
            updateTotals();
        };

        function clearOrder() {
            if (orderItems.length === 0) return;
            if (confirm('هل أنت متأكد من تفريغ الطلب؟')) {
                orderItems = [];
                renderOrderTable();
                updateTotals();
                taxRate.value = 0;
                discount.value = 0;
                orderNotes.value = '';
            }
        }

        function updateTotals() {
            const subtotal = orderItems.reduce((sum, item) => sum + item.total, 0);
            const taxPercent = parseFloat(taxRate.value) || 0;
            const taxValue = subtotal * (taxPercent / 100);
            const discountValue = parseFloat(discount.value) || 0;
            const total = subtotal + taxValue - discountValue;

            subtotalEl.textContent = subtotal.toFixed(2) + ' ر.س';
            taxAmount.textContent = taxValue.toFixed(2) + ' ر.س';
            grandTotal.textContent = total.toFixed(2) + ' ر.س';
        }

        async function updateOrder() {
            if (orderItems.length === 0) {
                showNotification('error', 'الرجاء إضافة أصناف إلى الطلب');
                return;
            }

            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
            document.getElementById('loadingOverlay').style.display = 'flex';

            try {
                const orderData = {
                    items: orderItems.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity
                    })),
                    tax: parseFloat(taxRate.value) || 0,
                    discount: parseFloat(discount.value) || 0,
                    payment_method: selectedPaymentMethod,
                    order_type: orderType.value,
                    notes: orderNotes.value,
                    _method: 'PUT'
                };

                const response = await fetch('{{ route("orders.update", $order->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'حدث خطأ في تحديث الطلب');
                }

                showNotification('success', 'تم تحديث الطلب بنجاح');
                setTimeout(() => {
                    window.location.href = '{{ route("orders.index") }}';
                }, 1500);

            } catch (error) {
                showNotification('error', error.message);
            } finally {
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save"></i> تحديث الطلب';
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        }

        window.updateOrder = updateOrder;
        window.clearOrder = clearOrder;

        function showNotification(type, message) {
            let notification = document.getElementById('notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'notification';
                document.body.appendChild(notification);
            }
            notification.className = `notification ${type} show`;
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            `;

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
    });
</script>