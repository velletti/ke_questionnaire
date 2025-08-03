<?php
if (!defined('TYPO3')) {
	die ('Access denied.');
}

if (isset($GLOBALS['TYPO3_REQUEST'] ) && TYPO3\CMS\Core\Http\ApplicationType::fromRequest( $GLOBALS['TYPO3_REQUEST'] )->isBackend()) {
}

