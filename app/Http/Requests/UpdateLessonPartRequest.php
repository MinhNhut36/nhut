<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonPartRequest extends FormRequest
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
            'part_type' => 'required|string|max:255',
            'content' => 'required|string',
            'order_index' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'part_type.required' => 'Loại phần học không được để trống.',
            'content.required' => 'Nội dung không được để trống.',
            'order_index.required' => 'Thứ tự không được để trống.',
            'order_index.integer' => 'Thứ tự phải là số nguyên.',
            'order_index.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        ];
    }
}
