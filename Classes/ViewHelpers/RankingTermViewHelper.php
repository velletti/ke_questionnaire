<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Kennziffer\KeQuestionnaire\Domain\Repository\AnswerRepository;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;
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
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class RankingTermViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @var ObjectManager
     */
    private $objectManager;


    /** * Constructor *
     * @api */
    public function initializeArguments(): void {
        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\Answer', ' The Answer ', true );
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', 'the question object  ', false );
        $this->registerArgument('as', 'string', 'the string the name of the iteration variable  ', false );
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', ' The result ', false , Null );

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
     $result = $this->arguments['result'];
     /** @var Result $result */
     $result = $this->arguments['result'] ;
     /** @var Question $question */
     $question = $this->arguments['question'] ;
     /** @var Answer $answer */
     $answer = $this->arguments['answer'] ;
     $as = $this->arguments['as'] ;
     $terms = $this->getTerms($question, $answer, $result);
     $templateVariableContainer = $this->renderingContext->getVariableProvider();
     if ($question === NULL) {
   			return '';
   		}
     //shuffle($images);
     $output =  '' ;
     foreach ($terms as $nr => $element){
   			$templateVariableContainer->add($as, $element);
   			$output .= $this->renderChildren();
   			$templateVariableContainer->remove($as);
   		}
     return $output;
 }
	
	/**
	 * Gets the Images
	 * 
	 * @param Question $question the terms are in
     * @param Result $result
	 * @return array
	 */
	public function getTerms($question, $header, $result){
		$terms = array();
		
		// workaround for pointer in question, so all following answer-objects are rendered.
		$addIt = false;
        $type = '';
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/** @var AnswerRepository $rep */
  $rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AnswerRepository');
		$answers = $rep->findByQuestion($question);
		
		$ranswers = array();
        if ($result){
            /** @var ObjectStorage<ResultQuestion> $rquestion */
            foreach ($result->getQuestions() as $rquestion){

                if ($rquestion->getQuestion()->getUid() == $question->getUid()){
                    foreach ($rquestion->getAnswers() as $ranswer){
                        $ranswers[$ranswer->getAnswer()->getUid()] = $ranswer->getValue();
                    }
                }
            }
        }
        ksort($ranswers);
        
        $counter = 0;
		foreach ($answers as $answer){
			//Add only after the correct Matrix-Header is found, only following rows will be added.
			if ((
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder'
					) AND $answer === $header) {
                        $addIt = true; 
                        $type = get_class($answer);                        
            } elseif (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder') $addIt = false;
			if ($addIt){
				if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm'){
                    $counter ++;
                    if ($answer->getOrder() == 0) $answer->setOrder($counter);
					if ($type == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder') {
                        if ($ranswers[$answer->getUid()]){
                            $answer->setOrder($ranswers[$answer->getUid()]);
                        }
                        $terms[$answer->getOrder()] = $answer;
                    } else {
                        if ($answer->getOrder() == 0) $answer->setOrder($counter);
                        $terms[] = $answer;
                    }
				}
			}
        }
        $selectItems = array();
        for ($i = 0; $i < $counter; $i++){
            $selectItems[$i+1] = $i+1;
        }
        foreach ($terms as $nr => $term){
            $terms[$nr]->setSelectItems($selectItems);
        }
        ksort($terms);
		
		return $terms;
	}
}
?>