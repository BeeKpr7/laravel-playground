<?php

namespace App\Service;

class TrancheImposition
{
    private int $nombre_enfant = 0;

    private bool $isMariee = false;

    private bool $isInvalid = false;

    private bool $isAlone = false;

    public function __construct()
    {
        $this->setNombrePart();
    }

    private function setNombrePart()
    {
        $part = 1 + $this->nombre_enfant * 0.5;

        if ($this->nombre_enfant > 2) {
            $part = 2 + ($this->nombre_enfant - 2);
        }

        if ($this->isMariee) {
            $part += 1;
        }

        if ($this->isInvalid) {
            $part += 0.5;
        }

        if ($this->isAlone && $this->nombre_enfant > 0) {
            $part += 0.5;
        }

        return $part;
    }
}
