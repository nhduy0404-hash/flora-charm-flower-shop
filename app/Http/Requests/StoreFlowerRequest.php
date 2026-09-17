<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlowerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorize handle qua middleware IsAdmin
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'stock'       => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không tồn tại.',
            'name.required'        => 'Vui lòng nhập tên hoa.',
            'name.string'          => 'Tên hoa phải là một chuỗi ký tự.',
            'name.max'             => 'Tên hoa không được vượt quá 255 ký tự.',
            'price.required'       => 'Vui lòng nhập giá hoa.',
            'price.numeric'        => 'Giá hoa phải là một số.',
            'price.min'            => 'Giá hoa không được nhỏ hơn 0.',
            'image.image'          => 'Tập tin tải lên phải là hình ảnh.',
            'image.mimes'          => 'Hình ảnh phải có định dạng: jpeg, png, jpg hoặc webp.',
            'image.max'            => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'stock.required'       => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer'        => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min'            => 'Số lượng tồn kho không được nhỏ hơn 0.',
        ];
    }
}
