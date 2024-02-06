<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\FiscaliteType;

class Simulateur extends Component
{
    public int $revenu_net_imposable;
    public int $nb_enfant = 0;
    public float $nb_part = 1;
    public float $impot = 0;
    public float $impot_correction = 0;
    public bool $isMaried = false;
    public bool $isAlone = true;
    public bool $isInvalide = false;
    public float $revenu_foncier = 0;
    public float $sociaux = 0;
    public int $decote_imposition = 0;
    public int $plafond_quotient_familial = 0;

    public array $tranches = [
        '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
        '1' => ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1242.34],
        '2' => ['min' => 28797, 'max' => 82341, 'taux' => 0.30, 'forfaitaire' => 6713.77],
        '3' => ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15771.28],
        '4' => ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 22855.52],
    ];
    public float $prelevement_sociaux = 0.172;
    public array $impot_decote = [
        'seul'   => ['seuil' => 1930, 'deduction' => 873, 'taux' => 0.4525],
        'mariee' => ['seuil' => 3192, 'deduction' => 1444, 'taux' => 0.4525]
    ];

    public function mount()
    {
        $this->simulateur = new Simulateur(FiscaliteType::DEFICIT_FONCIER);
    }

    public function updated($field)
    {
        $this->revenu_foncier = $this->revenu_foncier ?? 0;

        if ($this->nb_enfant <= 0)
            $this->nb_enfant = 0;
        if ($this->revenu_foncier <= 0)
            $this->revenu_foncier = 0;

        $this->nb_part = $this->nb_part ?? 1;

        if ($field == 'nb_enfant')
            $this->getPart();

        if ($field == 'isMaried')
            $this->isAlone = !$this->isAlone;

        if ($field == 'isAlone')
            $this->isMaried = !$this->isMaried;

        if ($field != 'nb_part')
            $this->getPart();

        $this->impot = $this->calculateur();

        // $this->set_plafond_quotient_familial();
    }

    //Calcul le nombre de part fiscale
    public function getPart(): int
    {
        $parts = 1 + $this->nb_enfant * 0.5;

        if ($this->nb_enfant > 2)
            $parts = 2 + ($this->nb_enfant - 2) * 1;

        if ($this->isMaried)
            $parts += 1;

        if ($this->isInvalide)
            $parts += 0.5;

        if ($this->isAlone && $this->nb_enfant > 0)
            $parts += 0.5;

        $this->nb_part = $parts;

        return $parts;
    }

    //Calcul le quotient
    public function getQuotient(): int
    {
        return $this->revenu_net_imposable / $this->nb_part;
    }

    //Retourn la tranche d'imposition
    public function getTranches(): array
    {
        foreach ($this->tranches as $tranche) {
            if ($this->getQuotient() <= $tranche['montant']) {
                return $tranche;
            }
        }
        return [];
    }

    //Retourne le montant des prélévements sociaux
    public function getPrelevementSociaux(): float
    {
        if ($this->revenu_foncier >= 0) {

            $this->sociaux = $this->revenu_foncier * $this->prelevement_sociaux;

            return $this->sociaux;
        }

        return 0;
    }

    public function calculateur(): int
    {
        $impot = $this->calcul_impot();

        $impot = $this->calcul_plafond_quotient_familial($impot);

        $impot -= $this->calcul_decote($impot);

        $impot += $this->getPrelevementSociaux();

        return round($impot);
    }

    //Calcul l'impot en fonction du revenu et du nombre de part
    public function calcul_impot(int $nb_part = null, int $revenu = null): int
    {
        $revenu = $revenu ? $revenu : $this->revenu_net_imposable ?? 0;

        $nb_part = $nb_part ? $nb_part : $this->nb_part;
        $quotient = $revenu / $nb_part;


        foreach ($this->tranches as $tranche) {

            if ($quotient <= $tranche['max'] && $quotient > $tranche['min']) {
                $impot = ($revenu * $tranche['taux']) - ($tranche['forfaitaire'] * $nb_part);

                return round($impot);
            }
        }
        return 0;
    }

    //Calcul la decote en fonction de l'impot
    public function calcul_decote(int $impot, bool $isMaried = false): int
    {

        $isMaried = $isMaried ? $isMaried : $this->isMaried;

        //En fonction de la situation maritale on recupere le decote correspondant
        $decote = $isMaried ? $this->impot_decote['mariee'] : $this->impot_decote['seul'];

        //Si l'impot est inferieur au seuil maximal de la decote
        //Donc eligibilité a la decote
        if ($impot < $decote['seuil']) {
            //On calcul la decote
            $result = round($decote['deduction'] - $impot * $decote['taux']);
            if ($impot - $result < 0) {
                $this->decote_imposition = 0;
                return 0;
            }
            $this->decote_imposition = $result;
            return $result;
        }

        //Sinon on retourne 0
        $this->decote_imposition = 0;
        return 0;
    }

    public function calcul_plafond_quotient_familial(int $impot_non_corrigé): int
    {
        $nb_part = 1;
        if ($this->isMaried)
            $nb_part += 1;

        //Calcul de l'impot sans benefice 
        $impot_sans_benefice = $this->calcul_impot($nb_part);

        //Calcul de l'avatage fiscale
        $avantage_fiscale = $impot_sans_benefice - $impot_non_corrigé;

        //Calcul du nombre de demi part
        $nombre_demi_part = $this->nb_enfant * 1;
        if ($this->nb_enfant > 2)
            $nombre_demi_part = ($this->nb_enfant - 2) * 2 + 2;

        //Calcul de l'avantage plafonné
        $avantage_plafonne = 1759 * $nombre_demi_part;

        //Si le contribuable est seul et a des enfants
        //On ajoute 4149 pour le premier enfant
        if ($this->isAlone && $this->nb_enfant > 0)
            $avantage_plafonne = 4149 + 1759 * ($nombre_demi_part - 1);

        //Si l'avantage fiscale est superieur a l'avantage plafonné
        //On applique le plafond
        if ($avantage_fiscale > $avantage_plafonne) {
            $this->plafond_quotient_familial = $avantage_plafonne;
            return $impot_sans_benefice - $avantage_plafonne;
        }
        //Sinon on retourne l'impot non corrigé
        //Et on met le plafond a 0
        $this->plafond_quotient_familial = 0;
        return $impot_non_corrigé;
    }

    public function calcul_quotient(int $revenu, int $nb_part = 1): int
    {
        return round($revenu / $nb_part);
    }

    public function render()
    {
        return view('livewire.simulateur')->extends('partials.layout');
        ;
    }
}
