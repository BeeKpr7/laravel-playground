<?php

abstract class Simulateur
{
    protected int $nombre_part;
    protected int $nombre_enfant;
    protected bool $isMaried;
    protected bool $isInvalide;
    protected bool $isPermissible;
    protected int $revenu_foncier;
    protected int $impot;
    protected int $plafond_quotient_familial;

    public array $tranches = [
        '0' => ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
        '1' => ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1185.47],
        '2' => ['min' => 28797, 'max' => 82341, 'taux' => 0.30, 'forfaitaire' => 6406.29],
        '3' => ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15048.99],
        '4' => ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 21808.75],
    ];
    public float $prelevement_sociaux = 0.172;


    /**
     * @param int $impot
     * @param bool $isMaried
     * @return int
     * @Renvoi le montant de la decote
     */
    const DECOTE = [
        'seul'   => ['seuil' => 1930, 'deduction' => 873, 'taux' => 0.4525],
        'mariee' => ['seuil' => 3192, 'deduction' => 1444, 'taux' => 0.4525]
    ];
    public function calcul_decote(int $impot, bool $isMaried = false): int
    {

        $decote = $isMaried ? self::DECOTE['mariee'] : self::DECOTE['seul'];

        if ($impot < $decote['seuil']) {
            return round($decote['deduction'] - $impot * $decote['taux']);
        }
        return 0;
    }

    /**
     * @param int $nb_enfant
     * @param bool $isMaried
     * @param bool $isInvalide
     * @return int
     * @Renvoi le nombre de part fiscale en fonction du nombre d'enfant, du mariage et de l'invalidité
     */
    public function calcul_nombre_part_fiscale(int $nb_enfant = 0, bool $isMaried = false, bool $isInvalide = false): int
    {
        $parts = 1 + $nb_enfant * 0.5;

        if ($nb_enfant > 2)
            $parts = 2 + ($nb_enfant - 2) * 1;

        if ($isMaried)
            $parts += 1;

        if ($isInvalide)
            $parts += 0.5;

        $this->nombre_part = $parts;

        return $parts;
    }

    /**
     * @param int $revenu_net_imposable
     * @param int $nb_part
     * @return int
     * @Renvoi le quotient familial
     */
    public function calcul_quotient_familial(int $revenu_net_imposable, int $nb_part = 1): int
    {
        return round($revenu_net_imposable / $nb_part);
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

}