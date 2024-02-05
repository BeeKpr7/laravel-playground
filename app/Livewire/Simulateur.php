<?php

namespace App\Livewire;

use Livewire\Component;

trait Fiscalite
{
    //Retourne le nombre de part en fonction du nombre d'enfant 
    //et de la situation familiale ( marié / ou non)
    public function getPart(int $nb_child = 0, bool $isMaried = false): int
    {
        $parts = 1 + $nb_child * 0.5;

        if ($nb_child > 2)
            $parts = 2 + ($nb_child - 2) * 1;

        if ($isMaried)
            $parts += 1;

        return $parts;
    }

    public function getQuotient(int $revenu, int $nb_part = 1): int
    {
        return round($revenu / $nb_part);
    }
}
class Simulateur extends Component
{
    public int $revenu_net_imposable;
    public int $nb_enfant = 0;
    public float $nb_part = 1;
    public float $impot = 0;
    public bool $isMaried = true;
    public bool $isAlone = true;
    public bool $isInvalide = false;
    public float $revenu_foncier = 0;
    public float $sociaux = 0;

    public array $tranches = [
        '0'  => ['montant' => 10777, 'taux' => 0, 'forfaitaire' => 0],
        '11' => ['montant' => 27478, 'taux' => 0.11, 'forfaitaire' => 1185.47],
        '30' => ['montant' => 78570, 'taux' => 0.30, 'forfaitaire' => 6406.29],
        '40' => ['montant' => 168994, 'taux' => 0.41, 'forfaitaire' => 15048.99],
        '45' => ['montant' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 21808.75],
    ];
    public float $prelevement_sociaux = 0.172;


    public function updated($field)
    {
        $this->revenu_foncier = $this->revenu_foncier ?? 0;

        if ($this->nb_enfant <= 0)
            $this->nb_enfant = 0;
        if ($this->revenu_foncier <= 0)
            $this->revenu_foncier = 0;
        //$this->getPart();

        $this->calculImpot();
    }

    //Calcul le nombre de part fiscale
    public function getPart(): int
    {
        $parts = 1 + $this->nb_enfant * 0.5;

        if ($this->nb_enfant > 2)
            $parts = 2 + ($this->nb_enfant - 2) * 1;

        if ($this->isMaried)
            $parts += 1;

        $this->nb_part = $parts;

        return $parts;
    }

    public function getQuotient(): int
    {
        return $this->revenu_net_imposable / $this->getPart();
    }

    public function getTranches(): array
    {
        foreach ($this->tranches as $tranche) {
            if ($this->getQuotient() <= $tranche['montant']) {
                return $tranche;
            }
        }
        return [];
    }

    public function getPrelevementSociauxs(): float
    {
        if ($this->revenu_foncier >= 0) {

            $this->sociaux = $this->impot = $this->revenu_foncier * $this->prelevement_sociaux;

            return $this->sociaux;
        }

        return 0;
    }

    public function calculImpot()
    {
        $revenu = $this->revenu_net_imposable ?? 0;
        $revenu_foncier = $this->revenu_foncier ?? 0;
        $nb_part = $this->nb_part;
        $quotient = $revenu / $nb_part;

        $this->impot = $this->getPrelevementSociauxs();


        foreach ($this->tranches as $tranche) {
            if ($quotient <= $tranche['montant']) {
                $this->impot += $revenu * $tranche['taux'] - $nb_part * $tranche['forfaitaire'];
                return;
            }
        }
    }
    public function render()
    {
        return view('livewire.simulateur')->extends('partials.layout');
        ;
    }
}
