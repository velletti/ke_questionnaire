<?php

use Kennziffer\KeQuestionnaire\Controller\BackendController;
use Kennziffer\KeQuestionnaire\Controller\ExportController;
use Kennziffer\KeQuestionnaire\Controller\AnalyseController;

return [
    'kequestionnairebe_index' => [
        'path' => '/kequestionnairebe',
        'target' => BackendController::class . '::indexAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_index',
    ],
    'kequestionnairebe_authcode' => [
        'path' => '/kequestionnairebe/authcode',
        'target' => BackendController::class . '::authCodesAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_authcode',
    ],
    'kequestionnairebe_export' => [
        'path' => '/kequestionnairebe/export',
        'target' => ExportController::class . '::indexAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_export',
    ],
    'kequestionnairebe_analyse' => [
        'path' => '/kequestionnairebe/analyse',
        'target' => AnalyseController::class . '::indexAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_analyse',
    ],
];