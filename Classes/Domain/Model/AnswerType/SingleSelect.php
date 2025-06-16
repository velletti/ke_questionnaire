<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model\AnswerType;

use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use Kennziffer\KeQuestionnaire\Validation\AbstractValidation;
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
class SingleSelect extends Answer {

	/**
	* SelectValues
	*
	* @var string
	*/
	protected $selectValues;
    
    /**
	* ComparisonText
	*
	* @var string
	*/
	protected $comparisonText;

	/**
	* Returns the selectValues
	*
	* @return string $selectValues
	*/
	public function getSelectValues() {
		return $this->selectValues;
	}

	/**
	* Sets the selectValues
	*
	* @param string $selectValues
	* @return void
	*/
	public function setSelectValues($selectValues): void {
		$this->selectValues = $selectValues;
	}
	
	/**
	* Returns the selectValues as array
	*
	* @return array $selectValues
	*/
	public function getSelectValuesArray() {
		$values = array();
		foreach (explode("\n",$this->selectValues) as $line){
			$temp = explode(':',$line);
			if ($temp[1] == '') $temp[1] = $temp[0];
			$values[trim($temp[0])] = trim($temp[1]);
		}
		
		return $values;
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
	public function setComparisonText($comparisonText): void {
		$this->comparisonText = $comparisonText;
	}

	/**
	 * Returns the comparisonText as array
	 *
	 * @return array
	 */
	public function getComparisonTextArray() {
		$values = array();
		foreach (explode("\n",$this->comparisonText) as $line){
			$temp = explode(':',$line);
			if ($temp[1] == '') $temp[1] = $temp[0];
			$values[trim($temp[0])] = trim($temp[1]);
		}

		return $values;
	}

    /**
     * Checks if the value is valid for this answer
     *
     * @param string $value value
     * @return boolean
     */
	public function isValid(string $value){
		$class = 'Kennziffer\\KeQuestionnaire\\Validation\\' . ucfirst($this->getValidationType());
		if (class_exists($class)) {
			$validator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance($class);
			if ($validator instanceof AbstractValidation) {
				/* @var $validator \Kennziffer\KeQuestionnaire\Validation\AbstractValidation */
				return $validator->isValid($value, $this);
			} else return false;
		} else return false;
	}
}
?>