<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump;
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
 * check the condition and create the javascript for conditional jumps
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ConditionalJumpViewHelper  extends AbstractViewHelper {


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
    public function initializeArguments() {
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump', ' The question ', true );
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', 'the Result object  ', false );
        parent::initializeArguments() ;
    }

	/**
     * @return string
	 */	 	
	public function render() {
        /** @var Result $result */
        $result = $this->arguments['result'] ;
        /** @var ConditionalJump $question */
        $question = $this->arguments['question'] ;

		$output = '';
        
        if ($question->isDependant()){
            $depJs = array();
			$if = '    if (';
			foreach ($question->getDependancies() as $id => $dependancy){
				$depJs[$id] = 'jQuery("#keq'.$dependancy->getQuestion()->getUid().'").on( "change", function() {'."\n";				
            	$if .= $dependancy->getRelationJs(count($depJs));             
            }
			
			$inside_js = '){'."\n";
			$inside_js .= '        jQuery(".requestedPage").val('.$question->getToPage().');'."\n";
			if ($question->isDirectJump()){
				$inside_js .= '			submitToPage('.$question->getToPage().');'."\n";
			}
			$inside_js .= '    } else {'."\n";
			$inside_js .= '        jQuery(".requestedPage").val(jQuery(".'.$question->getUid().'_originalRequestedPage").val());'."\n";
			$inside_js .= '    };'."\n";
			$inside_js .= '});'."\n";
			
			$js = '';
			foreach ($depJs as $part){
				$js .= $part;
				$js .= $if;
				$js .= $inside_js;
			}						
            
			if (!$question->isOnlyJs()){
                $this->jsViewhelper->cacheJavaScript($js);
			}
            if ($question->getJavascript() != ''){
                //$output = $this->renderChildren();
				//changed javascript text to file resource
				//add it to the footerData
				$GLOBALS['TSFE']->additionalFooterData['ke_questionnaire_jsq'.$question->getUid()] .= '<script type="text/javascript" src="'.$this->renderChildren().'"></script>'; 
            }
        }
        
        return $output;
	}

}
?>