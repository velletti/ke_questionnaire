<?php
declare(strict_types=1);

if (!defined ('TYPO3')) {
	die ('Access denied.');
}

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer',
        'label' => 'answer',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'fe_cruser_id' => 'fe_cruser_id',
        'sortby' => 'sorting',
        'versioningWS' => TRUE,
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
        'searchFields' => 'answer,value,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/resultanswer.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, resultquestion,answer,value,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime, endtime'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
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
                'foreign_table' => 'tx_kequestionnaire_domain_model_resultanswer',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_resultanswer.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_resultanswer.sys_language_uid IN (-1,0)',
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
        'fe_cruser_id' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'answer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer.answer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_table_where' => ' AND tx_kequestionnaire_domain_model_answer.pid = ###CURRENT_PID### ',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'value' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer.value',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'col' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer.col',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'additional_value' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer.additional_value',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'resultquestion' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_resultanswer.resultquestion',
            'config' =>  [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_kequestionnaire_domain_model_resultquestion',
                'foreign_table_where' => ' AND tx_kequestionnaire_domain_model_resultquestion.pid = ###CURRENT_PID### ',
                'maxitems' => 1,
            ]
        ],
    ],
];
