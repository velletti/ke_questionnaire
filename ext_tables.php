<?php
if (!defined('TYPO3')) {
	die ('Access denied.');
}


include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_questionnaire').'Classes/Utility/AddActivatorsToDependancy.php');



/*
 * Backend-Modules
 */
if (TYPO3_MODE === 'BE'){
	// Report zur Prüfung des FileAcces auf den Temp Ordner
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Kennziffer/Questionnaire'][]
		= 'Kennziffer\\KeQuestionnaire\\Reports\\FileAccessReport';
    
}
