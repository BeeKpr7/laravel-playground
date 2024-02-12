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
    public int $contribution_exceptionel = 0;
    public array $tableau_imposition = [];

    public array $tranches = [
        '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
        '1' => ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1242.34],
        '2' => ['min' => 28797, 'max' => 82341, 'taux' => 0.30, 'forfaitaire' => 6713.77],
        '3' => ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15771.28],
        '4' => ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 22855.52],
    ];
    public float $prelevement_sociaux = 0.172;
    public array $impot_decote = [
        'seul' => ['seuil' => 1939, 'deduction' => 873, 'taux' => 0.4525],
        'mariee' => ['seuil' => 3191, 'deduction' => 1444, 'taux' => 0.4525]
    ];

    public function mount()
    {
        //$this->simulateur = new Simulateur(FiscaliteType::DEFICIT_FONCIER);
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

        // dd($this->tableau_imposition($this->revenu_net_imposable, $this->nb_part));

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
        // $impot = $this->calcul_impot();
        $impot = $this->tableau_imposition();

        $impot = $this->calcul_plafond_quotient_familial($impot);
        
        $impot -= $this->calcul_decote($impot);
        
        $impot += $this->getPrelevementSociaux();
        
        $impot += $this->calcul_contribution_exceptionel();

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
        // $impot_sans_benefice = $this->calcul_impot($nb_part);
        $impot_sans_benefice = $this->tableau_imposition($nb_part);

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
            $this->tableau_imposition($nb_part);
            $this->plafond_quotient_familial = $avantage_plafonne;
            return $impot_sans_benefice - $avantage_plafonne;
        }
        //Sinon on retourne l'impot non corrigé
        //Et on met le plafond a 0
        $this->tableau_imposition();
        $this->plafond_quotient_familial = 0;
        return $impot_non_corrigé;
    }

    public function calcul_quotient(int $revenu, int $nb_part = 1): int
    {
        return round($revenu / $nb_part);
    }

    private array $tranches_contribution_exceptionel = [
            'seul' => [
                ['taux'=> 0 , 'min' => 0, 'max' => 250000],
                ['taux'=> 0.03 , 'min' => 250000, 'max' => 500000],
                ['taux'=> 0.04 , 'min' => 500000, 'max' => 10000000000000],
            ],
            'mariee' => [            
                ['taux'=> 0 , 'min' => 0, 'max' => 250000],
                ['taux'=> 0 , 'min' => 250000, 'max' => 500000],
                ['taux'=> 0.03 , 'min' => 500000, 'max' => 1000000],
                ['taux'=> 0.04 , 'min' => 1000000, 'max' => 10000000000000],
            ]
        ];

    public function calcul_contribution_exceptionel(int $revenu = null, bool $isMariee = false)
    {
        $isMariee = $isMariee ? $isMariee : $this->isMaried;
        $revenu = $revenu ? $revenu : $this->revenu_net_imposable ?? 0;

        $tranches = $this->tranches_contribution_exceptionel[$isMariee ? 'mariee' : 'seul'];

        foreach ($tranches as $tranche) {
            if ($revenu <= $tranche['max'] && $revenu > $tranche['min']) {
                $this->contribution_exceptionel = ($revenu - $tranche['min']) * $tranche['taux'];
                return ($revenu - $tranche['min']) * $tranche['taux'];
            }

        }
        $this->contribution_exceptionel = 0;
        return false;
    }

    public function tableau_imposition(int $nb_part = null, int $revenu = null): int
    {
        $revenu = $revenu ? $revenu : $this->revenu_net_imposable ?? 0;
        $nb_part = $nb_part ? $nb_part : $this->nb_part;
        $revenuRestant = $revenu;
        $impotTotal = 0;

        $tranches = $this->tranches;

        $tableau_imposition = [];
        // '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],

        foreach($tranches as $tranche) {

            // $revenuDansTranche = min($revenuRestant - ($tranche['max']*$nb_part - $tranche['min']*$nb_part));
            if ($revenuRestant > $tranche['max']*$nb_part - $tranche['min']*$nb_part )
                $revenuDansTranche = $tranche['max']*$nb_part - $tranche['min']*$nb_part;   
            else
                $revenuDansTranche = $revenuRestant;

        
            
            // Calculer l'impôt pour la tranche
            $impotTranche = $revenuDansTranche * $tranche['taux'];
            $impotTotal += $impotTranche;

            // Soustraire le revenu déjà imposé
            $revenuRestant -= $revenuDansTranche;

            $tableau_imposition[] = [
                'taux' => $tranche['taux']*100 . ' %', 
                'impot' => round($impotTranche), 
                'revenu' => $revenuDansTranche, 
                'tranche' => $tranche['min']*$nb_part.' - '.$tranche['max']*$nb_part
            ];
            // Si tout le revenu a été imposé, arrêter la boucle
            if ($revenuRestant <= 0) {
                break;
            }
        }
        $tableau_imposition[] = [
            'tranche' => 'TOTAL',
            'taux' => round($impotTotal * 100 / $revenu, 2).' %',
            'revenu' => $revenu,
            'impot' => round($impotTotal)
        ];
        //$this->impot = round($impotTotal);
        $this->tableau_imposition = $tableau_imposition;
        return round($impotTotal);

    }

    public function render()
    {
        return view('livewire.simulateur')->extends('partials.layout');
        ;
    }
}
