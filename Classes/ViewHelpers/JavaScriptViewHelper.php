<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Core\Environment;

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
 * create the temporary js file with given code
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class JavaScriptViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;


	/**
	 * @var bool
	 */
	protected $always;

    /** * Constructor *
     * @api */
    public function initializeArguments() {
        $this->registerArgument('alwaysreplace', 'mixed', 'if alwaysreplace do something', false , FALSE );
        parent::initializeArguments() ;
    }

	/**
	 * ViewHelper to bundle the javascript in a single file and include this
	 * 
	 */
	public function render() {
        $this->always = $this->arguments['alwaysreplace'] ;
		$this->cacheJavaScript($this->renderChildren());
	}
	
	/**
	 * write the Javascript in the file
	 */
	public function cacheJavaScript ($script){
		if (trim($script) != ''){
			$endOfFile = "\n});// end of file";
			$beginningOfFile = "jQuery(document).ready(function() {\n";
			
			//get the stored jsKey
			$jsKey = date("d-m-y-H-i-s-") . $GLOBALS['TSFE']->fe_user->getKey('ses', 'keq_jskey');
			//get the file
			$pathName = 'typo3temp/ke_questionnaire';
			$fileName = $pathName . '/' . $jsKey . '.js';

			if (!file_exists(Environment::getPublicPath() . '/' . $pathName)) {
                mkdir(Environment::getPublicPath() . '/' . $pathName, 0777);
                chmod(Environment::getPublicPath() . '/' . $pathName, 0777);
            }
			//get old file content
			$oldContent = '';

			if(file_exists(Environment::getPublicPath() . '/' . $fileName)) {
				$oldContent = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
			}

			//check old file content for alwaysreplace_start and _end parameter
			if ($this->always) {
				$pattern = '/\/\/' . $this->always . '_start(.*)\/\/' . $this->always . '_end/isU';
				$oldContent = preg_replace($pattern, '', $oldContent);
			}

			if ($oldContent == '') {
				$content = $beginningOfFile . $script . $endOfFile;
			} else {
				if (strpos($oldContent, $script) === FALSE) {
					$content = str_replace($endOfFile, $script . $endOfFile, $oldContent);
				} else $content = $oldContent;
			}
			//clear the file
            $jsFile = fopen(Environment::getPublicPath() . '/' . $fileName, 'w+b');

            //write the js
            fwrite($jsFile, $content);
			fclose($jsFile);
            chmod(Environment::getPublicPath() . '/' . $fileName, 0777);

			//add it to the headerData
			$GLOBALS['TSFE']->additionalFooterData['ke_questionnaire_tempjs'] = '<script type="text/javascript" src="' .
				$fileName . "?" . filemtime(Environment::getPublicPath() . '/' . $fileName) . '"></script>';
		}
	}
}
?>