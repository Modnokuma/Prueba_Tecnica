<?php
$usuario_description =
    [
        'entity' => 'usuario',
        'attributes' => [
            'id_usuario' => [
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
                            'exp_reg' => '/^[0-9]*$/'
                        ]
                    ]
                ]
            ],
            'correo_usuario' => [
                'type' => 'string',
                'unique' => true,
                'not_null' => [
                    'ADD' => true,
                    'EDIT' => true
                ],
                'rules' => [
                    'validations' => [
                        'ADD' => [
                            'min_size' => 6,
                            'max_size' => 100,
                            'exp_reg' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/',
                        ],
                        'EDIT' => [
                            'min_size' => 6,
                            'max_size' => 100,
                            'exp_reg' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/'
                        ],
                        'SEARCH' => [
                            'max_size' => 100,
                            'exp_reg' => '/^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,})?$/'
                        ]
                    ]
                ]
            ],
            'contrasena_usuario' => [
                'type' => 'string',
                'not_null' => [
                    'ADD' => true,
                    'EDIT' => true
                ],
                'rules' => [
                    'validations' => [
                        'ADD' => [
                            'min_size' => 4,
                            'max_size' => 255,
                            'exp_reg' => '/^.*/'
                        ],
                        'EDIT' => [
                            'min_size' => 4,
                            'max_size' => 255,
                            'exp_reg' => '/^.*/'
                        ]
                    ]
                ]
            ],
            'nombre_usuario' => [
                'type' => 'string',
                'not_null' => [
                    'ADD' => true,
                    'EDIT' => true
                ],
                'rules' => [
                    'validations' => [
                        'ADD' => [
                            'min_size' => 1,
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']+$/"
                        ],
                        'EDIT' => [
                            'min_size' => 1,
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']+$/"
                        ],
                        'SEARCH' => [
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']*$/"
                        ]
                    ]
                ]
            ],
            'apellidos_usuario' => [
                'type' => 'string',
                'not_null' => [
                    'ADD' => true,
                    'EDIT' => true
                ],
                'rules' => [
                    'validations' => [
                        'ADD' => [
                            'min_size' => 1,
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']+$/"
                        ],
                        'EDIT' => [
                            'min_size' => 1,
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']+$/"
                        ],
                        'SEARCH' => [
                            'max_size' => 100,
                            'exp_reg' => "/^[a-zA-ZáÁéÉíÍóÓúÚüÜ\s\-']*$/"
                        ]
                    ]
                ]
            ]
        ]
    ];
