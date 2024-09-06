<?php

namespace App\Enums;

enum FamillyStatus: int
{
    case CELIBATAIRE = 1;
    case MARIE = 2;
    case CONCUBINAGE = 3;
    case VEUF = 4;

    public function label(): string
    {
        return match ($this) {
            self::CELIBATAIRE => 'Célibataire',
            self::MARIE => 'Marié(e)/Pacsé(e)',
            self::CONCUBINAGE => 'Concubinage',
            self::VEUF => 'Veuf(ve)',
        };
    }

    public function is(ServerStatus $value): bool
    {
        return $this->value === $value;
    }
}
