<?php

return [
    'kequestionnairebe_index' => [
        'position' => 'web',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'KeQuestionnaire',
        'controllerActions' => [],
        'path' => '/kequestionnairebe', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-plugin',
    ],
    'kequestionnairebe_authcode' => [
        'parent' => 'kequestionnairebe_index',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf',
        'extensionName' => 'KeQuestionnaire',
        'pluginName' => 'Be',
        'controllerActions' => [
            'Kennziffer\KeQuestionnaire\Controller\BackendController' => [
                'index',
                'authCodes',
                'createAuthCodes',
                'authCodesSimple',
                'authCodesMail',
                'createAndMailAuthCodes',
                'authCodesRemind',
                'remindAndMailAuthCodes',
            ],
            'Export' => [
                'downloadPdf',
                'pdf',
                'downloadAuthCodesCsv',
            ],
        ],
        'path' => '/kequestionnairebe/authcode', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-authcode-plugin',
    ],
    'kequestionnairebe_export' => [
        'parent' => 'kequestionnairebe_index',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_export.xlf',
        'extensionName' => 'KeQuestionnaire',
        'pluginName' => 'Be',
        'controllerActions' => [
            'Kennziffer\KeQuestionnaire\Controller\ExportController' => [
                'index',
                'csv',
                'csvRb',
                'downloadCsv',
                'downloadCsvRb',
                'pdf',
                'downloadPdf',
                'csvInterval',
                'csvRbInterval',
                'csvCheckInterval',
                'downloadCsvInterval',
            ],
        ],
        'path' => '/kequestionnairebe/export', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-export-plugin',
    ],
    'kequestionnairebe_analyse' => [
        'parent' => 'kequestionnairebe_index',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_analyse.xlf',
        'extensionName' => 'KeQuestionnaire',
        'pluginName' => 'Be',
        'controllerActions' => [
            'Kennziffer\KeQuestionnaire\Controller\AnalyseController' => [
                'index',
                'questions',
                'general',
            ],
        ],
        'path' => '/kequestionnairebe/analyse', // Matches the route path in Routes.php
        'iconIdentifier' => 'kequestionnaire-analyse-plugin',
    ],
];