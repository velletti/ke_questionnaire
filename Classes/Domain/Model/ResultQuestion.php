<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
class ResultQuestion extends AbstractEntity {

	/**
	 * FeCruserId
	 *
	 * @var integer
	 */
	protected $feCruserId;

    /**
     * number of page if question list  has pagePbreaks . if Questions are Shuffled, need this to generate same questionnaire from stored temp result
     *
     * @var integer
     */
    protected $page;

	/**
  * Answers
  *
  * @var ObjectStorage<ResultAnswer>
  */
 #[Cascade(['value' => 'remove'])]
 protected $answers;

	/**
  * question
  *
  * @var Question
  */
 protected $question;

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
     * Min answers
     *
     * @var int
     */
    protected $minAnswers = 0 ;

    /**
     * Max answers
     *
     * @var int
     */
    protected $maxAnswers = 0 ;
    /**
     * Max answers
     *
     * @var int
     */
    protected $result = 0 ;

	/**
	 * Default constructor.
	 */
	public function __construct() {
		// Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->answers = new ObjectStorage();
	}


    /**
     * @return bool
     */
    public function isMinAnswers()
    {
        return $this->minAnswers;
    }

    /**
     * @param bool $minAnswers
     */
    public function setMinAnswers($minAnswers): void
    {
        $this->minAnswers = $minAnswers;
    }

    /**
     * @return bool
     */
    public function isMaxAnswers()
    {
        return $this->maxAnswers;
    }

    /**
     * @param bool $maxAnswers
     */
    public function setMaxAnswers($maxAnswers): void
    {
        $this->maxAnswers = $maxAnswers;
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
  * Checks the ResultAnswers if existent 
  *
  * @param ObjectStorage<ResultAnswer> $answers
  * @return void
  */
 public function checkAnswers(ObjectStorage $answers): void {
		foreach ($this->getAnswers() as $answer){
			$this->checkAnswer($answers, $answer);
		}
		
		if (count($answers) > count($this->getAnswers())){
			foreach ($answers as $checkAnswer){
				if ($checkAnswer->getCol() > 0){
					$found = false;
					foreach ($this->getAnswers() as $answer){
						if ($answer->getAnswer() === $checkAnswer->getAnswer() 
								AND $answer->getCol() == $checkAnswer->getCol()) $found = true;
					}
					if (!$found){
						$this->addAnswer($checkAnswer);
					}
				}
			}
		}
	}
	
	/**
  * Check the Answer in the saving-Process
  * the saveType dertermines if the answers is replaced or the value is replaced. Matrix Answers are worked differently
  *
  * @param ObjectStorage $answers
  * @param ResultAnswer $resultAnswer
  */
 public function checkAnswer($answers, &$resultAnswer): void{
		if ($resultAnswer->getAnswer()){
            foreach ($answers as $ranswer){
                if ($ranswer->getAnswer() === $resultAnswer->getAnswer()){
                    $resultAnswer->setValue($ranswer->getValue());
                    $resultAnswer->setAdditionalValue($ranswer->getAdditionalValue());
                }
            }
		}
	}
	
	/**
  * Adds a ResultAnswer
  *
  * @param ResultAnswer $answer
  * @return void
  */
 public function addAnswer(ResultAnswer $answer): void {
		$this->answers->attach($answer);
	}

	/**
  * Removes a ResultAnswer
  *
  * @param ResultAnswer $answerToRemove The ResultAnswer to be removed
  * @return void
  */
 public function removeAnswer(ResultAnswer $answerToRemove): void {
	    try {
            $this->answers->detach($answerToRemove);
        } catch(\Exception $e){
	        // ignore it detach throws an error !!
        }
	}

	/**
  * Returns the answers
  *
  * @return ObjectStorage<ResultAnswer> $answers
  */
 public function getAnswers() {
		return $this->answers;
	}
	
	/**
	 * clears the answers in the resultAnswer.
	 * When an answers is withdrawn => checkBox unchecked
	 *
	 * @return void
	 */
	public function clearAnswers(): void {
		foreach ($this->getAnswers() as $rAnswer){
			$rAnswer->setValue('');
			$rAnswer->setAdditionalValue('');
			$rAnswer->setCol('');
			$rAnswer->setClone(0);
			$rAnswer->setCloneTitle('');
		}
		$persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
	}

    /**
     * removes the answers from the resultAnswer.
     * needed when someone reloads 2.cond or thirdpage ..
     *
     * @return void
     */
    public function removeAnswers(): void {
        if($this->getAnswers()) {
            foreach ($this->getAnswers()->toArray() as $rAnswer){

                $this->removeAnswer($rAnswer);
            }
        }

    }
    
    /**
	 * Returns the cloned answers
	 *
	 * @return array $answers
	 */
	public function getClonedAnswers() {
		$cloned = array();
        foreach ($this->answers as $answer){
            if ($answer->getClone() > 0) {
                $cloned[$answer->getClone()]['title'] = $answer->getCloneTitle();
                $cloned[$answer->getClone()][$answer->getCol()] = $answer;
            }
        }
        return $cloned;
	}

	/**
  * Sets the answers
  *
  * @param ObjectStorage<ResultAnswer> $answers
  * @return void
  */
 public function setAnswers(ObjectStorage $answers): void {
		$this->answers = $answers;
	}

	/**
  * Returns the question
  *
  * @return Question $question
  */
 public function getQuestion() {
		return $this->question;
	}

	/**
  * Sets the question
  *
  * @param Question $question
  * @return void
  */
 public function setQuestion(Question $question): void {
		$this->question = $question;
	}

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
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
	public function setPoints($points): void {
		$this->points = $points;
	}
	
	/**
	 * Returns the maxPoints
	 *
	 * @return integer $maxPoints
	 */
	public function getMaxPoints() {
		if ($this->maxPoints == 0 AND $this->getQuestion()->getShortType() == 'Question' AND method_exists($this->getQuestion(), 'getMaxPoints')){
			$this->maxPoints = $this->getQuestion()->getMaxPoints();
		}
		return $this->maxPoints;
	}

	/**
	 * Sets the maxPoints
	 *
	 * @param integer $maxPoints
	 * @return void
	 */
	public function setMaxPoints($maxPoints): void {
		$this->maxPoints = $maxPoints;
	}

	/**
	 * helper method which checks if this question was answered
	 * regardless if they are correct or not
	 *
	 * return boolean
	 */
	public function isAnswered() {
		// check for radiobuttons
		if (!$this->getAnswers()->count()) {
			return false;
		}

		// check for checkboxes
		foreach ($this->getAnswers() as $answer) {
			if ($answer->getValue()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * helper method to check if this question was answered correctly
	 *
	 * return boolean
	 */
	public function isAnsweredCorrectly() {
		if(count($this->getAnswers())){
			foreach ($this->getAnswers() as $answer) {
				if ($answer->canBeAnsweredCorrectly()){
					$answer->setResultquestion($this);
					if (!$answer->isAnsweredCorrectly()) {
						return false;
					}
				}
			}
			return true;
		}
		else
			return false;

	}
	
	/**
     * helper method to check if this question can answered correctly
     *
     * return boolean
     */
	public function canBeAnsweredCorrectly() {
	    $canBe = false;
		if($this->getQuestion()->getType() == 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question') {
			if (count($this->getAnswers())) {
				foreach ($this->getAnswers() as $answer) {
					if ($answer->canBeAnsweredCorrectly()) {
						$canBe = true;
					}
				}
			} else
				$canBe = true;
		}

        return $canBe;
    }

    public function getResult(): int
    {
        return $this->result;
    }

    public function setResult(int $result): void
    {
        $this->result = $result;
    }
    
    
}