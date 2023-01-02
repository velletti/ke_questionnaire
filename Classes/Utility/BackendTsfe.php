<?php
namespace Kennziffer\KeQuestionnaire\Utility;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Kennziffer.com <info@kennziffer.com>, www.kennziffer.com
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

/**
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BackendTsfe {

    /**
     * @var int
     */
    public $pid=1 ;


	function buildTSFE() {
	    // j.v. page ID and TypeNum  needed to make the instance of TypoScriptFrontendController
	    $typeNum = 0 ;
	    if(  $this->pid < 1 ) {
            $this->pid = 1 ;
        }
		if (!is_object($GLOBALS['TT'])) {
			$GLOBALS['TT'] = new TimeTracker;
			GeneralUtility::makeInstance(TimeTracker::class)->start();
		}
/*
   * @param array $_ unused, previously defined to set TYPO3_CONF_VARS
   * @param mixed $id The value of GeneralUtility::_GP('id')
   * @param int $type The value of GeneralUtility::_GP('type')
   * @param bool|string $no_cache The value of GeneralUtility::_GP('no_cache'), evaluated to 1/0, will be unused in TYPO3 v10.0.
   * @param string $cHash The value of GeneralUtility::_GP('cHash')
   * @param string $_2 previously was used to define the jumpURL
   * @param string $MP The value of GeneralUtility::_GP('MP')
   */
  // public function __construct($_ = null, $id, $type, $no_cache = null, $cHash = '', $_2 = null, $MP = '')
  /** @var TypoScriptFrontendController $TSFEclassName */
  $TSFEclassName = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController' ,NULL , $this->pid , $typeNum  , null , '', '', '') ;
		$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], $this->pid, $typeNum , null , '', '', '', '');
        // note: we need to instantiate the logger manually here since the injection happens after the constructor
        $GLOBALS['TSFE']->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__) ;
		$GLOBALS['TSFE']->fetch_the_id();

		// done already in fetch the ID
	//	$GLOBALS['TSFE']->getPageAndRootline();
	//	$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->tmpl->getFileName_backPath = Environment::getPublicPath() . '/';
		GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
		$GLOBALS['TSFE']->getConfigArray();
	}	
}
?>