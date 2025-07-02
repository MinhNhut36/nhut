<?php

namespace App\Enum;

enum courseStatus: string
{
    case verifying = 'Chờ xác thực';
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
