<?php

return [

    'tranches_imposition' => [
            ['min' => 0, 'max' => 11294, 'taux' => 0, 'forfaitaire' => 0],
            ['min' => 11294, 'max' => 28797, 'taux' => 0.11, 'forfaitaire' => 1242.34],
            ['min' => 28797, 'max' => 82341, 'taux' => 0.3, 'forfaitaire' => 6713.77],
            ['min' => 82341, 'max' => 177106, 'taux' => 0.41, 'forfaitaire' => 15771.28],
            ['min' => 177106, 'max' => 10000000000, 'taux' => 0.45, 'forfaitaire' => 22855.52],
    ],
    
    'baladur' => 10700,
    
    'taux_prelevement_sociaux' => 0.172,
    
    'tranches_decote' => [
            'seul' => ['seuil' => 1939, 'deduction' => 873, 'taux' => 0.4525],
            'mariee' => ['seuil' => 3191, 'deduction' => 1444, 'taux' => 0.4525],
    ],
    
    'part_quotient_familial' => [
            "demipart" => 1759,
            "parent_seul" => 4149,
            "veuf" => 5476
    ],
    
    'tranches_contribution_exceptionnelle' => [
            'seul' => [
                ['taux' => 0, 'min' => 0, 'max' => 250000], 
                ['taux' => 0.03, 'min' => 250000, 'max' => 500000], 
                ['taux' => 0.04, 'min' => 500000, 'max' => 10000000000000]
            ],
            'mariee' => [
                ['taux' => 0, 'min' => 0, 'max' => 250000], 
                ['taux' => 0, 'min' => 250000, 'max' => 500000], 
                ['taux' => 0.03, 'min' => 500000, 'max' => 1000000], 
                ['taux' => 0.04, 'min' => 1000000, 'max' => 10000000000000]
            ],
    ],
];