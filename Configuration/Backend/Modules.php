<?php

return [
    'keQuestionnaireBe_KeQuestionnaire' => [
        'parent' => 'keQuestionnaireBe',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'KeQuestionnaire',
        'controllerActions' => [],
    ], 'keQuestionnaireBe_KeQuestionnaireAuthcode' => [
        'parent' => 'keQuestionnaireBe',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf',
        'extensionName' => 'KeQuestionnaire',
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
    ],
    'keQuestionnaireBe_KeQuestionnaireExport' => [
        'parent' => 'keQuestionnaireBe',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_export.xlf',
        'extensionName' => 'KeQuestionnaire',
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
    ],
    'keQuestionnaireBe_KeQuestionnaireAnalyse' => [
        'parent' => 'keQuestionnaireBe',
        'access' => 'user',
        'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_analyse.xlf',
        'extensionName' => 'KeQuestionnaire',
        'controllerActions' => [
            'Kennziffer\KeQuestionnaire\Controller\AnalyseController' => [
                'index',
                'questions',
                'general',
            ],
        ],
    ],
];
