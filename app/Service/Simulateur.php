<?php

class Simulateur
{
    protected int $nombre_part = 1;
    protected int $nombre_enfant = 0;

    protected bool $isMariee = false;
    protected bool $isInvalide = false;
    protected bool $isSeul = true;

    protected int $revenu_foncier = 0;
    protected int $revenu_net_imposable = 0;

    protected int $impot;
    protected int $quotient_familial_plafonne;

    protected array $tranches_imposition;
    protected array $tranches_decote;
    protected array $tranches_haut_revenus;
    protected int $taux_prelevement_sociaux;

    public function __construct()
    {
        //On récupere les données dans les fichier de config
        $this->tranches_imposition = config('simulateur.tranches_imposition');
        $this->taux_prelevement_sociaux = config('simulateur.taux_prelevement_sociaux');
        $this->tranches_decote = config('simulateur.tranches_decote');
        $this->tranches_haut_revenus = config('simulateur.tranches_haut_revenus');
    }

    // const TRANCHES = [
    //     '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
    //     '1' => ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1185.47],
    //     '2' => ['min' => 28797, 'max' => 82341, 'taux' => 0.3, 'forfaitaire' => 6406.29],
    //     '3' => ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15048.99],
    //     '4' => ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 21808.75],
    // ];
    // const TAUX_PRELEVEMENT_SOCIAUX = 0.172;

    /**
     * @param int $impot
     * @param bool $isMaried
     * @return int
     * @Renvoi le montant de la decote
     */
    // const DECOTE = [
    //     'seul' => ['seuil' => 1930, 'deduction' => 873, 'taux' => 0.4525],
    //     'mariee' => ['seuil' => 3192, 'deduction' => 1444, 'taux' => 0.4525],
    // ];
    public function calcul_decote(int $impot, bool $isMariee = false): int
    {
        // $decote = $isMaried ? self::DECOTE['mariee'] : self::DECOTE['seul'];
        $decote = $isMariee ? $this->tranches_decote['mariee'] : $this->tranches_decote['seul'];

        if ($impot < $decote['seuil']) {
            return round($decote['deduction'] - $impot * $decote['taux']);
        }
        return 0;
    }

    /**
     * @param int $nb_enfant
     * @param bool $isMaried
     * @param bool $isInvalide
     * @param bool $isAlone
     * @return int
     * @Renvoi le nombre de part fiscale en fonction du nombre d'enfant, du mariage et de l'invalidité
     */
    public function calcul_nombre_part_fiscale(int $nombre_enfant = 0, bool $isMariee = false, bool $isInvalide = false, bool $isSeul = false): int
    {
        //Minimum 1 part, plus 0,5 part pour les deux premier enfant 
        $resultat = 1 + $nombre_enfant * 0.5;

        //Si plus de deux enfant on refait le calcul
        //On part de 2 parts ( 1 personne + 2 enfant)
        //À partir du 3eme enfant 1enfant = 1 part
        if ($nb_enfant > 2) {
            $resultat = 2 + ($nb_enfant - 2) * 1;
        }

        //Si Mariee on ajoute une part
        if ($isMaried) {
            $resultat += 1;
        }

        //Si carte d'invalidité  on ajoute une demi part
        if ($isInvalide) {
            $resultat += 0.5;
        }

        //Si eleve des enfants seul alors on rajoute une demi part
        if ($isAlone && $nb_enfant > 0) {
            $resultat += 0.5;
        }

        //On set 
        $this->nombre_part = $resultat;

        return $parts;
    }

    /**
     * Calcul le quotient familial
     */
    private function set_quotient_familial(): void
    {
        $this->quotient_familial = round($this->revenu_net_imposable / $this->nombre_part);
    }

    /*
     * @param int $revenu_net_imposable
     * @param int $nb_part
     * @return array
     * @Renvoi la tranche d'imposition correspondant au quotient familial
     */
    public function get_tranche(int $revenu_net_imposable, int $nombre_part = 1): array
    {
        $quotien = $this->calcul_quotient_familial($revenu_net_imposable, $nombre_part);
        foreach ($this->tranches_imposition as $tranche) {
            if ($quotien > $tranche['min'] && $quotien <= $tranche['max']) {
                return $tranche;
            }
        }
        return [];
    }

    /**
     * @param int $revenu_net_imposable
     * @param int $nb_part
     * @return int
     * @Renvoi le montant de l'impot sur le revenu avant decote
     */
    public function calcul_impot(int $revenu_net_imposable, int $nb_part = 1): int
    {
        $quotient = $this->calcul_quotient_familial($revenu_net_imposable, $nb_part);

        $impot = 0;

        return round($impot);
    }

    //Calcul openAI

    function tableau_impot()
    {
        // Tranches d'imposition et taux correspondants
        $tranches = [
            ['plafond' => 11294, 'taux' => 0],
            ['plafond' => 28797, 'taux' => 0.11],
            ['plafond' => 73806, 'taux' => 0.30],
            ['plafond' => PHP_INT_MAX, 'taux' => 0.41] // PHP_INT_MAX pour indiquer pas de limite supérieure
        ];

        $impotTotal = 0;
        $revenuRestant = $revenuNetImposable;

        for ($i = 0; $i < count($tranches); $i++) {
            if ($i > 0) {
                // Soustraire le plafond de la tranche précédente pour obtenir la portion du revenu dans la tranche actuelle
                $revenuDansTranche = min($revenuRestant, $tranches[$i]['plafond'] - $tranches[$i - 1]['plafond']);
            } else {
                // Si c'est la première tranche
                $revenuDansTranche = min($revenuRestant, $tranches[$i]['plafond']);
            }

            // Calculer l'impôt pour la tranche
            $impotTranche = $revenuDansTranche * $tranches[$i]['taux'];
            $impotTotal += $impotTranche;

            // Soustraire le revenu déjà imposé
            $revenuRestant -= $revenuDansTranche;

            // Si tout le revenu a été imposé, arrêter la boucle
            if ($revenuRestant <= 0) {
                break;
            }
        }

        return $impotTotal;
    }

    // public function example_utilisation()
    // {
    //     // Exemple d'utilisation
    //     $revenuNetImposable = 90000; // Remplacez par le revenu net imposable réel
    //     $impot = calculerImpot($revenuNetImposable);

    //     echo "L'impôt sur le revenu pour un revenu net imposable de $revenuNetImposable € est de $impot €.";
    // }
}