<?php
if (!defined('TYPO3')) {
	die ('Access denied.');
}


include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_questionnaire').'Classes/Utility/AddActivatorsToDependancy.php');

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('ke_questionnaire');

$pluginSignature = strtolower($extensionName) . '_questionnaire';
$pluginSignature2 = strtolower($extensionName) . '_qlist';
$pluginSignature5 = strtolower($extensionName) . '_view';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content.pi_flexform.kequestionnaire_questionnaire.list', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_flexforms.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_question', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_question.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_question');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_answer', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_answer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_answer');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_resultquestion', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_resultquestion.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_resultquestion');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_resultanswer', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_resultanswer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_resultanswer');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_result', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_result.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_result');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_range', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_range.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_range');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_authcode', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_authcode.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_authcode');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kequestionnaire_domain_model_dependancy', 'EXT:ke_questionnaire/Resources/Private/Language/locallang_csh_tx_kequestionnaire_domain_model_dependancy.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kequestionnaire_domain_model_dependancy');


/*
 * Backend-Modules
 */
if (TYPO3_MODE === 'BE'){
   $mainModuleName = 'keQuestionnaireBe';
   

    // Hauptmodul erstellen
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'KeQuestionnaire',            # Extension-Key
		$mainModuleName,				   # Kategorie
		'',								   # Modulname
		'',                                # Position
		[],     # Controller
		[  	'access' => 'user,group',  # Konfiguration
            'icon'	 => 'EXT:'.'ke_questionnaire'.'/ext_icon.gif',
            'labels' => 'LLL:EXT:'.'ke_questionnaire'.'/Resources/Private/Language/locallang_mod.xml',
		]
	);
	
    // Authcode Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'KeQuestionnaire',                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Authcode',				   # Modulname
		'',                                # Position
		Array ( \Kennziffer\KeQuestionnaire\Controller\BackendController::class  => 'index,authCodes,createAuthCodes,authCodesSimple,authCodesMail,createAndMailAuthCodes,authCodesRemind,remindAndMailAuthCodes',
				'Export'  => 'downloadPdf, pdf, downloadAuthCodesCsv'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.'ke_questionnaire'.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.'ke_questionnaire'.'/Resources/Private/Language/locallang_mod_authcode.xml'
		)
	);
	
	// Export Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'KeQuestionnaire',                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Export',				   # Modulname
		'',                                # Position
		Array ( \Kennziffer\KeQuestionnaire\Controller\ExportController::class => 'index,csv,csvRb,downloadCsv,downloadCsvRb,pdf,downloadPdf,csvInterval,csvRbInterval,csvCheckInterval,downloadCsvInterval'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.'ke_questionnaire'.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.'ke_questionnaire'.'/Resources/Private/Language/locallang_mod_export.xml',
		)
	);
    
    // Analyse Backend Modul der Extension
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'KeQuestionnaire',                  # Extension-Key
		$mainModuleName,		   # Kategorie
		'Analyse',				   # Modulname
		'',                                # Position
		Array ( \Kennziffer\KeQuestionnaire\Controller\AnalyseController::class=> 'index,questions,general'),     # Controller
		Array (	'access' => 'user,group',  # Konfiguration
				'icon'	 => 'EXT:'.'ke_questionnaire'.'/ext_icon.gif',
				'labels' => 'LLL:EXT:'.'ke_questionnaire'.'/Resources/Private/Language/locallang_mod_analyse.xml',
		)
	);  
	
	// Report zur Prüfung des FileAcces auf den Temp Ordner
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Kennziffer/Questionnaire'][]
		= 'Kennziffer\\KeQuestionnaire\\Reports\\FileAccessReport';
    
}
