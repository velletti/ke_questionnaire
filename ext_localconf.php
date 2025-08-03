<?php
if (!defined('TYPO3')) {
	die ('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;

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



(function () {
    ExtensionManagementUtility::addModule(
        'web', // Main module key
        'kequestionnaire', // Submodule key
        '', // Position
        null, // Path to module icon (optional)
        [
            'routeTarget' => Kennziffer\KeQuestionnaire\Controller\BackendController::class . '::indexAction',
            'access' => 'user,group',
            'name' => 'web_kequestionnaire',
            'icon' => 'EXT:ke_questionnaire/Resources/Public/Icons/backend.svg',
            'labels' => 'LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
})();

(function () {
    $iconRegistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);

    $iconRegistry->registerIcon(
        'kequestionnaire-plugin',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ke_questionnaire/Resources/Public/Icons/backend_main.svg']
    );
    $iconRegistry->registerIcon(
        'kequestionnaire-plugin-index',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ke_questionnaire/Resources/Public/Icons/backend.svg']
    );

    $iconRegistry->registerIcon(
        'kequestionnaire-authcode-plugin',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ke_questionnaire/Resources/Public/Icons/authcode.svg']
    );

    $iconRegistry->registerIcon(
        'kequestionnaire-export-plugin',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ke_questionnaire/Resources/Public/Icons/export.svg']
    );

    $iconRegistry->registerIcon(
        'kequestionnaire-analyse-plugin',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ke_questionnaire/Resources/Public/Icons/analyse.svg']
    );
})();