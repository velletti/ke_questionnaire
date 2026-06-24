<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// 1. Plugin registrieren (Erzeugt den CType: kequestionnaire_questionnaire)
ExtensionUtility::registerPlugin(
    'KeQuestionnaire',
    'Questionnaire',
    'KeQ Test / Umfrage',
    'kequestionnaire-plugin',
    'Form',
    'KE Questionnaire Umfrage/Test plugin'
);

$GLOBALS['TCA']['tt_content']['types']['kequestionnaire_questionnaire']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,pages,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
            pi_flexform
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
    ';
// 2. Das FlexForm-Feld in die Standard-Anzeige (showitem) des neuen CTypes integrieren
// Ohne diesen Schritt weiß TYPO3 v13 nicht, WO es die Flexform im Backend rendern soll!
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'pi_flexform',
    'kequestionnaire_questionnaire', // Exakter CType des Plugins
    'after:bodytext'                 // Platzierung (z.B. nach dem Bodytext-Feld)
);

// 3. Die FlexForm-Datei exakt für DIESEN CType registrieren
ExtensionManagementUtility::addPiFlexFormValue(
    '' ,
    'FILE:EXT:ke_questionnaire/Configuration/FlexForms/questionnaire.xml' ,
    'kequestionnaire_questionnaire' // NICHT "*", sondern exakt der CType!
);