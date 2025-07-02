<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\courseStatus;
use Illuminate\Validation\Rule;

class EditCourseResquest extends FormRequest
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
        $rules = [
            'course_name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:2020'],
            'description' => ['nullable', 'string'],
            'starts_date' => ['required', 'date'],
        ];

        // Thêm điều kiện ngày >= hôm nay nếu là thêm mới
        if ($this->isMethod('post') && !$this->route('id')) {
            $rules['starts_date'][] = 'after_or_equal:today';
        }

        // Nếu là sửa thì kiểm tra trạng thái
        if ($this->isMethod('post') && $this->route('id')) {
            $rules['status'] = ['required', 'string', Rule::in([
                courseStatus::verifying->value,
                courseStatus::IsOpening->value,
                courseStatus::Complete->value,
            ])];

            $currentCourse = \App\Models\Course::find($this->route('id'));

            if ($currentCourse) {
                $currentStatus = $currentCourse->status instanceof courseStatus
                    ? $currentCourse->status->value
                    : $currentCourse->status;

                $nextValidStatus = match ($currentStatus) {
                    courseStatus::verifying->value => [courseStatus::IsOpening->value],
                    courseStatus::IsOpening->value => [courseStatus::Complete->value],
                    courseStatus::Complete->value => [courseStatus::Complete->value],
                    default => [],
                };

                $requestedStatus = $this->input('status');

                if (!in_array($requestedStatus, $nextValidStatus) && $requestedStatus !== $currentStatus) {
                    $rules['status'][] = function ($attribute, $value, $fail) use ($currentStatus) {
                        $fail("Không thể chuyển trạng thái từ [$currentStatus] sang [$value].");
                    };
                }
            }
        }

        return $rules;
    }


    public function messages(): array
    {
        return [
            'course_name.required' => 'Vui lòng nhập tên khóa học.',

            'level.required' => 'Vui lòng chọn cấp độ.',

            'year.required' => 'Vui lòng nhập năm.',
            'year.integer' => 'Năm phải là số nguyên.',
            'year.min' => 'Năm phải từ 2020 trở đi.',

            'description.string' => 'Mô tả phải là một chuỗi ký tự.',

            'starts_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'starts_date.after_or_equal' => 'Ngày bắt đầu không được nhỏ hơn ngày hiện tại.',
        ];
    }
}
