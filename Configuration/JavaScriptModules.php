<?php

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
    ],
];
