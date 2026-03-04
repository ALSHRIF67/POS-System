<?php
// app/Http/Requests/MenuItemRequest.php (Optional - Form Request)

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'track_inventory' => 'sometimes|boolean',
        ];

        // Add quantity rules based on track_inventory
        if ($this->track_inventory) {
            $rules['quantity'] = 'required|integer|min:0';
        } else {
            $rules['quantity'] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The item name is required.',
            'price.required' => 'The price is required.',
            'price.min' => 'The price must be greater than 0.',
            'category.required' => 'The category is required.',
            'quantity.required' => 'Quantity is required when inventory tracking is enabled.',
            'quantity.min' => 'Quantity cannot be negative.',
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->track_inventory) {
            $this->merge(['quantity' => null]);
        }
    }
}