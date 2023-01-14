<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Kennziffer.'.'ke_questionnaire',
	'Questionnaire',
	array(
		'Result' => 'new,create,show,feUserAccess,maxParticipations,authCodeAccess,dependancyAccess,end',
		'Mailing' => 'mail',
		'Evaluation' => 'show',
		'PointRange' => 'showText',
		'Question' => 'list',
		'Ajax' => 'test',
		'Export' => 'downloadPdf',
	),
	// non-cacheable actions
	array(
		'Result' => 'new,create',
		'Mailing' => '',
		'Evaluation' => '',
		'PointRange' => '',
		'Question' => '',
		'Ajax' => 'test',
		'Export' => 'downloadPdf',
	)
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Kennziffer.'.'ke_questionnaire',
	'QList',
	array(
		'Questionnaire' => 'list',
                'Export' => 'downloadPdf',
	),
	// non-cacheable actions
	array(
		'Questionnaire' => 'list',
                'Export' => 'downloadPdf',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Kennziffer.'.'ke_questionnaire',
	'View',
	array(
		'Result' => 'show',
	),
	// non-cacheable actions
	array(
		'Result' => 'show',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Kennziffer.'.'ke_questionnaire',
    'Be',
    array(
        'Backend' =>  'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
        'Export'  => 'downloadPdf, pdf, downloadAuthCodesCsv'
    ),
    // non-cacheable actions
    array(
        'Backend' =>  'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
        'Export'  => 'downloadPdf, pdf, downloadAuthCodesCsv'
    )
);
