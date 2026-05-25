<?php

declare(strict_types=1);

use Kennziffer\KeQuestionnaire\Middleware\Ajax;

return [
    'frontend' => [
        'kennziffer/kequestionnaire/ajax' => [
            'target' => Ajax::class,
            'after' => [
                'typo3/cms-frontend/authentication' ,
            ],
        ],
    ],
];
