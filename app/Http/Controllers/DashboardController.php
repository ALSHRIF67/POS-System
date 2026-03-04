<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get menu items statistics
        $totalMenuItems = MenuItem::count();
        
        // Get inventory statistics from menu items
        $lowStockItems = MenuItem::where('track_inventory', true)
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 4)
            ->count();
            
        $outOfStockItems = MenuItem::where('track_inventory', true)
            ->where('quantity', 0)
            ->count();
            
        $unlimitedItems = MenuItem::where('track_inventory', false)->count();
        
        // Format stats for the cards
        $stats = [
            [
                'title' => 'إجمالي العناصر',
                'value' => $totalMenuItems,
                'icon' => 'fa-utensils',
                'color' => 'from-[#6C63FF] to-[#FF6B6B]',
                'trend' => $this->getTrend()
            ],
            [
                'title' => 'مخزون منخفض',
                'value' => $lowStockItems,
                'icon' => 'fa-exclamation-triangle',
                'color' => 'from-[#FF6B6B] to-[#FFA07A]',
                'trend' => $this->getTrend()
            ],
            [
                'title' => 'غير متوفر',
                'value' => $outOfStockItems,
                'icon' => 'fa-times-circle',
                'color' => 'from-[#4ECDC4] to-[#45B7D1]',
                'trend' => $this->getTrend()
            ],
            [
                'title' => 'غير محدود',
                'value' => $unlimitedItems,
                'icon' => 'fa-infinity',
                'color' => 'from-[#FFD93D] to-[#FFB347]',
                'trend' => $this->getTrend()
            ],
        ];
        
        // Get weekly data (simulated for now)
        $weeklyData = $this->getWeeklyData();
        
        // Get top items (using menu items with highest quantities)
        $topItems = $this->getTopItems();
        
        // Get recent menu items
        $recentItems = $this->getRecentItems();
        
        // Get category distribution
        $categoryStats = $this->getCategoryStats();
        
        return view('dashboard', compact(
            'stats',
            'weeklyData',
            'topItems',
            'recentItems',
            'categoryStats'
        ));
    }
    
    private function getTrend()
    {
        $trends = ['+12%', '+8%', '-3%', '+5%', '+15%', '-2%', '+10%'];
        return $trends[array_rand($trends)];
    }
    
    private function getWeeklyData()
    {
        // Simulate weekly data based on menu items count
        $menuItems = MenuItem::all();
        $totalItems = $menuItems->count();
        
        // Generate random but realistic weekly data
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $data[] = max(1000, ($totalItems * 100) + rand(-500, 500));
        }
        
        return $data;
    }
    
    private function getTopItems()
    {
        $colors = ['#6C63FF', '#FF6B6B', '#4ECDC4', '#FFD93D', '#FFA07A'];
        
        // Get items with inventory
        $items = MenuItem::where('track_inventory', true)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->limit(5)
            ->get();
        
        $result = [];
        foreach ($items as $index => $item) {
            $result[] = [
                'name' => $item->name,
                'quantity' => $item->quantity,
                'revenue' => $item->price * $item->quantity,
                'color' => $colors[$index % count($colors)]
            ];
        }
        
        // If no items with inventory, show some menu items
        if (empty($result)) {
            $items = MenuItem::limit(5)->get();
            foreach ($items as $index => $item) {
                $result[] = [
                    'name' => $item->name,
                    'quantity' => $item->quantity ?? rand(5, 20),
                    'revenue' => $item->price * ($item->quantity ?? rand(5, 20)),
                    'color' => $colors[$index % count($colors)]
                ];
            }
        }
        
        return $result;
    }
    
    private function getRecentItems()
    {
        // Get latest menu items
        $items = MenuItem::latest()
            ->limit(5)
            ->get();
        
        $result = [];
        foreach ($items as $item) {
            $status = $item->status;
            $statusText = $this->getStatusText($status);
            $statusClass = $this->getStatusClass($status);
            
            $result[] = [
                'name' => $item->name,
                'price' => $item->price,
                'category' => $this->getArabicCategory($item->category),
                'status' => $statusText,
                'status_class' => $statusClass,
                'time' => $item->created_at->diffForHumans()
            ];
        }
        
        return $result;
    }
    
    private function getCategoryStats()
    {
        $categories = [
            'appetizer' => 'مقبلات',
            'main' => 'أطباق رئيسية',
            'drink' => 'مشروبات',
            'dessert' => 'حلويات'
        ];
        
        $colors = ['#6C63FF', '#FF6B6B', '#4ECDC4', '#FFD93D'];
        
        $result = [];
        foreach ($categories as $key => $arabicName) {
            $count = MenuItem::where('category', $key)->count();
            if ($count > 0) {
                $result[] = [
                    'name' => $arabicName,
                    'count' => $count,
                    'color' => $colors[array_rand($colors)]
                ];
            }
        }
        
        return $result;
    }
    
    private function getArabicCategory($category)
    {
        return match($category) {
            'appetizer' => 'مقبلات',
            'main' => 'أطباق رئيسية',
            'drink' => 'مشروبات',
            'dessert' => 'حلويات',
            default => $category
        };
    }
    
    private function getStatusText($status)
    {
        return match($status) {
            'available' => 'متوفر',
            'low_stock' => 'مخزون منخفض',
            'out_of_stock' => 'غير متوفر',
            'unlimited' => 'غير محدود',
            default => 'غير معروف'
        };
    }
    
    private function getStatusClass($status)
    {
        return match($status) {
            'available' => 'bg-green-50 text-green-600 border border-green-200',
            'low_stock' => 'bg-yellow-50 text-yellow-600 border border-yellow-200',
            'out_of_stock' => 'bg-red-50 text-red-600 border border-red-200',
            'unlimited' => 'bg-purple-50 text-purple-600 border border-purple-200',
            default => 'bg-gray-50 text-gray-600 border border-gray-200'
        };
    }
}