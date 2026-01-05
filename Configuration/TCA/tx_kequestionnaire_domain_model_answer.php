<?php
if (!defined ('TYPO3')) {
	die ('Access denied.');
}



return array(
    'ctrl' => array(
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
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'title,value,text,is_correct_answer,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/answer.svg'
    ),
    'types' => array(
        'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, points, is_correct_answer, show_textfield,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, points, is_correct_answer, show_textfield,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, width, pre_text, in_text, post_text, max_chars, validation_type, validation_text, validation_keys_amount, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, width, height, pre_text, in_text, post_text, validation_type, validation_text, validation_keys_amount, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, type, title, text, select_values, comparison_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden , starttime, endtime'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('label' => '', 'value' => 0),
                ),
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_answer.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_answer.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        't3ver_label' => array(
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => array(
                    'allowLanguageSynchronization' => true ,
                ) ,
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
            ),
        ),
        'type' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.Radiobutton', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.Checkbox', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.SingleInput', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.MultiInput', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.type.SingleSelect', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect'),
                ),
                'itemsProcFunc' => 'Kennziffer\\KeQuestionnaire\\Utility\\TCAAnswerType->checkTypes',
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => 'Kennziffer\\KeQuestionnaire\\Domain\\Model\\AnswerType\\Checkbox',
            ),
        ),
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'points' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=> Array (
                    'upper' => '1000',
                    'lower' => '-1000'
                ),
                'default' => 0
            )
        ),
        'points_start' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points_start',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=> Array (
                    'upper' => '1000',
                    'lower' => '-1000'
                ),
                'default' => 0
            )
        ),
        'points_increase' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.points_increase',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'checkbox' => '0',
                'range'	=> Array (
                    'upper' => '1000',
                    'lower' => '-1000'
                ),
                'default' => 0
            )
        ),
        'text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.text',
            'config' => array(
                'type' => 'text',
                'enableRichtext' => true,
            //  'richtextConfiguration' => 'jve_template',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ),
            // 'defaultExtras' => '',
        ),
        'is_correct_answer' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.is_correct_answer',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'question' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'width' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.width',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            )
        ),
        'height' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.height',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            )
        ),
        'pre_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.pre_text',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'in_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.in_text',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'post_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.post_text',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'max_chars' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_chars',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'validation_type' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.none', 'value' => ''),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.numeric', 'value' => 'numeric'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.integer', 'value' => 'integer'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.date', 'value' => 'date'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.string', 'value' => 'string'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.string2chars', 'value' => 'string2'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.email', 'value' => 'email'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.compareText', 'value' => 'compareText'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_type.keywords', 'value' => 'keywords'),
                ),
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => '',
            ),
        ),
        'validation_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_text',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'validation_keys_amount' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.validation_keys_amount',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'comparison_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.comparison_text',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'cloze_position' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cloze_position',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 1,
            ),
        ),
        'cloze_add_terms' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cloze_add_terms',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
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
        'coords' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.coords',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
                'fieldControl' => ['editPopup' => ['disabled' => false, 'options' => ['title' => 'Create Image Area Coordinates']]],
            ),
        ),
        'area_index' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.area_index',
            'config' => array(
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4'
            ),
        ),
        'area_highlight' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.area_highlight',
            'config' => array(
                'type' => 'check',
                'default' => true
            ),
        ),
        'answer' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'cols' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.cols',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_field' => 'answer',
                'foreign_sortby' => 'sorting',
                'maxitems'      => 9999,
                'appearance' => array(
                    'collapseAll' => TRUE,
                    'expandSingle' => TRUE,
                    'levelLinksPosition' => 'both',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'useSortable' => 1
                ),
            ),
        ),
        'show_textfield' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.show_textfield',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'max_answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_answers',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'min_answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.min_answers',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'select_values' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.select_values',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'left_label' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.left_label',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'right_label' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.right_label',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'min_value' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.min_value',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'max_value' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.max_value',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 10
            )
        ),
        'slider_increment' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.slider_increment',
            'config' => Array (
                'type'	 => 'input',
                'size'	 => '10',
                'max'	  => '10',
                'eval'	 => 'float',
                'default' => '1.0000'
            )
        ),
        'show_steps' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.show_steps',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'step_labels' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.step_labels',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'source_dir' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.source_dir',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'destination_dir' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.destination_dir',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'avatar_parts' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.avatar_parts',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'feuser_field' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.feuser_field',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'title_line' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_answer.title_line',
            'config' => array(
                'type' => 'check',
            ),
        ),
    ),
);
