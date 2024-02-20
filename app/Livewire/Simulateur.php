<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\FiscaliteType;

class Simulateur extends Component
{
    public int $revenu_net_imposable = 0;
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
    public array $tableau_avantage_fiscal = [];
    public int $montant_travaux = 0;
    public int $baladur = 10700;
    public int $annee1 = 50;
    public int $annee2 = 50;
    public int $annee3 = 0;
    public int $annee4 = 0;
    public array $repartition_travaux = [0.5, 0.5];

    public array $tranches = [
        '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
        '1' => ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1242.34],
        '2' => ['min' => 28797, 'max' => 82341, 'taux' => 0.3, 'forfaitaire' => 6713.77],
        '3' => ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15771.28],
        '4' => ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 22855.52],
    ];
    public float $prelevement_sociaux = 0.172;
    public array $impot_decote = [
        'seul' => ['seuil' => 1939, 'deduction' => 873, 'taux' => 0.4525],
        'mariee' => ['seuil' => 3191, 'deduction' => 1444, 'taux' => 0.4525],
    ];

    public function mount()
    {
        //$this->simulateur = new Simulateur(FiscaliteType::DEFICIT_FONCIER);
    }

    public function updated($field)
    {
        $this->revenu_foncier = $this->revenu_foncier ?? 0;

        if ($this->nb_enfant <= 0) {
            $this->nb_enfant = 0;
        }
        if ($this->revenu_foncier <= 0) {
            $this->revenu_foncier = 0;
        }

        $this->nb_part = $this->nb_part ?? 1;

        if ($field == 'nb_enfant') {
            $this->getPart();
        }

        if ($field == 'isMaried') {
            $this->isAlone = !$this->isAlone;
        }

        if ($field == 'isAlone') {
            $this->isMaried = !$this->isMaried;
        }

        if ($field != 'nb_part') {
            $this->getPart();
        }

        if ($this->revenu_net_imposable ?? null) {
            $this->impot = $this->calculateur();
        }

        if ($this->montant_travaux ?? 0 > 0 && $this->tableau_imposition && $this->revenu_net_imposable != 0) {
            $this->tableau_avantage_fiscal();
        }
    }

    //Incremente le nombre d'enfant
    public function incrementEnfant()
    {
        $this->nb_enfant++;
        $this->updated('nb_enfant');
    }

    //Decremente le nombre d'enfant
    public function decrementEnfant()
    {
        if ($this->nb_enfant > 0) {
            $this->nb_enfant--;
        }
        $this->updated('nb_enfant');
    }

    //Calcul le nombre de part fiscale
    public function getPart(): int
    {
        $parts = 1 + $this->nb_enfant * 0.5;

        if ($this->nb_enfant > 2) {
            $parts = 2 + ($this->nb_enfant - 2) * 1;
        }

        if ($this->isMaried) {
            $parts += 1;
        }

        if ($this->isInvalide) {
            $parts += 0.5;
        }

        if ($this->isAlone && $this->nb_enfant > 0) {
            $parts += 0.5;
        }

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
                $impot = $revenu * $tranche['taux'] - $tranche['forfaitaire'] * $nb_part;

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
        if ($this->isMaried) {
            $nb_part += 1;
        }

        //Calcul de l'impot sans benefice
        // $impot_sans_benefice = $this->calcul_impot($nb_part);
        $impot_sans_benefice = $this->tableau_imposition($nb_part);

        //Calcul de l'avatage fiscale
        $avantage_fiscale = $impot_sans_benefice - $impot_non_corrigé;

        //Calcul du nombre de demi part
        $nombre_demi_part = $this->nb_enfant * 1;
        if ($this->nb_enfant > 2) {
            $nombre_demi_part = ($this->nb_enfant - 2) * 2 + 2;
        }

        //Calcul de l'avantage plafonné
        $avantage_plafonne = 1759 * $nombre_demi_part;

        //Si le contribuable est seul et a des enfants
        //On ajoute 4149 pour le premier enfant
        if ($this->isAlone && $this->nb_enfant > 0) {
            $avantage_plafonne = 4149 + 1759 * ($nombre_demi_part - 1);
        }

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
        'seul' => [['taux' => 0, 'min' => 0, 'max' => 250000], ['taux' => 0.03, 'min' => 250000, 'max' => 500000], ['taux' => 0.04, 'min' => 500000, 'max' => 10000000000000]],
        'mariee' => [['taux' => 0, 'min' => 0, 'max' => 250000], ['taux' => 0, 'min' => 250000, 'max' => 500000], ['taux' => 0.03, 'min' => 500000, 'max' => 1000000], ['taux' => 0.04, 'min' => 1000000, 'max' => 10000000000000]],
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

        foreach ($tranches as $tranche) {
            // $revenuDansTranche = min($revenuRestant - ($tranche['max']*$nb_part - $tranche['min']*$nb_part));
            if ($revenuRestant > $tranche['max'] * $nb_part - $tranche['min'] * $nb_part) {
                $revenuDansTranche = $tranche['max'] * $nb_part - $tranche['min'] * $nb_part;
            } else {
                $revenuDansTranche = $revenuRestant;
            }

            // Calculer l'impôt pour la tranche
            $impotTranche = $revenuDansTranche * $tranche['taux'];
            $impotTotal += $impotTranche;

            // Soustraire le revenu déjà imposé
            $revenuRestant -= $revenuDansTranche;

            $tableau_imposition[] = [
                'taux' => $tranche['taux'],
                'impot' => round($impotTranche),
                'revenu' => $revenuDansTranche,
                'tranche' => $tranche['min'] * $nb_part . ' - ' . $tranche['max'] * $nb_part,
            ];
            // Si tout le revenu a été imposé, arrêter la boucle
            if ($revenuRestant <= 0) {
                break;
            }
        }
        $tableau_imposition[] = [
            'tranche' => 'TOTAL',
            'taux' => round(($impotTotal * 100) / $revenu, 2) . ' %',
            'revenu' => $revenu,
            'impot' => round($impotTotal),
        ];
        //$this->impot = round($impotTotal);
        $this->tableau_imposition = $tableau_imposition;
        return round($impotTotal);
    }

    public function tableau_avantage_fiscal(int $revenu = null, int $montant_travaux = null, array $repartition = null)
    {
        $revenu = $revenu ? $revenu : $this->revenu_net_imposable ?? 0;
        $montant_travaux = $montant_travaux ? $montant_travaux : $this->montant_travaux ?? 0;
        $repartition = $repartition ? $repartition : $this->repartition_travaux ?? [0.5, 0.5];
        $benefice_foncier = $this->revenu_foncier ?? 0;

        //Calcul de la repartition des travaux
        $repartition_travaux = $this->calcul_repartition_travaux($repartition, $montant_travaux);

        //On recupere le tableau d'imposition et on supprime la derniere ligne qui est le total
        $tableau_imposition = $this->tableau_imposition ?? [];
        unset($tableau_imposition[count($tableau_imposition) - 1]);

        $avantage_fiscal = 0;
        $cumul_deficit_reportable = 0;
        $annee = 0;
        $montant_travaux_reportable = $montant_travaux;

        //Calcul des tranches d'imposition du bénéfice foncier
        $tranches_benefice_foncier = $this->calcul_tranches_benefice_foncier($benefice_foncier, $tableau_imposition);        
        
        //On complete le tableau d'avantage fiscal tant que le cumul des déficits reportables est supérieur à 0
        $tableau_avantage_fiscal = [];

        do {
            //Part des travaux à déduire
            $tableau_avantage_fiscal[$annee]['travaux_deduire'] = $repartition_travaux[$annee] ?? 0;

            //Calcul du montant des travaux reportable
            $montant_travaux_reportable -= $tableau_avantage_fiscal[$annee]['travaux_deduire'];
            $tableau_avantage_fiscal[$annee]['montant_travaux_reportable'] = $montant_travaux_reportable;

            //Calcul des différentes réductions d'impôts
            //Réduction d'impôt sur le bénéfice foncier
            $tableau_avantage_fiscal[$annee]['reduction_impot_benefice_foncier'] = $this->calcul_reduction_impot_benefice_foncier($tranches_benefice_foncier, $cumul_deficit_reportable, $benefice_foncier, $tableau_imposition);

            //Réduction des prélèvements sociaux sur le bénéfice foncier
            $tableau_avantage_fiscal[$annee]['reduction_prelevement_sociaux_bénéfice_foncier'] = $this->calcul_reduction_prelevement_sociaux_benefice_foncier($cumul_deficit_reportable, $benefice_foncier);

            //Réduction d'impôt sur le revenu
            $tableau_avantage_fiscal[$annee]['reduction_impot_revenu'] = $this->calcul_reduction_impot_revenu($annee, $repartition, $tranches_benefice_foncier);

            //Calcul de l'avantage fiscal / an 
            $tableau_avantage_fiscal[$annee]['reduction_impot_total'] = $tableau_avantage_fiscal[$annee]['reduction_impot_benefice_foncier'] + $tableau_avantage_fiscal[$annee]['reduction_impot_revenu'] + $tableau_avantage_fiscal[$annee]['reduction_prelevement_sociaux_bénéfice_foncier'];
            //On ajoute l'avantage fiscal au cumul
            $avantage_fiscal += $tableau_avantage_fiscal[$annee]['reduction_impot_total'];
            $tableau_avantage_fiscal[$annee]['cumul_economie_impot'] = $avantage_fiscal;
            //Calcul du cumul des déficits reportables
            $cumul_deficit_reportable = $this->calcul_deficit_reportable($cumul_deficit_reportable, $repartition_travaux, $benefice_foncier, $annee);
            $tableau_avantage_fiscal[$annee]['cumul_deficit_reportable'] = $cumul_deficit_reportable;

            $annee++;

        } while ($cumul_deficit_reportable > 0 || $montant_travaux_reportable > 0);

        $this->tableau_avantage_fiscal = $tableau_avantage_fiscal;
    }

    public function calcul_tranches_benefice_foncier(int $benefice_foncier, array $tableau_imposition)
    {
        $tranches_benefice_foncier = [];

        for ($i = count($tableau_imposition) - 1; $i >= 0; $i--) {
            $taux_imposition = $tableau_imposition[$i]['taux'];
            if ($benefice_foncier - $tableau_imposition[$i]['revenu'] > 0) {
                $foncier = $tableau_imposition[$i]['revenu'];
            } else {
                $foncier = $benefice_foncier;
            }

            
            $tranches_benefice_foncier[] = [
                'taux' => $taux_imposition,
                'foncier' => $foncier,
            ];
            $benefice_foncier -= $foncier;
            if ($benefice_foncier <=0) break;
        }

        return $tranches_benefice_foncier;
    }

    public function calcul_reduction_impot_benefice_foncier(array $tranches_benefice_foncier, int $cumul_deficit_reportable, int $benefice_foncier, array $tableau_imposition)
    {
        $reduction = 0;
        //Si le cumul est plus petit que le bénéfice foncier
        //On recalcule les tranches en fonction du cumul
        if ($cumul_deficit_reportable != 0 && $cumul_deficit_reportable - $benefice_foncier < 0) 
            $tranches_benefice_foncier = $this->calcul_tranches_benefice_foncier($cumul_deficit_reportable, $tableau_imposition);

        foreach ($tranches_benefice_foncier as $tranche) {
            $reduction += $tranche['foncier'] * $tranche['taux'];
        }
        return round($reduction);
    }

    public function calcul_reduction_prelevement_sociaux_benefice_foncier(int $cumul_deficit_reportable, int $benefice_foncier)
    {
       if ($cumul_deficit_reportable != 0 && $cumul_deficit_reportable - $benefice_foncier < 0 ) {
            return round($cumul_deficit_reportable * $this->prelevement_sociaux);
       } else {
            return round($benefice_foncier * $this->prelevement_sociaux);
       }
    }

    public function calcul_reduction_impot_revenu(int $annee, array $repartition, array $tranches_benefice_foncier)
    {
        if (!isset($repartition[$annee])) {
            return 0;
        }

        $reduction = $this->baladur * $tranches_benefice_foncier[count($tranches_benefice_foncier) - 1]['taux'];

        return $reduction;
    }

    public function calcul_montant_travaux_reportable(int $annee, int $repartition_travaux)
    {

        $travaux_reportable = 0;
        $nombre_annee_travaux = count($repartition_travaux);

        if ($annee == $nombre_annee_travaux-1) {
            return 0;
        }
        // for ($i = $anne+1; $i >= $nombre_annee_travaux; $i++) {
        //     $travaux_reportable += $repartition_travaux[$i];
        // }
        foreach ($repartition_travaux as $key => $value) {
            if ($key > $annee) {
                $travaux_reportable += $value;
            }
        }
        return $travaux_reportable;
    }

    public function calcul_repartition_travaux(array $repartition, int $montant_travaux)
    {
        $repartition_travaux = [];
        foreach ($repartition as $key => $value) {
            $repartition_travaux[$key] = $montant_travaux * $value;
        }
        return $repartition_travaux;
    }

    public function calcul_deficit_reportable(int $cumul_deficit_reportable, array $repartition_travaux, int $benefice_foncier, int $annee)
    {
        $result = 0;
        if (isset($repartition_travaux[$annee])){
            $result = $repartition_travaux[$annee] - $this->baladur;
        }
        $result -= $benefice_foncier;

        $result += $cumul_deficit_reportable;

        if ($result < 0) {
            return 0;
        }
        return $result;
    }

    public function updateRepartitionTravaux()
    {
        $this->repartition_travaux = [];

        if ($this->annee1)
            $this->repartition_travaux[0] = $this->annee1 / 100;
        if ($this->annee2)
            $this->repartition_travaux[1] = $this->annee2 / 100;
        if ($this->annee3)
            $this->repartition_travaux[2] = $this->annee3 / 100;
        if ($this->annee4)
            $this->repartition_travaux[3] = $this->annee4 / 100;

        if ($this->annee1 + $this->annee2 + $this->annee3 + $this->annee4 != 100) {
            $this->repartition_travaux = [0.5, 0.5];
            $this->annee1 = 50;
            $this->annee2 = 50;
            $this->annee3 = 0;
            $this->annee4 = 0;
        }
        $this->updated('montant_travaux');

    }

    public function render()
    {
        return view('livewire.simulateur')->extends('partials.layout');
    }
}
