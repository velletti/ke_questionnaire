<?php
use Kennziffer\KeQuestionnaire\Controller\BackendController;

return [
    'kequestionnairebe' => [
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf',
        'iconIdentifier' => 'kequestionnaire-plugin',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'position' => ['after' => 'web'],
        'access' => 'user',
    ],
    'kequestionnairebe_authcodes' => [
        'parent' => 'kequestionnairebe' ,
        'position' => ['after' => 'index'],
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf',
        'extensionName' => 'KeQuestionnaire',
        'routes' => [
            '_default' => [
                'target' => BackendController::class . '::handleRequest',
            ],
        ],
        'path' => '/kequestionnairebe/authcodes', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-authcode-plugin',
    ],
    'kequestionnairebe_export' => [
        'parent' => 'kequestionnairebe' ,
        'position' => ['after' => 'kequestionnairebe_authcodes'],
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_export.xlf',
        'extensionName' => 'KeQuestionnaire',
        'routes' => [
            '_default' => [
                'target' => BackendController::class . '::handleRequest',
            ],
        ],
        'path' => '/kequestionnairebe/export', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-export-plugin',
    ],
];