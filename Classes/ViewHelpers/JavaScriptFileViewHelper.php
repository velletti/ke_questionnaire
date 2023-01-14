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
 * add a javascript file to footer or header data
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class JavaScriptFileViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;


    /** * Constructor *
     * @api */
    public function initializeArguments() {
        $this->registerArgument('key', 'string', ' The key ', false , '' );
        $this->registerArgument('filename', 'string', ' The $filename ', true  );
        $this->registerArgument('footer', 'boolean', 'Put file to footer', false , true );
        parent::initializeArguments() ;
    }

	/**
	 * ViewHelper to bundle the javascript in a single file and include this
	 * 
	 */
	public function render() {
        $key = $this->arguments['key'] ;
        $filename = $this->arguments['filename'] ;
        $footer = $this->arguments['footer'] ;

		if ($footer) $GLOBALS['TSFE']->additionalFooterData['ke_questionnaire_'.$key] = '<script type="text/javascript" src="'.$filename."?".filemtime(Environment::getPublicPath() . '/' .$filename).'"></script>';
		else  $GLOBALS['TSFE']->additionalHeaderData['ke_questionnaire_'.$key] = '<script type="text/javascript" src="'.$filename."?".filemtime(Environment::getPublicPath() . '/' .$filename).'"></script>';
	}	
}
?>