<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined ('TYPO3')) {
    die ('Access denied.');
}
ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'Questionnaire',
    'KeQ Inserts a questionnaire'
);

ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'QList',
    'KeQ List of questionnaires'
);

ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'View',
    'KeQ FeView of Participations'
);

$extensionName = GeneralUtility::underscoredToUpperCamelCase('ke_questionnaire');
$pluginSignature = strtolower($extensionName) . '_questionnaire';
$pluginSignature2 = strtolower($extensionName) . '_qlist';
$pluginSignature5 = strtolower($extensionName) . '_view';
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', $pluginSignature, 'after:subheader');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', $pluginSignature2, 'after:subheader');

ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:ke_questionnaire/Configuration/FlexForms/questionnaire.xml', $pluginSignature);
ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:ke_questionnaire/Configuration/FlexForms/qlist.xml', $pluginSignature2);
