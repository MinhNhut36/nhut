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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('grades', []) as $index => $data) {
                // Collect scores
                $scores = [
                    $data['listening_score'] ?? null,
                    $data['speaking_score']  ?? null,
                    $data['writing_score']   ?? null,
                    $data['reading_score']   ?? null,
                ];
                $filledCount = collect($scores)->filter(fn($s) => $s !== null && $s !== '')->count();

                // If partially filled, require all four
                if ($filledCount > 0 && $filledCount < 4) {
                    $validator->errors()->add(
                        "grades.$index.listening_score",
                        'Nếu nhập một kỹ năng, cần nhập đủ cả 4 kỹ năng (Nghe, Nói, Viết, Đọc).'
                    );
                }

                // If all four filled, require exam_date
                if ($filledCount === 4 && empty($data['exam_date'])) {
                    $validator->errors()->add(
                        "grades.$index.exam_date",
                        'Vui lòng nhập ngày thi nếu đã nhập đủ 4 kỹ năng.'
                    );
                }
            }
        });
    }
}
