<?php

namespace App\Enum;

enum personStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getStatus(): string
    {
        return match ($this) {
            self::ACTIVE => 'Hoạt dộng',
            self::INACTIVE => 'Đã khóa',
        };
    }
    public function badgeClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'status-active',
            self::INACTIVE => 'status-inactive',
        };
    }
}
