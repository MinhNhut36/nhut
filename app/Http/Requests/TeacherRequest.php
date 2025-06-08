<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:6|regex:/^[a-zA-Z0-9]+$/',
        ];
    }
      public function messages(): array
    {
        return [
            'username.required' => 'Tên đăng nhập không được để trống', 
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.regex' => 'Mật khẩu chỉ được chứa chữ cái và số',
        ];
    }
}
