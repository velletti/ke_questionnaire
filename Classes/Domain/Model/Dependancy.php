<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
class Dependancy extends AbstractEntity {

	/**
  * answer
  *
  * @var Answer
  */
 protected $answer;
    
    /**
  * question
  *
  * @var Question
  */
 protected $question;
	
	/**
  * dquestion
  *
  * @var Question
  */
 protected $dquestion;
    
    /**
	 * relation
	 *
	 * @var string
	 */
	protected $relation;
    
    /**
  * Returns the answer
  *
  * @return Answer $answer
  */
 public function getAnswer() {
		return $this->answer;
	}

	/**
  * Sets the answer
  *
  * @param Answer $answer
  * @return void
  */
 public function setAnswer($answer): void {
		$this->answer = $answer;
	}
	
	/**
  * Returns the question
  *
  * @return Question $question
  */
 public function getQuestion() {
        if (!$this->question){
            $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
			$q_rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\QuestionRepository');
            $this->question = $q_rep->findByUid($this->answer->getQuestion());
        }
		return $this->question;
	}

	/**
  * Sets the question
  *
  * @param Question $question
  * @return void
  */
 public function setQuestion($question): void {
		$this->question = $question;
	}
    
    /**
  * Returns the dQuestion
  *
  * @return Question $dQuestion
  */
 public function getDQuestion() {
		return $this->dQuestion;
	}

	/**
  * Sets the dQuestion
  *
  * @param Question $dQuestion
  * @return void
  */
 public function setDQuestion($dQuestion): void {
		$this->dQuestion = $dQuestion;
	}
    
    /**
	 * Returns the relation
	 *
	 * @return string $relation
	 */
	public function getRelation() {
		return $this->relation;
	}

	/**
	 * Sets the relation
	 *
	 * @param string $relation
	 * @return void
	 */
	public function setRelation($relation): void {
		$this->relation = $relation;
	}
	
	/**
	 * creates the JS to be used in the CheckDependanciesViewHelper
	 * 
	 * @param integer $nr 
	 * @return string
	 */
	public function getRelationJs($nr) {
		$js = '';
		
		if ($nr > 1){
			switch ($this->getRelation()){
				case 'and':
						$js .= '&& (';
					break;
				case 'or':
						$js .= '|| (';
					break;
				default :
						$js .= '(';
					break;
			}
		} else $js .= '(';
		
		switch ($this->getAnswer()->getShortType()){
			case 'Radiobutton': 
					$js .= 'jQuery("input[name=\'tx_kequestionnaire_questionnaire[newResult][questions]['.$this->getQuestion()->getUid().'][answers]['.$this->getQuestion()->getUid().'][answer]\']:checked").val() == '.$this->getAnswer()->getUid();
				break;
			default:
					$js .= 'jQuery("input[name=\'tx_kequestionnaire_questionnaire[newResult][questions]['.$this->getQuestion()->getUid().'][answers]['.$this->getAnswer()->getUid().'][value]\']:checked").val() == '.$this->getAnswer()->getUid();
				break;
		}           
		$js .= ')';
		
		return $js;
	}
	
	/**
	 * creates the Condition-Check to be used in the CheckDependanciesViewHelper
	 * 
	 * @return string
	 */
	public function getRelationCondition() {
		$condition = array();
		$condition['type'] = $this->getRelation();
		$condition['compareToQuestion'] = $this->getQuestion()->getUid();
		$condition['compareToAnswer'] = $this->getAnswer()->getUid();
		$condition['answerType'] = $this->getAnswer()->getShortType();
		
		return $condition;
	}

}
?>