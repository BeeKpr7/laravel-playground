<?php 

return [

    /*
    Tableau qui contient les différentes tranches d'imposition min & max
    Le taux d'imposition 
    Le taux forfaitaire pour le calculs
    */
    'tranches_imposition' =>  [
        ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
        ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1185.47],
        ['min' => 28797, 'max' => 82341, 'taux' => 0.3, 'forfaitaire' => 6406.29],
        ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15048.99],
        ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 21808.75],
    ],

    /*
    Tableau des différentes tranches de decote de l'imposition 
    En fonction de la situation marital 
    */
    'tranches_decote' => [
        'seul' => ['seuil' => 1930, 'deduction' => 873, 'taux' => 0.4525],
        'mariee' => ['seuil' => 3192, 'deduction' => 1444, 'taux' => 0.4525],
    ],

    /*
    Taux des prélevements sociaux à 17,2%
    */
    'taux_prelevement_sociaux' => 0,172,

    'tranches_haut_revenus' => [
        'seul' => [
            ['taux'=> 0.03 , 'plafond' => 500000],
            ['taux'=> 0.04 , 'plafond' => 1000000],
            ['taux'=> 0.04 , 'plafond' => 1000000000000000],
        ],
        'mariee' => [            
            ['taux'=> 0 , 'plafond' => 500000],
            ['taux'=> 0.03 , 'plafond' => 1000000],
            ['taux'=> 0.04 , 'plafond' => 1000000000000000],
        ]
    ]


];