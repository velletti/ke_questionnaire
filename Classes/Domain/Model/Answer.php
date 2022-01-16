<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;
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
 * base Type for answers
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Answer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Type
	 *
	 * @var string
	 */
	protected $type;
	
	/**
	 * pdfType
	 *
	 * @var string
	 */
	protected $pdfType = 'normal';

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Points
	 *
	 * @var string
	 */
	protected $points;

	/**
	 * Text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * Is correct answer
	 *
	 * @var boolean
	 */
	protected $isCorrectAnswer;
    
    /**
	 * questionid
	 *
	 * @var integer
	 */
	protected $question;

    /**
	 * Template
	 *
	 * @var string
	 */
	protected $template;
	
	/**
	 * saveType
	 *
	 * @var string
	 */
	protected $saveType;



    /**
     * ValidationType
     *
     * @var string
     */
    protected $validationType;

    /**
     * ValidationText
     *
     * @var string
     */
    protected $validationText;

    /**
     * ValidationKeysAmount
     *
     * @var integer
     */
    protected $validationKeysAmount;

    /**
     * ComparisonText
     *
     * @var string
     */
    protected $comparisonText;


    /**
     * Width
     *
     * @var integer
     */
    protected $width;

    /**
     * Height
     *
     * @var integer
     */
    protected $height;





    /**
     * Returns the width
     *
     * @return integer $width
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Sets the width
     *
     * @param integer $width
     * @return void
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * Returns the height
     *
     * @return integer $height
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Sets the height
     *
     * @param integer $height
     * @return void
     */
    public function setHeight($height) {
        $this->height = $height;
    }

	/**
	 * Returns the type
	 *
	 * @return string $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Returns the short version of type
	 * type is the complete class name, but for partials it's better to have shorter type names
	 *
	 * @return string $shortType
	 */
	public function getShortType() {
		return substr (strrchr ($this->type, '\\'), 1);
	}

	/**
	 * Sets the type
	 *
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Returns the type
	 *
	 * @return string $type
	 */
	public function getPdfType() {
		return $this->pdfType;
	}
	
	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the points
     * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer $resultAnswer
	 * @return string $points
	 */
	public function getPoints(\Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer $resultAnswer = NULL) {
		return $this->points;
	}

	/**
	 * Sets the points
	 *
	 * @param string $points
	 * @return void
	 */
	public function setPoints($points) {
		$this->points = $points;
	}

	/**
	 * Returns the text
	 *
	 * @return string $text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Sets the text
	 *
	 * @param string $text
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Returns the isCorrectAnswer
	 *
	 * @return boolean isCorrectAnswer
	 */
	public function getIsCorrectAnswer() {
		return $this->isCorrectAnswer;
	}


    /**
     * Returns the validationType
     *
     * @return string $validationType
     */
    public function getValidationType() {
        return $this->validationType;
    }

    /**
     * Sets the validationType
     *
     * @param string $validationType
     * @return void
     */
    public function setValidationType($validationType) {
        $this->validationType = $validationType;
    }

    /**
     * Returns the validationText
     *
     * @return string $validationText
     */
    public function getValidationText() {
        return $this->validationText;
    }

    /**
     * Sets the validationText
     *
     * @param string $validationText
     * @return void
     */
    public function setValidationText($validationText) {
        $this->validationText = $validationText;
    }

    /**
     * Returns the validationKeysAmount
     *
     * @return integer $validationKeysAmount
     */
    public function getValidationKeysAmount() {
        return $this->validationKeysAmount;
    }

    /**
     * Sets the validationKeysAmount
     *
     * @param integer $validationKeysAmount
     * @return void
     */
    public function setValidationKeysAmount($validationKeysAmount) {
        $this->validationKeysAmount = $validationKeysAmount;
    }

    /**
     * Returns the comparitionText
     *
     * @return string $comparisonText
     */
    public function getComparisonText() {
        return $this->comparisonText;
    }

    /**
     * Sets the comparisonText
     *
     * @param string $comparisonText
     * @return void
     */
    public function setComparisonText($comparisonText) {
        $this->comparisonText = $comparisonText;
    }

    /**
     * Checks if the value is valid for this answer
     *
     * @param string $value value
     * @return boolean
     */
    public function isValid(string $value){
        if ($value){
            $class = 'Kennziffer\\KeQuestionnaire\\Validation\\' . ucfirst($this->getValidationType());
            if (class_exists($class)) {
                $objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager;
                $validator = $objectManager->get($class);
                if ($validator instanceof \Kennziffer\KeQuestionnaire\Validation\AbstractValidation) {
                    /* @var $validator \Kennziffer\KeQuestionnaire\Validation\AbstractValidation */
                    return $validator->isValid($value, $this);
                } else return false;
            } else return false;
        } else return true;
    }


	/**
	 * Returns the isCorrectAnswer
	 *
	 * @return boolean isCorrectAnswer
	 */
	public function isCorrectAnswer() {
		return (boolean) $this->isCorrectAnswer;
	}

	/**
	 * Sets the isCorrectAnswer
	 *
	 * @param boolean $isCorrectAnswer
	 * @return void
	 */
	public function setIsCorrectAnswer($isCorrectAnswer) {
		$this->isCorrectAnswer = $isCorrectAnswer;
	}
	
	/**
	 * Check if this type of answer should be shown in the Csv Export
	 * @return boolean
	 */
	public function exportInCsv() {
		return true;
	}
	
	/**
	 * Create the header of the line
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * @param array options
	 * @return string
	 */
	public function getCsvLineHeader(\Kennziffer\KeQuestionnaire\Domain\Model\Question $question, $options = array()) {
		$aL = array();
		$addL = array();
		$hasAddL = false;
		//start-columns of the line
		for ($i = 0; $i < $options['emptyFields']; $i++){
			$aL[] = '';
		}
		//title of answer
		$aL[] = $this->getTitle();
		//if the answer-text should be shown
		if ($options['showAText']) {
			$aL[] = strip_tags($this->getText());
		}		
		
		$line = implode($options['separator'],$aL).$options['separator'];
		
		return $line;
	}
	
	/**
	 * Create the data of the Csv Line
	 * @param array $results
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * @param array options
	 * @return string
	 */
	public function getCsvLineValues(array $results, \Kennziffer\KeQuestionnaire\Domain\Model\Question $question, $options = array()) {
		$line = '';
		//for each results get the values
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repRA = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultAnswerRepository');
        $repRQ = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultQuestionRepository');
		foreach ($results as $result){
			// $rAnswer = $result->getAnswer($question->getUid(), $this->getUid());
                        $rQuestion = $repRQ->findByQuestionAndResultIdRaw($question, $result['uid']);
						// $rAnswer = $repRA->findForResultQuestionRaw($rQuestion[0]['uid'] );
			//JVE: Jörg velletti BUG
                        $rAnswer = $repRA->findForResultQuestionAndAnswerRaw($rQuestion[0]['uid'] , $this->getUid());

                        $rAnswer = $rAnswer[0];
                        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($rQuestion, 'rQuestion');
                        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($rAnswer, 'rAnswer');
                        //exit;
			if ($rAnswer) {
				$aL[] = $this->getCsvValueRaw($rAnswer,$options);
				if ($rAnswer AND $rAnswer['additionalValue']){
					$addL[count($aL)-1] = $rAnswer['additionalValue'];
					$hasAddL = true;
				};
			} else $aL[] = '';
		}		
		if (is_array($aL)){
			//insert text-markers
			foreach ($aL as $nr => $value){
				if (!is_numeric($value)) $aL[$nr] = $options['textMarker'].$value.$options['textMarker'];
			}
			//implode the csv
			$line = implode($options['separator'],$aL).$options['newline'];
			//if there is additional text add a line
			if ($hasAddL){
				$addLine = array();
				foreach ($aL as $nr => $value){
					if ($addL[$nr])	{
						if (!is_numeric($addL[$nr])) $addLine[] = $options['textMarker'].$addL[$nr].$options['textMarker'];
					} else $addLine[] = '';

				}
				$line .= implode($options['separator'],$addLine);
			}
		}
		
		return $line;
	}
	
	/**
	 * Create the whole Csv Line
	 * @param array $results
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\Question $question
	 * @param array options
	 * @return string
	 */
	public function getCsvLine(array $results, \Kennziffer\KeQuestionnaire\Domain\Model\Question $question, $options = array()) {
		
		$line = $this->getCsvLineHeader($question, $options);
		$line .= $this->getCsvLineValues($results, $question, $options);
		
		return $line;
	}

	/**
	 * return the Value shown in the Csv Export
	 * @param \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer $rAnswer
	 * @param array $options
	 * @return string
	 */
	public function getCsvValue(\Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer $rAnswer, $options = array()){
		return $rAnswer->getValue();
	}
        
        /**
	 * return the Value shown in the Csv Export
	 * @param array $rAnswer
	 * @param array $options
	 * @return string
	 */
	public function getCsvValueRaw(array $rAnswer, $options = array()){
		return $rAnswer['value'];
	}
    
    /**
     * return the id of the Question
     * 
     * return integer
     */
    public function getQuestion(){
        return $this->question;
    }
    
    /**
	 * Returns the template
	 *
	 * @return string $template
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Sets the template
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	/**
	 * Returns the saveType
	 *
	 * @return string $saveTxpe
	 */
	public function getSaveType() {
		return 'replaceValue';
	}
}
?>