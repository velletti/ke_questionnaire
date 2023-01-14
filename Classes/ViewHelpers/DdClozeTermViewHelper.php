<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;

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
 * creates the cloze terms for the drag-and-drop display
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class DdClozeTermViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {


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
        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD', ' The answerType ', true );
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', 'the question object  ', false );
        parent::initializeArguments() ;
    }


    /**
	 * Adds the needed Javascript-File to Additional Header Data
	 *
	 * @param ClozeTextDD $answer Answer to be rendered
	 * @param Question $question the terms are in
	 * @param string $as The name of the iteration variable
	 * @return string
	 */
	public function render(ClozeTextDD $answer,
                           Question $question,
                           $as) {

        /** @var Question $question */
        $question = $this->arguments['question'] ;

        /** @var ClozeTextDD $answer */
        $answer = $this->arguments['answer'] ;
        $as = $this->arguments['as'] ;

		$terms = $this->getClozeTerms($question);
		$additional_terms = explode(',',$answer->getClozeAddTerms());
		
		$templateVariableContainer = $this->renderingContext->getVariableProvider();
		if ($question === NULL) {
			return '';
		}
		
		$output = '';
		$term_array = array();
		foreach ($terms as $word => $element) {
			$term_array[] = $word;
		}
		foreach ($additional_terms as $element){
			if (trim($element) != '')  $term_array[] = trim($element);
		}
		shuffle($term_array);
		foreach ($term_array as $nr => $element){
			$temp = array();
			$temp['counter'] = $nr;
			$temp['text'] = $element;
			$templateVariableContainer->add($as, $temp);
			$output .= $this->renderChildren();
			$templateVariableContainer->remove($as);
		}
		return $output;
	}
	
	/**
	 * Gets the Terms to be be replaced
	 * 
	 * @param Question $question the terms are in
	 * @return array
	 */
	public function getClozeTerms($question){
		$terms = array();
		
		foreach ($question->getAnswers() as $answer){
			if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm'){
				$terms[$answer->getTitle()][$answer->getClozePosition()] = $answer;
			}
		}
		
		return $terms;
	}
}
?>