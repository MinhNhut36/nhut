<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCourseRequest extends FormRequest
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
        $currentYear = date('Y');
        return [
            'course_name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:' . $currentYear],
            'description' => ['nullable', 'string'],
            'starts_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        $currentYear = date('Y');
        return [
            'course_name.required' => 'Vui lòng nhập tên khóa học.',

            'level.required' => 'Vui lòng chọn cấp độ.',

            'year.required' => 'Vui lòng nhập năm.',
            'year.integer' => 'Năm phải là số nguyên.',
            'year.min' => 'Năm phải từ ' . $currentYear . ' trở đi.',

            'description.string' => 'Mô tả phải là một chuỗi ký tự.',

            'starts_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'starts_date.after_or_equal' => 'Ngày bắt đầu không được nhỏ hơn ngày hôm nay.',
        ];
    }
}
