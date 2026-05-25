<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Kennziffer\KeQuestionnaire\Controller\ResultController;
use Kennziffer\KeQuestionnaire\Controller\EvaluationController;
use Kennziffer\KeQuestionnaire\Controller\AjaxController;
use Kennziffer\KeQuestionnaire\Controller\ExportController;
use Kennziffer\KeQuestionnaire\Controller\BackendController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (!defined('TYPO3')) {
	die ('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;

ExtensionUtility::configurePlugin(
	'KeQuestionnaire',
	'Questionnaire',
	[
        ResultController::class => 'new,create,show,feUserAccess,maxParticipations,authCodeAccess,dependancyAccess,end',
        EvaluationController::class => 'show',
		AjaxController::class => 'test',
		ExportController::class => 'downloadPdf',
	],
	// non-cacheable actions
	[
        ResultController::class => 'new,create,feUserAccess,maxParticipations,authCodeAccess,dependancyAccess,',
		EvaluationController::class => '',
		AjaxController::class => 'test',
		ExportController::class => 'downloadPdf',
	],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);


ExtensionUtility::configurePlugin(
	'KeQuestionnaire',
	'View',
	[
        ResultController::class => 'show',
	],
	// non-cacheable actions
	[
        ResultController::class => 'show',
	],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

