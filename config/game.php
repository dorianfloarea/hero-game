<?php

return [
    'maxTurns'   => 20,
    'characters' => [
        PLAYER_1_NAME => [
            'stats'  => [
                'health'   => [70, 100],
                'strength' => [70, 80],
                'defence'  => [45, 55],
                'speed'    => [40, 50],
                'luck'     => [10, 30]
            ],
            'skills' => [
                /*
                 * skill class name => chance of trigger
                 */
                \App\Models\Skills\RapidStrikeSkill::class    => 10,
                \App\Models\Skills\CriticalStrikeSkill::class => 5,
                \App\Models\Skills\MagicShieldSkill::class    => 20
            ]
        ],
        PLAYER_2_NAME => [
            'stats'  => [
                'health'   => [60, 90],
                'strength' => [60, 90],
                'defence'  => [40, 60],
                'speed'    => [40, 60],
                'luck'     => [25, 40]
            ],
            'skills' => [
                \App\Models\Skills\CriticalStrikeSkill::class => 10,
                \App\Models\Skills\ThickSkinSkill::class      => 20
            ]
        ]
    ]
];
