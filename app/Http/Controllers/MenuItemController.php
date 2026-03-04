<?php
// app/Http/Controllers/MenuItemController.php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    /**
     * Display the menu management view with all items.
     */
    public function index(): View
    {
        $menuItems = MenuItem::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'category' => $item->category,
                'category_arabic' => $this->getArabicCategory($item->category),
                'category_icon' => $this->getCategoryIcon($item->category),
                'track_inventory' => $item->track_inventory,
                'quantity' => $item->quantity,
                'status' => $item->status,
                'status_text' => $this->getArabicStatus($item->status),
                'status_class' => $this->getStatusClass($item->status),
                'is_available' => $item->is_available,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i')
            ];
        });

        $stats = [
            'total_items' => MenuItem::count(),
            'low_stock' => MenuItem::all()->filter(fn($item) => $item->status === 'low_stock')->count(),
            'out_of_stock' => MenuItem::all()->filter(fn($item) => $item->status === 'out_of_stock')->count(),
            'unlimited' => MenuItem::where('track_inventory', false)->count(),
        ];

        return view('menu.menu-management', compact('menuItems', 'stats'));
    }

    /**
     * Get dashboard statistics for AJAX requests.
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total_items' => MenuItem::count(),
                'low_stock' => MenuItem::all()->filter(fn($item) => $item->status === 'low_stock')->count(),
                'out_of_stock' => MenuItem::all()->filter(fn($item) => $item->status === 'out_of_stock')->count(),
                'unlimited' => MenuItem::where('track_inventory', false)->count(),
                'available' => MenuItem::all()->filter(fn($item) => $item->status === 'available')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0.01',
                'category' => 'required|string|in:appetizer,main,drink,dessert',
                'track_inventory' => 'sometimes|boolean',
                'quantity' => [
                    'nullable',
                    'integer',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->track_inventory && $value === null) {
                            $fail('Quantity is required when inventory tracking is enabled.');
                        }
                        if (!$request->track_inventory && $value !== null) {
                            $fail('Quantity must be null when inventory tracking is disabled.');
                        }
                    },
                ],
            ], [
                'name.required' => 'اسم العنصر مطلوب',
                'price.required' => 'السعر مطلوب',
                'price.min' => 'السعر يجب أن يكون أكبر من 0',
                'category.required' => 'التصنيف مطلوب',
                'category.in' => 'التصنيف غير صحيح',
                'quantity.min' => 'الكمية يجب أن تكون 0 أو أكثر',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            if (!$request->track_inventory) {
                $data['quantity'] = null;
            }

            $menuItem = MenuItem::create($data);

            // Prepare response with Arabic fields
            $responseData = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'category' => $menuItem->category,
                'category_arabic' => $this->getArabicCategory($menuItem->category),
                'category_icon' => $this->getCategoryIcon($menuItem->category),
                'track_inventory' => $menuItem->track_inventory,
                'quantity' => $menuItem->quantity,
                'status' => $menuItem->status,
                'status_text' => $this->getArabicStatus($menuItem->status),
                'status_class' => $this->getStatusClass($menuItem->status),
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العنصر بنجاح',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إضافة العنصر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified menu item.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $menuItem = MenuItem::find($id);

            if (!$menuItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }

            $responseData = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'category' => $menuItem->category,
                'category_arabic' => $this->getArabicCategory($menuItem->category),
                'category_icon' => $this->getCategoryIcon($menuItem->category),
                'track_inventory' => $menuItem->track_inventory,
                'quantity' => $menuItem->quantity,
                'status' => $menuItem->status,
                'status_text' => $this->getArabicStatus($menuItem->status),
                'status_class' => $this->getStatusClass($menuItem->status),
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العنصر بنجاح',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب العنصر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $menuItem = MenuItem::find($id);

            if (!$menuItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0.01',
                'category' => 'sometimes|required|string|in:appetizer,main,drink,dessert',
                'track_inventory' => 'sometimes|boolean',
                'quantity' => [
                    'nullable',
                    'integer',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request, $menuItem) {
                        $trackInventory = $request->track_inventory ?? $menuItem->track_inventory;
                        if ($trackInventory && $value === null) {
                            $fail('Quantity is required when inventory tracking is enabled.');
                        }
                        if (!$trackInventory && $value !== null) {
                            $fail('Quantity must be null when inventory tracking is disabled.');
                        }
                    },
                ],
            ], [
                'name.required' => 'اسم العنصر مطلوب',
                'price.required' => 'السعر مطلوب',
                'price.min' => 'السعر يجب أن يكون أكبر من 0',
                'category.required' => 'التصنيف مطلوب',
                'category.in' => 'التصنيف غير صحيح',
                'quantity.min' => 'الكمية يجب أن تكون 0 أو أكثر',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            $trackInventory = $request->track_inventory ?? $menuItem->track_inventory;
            if (!$trackInventory) {
                $data['quantity'] = null;
            }

            $menuItem->update($data);

            // Prepare response with Arabic fields
            $responseData = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'category' => $menuItem->category,
                'category_arabic' => $this->getArabicCategory($menuItem->category),
                'category_icon' => $this->getCategoryIcon($menuItem->category),
                'track_inventory' => $menuItem->track_inventory,
                'quantity' => $menuItem->quantity,
                'status' => $menuItem->status,
                'status_text' => $this->getArabicStatus($menuItem->status),
                'status_class' => $this->getStatusClass($menuItem->status),
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث العنصر بنجاح',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث العنصر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $menuItem = MenuItem::find($id);

            if (!$menuItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }

            $menuItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العنصر بنجاح'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف العنصر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Arabic category name.
     */
    private function getArabicCategory(string $category): string
    {
        return match($category) {
            'appetizer' => 'مقبلات',
            'main' => 'أطباق رئيسية',
            'drink' => 'مشروبات',
            'dessert' => 'حلويات',
            default => $category
        };
    }

    /**
     * Get category icon.
     */
    private function getCategoryIcon(string $category): string
    {
        return match($category) {
            'appetizer' => '🍢',
            'main' => '🍖',
            'drink' => '🥤',
            'dessert' => '🍰',
            default => '🍽️'
        };
    }

    /**
     * Get Arabic status text.
     */
    private function getArabicStatus(string $status): string
    {
        return match($status) {
            'available' => 'متوفر',
            'low_stock' => 'مخزون منخفض',
            'out_of_stock' => 'غير متوفر',
            'unlimited' => 'غير محدود',
            default => 'غير معروف'
        };
    }

    /**
     * Get status CSS class.
     */
    private function getStatusClass(string $status): string
    {
        return match($status) {
            'available' => 'status-available',
            'low_stock' => 'status-low-stock',
            'out_of_stock' => 'status-out-of-stock',
            'unlimited' => 'status-unlimited',
            default => ''
        };
    }
}