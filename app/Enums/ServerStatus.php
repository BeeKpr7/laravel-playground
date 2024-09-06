<?php

namespace App\Enums;

enum ServerStatus: int
{
    case ONLINE = 1;
    case OFFLINE = 2;
    case MAINTENANCE = 3;

    public function label(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::OFFLINE => 'Offline',
            self::MAINTENANCE => 'Maintenance',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ONLINE => 'green',
            self::OFFLINE => 'red',
            self::MAINTENANCE => 'yellow',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ONLINE => 'check',
            self::OFFLINE => 'x',
            self::MAINTENANCE => 'wrench',
        };
    }

    public function is(ServerStatus $value): bool
    {
        return $this->value === $value;
    }
}
