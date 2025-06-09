<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Result;

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
 * check the dependancy type and create the javascript
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CheckDependanciesViewHelper extends AbstractViewHelper {


    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @var JavaScriptViewHelper
     */
    public $jsViewhelper ;

    /** * Constructor *
     * @api */
    public function initializeArguments(): void {
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\Question', ' The question ', true );
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', 'the Result object  ', false );
        parent::initializeArguments() ;
    }

	/**
     * @return string
	 */	 	
	public function render() {
        /** @var Result $result */
        $result = $this->arguments['result'] ;
        /** @var Question $question */
        $question = $this->arguments['question'] ;

		/*var $output string*/
		$output = $this->renderChildren();        
        
        if ($question->isDependant()){
			if ($question->fullfillsDependancies($result)) $dependant = '<div id="depKeq'.$question->getUid().'">';
			else $dependant = '<div id="depKeq'.$question->getUid().'" style="display: none;">';
            $dependant .= $output;
            $dependant .= '</div>';
            
            $depJs = array();
			$if = '    if (';
			foreach ($question->getDependancies() as $id => $dependancy){
				$depJs[$id] = 'jQuery("#keq'.$dependancy->getQuestion()->getUid().'").on( "change", function() {'."\n";				
            	$if .= $dependancy->getRelationJs(count($depJs));             
            }
			
			$js_inside = '){'."\n";
			$js_inside .= '        jQuery("#depKeq'.$question->getUid().'").show();'."\n";
			$js_inside .= '    } else {'."\n";
			$js_inside .= '        jQuery("#depKeq'.$question->getUid().'").hide();'."\n";
			//$js_inside .= '        jQuery("input:not([type=hidden])", "#depKeq'.$question->getUid().'").val("");'."\n";
			//$js_inside .= '        jQuery("input:radio", "#depKeq'.$question->getUid().'").prop("checked",false);'."\n";
			//$js_inside .= '        jQuery("input:checkbox", "#depKeq'.$question->getUid().'").prop("checked",false);'."\n";
			$js_inside .= '    };'."\n";
			$js_inside .= '});'."\n";
            $js = '' ;
			foreach ($depJs as $part){
				$js .= $part;
				$js .= $if;
				$js .= $js_inside;
			}
			$this->jsViewhelper->cacheJavaScript($js);
			
            $output = $dependant;
        }
        
        return $output;
	}
	
}
?>