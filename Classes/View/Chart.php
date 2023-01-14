<?php
namespace Kennziffer\KeQuestionnaire\View;

use TYPO3\CMS\Fluid\View\AbstractTemplateView;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;
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
class Chart extends AbstractTemplateView {

	/**
	 * Source code of the Fluid template
	 * @var string
	 */
	protected $templateSource = NULL;

	/**
	 * absolute path of the Fluid template
	 * @var string
	 */
	protected $templatePathAndFilename = NULL;

	/**
	 * absolute root path of the folder that contains Fluid layouts
	 * @var string
	 */
	protected $layoutRootPath = NULL;

	/**
	 * absolute root path of the folder that contains Fluid partials
	 * @var string
	 */
	protected $partialRootPath = NULL;

	/**
	 * @var Tx_Fluid_Core_Compiler_TemplateCompiler
	 */
	protected $templateCompiler;

	/**
	 * Constructor
	 *
	 * @param tslib_cObj $contentObject The current cObject. If NULL a new instance will be created
	 */
	public function __construct(tslib_cObj $contentObject = NULL) {
		if (!ExtensionManagementUtility::isLoaded('extbase')) {
			return 'In the current version you still need to have Extbase installed in order to use the Fluid Standalone view!';
		}
		$this->objectManager = GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');

		$configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface');
		if ($contentObject === NULL) {
			$contentObject = GeneralUtility::makeInstance('tslib_cObj');
		}
		$configurationManager->setContentObject($contentObject);

		$this->templateParser = $this->objectManager->get('Tx_Fluid_Core_Parser_TemplateParser');
		$this->setRenderingContext($this->objectManager->get('Tx_Fluid_Core_Rendering_RenderingContext'));

		$request = $this->objectManager->get('Tx_Extbase_MVC_Web_Request');
		$request->setRequestURI(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
		$request->setBaseURI(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));

		$uriBuilder = $this->objectManager->get('Tx_Extbase_MVC_Web_Routing_UriBuilder');
		$uriBuilder->setRequest($request);

		$controllerContext = $this->objectManager->get('Tx_Extbase_MVC_Controller_ControllerContext');
		$controllerContext->setRequest($request);
		$controllerContext->setUriBuilder($uriBuilder);
		$flashMessageContainer = $this->objectManager->get('Tx_Extbase_MVC_Controller_FlashMessages'); // singleton
		$controllerContext->setFlashMessageContainer($flashMessageContainer);
		$this->setControllerContext($controllerContext);

		$this->templateCompiler = $this->objectManager->get('Tx_Fluid_Core_Compiler_TemplateCompiler'); // singleton
		$this->templateCompiler->setTemplateCache($GLOBALS['typo3CacheManager']->getCache('fluid_template'));
	}

	/**
	 * Sets the format of the current request (default format is "html")
	 *
	 * @param string $format
	 * @return void
	 * @api
	 */
	public function setFormat($format) {
		$this->getRequest()->setFormat($format);
	}

	/**
	 * Returns the format of the current request (defaults is "html")
	 *
	 * @return string $format
	 * @api
	 */
	public function getFormat() {
		return $this->getRequest()->getFormat();
	}

	/**
	 * Returns the current request object
	 *
	 * @return Tx_Extbase_MVC_Web_Request
	 */
	public function getRequest() {
		return $this->controllerContext->getRequest();
	}

	/**
	 * Sets the absolute path to a Fluid template file
	 *
	 * @param string $templatePathAndFilename Fluid template path
	 * @return void
	 * @api
	 */
	public function setTemplatePathAndFilename($templatePathAndFilename) {
		$this->templatePathAndFilename = $templatePathAndFilename;
	}

	/**
	 * Returns the absolute path to a Fluid template file if it was specified with setTemplatePathAndFilename() before
	 *
	 * @return string Fluid template path
	 * @api
	 */
	public function getTemplatePathAndFilename() {
		return $this->templatePathAndFilename;
	}

	/**
	 * Sets the Fluid template source
	 * You can use setTemplatePathAndFilename() alternatively if you only want to specify the template path
	 *
	 * @param string $templateSource Fluid template source code
	 * @return void
	 * @api
	 */
	public function setTemplateSource($templateSource) {
		$this->templateSource = $templateSource;
	}

	/**
	 * Sets the absolute path to the folder that contains Fluid layout files
	 *
	 * @param string $layoutRootPath Fluid layout root path
	 * @return void
	 * @api
	 */
	public function setLayoutRootPath($layoutRootPath) {
		$this->layoutRootPath = $layoutRootPath;
	}

	/**
	 * Returns the absolute path to the folder that contains Fluid layout files
	 *
	 * @return string Fluid layout root path
	 * @api
	 */
	public function getLayoutRootPath() {
		if ($this->layoutRootPath === NULL && $this->templatePathAndFilename === NULL) {
			throw new InvalidTemplateResourceException('No layout root path has been specified. Use setLayoutRootPath().', 1288091419);
		}
		if ($this->layoutRootPath === NULL) {
			$this->layoutRootPath = dirname($this->templatePathAndFilename) . '/Layouts';
		}
		return $this->layoutRootPath;
	}

	/**
	 * Sets the absolute path to the folder that contains Fluid partial files.
	 *
	 * @param string $partialRootPath Fluid partial root path
	 * @return void
	 * @api
	 */
	public function setPartialRootPath($partialRootPath) {
		$this->partialRootPath = $partialRootPath;
	}

	/**
	 * Returns the absolute path to the folder that contains Fluid partial files
	 *
	 * @return string Fluid partial root path
	 * @api
	 */
	public function getPartialRootPath() {
		if ($this->partialRootPath === NULL && $this->templatePathAndFilename === NULL) {
			throw new InvalidTemplateResourceException('No partial root path has been specified. Use setPartialRootPath().', 1288094511);
		}
		if ($this->partialRootPath === NULL) {
			$this->partialRootPath = dirname($this->templatePathAndFilename) . '/Partials';
		}
		return $this->partialRootPath;
	}

	/**
	 * Checks whether a template can be resolved for the current request
	 *
	 * @return boolean
	 * @api
	 */
	public function hasTemplate() {
		try {
			$this->getTemplateSource();
			return TRUE;
		} catch (InvalidTemplateResourceException $e) {
			return FALSE;
		}
	}

	/**
	 * Returns a unique identifier for the resolved template file
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $actionName Name of the action. This argument is not used in this view!
	 * @return string template identifier
	 */
	protected function getTemplateIdentifier($actionName = NULL) {
		if ($this->templateSource === NULL) {
			$templatePathAndFilename = $this->getTemplatePathAndFilename();
			$templatePathAndFilenameInfo = pathinfo($templatePathAndFilename);
			$templateFilenameWithoutExtension = basename($templatePathAndFilename, '.' . $templatePathAndFilenameInfo['extension']);
			$prefix = sprintf('template_file_%s', $templateFilenameWithoutExtension);
			return $this->createIdentifierForFile($templatePathAndFilename, $prefix);
		} else {
			$templateSource = $this->getTemplateSource();
			$prefix = 'template_source';
			$templateIdentifier = sprintf('Standalone_%s_%s', $prefix, sha1($templateSource));
			return $templateIdentifier;
		}
	}

	/**
  * Returns the Fluid template source code
  *
  * @param string $actionName Name of the action. This argument is not used in this view!
  * @return string Fluid template source
  * @throws InvalidTemplateResourceException
  */
 protected function getTemplateSource($actionName = NULL) {
		if ($this->templateSource === NULL && $this->templatePathAndFilename === NULL) {
			throw new InvalidTemplateResourceException('No template has been specified. Use either setTemplateSource() or setTemplatePathAndFilename().', 1288085266);
		}
		if ($this->templateSource === NULL) {
			if (!file_exists($this->templatePathAndFilename)) {
				throw new InvalidTemplateResourceException('Template could not be found at "' . $this->templatePathAndFilename . '".', 1288087061);
			}
			$this->templateSource = file_get_contents($this->templatePathAndFilename);
		}
		return $this->templateSource;
	}

	/**
	 * Returns a unique identifier for the resolved layout file.
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $layoutName The name of the layout
	 * @return string layout identifier
	 */
	protected function getLayoutIdentifier($layoutName = 'Default') {
		$layoutPathAndFilename = $this->getLayoutPathAndFilename($layoutName);
		$prefix = 'layout_' . $layoutName;
		return $this->createIdentifierForFile($layoutPathAndFilename, $prefix);
	}

	/**
  * Resolves the path and file name of the layout file, based on
  * $this->getLayoutRootPath() and request format and returns the file contents
  *
  * @param string $layoutName Name of the layout to use. If none given, use "Default"
  * @return string contents of the layout file if it was found
  * @throws InvalidTemplateResourceException
  */
 protected function getLayoutSource($layoutName = 'Default') {
		$layoutPathAndFilename = $this->getLayoutPathAndFilename($layoutName);
		$layoutSource = file_get_contents($layoutPathAndFilename);
		if ($layoutSource === FALSE) {
			throw new InvalidTemplateResourceException('"' . $layoutPathAndFilename . '" is not a valid template resource URI.', 1312215888);
		}
		return $layoutSource;
	}

	/**
  * Resolve the path and file name of the layout file, based on
  * $this->getLayoutRootPath() and request format
  *
  * In case a layout has already been set with setLayoutPathAndFilename(),
  * this method returns that path, otherwise a path and filename will be
  * resolved using the layoutPathAndFilenamePattern.
  *
  * @param string $layoutName Name of the layout to use. If none given, use "Default"
  * @return string Path and filename of layout files
  * @throws InvalidTemplateResourceException
  */
 protected function getLayoutPathAndFilename($layoutName = 'Default') {
		$layoutRootPath = $this->getLayoutRootPath();


		if (!is_dir($layoutRootPath)) {
			throw new InvalidTemplateResourceException('Layout root path "' . $layoutRootPath . '" does not exist.', 1288092521);
		}
		$possibleLayoutPaths = array();
		$possibleLayoutPaths[] = GeneralUtility::fixWindowsFilePath($layoutRootPath . '/' . $layoutName . '.' . $this->getRequest()->getFormat());
		$possibleLayoutPaths[] = GeneralUtility::fixWindowsFilePath($layoutRootPath . '/' . $layoutName);
		foreach($possibleLayoutPaths as $layoutPathAndFilename) {
			if (file_exists($layoutPathAndFilename)) {
				return $layoutPathAndFilename;
			}
		}
		throw new InvalidTemplateResourceException('Could not load layout file. Tried following paths: "' . implode('", "', $possibleLayoutPaths) . '".', 1288092555);
	}

	/**
	 * Returns a unique identifier for the resolved partial file.
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $partialName The name of the partial
	 * @return string partial identifier
	 */
	protected function getPartialIdentifier($partialName) {
		$partialPathAndFilename = $this->getPartialPathAndFilename($partialName);
		$prefix = 'partial_' . $partialName;
		return $this->createIdentifierForFile($partialPathAndFilename, $prefix);
	}

	/**
  * Resolves the path and file name of the partial file, based on
  * $this->getPartialRootPath() and request format and returns the file contents
  *
  * @param string $partialName The name of the partial
  * @return string contents of the layout file if it was found
  * @throws InvalidTemplateResourceException
  */
 protected function getPartialSource($partialName) {
		$partialPathAndFilename = $this->getPartialPathAndFilename($partialName);
		$partialSource = file_get_contents($partialPathAndFilename);
		if ($partialSource === FALSE) {
			throw new InvalidTemplateResourceException('"' . $partialPathAndFilename . '" is not a valid template resource URI.', 1257246929);
		}
		return $partialSource;
	}

	/**
  * Resolve the partial path and filename based on $this->getPartialRootPath() and request format
  *
  * @param string $partialName The name of the partial
  * @return string the full path which should be used. The path definitely exists.
  * @throws InvalidTemplateResourceException
  */
 protected function getPartialPathAndFilename($partialName) {
		$partialRootPath = $this->getPartialRootPath();
		if (!is_dir($partialRootPath)) {
			throw new InvalidTemplateResourceException('Partial root path "' . $partialRootPath . '" does not exist.', 1288094648);
		}
		$possiblePartialPaths = array();
		$possiblePartialPaths[] = GeneralUtility::fixWindowsFilePath($partialRootPath . '/' . $partialName . '.' . $this->getRequest()->getFormat());
		$possiblePartialPaths[] = GeneralUtility::fixWindowsFilePath($partialRootPath . '/' . $partialName);
		foreach($possiblePartialPaths as $partialPathAndFilename) {
			if (file_exists($partialPathAndFilename)) {
				return $partialPathAndFilename;
			}
		}
		throw new InvalidTemplateResourceException('Could not load partial file. Tried following paths: "' . implode('", "', $possiblePartialPaths) . '".', 1288092555);
	}

	/**
	 * Returns a unique identifier for the given file in the format
	 * Standalone_<prefix>_<SHA1>
	 * The SH1 hash is a checksum that is based on the file path and last modification date
	 *
	 * @param string $pathAndFilename
	 * @param string $prefix
	 * @return string
	 */
	protected function createIdentifierForFile($pathAndFilename, $prefix) {
		$templateModifiedTimestamp = filemtime($pathAndFilename);
		$templateIdentifier = sprintf('Standalone_%s_%s', $prefix, sha1($pathAndFilename . '|' . $templateModifiedTimestamp));
		$templateIdentifier = str_replace('/', '_', str_replace('.', '_', $templateIdentifier));
		return $templateIdentifier;
	}

}
?>