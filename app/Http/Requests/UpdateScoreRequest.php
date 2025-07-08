<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'grades' => 'required|array',
            'grades.*.student_id'       => 'required|exists:students,student_id',
            'grades.*.listening_score'  => 'nullable|numeric|min:0|max:10',
            'grades.*.speaking_score'   => 'nullable|numeric|min:0|max:10',
            'grades.*.writing_score'    => 'nullable|numeric|min:0|max:10',
            'grades.*.reading_score'    => 'nullable|numeric|min:0|max:10',
            'grades.*.exam_date'        => 'nullable|date',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'grades.required' => 'Dữ liệu điểm là bắt buộc.',
            'grades.array' => 'Dữ liệu điểm phải là mảng.',

            'grades.*.student_id.required' => 'Thiếu mã số sinh viên.',
            'grades.*.student_id.exists' => 'Mã số sinh viên không tồn tại.',

            'grades.*.listening_score.numeric' => 'Điểm nghe phải là số.',
            'grades.*.listening_score.min' => 'Điểm nghe phải từ 0 đến 10.',
            'grades.*.listening_score.max' => 'Điểm nghe phải từ 0 đến 10.',

            'grades.*.speaking_score.numeric' => 'Điểm nói phải là số.',
            'grades.*.speaking_score.min' => 'Điểm nói phải từ 0 đến 10.',
            'grades.*.speaking_score.max' => 'Điểm nói phải từ 0 đến 10.',

            'grades.*.writing_score.numeric' => 'Điểm viết phải là số.',
            'grades.*.writing_score.min' => 'Điểm viết phải từ 0 đến 10.',
            'grades.*.writing_score.max' => 'Điểm viết phải từ 0 đến 10.',

            'grades.*.reading_score.numeric' => 'Điểm đọc phải là số.',
            'grades.*.reading_score.min' => 'Điểm đọc phải từ 0 đến 10.',
            'grades.*.reading_score.max' => 'Điểm đọc phải từ 0 đến 10.',

            'grades.*.exam_date.date' => 'Ngày thi không hợp lệ.',
        ];
    }
}
