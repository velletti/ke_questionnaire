<?php

use Kennziffer\KeQuestionnaire\Controller\BackendController;

return [
    'kequestionnairebe_index' => [
        'path' => '/kequestionnairebe',
        'target' => BackendController::class . '::indexAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_index',
    ],
    'kequestionnairebe_authcodes' => [
        'path' => '/kequestionnairebe/authcodes',
        'target' => BackendController::class . '::authCodesAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_authcodes',
    ],
    'kequestionnairebe_createauthcodes' => [
        'path' => '/kequestionnairebe/createauthcodes',
        'target' => BackendController::class . '::createAuthCodesAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_createauthcodes',
    ],
    'kequestionnairebe_authcodessimple' => [
        'path' => '/kequestionnairebe/authcodessimple',
        'target' => BackendController::class . '::authCodesSimpleAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_authcodessimple',
    ],
    'kequestionnairebe_authcodesmail' => [
        'path' => '/kequestionnairebe/authcodesmail',
        'target' => BackendController::class . '::authCodesMailAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_authcodesmail',
    ],
    'kequestionnairebe_createandmailauthcodes' => [
        'path' => '/kequestionnairebe/createandmailauthcodes',
        'target' => BackendController::class . '::createAndMailAuthCodesAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_createandmailauthcodes',
    ],
    'kequestionnairebe_authcodesremind' => [
        'path' => '/kequestionnairebe/authcodesremind',
        'target' => BackendController::class . '::authCodesRemindAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_authcodesremind',
    ],
    'kequestionnairebe_remindandmailauthcodes' => [
        'path' => '/kequestionnairebe/remindandmailauthcodes',
        'target' => BackendController::class . '::remindAndMailAuthCodesAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_remindandmailauthcodes',
    ],
    'kequestionnairebe_exportcsv' => [
        'path' => '/kequestionnairebe/exportcsv',
        'target' => BackendController::class . '::exportCsvAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_exportcsv',
    ],
    'kequestionnairebe_export' => [
        'path' => '/kequestionnairebe/export',
        'target' => BackendController::class . '::exportAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_export',
    ],
    'kequestionnairebe_analyse' => [
        'path' => '/kequestionnairebe/analyse',
        'target' => BackendController::class . '::analyseAction',
        'access' => 'user',
        'name' => 'kequestionnairebe_analyse',
    ]
];