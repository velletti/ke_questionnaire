<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;

use Mpdf\Tag\A;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group;
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
class Result extends AbstractEntity {

	/**
	 * FeCruserId
	 *
	 * @var integer
	 */
	protected $feCruserId;
	
	/**
	 * Crdate
	 *
	 * @var integer
	 */
	protected $crdate;

	/**
	 * Finished
	 *
	 * @var integer
	 */
	protected $finished;

	/**
  * Questions
  *
  * @var ObjectStorage<ResultQuestion>
  */
 #[Cascade(['value' => 'remove'])]
 protected $questions;

	/**
	 * Points
	 *
	 * @var integer
	 */
	protected $points;

	/**
	 * MaxPoints
	 *
	 * @var integer
	 */
	protected $maxPoints;
	
	/**
  * FeUser
  * @var int|null
  */
 protected $feUser;

	/**
  * AuthCode
  * @var AuthCode|null
  */
 protected $authCode;
	
	/**
	 * selectLabel
	 *
	 * @var string
	 */
	protected $selectLabel;
        
        /**
	 * addParameter
	 *
	 * @var string
	 */
	protected $addParameter;



	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
	    $this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->questions = new ObjectStorage();
	}

	/**
	 * Returns the feCruserId
	 *
	 * @return integer $feCruserId
	 */
	public function getFeCruserId() {
		return $this->feCruserId;
	}

	/**
	 * Sets the feCruserId
	 *
	 * @param integer $feCruserId
	 * @return void
	 */
	public function setFeCruserId($feCruserId): void {
		$this->feCruserId = $feCruserId;
	}
	
	/**
	 * Returns the crdate
	 *
	 * @return integer $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the crdate
	 *
	 * @param integer $crdate
	 * @return void
	 */
	public function setCrdate($crdate): void {
		$this->crdate = $crdate;
	}

	/**
	 * Returns the finished
	 *
	 * @return integer $finished
	 */
	public function getFinished() {
		return $this->finished;
	}

	/**
	 * Sets the finished
	 *
	 * @param integer $finished
	 * @return void
	 */
	public function setFinished($finished): void {
		$this->finished = $finished;
	}

	/**
  * Adds a ResultQuestion
  *
  * @param ResultQuestion $rquestion
  * @return void
  */
 public function addOrUpdateQuestion(ResultQuestion $rquestion): void {
		//check if a resultQuestion with this Question is already here
		//if no question is given		

		if (!$this->questionKnown($rquestion)){
			$this->addQuestion($rquestion);
		} else {
			$oldrquestion = $this->getResultQuestionForQuestion($rquestion->getQuestion());
			$oldrquestion->setAnswers($rquestion->getAnswers());
		}
	}	

	/**
  *
  * @param Answer $answer
  *
  * @return Answer $newAnswer
  */
 private function duplicateAnswer($answer){
		$availableProperties = ObjectAccess::getGettablePropertyNames($answer);
		$newAnswer = new ResultAnswer();
		foreach ($availableProperties as $propertyName) {
			if (ObjectAccess::isPropertySettable($newAnswer, $propertyName) && !in_array($propertyName, array('uid','pid','resultquestion'))) {
				$propertyValue = ObjectAccess::getProperty($answer, $propertyName);
				ObjectAccess::setProperty($newAnswer, $propertyName, $propertyValue);
			}
		}
		
		return $newAnswer;
	}
	
	/**
  * Adds a ResultQuestion
  *
  * @param ResultQuestion $resultQuestion
  * @return void
  */
 public function addQuestion(ResultQuestion $resultQuestion): void {
		$this->questions->attach($resultQuestion);
	}

	/**
  * Removes a ResultQuestion
  *
  * @param ResultQuestion $questionToRemove The ResultQuestion to be removed
  * @return void
  */
 public function removeQuestion(ResultQuestion $questionToRemove): void {
		$this->questions->detach($questionToRemove);
	}

	/**
  * Returns the questions
  *
  * @return ObjectStorage<ResultQuestion> $questions
  */
 public function getQuestions() {
		return $this->questions;
	}
    /** returns array of questions for specific page in questionaiere
     * @return array
     */
    public function getQuestionsForPage($page) {
	    $questions=[] ;
	    /** @var ResultQuestion $question */
        foreach ( $this->questions as $question) {
	        if( $question->getPage() == $page ) {
                $questions[] = $question->getQuestion() ;
            }
        }
        return $questions ;
    }


    /**
     * Returns the questions
     *
     * @param int $question
     * @return ResultQuestion
     */
    public function getResultQuestionByQuestionUid( $question) {
        $rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
        $rQuestion = $rep->findByQuestionIdAndResultIdRaw($question,$this->getUid() , false);
        if ($rQuestion ) {
            return $rQuestion->getFirst();
        }
        return null;
    }
	
	/**
  * Returns the questions
  *
  * @param Question $question
  * @return ResultQuestion
  */
 public function getResultQuestionForQuestion(Question $question) {
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$rQuestion = $rep->findByQuestionAndResult($question,$this);
		if ($rQuestion[0] AND $this->getQuestions()->contains($rQuestion[0])) {
			return $rQuestion[0];
		} else {
			return false;
		}
	}

	/**
  * Try to find a resultQuestion with help of the question UID
  *
  * @param ResultQuestion $question The question UID. NOT the UID of the resultQuestion
  * @return ResultQuestion
  */
 public function questionKnown($question) {
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$rQuestion = $rep->findByQuestionAndResult($question->getQuestion(),$this);
		if ($rQuestion[0] AND $this->getQuestions()->contains($rQuestion[0])) {
			$rq = $rQuestion[0];
           //  $rq->checkAnswers($question->getAnswers());
			return $rq;
		}
		else {
            return FALSE;
        }
	}
	
	/**
  * Try to find a resultAnswer with help of the answer UID
  *
  * @param integer $questionUid The question UID. NOT the UID of the resultQuestion
  * @param integer $answerUid The answer UID. NOT the UID of the resultAnswer
  * @param integer $columnUid  The answer UID of the row. NOT the UID of the resultAnswer
  * @return ResultAnswer
  */
 public function getAnswer($questionUid, $answerUid, $columnUid = 0) {
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$resultQuestion = $rep->findByQuestionAndResult($questionUid, $this);
		$resultQuestion = $resultQuestion[0];
		
		if ($resultQuestion){
			foreach ($resultQuestion->getAnswers() as $resultAnswer) {
				$answer = $resultAnswer->getAnswer();
				if ($answer){
					if ($columnUid == 0 AND $resultAnswer->getAnswer()->getShortType() == "MatrixRow") {
						if ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == intval($resultAnswer->getCol()) AND $resultAnswer->getValue() != $resultAnswer->getAnswer()->getUid()) {
							return $resultAnswer;
						} 
					} elseif ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == intval($resultAnswer->getCol())) {
						return $resultAnswer;
					} 
				}
			}
		}
		return NULL;
	}	
	
	/**
  * Try to find a resultAnswer with help of the answer UID
  *
  * @param integer $questionUid The question UID. NOT the UID of the resultQuestion
  * @param integer $answerUid The answer UID. NOT the UID of the resultAnswer
  * @param integer $columnUid  The answer UID of the row. NOT the UID of the resultAnswer
  * @return ResultAnswer
  */
 public function getRadioAnswer($questionUid, $answerUid, $columnUid) {
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		$resultQuestion = $rep->findByQuestionAndResult($questionUid, $this);
		$resultQuestion = $resultQuestion[0];
		
		if ($resultQuestion){
			foreach ($resultQuestion->getAnswers() as $resultAnswer) {
				$answer = $resultAnswer->getAnswer();
				if ($answer){
					if ($answerUid == $resultAnswer->getAnswer()->getUid() AND $columnUid == $resultAnswer->getValue()) {
						return $resultAnswer;
					} 
				}
			}
		}
		return NULL;
	}	
	
	/**
  * Try to find a resultAnswer with help of the answer UID
  *
  * @param integer $answerUid The resultAnswer UID
  * @return ResultAnswer
  */
 public function getResultAnswer($answerUid) {
		$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultAnswerRepository');
		return $rep->findByUid($answerUid);
	}

	/**
  * Sets the questions
  *
  * @param ObjectStorage<ResultQuestion> $questions
  * @return void
  */
 public function setQuestions(ObjectStorage $questions): void {
		$this->questions = $questions;
	}

	/**
	 * Returns the points
	 *
	 * @return integer $points
	 */
	public function getPoints() {
		return $this->points;
	}

	/**
	 * Sets the points
	 *
	 * @param integer $points
	 * @return void
	 */
	public function setPoints(int $points): void {
		$this->points = $points;
	}

	/**
	 * Returns the maxPoints
	 *
	 * @return integer $maxPoints
	 */
	public function getMaxPoints() {
		return $this->maxPoints;
	}

	/**
	 * Sets the maxPoints
	 *
	 * @param integer $maxPoints
	 * @return void
	 */
	public function setMaxPoints(int $maxPoints): void {
		$this->maxPoints = $maxPoints;
	}
	
	/**
  * Setter for feUser
  *
  * @return void
  */
 public function setFeUser( $feUser): void {
     if ( is_array($feUser)) {
         $this->feUser = ($feUser['uid'] ?? 0) ;
     } else {
         $this->feUser = 0;
     }

	}

	/**
  * Getter for feUser
  *
  * @return ?int feUser
  */
 public function getFeUser() {
		return ($this->feUser ?? 0 ) ;
	}
	
	/**
  * Setter for authCode
  *
  * @param AuthCode|null $authCode authCode
  * @return void
  */
 public function setAuthCode(?AuthCode $authCode): void {
		$this->authCode = $authCode;
	}

	/**
  * Getter for authCode
  *
  * @return ?AuthCode authCode
  */
 public function getAuthCode() : ?AuthCode
 {
		return $this->authCode;
	}
	
	/**
	 * Returns the selectLabel
	 *
	 * @return string $selectLabel
	 */
	public function getSelectLabel() {
		$label = $this->getUid();
		if ($this->getFinished() > 0){
			$date = date('d.m.Y',$this->getFinished());
			$label .= ' ('.$date.')';
		}
		
		return $label;		
	}
	
	/**
	 * Returns the calculated Average
	 *
	 * @param boolean $all
	 * @return integer $average
	 */
	public function getAverage($all = false) {
		$qCount = 0;
		foreach ($this->getQuestions() as $rQuestion){
			if ($rQuestion->getMaxPoints() > 0){
				if ($all){
					$qCount ++;
				} else {
					if (count($rQuestion->getAnswers()) > 0) $qCount ++;
				}
			}
		}		
                if ($qCount > 0) $average = $this->getPoints() / $qCount;
		$average = number_format($average,2,',',' ');
		return $average;
	}

        
        /**
	 * Returns the addParameter
	 *
	 * @return string $addParameter
	 */
	public function getAddParameter() {
		return $this->addParameter;
	}

	/**
	 * Sets the addParameter
	 *
	 * @param string $addParameter
	 * @return void
	 */
	public function setAddParameter($addParameter): void {
		$this->addParameter = $addParameter;
	}
}
?>