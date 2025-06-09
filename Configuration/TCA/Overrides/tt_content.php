<?php
if (!defined ('TYPO3')) die ('Access denied.');


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'Questionnaire',
    'KeQ Inserts a questionnaire'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'QList',
    'KeQ List of questionnaires'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'View',
    'KeQ FeView of Participations'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('ke_questionnaire');
$pluginSignature = strtolower($extensionName) . '_questionnaire';
$pluginSignature2 = strtolower($extensionName) . '_qlist';
$pluginSignature5 = strtolower($extensionName) . '_view';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature2] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature2] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature5] = 'layout,select_key,recursive';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . 'ke_questionnaire' . '/Configuration/FlexForms/questionnaire.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature2, 'FILE:EXT:' . 'ke_questionnaire' . '/Configuration/FlexForms/qlist.xml');
