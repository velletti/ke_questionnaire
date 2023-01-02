<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;
use \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText;

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
 * check the text and create the cloze-text display
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ClozeTextViewHelper extends AbstractViewHelper {

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
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', ' The question ', true );
        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText', 'the answer object  ', false );
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
     /** @var ClozeText $answer */
     $answer = $this->arguments['answer'] ;
     /** @var Question $question */
     $question = $this->arguments['question'] ;
     $as = $this->arguments['as'] ;
     $templateVariableContainer = $this->renderingContext->getVariableProvider();
     if ($question === NULL OR ($answer->getShortType() != 'ClozeText' AND $answer->getShortType() != 'ClozeTextDD')) {
			return '';
		}
     //$textArray = $this->getClozeTextArray($answer->getText(), $question);
     $wordPositions = $answer->getWordPositions($question);
     $text = $answer->getText();
     $content = '';
     $start = 0;
     if (is_array($wordPositions)){
   			foreach ($wordPositions as $id => $wordPosition) {
   				if ($id){
   					$content .= mb_substr($text, $start, ($wordPosition[0] - $start));
   					$templateVariableContainer->add($as, $wordPosition['answer']);
   					$content .= $this->renderChildren();
   					$templateVariableContainer->remove($as);
   					$start = $wordPosition[0] + $wordPosition[1];
   				}
   			}
   		}
     $content .= mb_substr($text, $start);
     return $content;
 }	
}
?>