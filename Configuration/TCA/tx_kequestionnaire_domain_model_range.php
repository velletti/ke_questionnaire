<?php
if (!defined ('TYPO3')) {
	die ('Access denied.');
}
return array(
    'ctrl' => array(
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_range',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'fe_cruser_id' => 'fe_cruser_id',
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
        'searchFields' => 'text,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/range.svg'
    ),
    'types' => array(
        '1' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, text, points_from, points_until,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime, endtime'),
    ),
    'palettes' => array(),
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
                'foreign_table' => 'tx_kequestionnaire_domain_model_range',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_range.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_range.sys_language_uid IN (-1,0)',
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
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xlf:tx_kequestionnaire_domain_model_range.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ),
        ),
        'text' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_range.text',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim' ,
                'enableRichtext' => true
            ),
        ),
        'points_from' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_range.points_from',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '8',
                'max'	  => '8',
                'checkbox' => '0',
                'default' => 0
            )
        ),
        'points_until' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_range.points_until',
            'config' => Array (
                'type'	 => 'number',
                'size'	 => '8',
                'max'	  => '8',
                'checkbox' => '0',
                'default' => 0
            )
        ),
    ),
);
