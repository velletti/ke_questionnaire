<?php
declare(strict_types=1);

use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak;
use TYPO3\CMS\Core\Resource\FileType;

if (!defined ('TYPO3')) {
	die ('Access denied.');
}


return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'type' => 'type',
        'thumbnail' => 'image',
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
        'searchFields' => 'title,show_title,text,help_text,image,image_position,is_mandatory,must_be_correct,answers,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/question.svg'
    ],
    'types' => [
        Question::class => ['showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,type,title,show_title,text,--palette--;;4,image,--div--;LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.answers,answers,--div--;Settings,is_mandatory,random_answers,column_count,max_answers,min_answers,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'],
        PageBreak::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type, title'],
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
                'foreign_table' => 'tx_kequestionnaire_domain_model_question',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_question.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_question.sys_language_uid IN (-1,0)',
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
        'type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.Question', 'value' => Question::class],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.PageBreak', 'value' => PageBreak::class],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => Question::class,
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => [
                    'title_picker' => [
                        'type' => 'select',
                        'mode' => '',
                        'items' => [
                            ['LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.2', 'Pagebreak'],
                        ],
                    ],
                ],
                'required' => true,
            ],
        ],
        'show_title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.show_title',
            'config' => [
                'type' => 'check',
            ],
        ],
        'text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.text',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => [
                    't3editorHtml' => [
                        'enableByTypeConfig' => 1,
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
                        'params' => [
                            'format' => 'html',
                        ],
                    ],
                    't3editorTypoScript' => [
                        'enableByTypeConfig' => 1,
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
                        'params' => [
                            'format' => 'ts',
                        ],
                    ],
                ],
            ],
        ],
        'help_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.help_text',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'image' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image',
            'config' => [
                ### !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'allowed' => "jpg,jpeg,gif,png",
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                'maxitems' => 1,
                'overrideChildTca' => ['types' => [
                    '0' => [
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ],
                    FileType::TEXT->value => [
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ],
                    FileType::IMAGE->value => [
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ],


                ]],
            ],
        ],

        'image_position' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.1', 'value' => 'top'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.2', 'value' => 'right'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.3', 'value' => 'left'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.4', 'value' => 'bottom']
                ],
                'size' => 1,
                'maxitems' => 1,
                'default' => 'top',
                'renderType' => 'selectSingle',
            ],
        ],
        'is_mandatory' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.is_mandatory',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'must_be_correct' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.must_be_correct',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.answers',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_field' => 'question',
                'foreign_sortby' => 'sorting',
                'maxitems'      => 9999,
                'appearance' => [
                    'collapseAll' => TRUE,
                    'expandSingle' => TRUE,
                    'levelLinksPosition' => 'both',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'useSortable' => 1
                ],
            ],
        ],
        'random_answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.random_answers',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'column_count' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.column_count',
            'config' => [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	 => '4',
                'default'=> 1
            ],
        ],
        'max_answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.max_answers',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'min_answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.min_answers',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'content_id' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.content_id',
            'config' => [
                'type' => 'group',
                'allowed' => 'tt_content',
                'size' => 1,
                'maxitems' => 1
            ],
        ],


        'css' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.css',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ]

    ],
];
