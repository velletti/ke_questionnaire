<?php

return [
    'frontend' => [
        'kennziffer/kequestionnaire/ajax' => [
            'target' => \Kennziffer\KeQuestionnaire\Middleware\Ajax::class,
            'after' => [
                'typo3/cms-frontend/authentication' ,
            ],
        ],
    ],
];
