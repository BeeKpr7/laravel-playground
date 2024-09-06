<?php
/**
 * Librairie permetant de calculer le montant d'imposition en fonction des revenu, bénéfice foncier et
 * de la situation familial
 *
 * UTILISATION
 *
 * $impoNet = $this->CI->simulateurimpot_lib->setRevenuNetImposable(900000)
 *                       ->setNbEnfant(2)
 *                       ->setIsMaried(true)
 *                       ->setRevenuFoncier(300000)
 *                       ->calculImpotNet() // Procede au calcul !! NE PAS OUBLIER !!
 *                       ->getImpotNet(); // Renvoie l'impot net
 *
 * Autre fonctions :
 *  ->getTableauImposition() //Renvoie un tableau d'imposition, utilisable dans des calculs de fiscalite
 *  ->getTableauImpositionForPDF // Renvoie le tableau pour la présentation PDF
 */

use App\Enums\FamillyStatus;

class Imposition
{
    //Instance
    private $CI;

    //Variables d'impositions récupérer dans le fichier config
    private $tranches_imposition;

    private $taux_prelevement_sociaux;

    private $tranches_decote;

    private $tranches_contribution_exceptionnelle;

    private $baladur;

    private $part_quotient_familial;

    //Variables de calcul
    private $revenu_net_imposable = 0;

    private $revenu_foncier = 0;

    private $nb_enfant = 0;

    private $isMaried = false;

    private $isAlone = true;

    private $isInvalide = false;

    private $isVeuf = false;

    private $nb_part = 1;

    private $impot = 0;

    private $tableau_imposition = [];

    private $impot_net = 0;

    private $prelevement_sociaux = 0;

    private $decote_imposition = 0;

    private $plafond_quotient_familial = 0;

    private $contribution_exceptionelle = 0;

    public function __construct()
    {
        //Récupération des variables d'imposition dans le fichier config
        // $this->CI = &get_instance();
        // $this->CI->config->load('imposition');

        $config = $this->CI->config;

        $this->tranches_imposition = config('simulateur.tranches_imposition');
        $this->taux_prelevement_sociaux = config('simulateur.taux_prelevement_sociaux');
        $this->tranches_decote = config('simulateur.tranches_decote');
        $this->tranches_contribution_exceptionnelle = config('simulateur.tranches_contribution_exceptionnelle');
        $this->baladur = config('simulateur.baladur');
        $this->part_quotient_familial = config('simulateur.part_quotient_familial');

    }

    //Effectue l'ensemble des calculs d'imposition
    public function calculImpotNet()
    {
        $this->setNbPart()
            ->setQuotientFamilial()
            ->setDecote()
            ->setPrelevementSociaux()
            ->setContributionExceptionelle()
            ->setImpotNet();

        return $this;
    }

    //Setters
    public function setRevenuNetImposable($revenu_net_imposable)
    {
        $this->revenu_net_imposable = $revenu_net_imposable;

        return $this;
    }

    public function setRevenuFoncier($revenu_foncier)
    {
        $this->revenu_foncier = $revenu_foncier;

        return $this;
    }

    public function setNbEnfant($nb_enfant)
    {
        $this->nb_enfant = $nb_enfant;

        return $this;
    }

    public function setSituationFamilial(FamillyStatus $situation_familial): self
    {

        switch ($situation_familial) {
            case FamillyStatus::CELIBATAIRE:
                $this->isMaried = false;
                $this->isAlone = true;
                $this->isVeuf = false;
                break;
            case FamillyStatus::MARIE:
                $this->isMaried = true;
                $this->isAlone = false;
                $this->isVeuf = false;
                break;
            case FamillyStatus::CONCUBINAGE:
                $this->isMaried = false;
                $this->isAlone = false;
                $this->isVeuf = false;
                break;
            case FamillyStatus::VEUF:
                $this->isMaried = false;
                $this->isAlone = true;
                $this->isVeuf = true;
                break;
        }

        return $this;
    }

    //End Setters

    //Getters
    //Récupération de l'impot
    public function getImpot()
    {
        return $this->impot;
    }

    //Récupération de l'impot net
    public function getImpotNet()
    {
        return $this->impot_net;
    }

    //Récupération du tableau d'imposition
    public function getTableauImposition()
    {
        return $this->tableau_imposition;
    }

    //Génération et renvoie du tableau d'impositions pour l'affichage PDF
    public function toPDF()
    {
        $toPDF = [];
        $toPDF['tableau_imposition'] = $this->tableau_imposition;
        $impot = $this->impot;
        $revenu = $this->revenu_net_imposable;
        $tranches_imposition = $this->tranches_imposition;
        $nb_part = $this->tableau_imposition[0]['nb_part'];

        //Génère le tableau d'imposition à afficher
        foreach ($toPDF['tableau_imposition'] as $key => $tranche) {
            //Colone qui affiche l'intervalle des tranches
            $toPDF['tableau_imposition'][$key]['tranche'] = 'Entre '.$tranches_imposition[$key]['min'] * $nb_part.' et '.$tranches_imposition[$key]['max'] * $nb_part.' €';
            if ($tranches_imposition[$key]['taux'] === 0) {
                $toPDF['tableau_imposition'][$key]['tranche'] = 'Jusqu\'à '.$tranches_imposition[$key]['max'] * $nb_part.' €';
            }
            if ($tranches_imposition[$key]['taux'] === 0.45) {
                $toPDF['tableau_imposition'][$key]['tranche'] = $tranches_imposition[$key]['min'] * $nb_part.' € et plus';
            }

            //Colonne pour le pourcentage d'imposition
            $toPDF['tableau_imposition'][$key]['taux'] = $tranche['taux'] * 100 .' %';
            // deb($tranche['taux'], 1);
            //Colonne pour le montant des revenus impossé dans la tranches
            $toPDF['tableau_imposition'][$key]['revenu'] = $this->formatNumber($tranche['revenu']);

            //Colone pour le montant de l'impot sur la tranche
            $toPDF['tableau_imposition'][$key]['impot'] = $this->formatNumber($tranche['impot']);

        }

        //Dernière Ligne total pour l'affichage du tableau
        $toPDF['tableau_imposition'][] = [
            'tranche' => 'TOTAL',
            'taux' => round(($impot * 100) / $revenu, 2).' %',
            'revenu' => $revenu.' €',
            'impot' => $impot.' €',
        ];

        //Données à afficher sur le PDF
        $toPDF['nombre_part'] = $this->nb_part;
        $toPDF['revenu_brut'] = $this->formatNumber($this->revenu_net_imposable);
        $toPDF['revenu_net_imposable'] = $this->formatNumber($this->revenu_net_imposable);
        $toPDF['revenu_fiscal_reference'] = $this->formatNumber($this->revenu_net_imposable);
        $toPDF['montant_decote'] = $this->formatNumber($this->decote_imposition);
        $toPDF['quotient_familial_plafonne'] = $this->formatNumber($this->plafond_quotient_familial);
        $toPDF['prelevement_sociaux'] = $this->formatNumber($this->prelevement_sociaux);
        $toPDF['contribution_exceptionelle'] = $this->formatNumber($this->contribution_exceptionelle);
        $toPDF['impot_net'] = $this->formatNumber($this->impot_net);

        return $toPDF;

    }
    //End Getters

    //Calcul de l'impot
    //Calcul le nombre de part en fonction de la situation familiale
    private function setNbPart()
    {
        //Pour les deux premiers enfants 0.5 part par enfant
        $parts = 1 + $this->nb_enfant * 0.5;

        //A partir du 3ème enfant 1 part par enfant
        if ($this->nb_enfant > 2) {
            $parts = 2 + ($this->nb_enfant - 2) * 1;
        }

        //Si le couple est marié ou pacsé 1 part
        if ($this->isMaried) {
            $parts += 1;
        }

        //Si la personne possède une carte d'invalidité 0.5 part
        if ($this->isInvalide) {
            $parts += 0.5;
        }

        //Si la personne est seule et a des enfants 0.5 part
        if ($this->isAlone && $this->nb_enfant > 0) {
            $parts += 0.5;
        }

        //Si la personne est veuve + 0,5 part
        if ($this->isVeuf) {
            $parts += 0.5;
        }

        $this->nb_part = $parts;

        return $this;
    }

    //Calcul de l'impot sur le revenu et génération du tableau d'imposition
    private function setImpot($nb_part = null)
    {
        //Permet d'appeler la fonction avec un nombre de part spécifique
        //Sinon on prend le nombre de part défini
        $nb_part = $nb_part ? $nb_part : $this->nb_part;

        $revenu_restant = $this->revenu_net_imposable;

        $impot_total = 0;
        $tableau_imposition = [];

        foreach ($this->tranches_imposition as $tranche) {

            //Calcul de du montant de la part de tranche d'imposition
            $montant_part_tranche = $tranche['max'] * $nb_part - $tranche['min'] * $nb_part;

            // On compare le revenu restant a imposer au montant de la part de tranche d'imposition
            //Et on retient le plus petit pour calculer l'impostion sur cette tranche
            $revenu_dans_tranche = $revenu_restant;

            if ($revenu_restant > $montant_part_tranche) {
                $revenu_dans_tranche = $montant_part_tranche;
            }

            // Calcule de l'impôt pour la tranche
            $impotTranche = $revenu_dans_tranche * $tranche['taux'];

            //On cumul le résultat dans pour obtenir l'impot total
            $impot_total += $impotTranche;

            // Soustraire le revenu déjà imposé
            $revenu_restant -= $revenu_dans_tranche;

            $tableau_imposition[] = [
                'taux' => $tranche['taux'],
                'impot' => round($impotTranche),
                'revenu' => $revenu_dans_tranche,
                'nb_part' => $nb_part,
            ];

            // Si tout le revenu a été imposé, arrêter la boucle
            if ($revenu_restant <= 0) {
                break;
            }
        }

        $this->tableau_imposition = $tableau_imposition;

        $this->impot = round($impot_total);

        return $this;
    }

    //Calcul du plafonnement du quotient familial
    private function setQuotientFamilial()
    {
        $nb_part = 1;
        if ($this->isMaried) {
            $nb_part += 1;
        }

        //Calcul de l'impot
        $impot_non_corrigé = $this->setImpot()->getImpot();

        //Calcul de l'impot sans benefice des enfants
        $impot_sans_benefice = $this->setImpot($nb_part)->getImpot();

        //Calcul de l'avantage fiscale
        //Différence entre l'impot sans benefice et l'impot
        $avantage_fiscale = $impot_sans_benefice - $impot_non_corrigé;

        //Calcul du nombre de demi part
        //Pour les deux premiers enfants 1 demi part
        $nombre_demi_part = $this->nb_enfant * 1;
        //Pour les enfants suivants 2 demi part
        if ($this->nb_enfant > 2) {
            $nombre_demi_part = ($this->nb_enfant - 2) * 2 + 2;
        }

        //Calcul de l'avantage plafonné
        //Avantage fiscale plafonné
        $avantage_plafonne = $this->part_quotient_familial['demipart'] * $nombre_demi_part;

        //Si le contribuable est seul et a des enfants
        if ($this->isAlone && $this->nb_enfant > 0) {
            $avantage_plafonne = $this->part_quotient_familial['parent_seul'] + $this->part_quotient_familial['demipart'] * ($nombre_demi_part - 1);
        }

        //Si le contribuable est veuf et a des enfants
        if ($this->isVeuf && $this->isAlone && $this->nb_enfant > 0) {
            $avantage_plafonne = $this->part_quotient_familial['veuf'] + $this->part_quotient_familial['demipart'] * ($nombre_demi_part);
        }

        //Le plafond est egale a l'avantage plafonné
        $this->plafond_quotient_familial = $avantage_plafonne;

        //Si l'avantage fiscale est inferieur a l'avantage plafonné
        //On n'applique pas le plafonnement
        if ($avantage_fiscale <= $avantage_plafonne) {
            $this->plafond_quotient_familial = 0;
            $this->setImpot();
        }

        return $this;
    }

    //Calcul la decote en fonction de l'impot
    private function setDecote()
    {
        $impot = $this->getImpot();

        //En fonction de la situation maritale on recupere le decote correspondant
        $decote = $this->isMaried ? $this->tranches_decote['mariee'] : $this->tranches_decote['seul'];

        $this->decote_imposition = 0;

        //Si l'impot est inferieur au seuil maximal de la decote
        //Donc eligibilité a la decote
        if ($impot < $decote['seuil']) {
            //On calcul la decote
            $result = round($decote['deduction'] - $impot * $decote['taux']);
            //Si le resultat est negatif on ne peut pas appliquer la decote
            if ($impot - $result < 0) {
                return $this;
            }
            //On applique la decote
            $this->decote_imposition = $result;
        }

        return $this;
    }

    //Calcul des prelevements sociaux
    private function setPrelevementSociaux()
    {
        $this->prelevement_sociaux = 0;

        if ($this->revenu_foncier >= 0) {
            $this->prelevement_sociaux = $this->revenu_foncier * $this->taux_prelevement_sociaux;
        }

        return $this;
    }

    //Calcul de la contribution exceptionnelle
    private function setContributionExceptionelle()
    {
        $isMaried = $this->isMaried;
        $revenu = $this->revenu_net_imposable;

        $tranches = $this->tranches_contribution_exceptionnelle[$isMaried ? 'mariee' : 'seul'];

        $this->contribution_exceptionelle = 0;

        foreach ($tranches as $tranche) {
            if ($revenu <= $tranche['max'] && $revenu > $tranche['min']) {
                $this->contribution_exceptionelle = round(($revenu - $tranche['min']) * $tranche['taux']);

                return $this;
            }
        }

        return $this;
    }

    //Calcul de l'impot sur le revenu net imposable
    private function setImpotNet()
    {
        $this->impot_net = $this->impot + $this->prelevement_sociaux + $this->contribution_exceptionelle - $this->decote_imposition - $this->plafond_quotient_familial;

        return $this;
    }

    //Formate un nombre pour l'affichage
    private function formatNumber($number)
    {
        return number_format($number, 2, ',', ' ').' €';
    }

    //End Calcul de l'impot

}
