<?php

namespace App\Enum;

enum courseStatus: string
{
    // chờ xác thực: 0 , đang học: 1, đạt:2, không đạt:3
    case verifying = 'Chờ phê duyệt';
    case IsOpening = 'Đang mở lớp';
    case Complete = 'Đã hoàn thành';

    public function BagdeClass(): string
    {
        return match ($this) {
            self::verifying => 'bg-warning text-dark',
            self::IsOpening => 'bg-success',
            self::Complete => 'bg-secondary',
        };
    }
}
