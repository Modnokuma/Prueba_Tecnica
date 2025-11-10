<?php
$tarea_description = [
    'entity' => 'tarea',
    'attributes' => [
        'id_tarea' => [
            'pk' => true,
            'autoincrement' => true,
            'type' => 'integer',
            'not_null' => [
                'EDIT' => true,
                'DELETE' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'max_size' => 10,
                        'exp_reg' => '/^[0-9]+$/'
                    ],
                    'EDIT' => [
                        'max_size' => 10,
                        'exp_reg' => '/^[0-9]+$/'
                    ],
                    'SEARCH' => [
                        'max_size' => 10,
                        
                    ]
                ]
            ]
        ],
        'id_usuario' => [
            'type' => 'integer',
            'not_null' => [
                'ADD' => true,
                'EDIT' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'exp_reg' => '/^[0-9]+$/'
                    ],
                    'EDIT' => [
                        'exp_reg' => '/^[0-9]+$/'
                    ],
                    'SEARCH' => [
                        'exp_reg' => '/^[0-9]*$/'
                    ]
                ]
            ]
        ],
        'nombre_tarea' => [
            'type' => 'string',
            'not_null' => [
                'ADD' => true,
                'EDIT' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'min_size' => 1,
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]+$/u"
                    ],
                    'EDIT' => [
                        'min_size' => 1,
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]+$/u"
                    ],
                    'SEARCH' => [
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]*$/u"
                    ]
                ]
            ]
        ],
        'descripcion_tarea' => [
            'type' => 'string',
            'not_null' => [
                'ADD' => true,
                'EDIT' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'min_size' => 1,
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]+$/u"
                    ],
                    'EDIT' => [
                        'min_size' => 1,
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]+$/u"
                    ],
                    'SEARCH' => [
                        'max_size' => 200,
                        'exp_reg' => "/^[\p{L}0-9\s\-\.,:;()'\"]*$/u"
                    ]
                ]
            ]
        ],
        'fecha_inicio_tarea' => [
            'type' => 'datetime',
            'not_null' => [
                'ADD' => true,
                'EDIT' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        // formato YYYY-MM-DD HH:MM:SS
                        'exp_reg' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'
                    ],
                    'EDIT' => [
                        'exp_reg' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'
                    ],
                    'SEARCH' => [
                        'exp_reg' => '/.*/'
                    ]
                ]
            ]
        ],
        'fecha_fin_tarea' => [
            'type' => 'datetime',
            'not_null' => [
                'ADD' => true,
                'EDIT' => true
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'exp_reg' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'
                    ],
                    'EDIT' => [
                        'exp_reg' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'
                    ],
                    'SEARCH' => [
                        'exp_reg' => '/.*/'
                    ]
                ]
            ]
        ],
        'completada_tarea' => [
            'type' => 'integer',
            'default_value' => 0,
            'not_null' => [
                'ADD' => false,
                'EDIT' => false
            ],
            'rules' => [
                'validations' => [
                    'ADD' => [
                        'exp_reg' => '/^[01]$/'
                    ],
                    'EDIT' => [
                        'exp_reg' => '/^[01]$/'
                    ],
                    'SEARCH' => [
                        'exp_reg' => '/^[01]?$/'
                    ]
                ]
            ]
        ]
    ],
    'associations' => [
       'BelongsTo' => [
            [
                'entity' => 'usuario', 
                'attributes-own' => ['id_usuario'],
                'attributes-rel' => ['id_usuario']
            ],
        ],
    ]
];

?>
