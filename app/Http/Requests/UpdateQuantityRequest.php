<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuantityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id'  => 'required|integer|exists:cart_items,id',
            'quantity' => 'required|integer|min:1|max:50',
        ];
    }

    public function messages()
    {
        return [
            'item_id.required'  => 'Field item_id is crequired',
            'item_id.integer'   => 'Field item_id must be an integer',
            'item_id.exists'    => 'Item with item_id doesent exists',
            'quantity.required' => 'Field quantity is required',
            'quantity.integer'  => 'Field quantity must be an integer',
            'quantity.min'      => 'Field quantity cant be less then 1',
            'quantity.max'      => 'Field quantity cant be more then 50',
        ];
    }
}
