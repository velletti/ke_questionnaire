<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model\AnswerType;

use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
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
 *  it under the rows of the GNU General Public License as published by
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
class MatrixHeader extends Answer {
	/**
  * Cols
  *
  * @var ObjectStorage<Answer>
  */
 #[Lazy]
 protected $cols;
	
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
	 * addClones
	 *
	 * @var boolean
	 */
	protected $addClones;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
  * Adds a Col
  *
  * @param Answer $col
  * @return void
  */
 public function addCol(Answer $col): void {
		$this->cols->attach($col);
	}

	/**
  * Removes a Col
  *
  * @param Answer $colToRemove The Col to be removed
  * @return void
  */
 public function removeCol(Answer $colToRemove): void {
		$this->cols->detach($colToRemove);
	}

	/**
  * Returns the cols
  *
  * @return ObjectStorage<Answer> $cols
  */
 public function getCols() {
		$cols = $this->cols;
		return $cols;
	}

	/**
  * Sets the cols
  *
  * @param ObjectStorage<Answer> $cols
  * @return void
  */
 public function setCols(ObjectStorage $cols): void {
		$this->cols = $cols;
	}
	
	/**
	 * Returns the maxAnswers
	 *
	 * @return integer $maxAnswers
	 */
	public function getMaxAnswers() {
		return $this->maxAnswers;
	}

	/**
	 * Sets the maxAnswers
	 *
	 * @param integer $maxAnswers
	 * @return void
	 */
	public function setMaxAnswers($maxAnswers): void {
		$this->maxAnswers = $maxAnswers;
	}
	
	/**
	 * Returns the minAnswers
	 *
	 * @return integer $minAnswers
	 */
	public function getMinAnswers() {
		return $this->minAnswers;
	}

	/**
	 * Sets the minAnswers
	 *
	 * @param integer $minAnswers
	 * @return void
	 */
	public function setMinAnswers($minAnswers): void {
		$this->minAnswers = $minAnswers;
	}
	
	/**
  * Create the whole Csv Line
  * @param array $results
  * @param Question $question
  * @param array options
  * @return string
  */
 public function getCsvLine(array $results, Question $question, $options = array()) {
		if ($options['rows']) $rows = $options['rows'];
		else $rows = $this->getRows($question);
		$line = '';
		
		foreach ($rows as $row){
			$aL = array();
			for ($i = 0; $i < $options['emptyFields']; $i++){
				$aL[] = '';
			}

			$aL[] = $row->getTitle();
			if ($options['showAText']) {
				$aL[] = strip_tags($row->getText());
			}

			$line .= implode($options['separator'],$aL).$options['newline'];
			if (!$row->isTitleLine()){
				foreach ($this->getCols() as $column){
					$aL = array();
					for ($i = 0; $i < $options['emptyFields']; $i++){
						$aL[] = '';
					}
					$aL[] = $column->getTitle();
					if ($options['showAText']) {
						$aL[] = strip_tags($column->getText());
					}

					foreach ($results as $result){
                                            //TODO: 
						//if ($column->getShortType() == 'Radiobutton' AND !$options['extended']) $rAnswer = $result->getAnswer($question->getUid(), $row->getUid(), 0);
						//else $rAnswer = $result->getAnswer($question->getUid(), $row->getUid(), $column->getUid());
						$options['row'] = $row;
						if ($rAnswer) $aL[] = $column->getCsvValue($rAnswer,$options);
						else $aL[] = '';
					}

					foreach ($aL as $nr => $value){
						if (!is_numeric($value)) $aL[$nr] = $this->text.$value.$this->text;
					}

					$line .= implode($options['separator'],$aL).$options['newline'];
				}
			}
		}
		return $line;
	}
	
	/**
  * Create the header of the line
  * @param Question $question
  * @param array options
  * @return string
  */
 public function getCsvLineHeader(Question $question, $options = array()) {
		if ($options['rows']) $rows = $options['rows'];
		else $rows = $this->getRows($question);
		$line = '';
		
		foreach ($rows as $row){
			$aL = array();
			for ($i = 0; $i < $options['emptyFields']; $i++){
				$aL[] = '';
			}

			$aL[] = $row->getTitle();
			if ($options['showAText']) {
				$aL[] = strip_tags($row->getText());
			}

			$line .= implode($options['separator'],$aL).$options['newline'];
			if (!$row->isTitleLine()){
				foreach ($this->getCols() as $column){
					$aL = array();
					for ($i = 0; $i < $options['emptyFields']; $i++){
						$aL[] = '';
					}
					$aL[] = $column->getTitle();
					if ($options['showAText']) {
						$aL[] = strip_tags($column->getText());
					}

					$line .= implode($options['separator'],$aL).$options['newline'];
				}
			}
		}
		return $line;
	}


        /**
  * Create the data of the Csv Line
  * @param array $results
  * @param Question $question
  * @param array options
  * @param array oldArray
  * @param integer counter
  * @return string
  */
 public function getCsvLineValues(array $results, Question $question, $options = array()) {
		if ($options['rows']) $rows = $options['rows'];
		else $rows = $this->getRows($question);
		$line = '';
				
		foreach ($rows as $row){
			$line .= $options['newline'];
			$aL = array();
			if (!$row->isTitleLine()){
				foreach ($this->getCols() as $column){
					$aL = array();
					foreach ($results as $result){
						if ($column->getShortType() == 'Radiobutton' AND !$options['extended']) $rAnswer = $result->getAnswer($question->getUid(), $row->getUid(), 0);
						else $rAnswer = $result->getAnswer($question->getUid(), $row->getUid(), $column->getUid());
						$options['row'] = $row;
						if ($rAnswer) {
							$aL[] = $column->getCsvValue($rAnswer,$options);
						} else $aL[] = '';
					}
					
					foreach ($aL as $nr => $value){
						if (!is_numeric($value)) $aL[$nr] = $this->text.$value.$this->text;
					}
					
					//\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('$aL', 'keq', 0, $aL);
					if (is_array($aL)){
						//implode the csv
						$line .= implode($options['separator'],$aL).$options['newline'];
					}
				}
			}
		}
		//\TYPO3\CMS\Core\Utility\GeneralUtility::devLog($line, 'keq', 0, $aL);
		return $line;
	}
	
		
	/**
	 * Gets the Rows
	 * 
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question the rows are in
	 * @return array
	 */
	public function getRows(\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question){
		$rows = array();
		
		// workaround for pointer in question, so all following answer-objects are rendered.
		$addIt = false;
		//$rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AnswerRepository');
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AnswerRepository');
		$answers = $rep->findByQuestionWithoutPid($question);
		
		foreach ($answers as $answer){
			//Add only after the correct Matrix-Header is found, only following rows will be added.
			if ((get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader' OR get_class($answer) == 'Kennziffer\KeQuestionnairePremium\Domain\Model\AnswerType\ExtendedMatrixHeader')
					AND $answer === $this) $addIt = true;
			elseif (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader') $addIt = false;
			if ($addIt){
				if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow'){
					$rows[] = $answer;
				}
			}
		}
		
		return $rows;
	}
    
    /**
     * get clone-Row
     *
     * @param \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question the rows are in
     * @return MatrixRow $row
     */
    public function getCloneableRow(\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question $question){
        $rows = $this->getRows($question);
        return ($rows[count($rows)-1]);
    }
    
    /**
	 * Returns the addClones
	 *
	 * @return boolean addClones
	 */
	public function getAddClones() {
		return (boolean) $this->addClones;
	}

	/**
	 * Sets the addClones
	 *
	 * @param boolean $addClones
	 * @return void
	 */
	public function setAddClones($addClones): void {
		$this->addClones = $addClones;
	}
	
}
?>