<?php

namespace App\Enum;

enum gender :int
{
    case MALE = 1;
    case FEMALE = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Nam', 
            self::FEMALE => 'Nữ', 
        };
    }
     public function getLabelStyles(): string
    {
        return match ($this) {
            self::MALE => 'gender-male', 
            self::FEMALE => 'gender-female', 
        };
    }
}
