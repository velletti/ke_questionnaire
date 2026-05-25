<?php
declare(strict_types=1);

if (!defined ('TYPO3')) {
	die ('Access denied.');
}
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_authcode',
        'label' => 'auth_code',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'fe_cruser_id' => 'fe_cruser_id',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'auth_code,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/authcode.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, auth_code, email, lastreminder,firstactive,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime, endtime'],
    ],
    'palettes' => [],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_kequestionnaire_domain_model_authcode',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_authcode.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_authcode.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true ,
                ] ,
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true ,
                ] ,
            ],
        ],
        'auth_code' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_authcode.auth_code',
            'config' =>  [
                'type'	 => 'input',
                'size'	 => '100',
                'max'	  => '255'
            ]
        ],
        'email' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_authcode.email',
            'config' =>  [
                'type'	 => 'input',
                'size'	 => '100',
                'max'	  => '255'
            ]
        ],
        'fe_user' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tt_address' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'lastreminder' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_authcode.lastreminder',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true ,
                ] ,
            ],
        ],
        'firstactive' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_authcode.firstactive',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true ,
                ] ,
            ],
        ],
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
