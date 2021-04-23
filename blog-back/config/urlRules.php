<?php

return [
    [
        'class' => 'app\components\UrlRule',
        'controller' => 'active',
        'pluralize' => false,
        'models' => [
            'box',
            'guarantor',
        ],
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'crud/user',
        'pluralize' => false,
        'patterns' =>
        [
            'GET ' => 'index',
            'POST save/<id>' => 'save',
            'DELETE delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'crud/categories',
        'pluralize' => false,
        'patterns' =>
        [
            'GET ' => 'index',
            'POST save/<id>' => 'save',
            'DELETE delete/<id>' => 'delete',
        ],
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'crud/articles',
        'pluralize' => false,
        'patterns' =>
        [
            'GET ' => 'index',
            'POST save/<id>' => 'save',
            'POST like/<idArticulo>/<idUsuario>' => 'like',
            'DELETE delete/<id>' => 'delete',
        ],
    ],
];
