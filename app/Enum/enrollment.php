<?php

namespace App\Enum;

enum enrollment: int
{
    case studying = 0;
    case completed = 1;
    case stopStudying = 2;
    public function getEnrollment(): string
    {
        return match ($this) {
            self::studying => 'Đang học',
            self::completed => 'Đã hoàn thành',
            self::stopStudying => 'tạm ngưng',
        };
    }
    public function getStatus(): string
    {
        return match ($this) {
            self::studying => 'card-status-studying',
            self::completed => 'card-status-completed',
            self::stopStudying => 'card-status-stopStudying',
        };
    }
}
