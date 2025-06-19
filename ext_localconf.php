<?php
if (!defined('TYPO3')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'KeQuestionnaire',
	'Questionnaire',
	array(
        Kennziffer\KeQuestionnaire\Controller\ResultController::class => 'new,create,show,feUserAccess,maxParticipations,authCodeAccess,dependancyAccess,end',
        Kennziffer\KeQuestionnaire\Controller\EvaluationController::class => 'show',
		Kennziffer\KeQuestionnaire\Controller\PointRangeController::class => 'showText',
		Kennziffer\KeQuestionnaire\Controller\AjaxController::class => 'test',
		Kennziffer\KeQuestionnaire\Controller\ExportController::class => 'downloadPdf',
	),
	// non-cacheable actions
	array(
        Kennziffer\KeQuestionnaire\Controller\ResultController::class => 'new,create,feUserAccess,maxParticipations,authCodeAccess,dependancyAccess,',
		Kennziffer\KeQuestionnaire\Controller\EvaluationController::class => '',
		Kennziffer\KeQuestionnaire\Controller\PointRangeController::class => '',
		Kennziffer\KeQuestionnaire\Controller\AjaxController::class => 'test',
		Kennziffer\KeQuestionnaire\Controller\ExportController::class => 'downloadPdf',
	)
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'KeQuestionnaire',
	'QList',
	array(
        Kennziffer\KeQuestionnaire\Controller\QuestionnaireController::class => 'list',
        Kennziffer\KeQuestionnaire\Controller\ExportController::class => 'downloadPdf',
	),
	// non-cacheable actions
	array(
        Kennziffer\KeQuestionnaire\Controller\QuestionnaireController::class => 'list',
        Kennziffer\KeQuestionnaire\Controller\ExportController::class => 'downloadPdf',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'KeQuestionnaire',
	'View',
	array(
        Kennziffer\KeQuestionnaire\Controller\ResultController::class => 'show',
	),
	// non-cacheable actions
	array(
        Kennziffer\KeQuestionnaire\Controller\ResultController::class => 'show',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'KeQuestionnaire',
    'Be',
    array(
        Kennziffer\KeQuestionnaire\Controller\BackendController::class =>  'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
        Kennziffer\KeQuestionnaire\Controller\ExportController::class  => 'downloadPdf, pdf, downloadAuthCodesCsv'
    ),
    // non-cacheable actions
    array(
        Kennziffer\KeQuestionnaire\Controller\BackendController::class =>  'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
        Kennziffer\KeQuestionnaire\Controller\ExportController::class  => 'downloadPdf, pdf, downloadAuthCodesCsv'
    )
);
