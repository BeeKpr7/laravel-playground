<?php

namespace App\Enums;

enum FiscaliteType: int
{
    case DEFICIT_FONCIER = 1;
    case PINEL = 2;
    case PINEL_PLUS = 3;
    case DROIT_COMMUN = 4;

    public function label(): string
    {
        return match ($this) {
            self::DEFICIT_FONCIER => 'Déficit foncier',
            self::PINEL => 'Pinel',
            self::PINEL_PLUS => 'Pinel +',
            self::DROIT_COMMUN => 'Droit commun',
        };
    }

    public function is(FiscaliteType $value): bool
    {
        return $this->value === $value;
    }
}
