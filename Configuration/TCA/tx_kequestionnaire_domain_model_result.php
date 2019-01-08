<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

return  array(
    'ctrl' => array(
        'title'	=> 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result',
        'label' => 'finished',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'fe_cruser_id' => 'fe_cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => TRUE,
        'versioning_followPages' => TRUE,
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
        'searchFields' => 'finished,questions,auth_code,',
        'iconfile' => 'EXT:ke_questionnaire/Resources/Public/Icons/result.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, finished, questions, fe_user, auth_code',
    ),
    'types' => array(
        '1' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, finished, questions, fe_user, auth_code,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime, endtime'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0)
                ),
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_kequestionnaire_domain_model_result',
                'foreign_table_where' => 'AND tx_kequestionnaire_domain_model_result.pid=###CURRENT_PID### AND tx_kequestionnaire_domain_model_result.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        't3ver_label' => array(
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => array(
                    'allowLanguageSynchronization' => true ,
                ) ,
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => array(
                    'allowLanguageSynchronization' => true ,
                ) ,
            ),
        ),
        'fe_cruser_id' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'finished' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.finished',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ),
        ),
        'questions' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.questions',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_kequestionnaire_domain_model_resultquestion',
                'foreign_field' => 'result',
                'maxitems'      => 9999,
                'appearance' => array(
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'points' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.points',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ),
        ),
        'max_points' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.max_points',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ),
        ),
        'fe_user' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.fe_user',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'size' => 1,
                'maxitems' => 1
            ),
        ),
        'auth_code' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_db.xml:tx_kequestionnaire_domain_model_result.auth_code',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_kequestionnaire_domain_model_authcode',
                'size' => 1,
                'maxitems' => 1
            ),
        ),
        'crdate' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
    ),
);
