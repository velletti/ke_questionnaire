<?php
namespace Kennziffer\KeQuestionnaire\Reports;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kennziffer.com <info@kennziffer.com>, www.kennziffer.com
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Reports\StatusProviderInterface;
use TYPO3\CMS\Reports\Status;
use Kennziffer\KeQuestionnaire\Exception;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FileAccessReport implements StatusProviderInterface {
	/**
	 * @var string
	 */
	protected $tmpFileAndPath = '';

	/**
	 * @var string
	 */
        
	protected $siteUrl = '';

	/**
	 * @var array
	 */
	protected $staticStateResponseData = array();

	public function getLabel(): string
	{
		return $GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.label');
	}


	/**
	 * Returns the status of an extension or (sub)system
	 *
	 * @return array An array of \TYPO3\CMS\Reports\Status objects
	 */
	public function getStatus(): array
	{
		$statusArray = array();
		$failState = '';

		$this->init();
		$failState = $this->checkStatus();

		list($title, $value, $message, $severity) = $this->staticStateResponseData[$failState];

		$status = GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Reports\\Status',
			$title,
			$value,
			$message,
            ($severity ?? \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK)
		);

		$statusArray[] = $status;

		return $statusArray;
	}

	/**
	 * Do some initialization for texts of the reports
	 *
	 * @return void
	 */
	protected function init() {
		$this->tmpFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf/TEST';
		$this->siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');

		$this->staticStateResponseData = array(
			'ok' => array(
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.title'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.ok'),
				'',
				\TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK
			),
			'writeFail' => array(
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.title'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.warning'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.warning.details'),
				\TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING
			),
			'tmpFileReadable' => array(
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.title'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.error'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.error.explanation'),
				\TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
			),
			'unknownErrorCheckingTmpFile' => array(
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.title'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.warning'),
				$GLOBALS['LANG']->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang.xml:report.fileAccess.warning.unknown'),
				\TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING
			)
		);
	}

	/**
	 * checks the status of the temp files
	 *
	 * @return string
	 */
	protected function checkStatus() {
		$failState = '';

		$failState = $this->createAndCheckTmpFile();
		$failState = (!strlen($failState))?$this->checkTmpFileReadable():$failState;

		if(!strlen(trim($failState))) {
			$failState = 'ok';
		}

		return $failState;
	}

	/**
	 * Try to write check file to typo3temp folder
	 *
	 * @return string
	 */
	protected function createAndCheckTmpFile() {
		if(!is_dir(Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf')) {
			@mkdir(Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf');
		}
		//create htaccess file
		$htaccess = '
Order Deny,Allow
Deny from all
Allow from 127.0.0.1

<FilesMatch ".*\.(css|js)$">
	Order Allow,Deny
	Allow from all
</FilesMatch>';
		$htaccessFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/.htaccess';
		//$htaccessFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf/.htaccess';
		$writeHtaccess = GeneralUtility::writeFileToTypo3tempDir($htaccessFileAndPath, $htaccess);
		if($writeHtaccess !== NULL) {
			return 'writeFail';
		}

		//write test file
		$writeResult = GeneralUtility::writeFileToTypo3tempDir($this->tmpFileAndPath, 'TEST');
		if($writeResult !== NULL) {
			return 'writeFail';
		}

		return '';
	}

	/**
	 * Try to check if testfile is "world readable"
	 *
	 * @return string
	 */
	protected function checkTmpFileReadable() {
		$responseHeaders = array();

		//try to read test file
		$url = $this->siteUrl . 'typo3temp/ke_questionnaire/pdf/TEST' ;
		try{
			$response = GeneralUtility::makeInstance(RequestFactory::class)->request($url);
			$responseHeaders = $response->getHeaders();
			@unlink($this->tmpFileAndPath);

			//if testfile is readable
			if($responseHeaders['error'] !== 0 && $responseHeaders['error'] !== 22) {
				$this->staticStateResponseData['unknownErrorCheckingTmpFile'][2] .= '<br /><br />' . $responseHeaders['message'];

				return 'unknownErrorCheckingTmpFile';
			}

			if(intval($responseHeaders['http_code']) === 200) {
				return 'tmpFileReadable';
			}
		} catch (\Exception $e) {
			return 'tmpFileNotReadable';
		}


		return '';
	}

	public function getIdentifier(): string
	{
		return 'general';
	}

	public function getTitle(): string
	{
		return 'File Access report';
	}

	public function getDescription(): string
	{
		return '....';
	}

	public function getIconIdentifier(): string
	{
		return 'module-reports';
	}
}

