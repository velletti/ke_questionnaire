<?php
if (!defined ('TYPO3')) {
	die ('Access denied.');
}


return array(
    'ctrl' => array(
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
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'title,show_title,text,help_text,image,image_position,is_mandatory,must_be_correct,answers,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/question.svg'
    ),
    'types' => array(
        'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question' => array('showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,type,title,show_title,text,--palette--;;4,image,--div--;LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.answers,answers,--div--;Settings,is_mandatory,random_answers,column_count,max_answers,min_answers,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'),
        'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type, title'),
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
                'foreign_table' => 'tx_kequestionnaire_domain_model_question',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_question.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_question.sys_language_uid IN (-1,0)',
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
                'behaviour' => array(
                    'allowLanguageSynchronization' => true ,
                ) ,
            ),
        ),
        'type' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.Question', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.PageBreak', 'value' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak'),
                ),
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'default' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question',
            ),
        ),
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => array(
                    'title_picker' => array(
                        'type' => 'select',
                        'mode' => '',
                        'items' => array(
                            array('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.type.I.2', 'Pagebreak'),
                        ),
                    ),
                ),
                'required' => true,
            ),
        ),
        'show_title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.show_title',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.text',
            'config' => array(
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    't3editorHtml' => array(
                        'enableByTypeConfig' => 1,
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
                        'params' => array(
                            'format' => 'html',
                        ),
                    ),
                    't3editorTypoScript' => array(
                        'enableByTypeConfig' => 1,
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\\CMS\\T3Editor\\FormWizard->main',
                        'params' => array(
                            'format' => 'ts',
                        ),
                    ),
                ),
            ),
        ),
        'help_text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.help_text',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ),
        ),

        'image' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image',
            'config' => [
                ### !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'allowed' => "jpg,jpeg,gif,png",
                'appearance' => array(
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ),
                'maxitems' => 1,
                'overrideChildTca' => ['types' => array(
                    '0' => array(
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ),
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => array(
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ),
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
                        'showitem' => '
							--palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                    ),


                )],
            ],
        ),

        'image_position' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.1', 'value' => 'top'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.2', 'value' => 'right'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.3', 'value' => 'left'),
                    array('label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.image_position.I.4', 'value' => 'bottom')
                ),
                'size' => 1,
                'maxitems' => 1,
                'default' => 'top',
                'renderType' => 'selectSingle',
            ),
        ),
        'is_mandatory' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.is_mandatory',
            'config' => array(
                'type' => 'check',
                'default' => 0
            ),
        ),
        'must_be_correct' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.must_be_correct',
            'config' => array(
                'type' => 'check',
                'default' => 0
            ),
        ),
        'answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.answers',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_kequestionnaire_domain_model_answer',
                'foreign_field' => 'question',
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
        'random_answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.random_answers',
            'config' => array(
                'type' => 'check',
                'default' => 0
            ),
        ),
        'column_count' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.column_count',
            'config' => array(
                'type'	 => 'number',
                'size'	 => '4',
                'max'	 => '4',
                'default'=> 1
            ),
        ),
        'max_answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.max_answers',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'min_answers' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.min_answers',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '4',
                'max'	  => '4',
                'default' => 0
            )
        ),
        'content_id' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.content_id',
            'config' => array(
                'type' => 'group',
                'allowed' => 'tt_content',
                'size' => 1,
                'maxitems' => 1
            ),
        ),


        'css' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_question.css',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ),
        )

    ),
);
