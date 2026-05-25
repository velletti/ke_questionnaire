<?php

declare(strict_types=1);

return [
    'dependencies' => [
        'backend',
        'core',
    ],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@jvelletti/kequestionnaire/ExportCsv.js' => 'EXT:ke_questionnaire/Resources/Public/JavaScript/Backend/ExportCsv.js',
        '@jvelletti/kequestionnaire/Analyse.js' => 'EXT:ke_questionnaire/Resources/Public/JavaScript/Backend/Analyse.js',
    ],
];
