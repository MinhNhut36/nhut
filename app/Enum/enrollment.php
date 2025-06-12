<?php

namespace App\Enum;

enum enrollment: int
{
    // chờ xác thực: 0 , đang học: 1, đạt:2, không đạt:3
    case verifying = 0;
    case studying = 1;
    case pass = 2;
    case fail =3;

    public function getEnrollment(): string
    {
        return match ($this) {
            self::verifying => 'Chờ xác thực',
            self::studying => 'Đang học',
            self::pass => 'Đạt',
            self::fail => 'Không đạt',
        };
    }

    public function getStatus(): string
    {
        return match ($this) {
            self::verifying => 'card-status-verifying',
            self::studying => 'card-status-studying',
            self::pass => 'card-status-pass',
            self::fail => 'card-status-fail',
        };
    }
}
