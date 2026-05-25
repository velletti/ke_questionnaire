<?php
declare(strict_types=1);

use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect;

if (!defined ('TYPO3')) {
	die ('Access denied.');
}



return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer',
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
        'searchFields' => 'title,value,text,is_correct_answer,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/answer.svg'
    ],
    'types' => [
        Radiobutton::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, points, is_correct_answer, show_textfield,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'],
        Checkbox::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, points, is_correct_answer, show_textfield,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'],
        SingleInput::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, width, pre_text, in_text, post_text, max_chars, validation_type, validation_text, validation_keys_amount, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'],
        MultiInput::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, width, height, pre_text, in_text, post_text, validation_type, validation_text, validation_keys_amount, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'],
        SingleSelect::class => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, select_values, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'],
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
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_answer.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_answer.sys_language_uid IN (-1,0)',
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
            ],
        ],
        'type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.Radiobutton', 'value' => Radiobutton::class],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.Checkbox', 'value' => Checkbox::class],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.SingleInput', 'value' => SingleInput::class],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.MultiInput', 'value' => MultiInput::class],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.SingleSelect', 'value' => SingleSelect::class],
                ],
                'itemsProcFunc' => 'Kennziffer\\KeQuestionnaire\\Utility\\TCAAnswerType->checkTypes',
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => Checkbox::class,
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'points' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=>  [
                    'upper' => '1000',
                    'lower' => '-1000'
                ],
                'default' => 0
            ]
        ],
        'points_start' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points_start',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=>  [
                    'upper' => '1000',
                    'lower' => '-1000'
                ],
                'default' => 0
            ]
        ],
        'points_increase' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points_increase',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=>  [
                    'upper' => '1000',
                    'lower' => '-1000'
                ],
                'default' => 0
            ]
        ],
        'text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.text',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            //  'richtextConfiguration' => 'jve_template',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
            // 'defaultExtras' => '',
        ],
        'is_correct_answer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.is_correct_answer',
            'config' => [
                'type' => 'check',
            ],
        ],
        'question' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'width' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.width',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            ]
        ],
        'height' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.height',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            ]
        ],
        'pre_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.pre_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'in_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.in_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'post_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.post_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'max_chars' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_chars',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'validation_type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.none', 'value' => ''],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.numeric', 'value' => 'numeric'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.integer', 'value' => 'integer'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.date', 'value' => 'date'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.string', 'value' => 'string'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.string2chars', 'value' => 'string2'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.email', 'value' => 'email'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.compareText', 'value' => 'compareText'],
                    ['label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.keywords', 'value' => 'keywords'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => '',
            ],
        ],
        'validation_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_text',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'validation_keys_amount' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_keys_amount',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'comparison_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.comparison_text',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'cloze_position' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cloze_position',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 1,
            ],
        ],
        'cloze_add_terms' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cloze_add_terms',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        /*
        'image' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.image',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'file',
                'uploadfolder' => 'uploads/tx_kequestionnaire',
                'show_thumbs' => 1,
                'size' => 1,
                'maxitems' => 1,
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'disallowed' => '',
            ),
        ),
        */
        'coords' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.coords',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
                'fieldControl' => ['editPopup' => ['disabled' => false, 'options' => ['title' => 'Create Image Area Coordinates']]],
            ],
        ],
        'area_index' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.area_index',
            'config' => [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            ],
        ],
        'area_highlight' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.area_highlight',
            'config' => [
                'type' => 'check',
                'default' => true
            ],
        ],
        'answer' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'cols' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cols',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_field' => 'answer',
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
        'show_textfield' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.show_textfield',
            'config' => [
                'type' => 'check',
            ],
        ],
        'max_answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_answers',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'min_answers' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.min_answers',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'select_values' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.select_values',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'left_label' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.left_label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'right_label' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.right_label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'min_value' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.min_value',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            ]
        ],
        'max_value' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_value',
            'config' =>  [
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 10
            ]
        ],
        'slider_increment' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.slider_increment',
            'config' =>  [
                'type'	 => 'input',
                'size'	 => '10',
                'max'	  => '10',
                'eval'	 => 'float',
                'default' => '1.0000'
            ]
        ],
        'show_steps' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.show_steps',
            'config' => [
                'type' => 'check',
            ],
        ],
        'step_labels' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.step_labels',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'source_dir' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.source_dir',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'destination_dir' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.destination_dir',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'avatar_parts' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.avatar_parts',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'feuser_field' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.feuser_field',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'title_line' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.title_line',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
];
