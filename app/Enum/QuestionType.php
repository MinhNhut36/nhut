<?php

namespace App\Enum;

enum QuestionType: string
{
    case SingleChoice = 'single_choice';
    case Matching = 'matching';
    case Classification = 'classification';
    case FillBlank = 'fill_blank';
    case Arrangement = 'arrangement';
    case ImageWord = 'image_word';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Trắc nghiệm 1 đáp án',
            self::Matching => 'Nối từ',
            self::Classification => 'Phân loại từ',
            self::FillBlank => 'Điền vào chỗ trống',
            self::Arrangement => 'Sắp xếp câu',
            self::ImageWord => 'Hình ảnh thành từ',
        };
    }
}
