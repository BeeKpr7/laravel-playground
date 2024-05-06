<?php

namespace App\Service;

class TrancheImposition
{
    private int $nombre_enfant;
    private bool $isMariee;
    private bool $isInvalid;
    private int $nombre_part;
    private int $quotient_imposition;

    public function __construct()
    {
        $this->nombre_enfant = 0;
        $this->isMariee = false;
        $this->isInvalid = false;

        $this->setNombrePart();
    }
    public static function fromRevenu(int $revenu) : self
    {
         
    }

    private function setNombrePart()
    {
        $part = 1 + $this->nombre_enfant * 0.5;

        if ($this->nombre_enfant > 2) {
            $part = 2 + ($this->nombre_enfant - 2) * 1;
        }

        if ($this->isMariee) {
            $part += 1;
        }

        if ($this->isInvalide) {
            $part += 0.5;
        }

        if ($this->isAlone && $this->nombre_enfant > 0) {
            $part += 0.5;
        }

        $this->nb_part = $part;

        return $part;
    }
}