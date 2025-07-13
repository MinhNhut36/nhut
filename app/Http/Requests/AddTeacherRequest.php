<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTeacherRequest extends FormRequest
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
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:0,1',
            'is_status' => 'required|in:0,1',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email giảng viên này đã tồn tại.',
            'date_of_birth.required' => 'Vui lòng chọn ngày sinh.',
            'date_of_birth.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hôm nay.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'is_status.required' => 'Vui lòng chọn trạng thái.',
            'avatar.image' => 'Avatar phải là hình ảnh.',
            'avatar.mimes' => 'Định dạng ảnh hợp lệ là: jpeg, png, jpg, gif.',
        ];
    }
}
