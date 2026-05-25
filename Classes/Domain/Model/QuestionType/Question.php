<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model\QuestionType;

use Kennziffer\KeQuestionnaire\Domain\Repository\AnswerRepository;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
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
class Question extends \Kennziffer\KeQuestionnaire\Domain\Model\Question {

	/**
	 * Text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * Help Text
	 *
	 * @var string|null
	 */
	protected $helpText;

	/**
	 * Image
	 *
	 * @var string|null
	 */
	protected $image;

	/**
	 * Image position
	 *
	 * @var string
	 */
	protected $imagePosition;

	/**
	 * Is mandatory
	 *
	 * @var boolean
	 */
	protected $isMandatory = FALSE;

	/**
	 * Have the question to be answered correctly?
	 *
	 * @var boolean
	 */
	protected $mustBeCorrect = FALSE;

	/**
  * Answers
  *
  * @var ObjectStorage<Answer>
  */
 #[Lazy]
 protected $answers;
	
	/**
	 * random answers
	 *
	 * @var boolean
	 */
	protected $randomAnswers = FALSE;
	
	/**
	 * Column Count
	 *
	 * @var integer
	 */
	protected $columnCount;
	
	/**
	 * MaxAnswers
	 *
	 * @var integer
	 */
	protected $maxAnswers;
	
	/**
	 * MinAnswers
	 *
	 * @var integer
	 */
	protected $minAnswers;


    /**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Returns the text
	 *
	 * @return string $text
	 */
	#[\Override]
    public function getText(): string
    {
		return $this->text;
	}

	/**
	 * Sets the text
	 *
	 * @param string $text
	 * @return void
	 */
	#[\Override]
    public function setText($text): void {
		$this->text = $text;
	}

	/**
	 * Returns the helpText
	 *
	 * @return string|null $helpText
	 */
	#[\Override]
    public function getHelpText(): ?string
    {
		return $this->helpText;
	}

	/**
	 * Sets the helpText
	 *
	 * @param string $helpText
	 * @return void
	 */
	#[\Override]
    public function setHelpText($helpText): void {
		$this->helpText = $helpText;
	}

	/**
	 * Returns the image
	 *
	 * @return string|null $image
	 */
	#[\Override]
    public function getImage(): ?string
    {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param string $image
	 * @return void
	 */
	#[\Override]
    public function setImage($image): void {
		$this->image = $image;
	}

	/**
	 * Returns the imagePosition
	 *
	 * @return string $imagePosition
	 */
	#[\Override]
    public function getImagePosition(): string
    {
		return $this->imagePosition;
	}

	/**
	 * Sets the imagePosition
	 *
	 * @param string $imagePosition
	 * @return void
	 */
	#[\Override]
    public function setImagePosition($imagePosition): void {
		$this->imagePosition = $imagePosition;
	}

	/**
	 * Returns the isMandatory
	 *
	 * @return boolean $isMandatory
	 */
	#[\Override]
    public function getIsMandatory(): bool
    {
		// Check if one answer is a DataPrivacy. If yes, the the question is always mandatory
		$rep = GeneralUtility::makeinstance(AnswerRepository::class);
		$answers = $rep->findByQuestion($this);
		foreach ($answers as $answer){
			if ($answer->getShortType() == 'DataPrivacy') {
                $this->isMandatory = true;
            }
		}
		return (boolean) $this->isMandatory;
	}

	/**
	 * Sets the isMandatory
	 *
	 * @param boolean $isMandatory
	 * @return void
	 */
	#[\Override]
    public function setIsMandatory($isMandatory): void {
		$this->isMandatory = $isMandatory;
	}

	/**
	 * Returns the boolean state of isMandatory
	 *
	 * @return boolean
	 */
	#[\Override]
    public function isIsMandatory(): bool
    {
		return $this->getIsMandatory();
	}

	/**
	 * Returns the mustBeCorrect
	 *
	 * @return boolean $mustBeCorrect
	 */
	#[\Override]
    public function getMustBeCorrect(): bool
    {
		return $this->mustBeCorrect;
	}

	/**
	 * Sets the mustBeCorrect
	 *
	 * @param boolean $mustBeCorrect
	 * @return void
	 */
	#[\Override]
    public function setMustBeCorrect($mustBeCorrect): void {
		$this->mustBeCorrect = $mustBeCorrect;
	}

	/**
	 * Returns the boolean state of mustBeCorrect
	 *
	 * @return boolean
	 */
	#[\Override]
    public function isMustBeCorrect(): bool
    {
		return $this->getMustBeCorrect();
	}

	/**
  * Adds a Answer
  *
  * @param Answer $answer
  * @return void
  */
 #[\Override]
 public function addAnswer(Answer $answer): void {
		$this->answers->attach($answer);
	}

	/**
  * Removes a Answer
  *
  * @param Answer $answerToRemove The Answer to be removed
  * @return void
  */
 #[\Override]
 public function removeAnswer(Answer $answerToRemove): void {
		$this->answers->detach($answerToRemove);
	}

	/**
  * Returns the answers
  *
  * @return ObjectStorage<Answer> $answers
  */
 #[\Override]
 public function getAnswers() {
		if ($this->isRandomAnswers()){
			$answers = $this->answers->toArray();
			shuffle($answers);
			return $answers;
		} else {
			return $this->answers;
		}
	}

	/**
  * Sets the answers
  *
  * @param ObjectStorage<Answer> $answers
  * @return void
  */
 #[\Override]
 public function setAnswers(ObjectStorage $answers): void {
		$this->answers = $answers;
	}
	
	/**
	 * Returns the randomAnswers
	 *
	 * @return boolean $randomAnswers
	 */
	public function getRandomAnswers(): bool
    {
		return $this->randomAnswers;
	}

	/**
	 * Sets the randomAnswers
	 *
	 * @param boolean $randomAnswers
	 * @return void
	 */
	#[\Override]
    public function setRandomAnswers(bool $randomAnswers):void
    {
		$this->randomAnswers = $randomAnswers;
	}

	/**
	 * Returns the boolean state of randomAnswers
	 *
	 * @return boolean
	 */
	#[\Override]
    public function isRandomAnswers():bool
    {
		return $this->getRandomAnswers();
	}
	
	/**
	 * Returns the columnCount
	 *
	 * @return integer $columnCount
	 */
	#[\Override]
    public function getColumnCount(): int
    {
		return $this->columnCount;
	}
	
	/**
	 * Returns the columnPercentage
	 *
	 * @return integer $columnPercent
	 */
	#[\Override]
    public function getColumnPercent() {
		return 100 / $this->columnCount;
	}

	/**
	 * Sets the columnCount
	 *
	 * @param integer $columnCount
	 * @return void
	 */
	#[\Override]
    public function setColumnCount(int $columnCount):void
    {
		$this->columnCount = $columnCount;
	}
	
	/**
	 * Returns the maxAnswers
	 *
	 * @return integer $maxAnswers
	 */
	#[\Override]
    public function getMaxAnswers(): int
    {
		return $this->maxAnswers;
	}

	/**
	 * Sets the maxAnswers
	 *
	 * @param integer $maxAnswers
	 * @return void
	 */
	#[\Override]
    public function setMaxAnswers($maxAnswers): void {
		$this->maxAnswers = $maxAnswers;
	}
	
	/**
	 * Returns the minAnswers
	 *
	 * @return integer $minAnswers
	 */
	#[\Override]
    public function getMinAnswers(): int
    {
		return $this->minAnswers;
	}

	/**
	 * Sets the minAnswers
	 *
	 * @param integer $minAnswers
	 * @return void
	 */
	#[\Override]
    public function setMinAnswers($minAnswers): void {
		$this->minAnswers = $minAnswers;
	}
	
	/**
	 * Returns the maxPoints
	 *
	 * @return integer $maxPoints
	 */
	public function getMaxPoints(): int
    {
		$max = 0;		
                
		foreach ($this->getAnswers() as $answer){
                    switch ($answer->getShortType()){
                        case 'SemanticDifferential':
                                if ($max < $answer->getMaxPoints()) {
                                    $max = $answer->getMaxPoints();
                                }
                            break;
                        case 'Checkbox':
                                if ($answer->getPoints() > 0) {
                                    $max += $answer->getPoints();
                                }
                            break;
                        default:
                                if ($max < $answer->getPoints()) {
                                    $max = $answer->getPoints();
                                }
                            break;
                    }
		}
		return (integer) $max;
	}

}
