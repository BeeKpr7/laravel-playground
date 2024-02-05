<?php

abstract class Simulateur
{

    public function getNumberPart(): int
    {
        $parts = $this->nb_parts + $this->nb_enfants * 0.5;

        if ($this->nb_enfants > 2)
            $parts = $this->nb_parts + ($this->nb_enfants - 2) * 1 + 1;

        if ($this->isMaried)
            $parts += 1;

        $this->nb_parts = $parts;
    }
}