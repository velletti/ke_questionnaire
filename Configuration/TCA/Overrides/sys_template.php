<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined ('TYPO3')) {
    die ('Access denied.');
}
ExtensionManagementUtility::addStaticFile('ke_questionnaire', 'Configuration/TypoScript', 'Questionnaire');
