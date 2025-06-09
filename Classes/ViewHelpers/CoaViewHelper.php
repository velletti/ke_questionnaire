<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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
class CoaViewHelper extends AbstractViewHelper {


    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;


	/**
	 * Disable the escaping interceptor because otherwise the child nodes would be escaped before this view helper
	 * can decode the text's entities.
	 *
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * @var array
	 */
	protected $typoScriptSetup;

	/**
  * @var TypoScriptParser
  */
 protected $typoScriptParser;

	/**
	 * @var	t3lib_fe contains a backup of the current $GLOBALS['TSFE'] if used in BE mode
	 */
	protected $tsfeBackup;

	/**
  * @var ConfigurationManagerInterface
  */
 protected $configurationManager;
 public function __construct(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager, \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser $typoScriptParser)
 {
     $this->configurationManager = $configurationManager;
     $this->typoScriptParser = $typoScriptParser;
 }



    /** * Constructor *
     * @api */
    public function initializeArguments(): void {
        $this->registerArgument('typoScript', 'string', ' the TypoScript to render ', false );
        $this->registerArgument('data', 'mixed', ' the data to be used for rendering the cObject. Can be an object, array or string. If this argument is not set, child nodes will be used', false );
        $this->registerArgument('currentValueKey', 'string', ' currentValueKey ', false );
        parent::initializeArguments() ;
    }

	/**
	 * Renders the TypoScript
	 *
	 * @return string the content of the rendered TypoScript object
	 */
	public function render() {
        $typoScript = $this->arguments['typoScript'] ;
        $data = $this->arguments['data'] ;
        $currentValueKey = $this->arguments['currentValueKey'] ;

		if (\TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
			$this->simulateFrontendEnvironment();
		}

		if ($data === NULL) {
			$data = $this->renderChildren();
		}
		$currentValue = NULL;
		if (is_object($data)) {
			$data = ObjectAccess::getGettableProperties($data);
		} elseif (is_string($data) || is_numeric($data)) {
			$currentValue = (string) $data;
			$data = array($data);
		}

        /* @var ContentObjectRenderer $contentObject */
		$contentObject = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

		$contentObject->start($data);

		if ($currentValue !== NULL) {
			$contentObject->setCurrentVal($currentValue);
		} elseif ($currentValueKey !== NULL && isset($data[$currentValueKey])) {
			$contentObject->setCurrentVal($data[$currentValueKey]);
		}

		$this->typoScriptParser->parse($typoScript);
		$typoScriptConf = $this->typoScriptParser->setup;

		//$content = $contentObject->COBJ_ARRAY($typoScriptConf);
        $content = $contentObject->cObjGet($typoScriptConf);

		if (\TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
			$this->resetFrontendEnvironment();
		}

		return $content;
	}

	/**
	 * Sets the $TSFE->cObjectDepthCounter in Backend mode
	 * This somewhat hacky work around is currently needed because the cObjGetSingle() function of tslib_cObj relies on this setting
	 *
	 * @return void
	 */
	protected function simulateFrontendEnvironment() {
		$this->tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : NULL;
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->cObjectDepthCounter = 100;
	}

	/**
	 * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
	 *
	 * @return void
	 * @see simulateFrontendEnvironment()
	 */
	protected function resetFrontendEnvironment() {
		$GLOBALS['TSFE'] = $this->tsfeBackup;
	}
}
?>