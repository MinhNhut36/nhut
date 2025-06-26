<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddLevelRequest extends FormRequest
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
    public function rules()
    {
        return [
            'level' => 'required|string|max:10|unique:lessons,level',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'level.required' => 'Vui lòng nhập trình độ.',
            'level.unique' => 'Trình độ này đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'order_index.required' => 'Vui lòng nhập thứ tự.',
            'order_index.integer' => 'Thứ tự phải là số nguyên.',
            'order_index.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        ];
    }
}
