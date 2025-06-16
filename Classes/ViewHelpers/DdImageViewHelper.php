<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
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
 * loads the images for the drag-and-drop images
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class DdImageViewHelper extends AbstractViewHelper {

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
    public function initializeArguments(): void {

        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\Answer', ' The answerType ', true );
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', 'the question object  ', false );
        $this->registerArgument('as', 'string', 'the string the name of the iteration variable  ', false );

        parent::initializeArguments() ;
    }

	/**
	 * Adds the needed Javascript-File to Additional Header Data
	 *
	 * @return string
	 */
	public function render()
 {
     $answer = $this->arguments['answer'];
     $question = $this->arguments['question'];
     $as = $this->arguments['as'];
     /** @var Question $question */
     $question = $this->arguments['question'] ;
     /** @var Answer $answer */
     $answer = $this->arguments['answer'] ;
     $as = $this->arguments['as'] ;
     $images = $this->getImages($question, $answer);
     $templateVariableContainer = $this->renderingContext->getVariableProvider();
     if ($question === NULL) {
   			return '';
   		}
     shuffle($images);
     $output =  '' ;
     foreach ($images as $nr => $element){
			$temp = array();
			$temp['counter'] = $nr+1;
			$temp['image'] = $element;
			$templateVariableContainer->add($as, $temp);
			$output .= $this->renderChildren();
			$templateVariableContainer->remove($as);
		}
     return $output;
 }
	
	/**
  * Gets the Images
  *
  * @param Question $question the terms are in
  * @return array
  */
 public function getImages($question, $header){
		$terms = array();
		
		// workaround for pointer in question, so all following answer-objects are rendered.
		$addIt = false;
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AnswerRepository');
		$answers = $rep->findByQuestion($question);
		//$answers = $question->getAnswers();
        
		foreach ($answers as $answer){
			//Add only after the correct Matrix-Header is found, only following rows will be added.
			if ((
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale'
					) AND $answer === $header) $addIt = true;
			elseif (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale') $addIt = false;
			if ($addIt){
				if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage'){
					$terms[] = $answer;
				}
			}
		}
		
		return $terms;
	}
}
?>