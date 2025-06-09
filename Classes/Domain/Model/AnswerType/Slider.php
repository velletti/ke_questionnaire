<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model\AnswerType;

use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
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
class Slider extends Answer {
	
	/**
	 * pdfType
	 *
	 * @var string
	 */
	protected $pdfType = 'special';

	/**
	 * RightLabel
	 *
	 * @var string
	 */
	protected $leftLabel;
	
	/**
	 * RightLabel
	 *
	 * @var string
	 */
	protected $rightLabel;
	
	/**
	 * minValue
	 *
	 * @var integer
	 */
	protected $minValue;
	
	/**
	 * maxValue
	 *
	 * @var integer
	 */
	protected $maxValue;
	
	/**
	 * sliderIncrement
	 *
	 * @var float
	 */
	protected $sliderIncrement;
	
	/**
	 * sliderSteps
	 *
	 * @var array
	 */
	protected $sliderSteps;
	
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
	 * Returns the leftLabel
	 *
	 * @return string $leftLabel
	 */
	public function getLeftLabel() {
		return $this->leftLabel;
	}

	/**
	 * Sets the leftLabel
	 *
	 * @param string $leftLabel
	 * @return void
	 */
	public function setLeftLabel($leftLabel): void {
		$this->leftLabel = $leftLabel;
	}
	
	/**
	 * Returns the rightLabel
	 *
	 * @return string $rightLabel
	 */
	public function getRightLabel() {
		return $this->rightLabel;
	}

	/**
	 * Sets the rightLabel
	 *
	 * @param string $rightLabel
	 * @return void
	 */
	public function setRightLabel($rightLabel): void {
		$this->rightLabel = $rightLabel;
	}
	
	/**
	 * Returns the minValue
	 *
	 * @return integer $minValue
	 */
	public function getMinValue() {
		return $this->minValue;
	}

	/**
	 * Sets the minValue
	 *
	 * @param integer $minValue
	 * @return void
	 */
	public function setMinValue($minValue): void {
		$this->minValue = $minValue;
	}
	
	/**
	 * Returns the maxValue
	 *
	 * @return integer $maxValue
	 */
	public function getMaxValue() {
		return $this->maxValue;
	}

	/**
	 * Sets the maxValue
	 *
	 * @param integer $maxValue
	 * @return void
	 */
	public function setMaxValue($maxValue): void {
		$this->maxValue = $maxValue;
	}
	
	/**
	 * Returns the sliderIncrement
	 *
	 * @return integer $sliderIncrement
	 */
	public function getSliderIncrement() {
		return $this->sliderIncrement;
	}

	/**
	 * Sets the sliderIncrement
	 *
	 * @param integer $sliderIncrement
	 * @return void
	 */
	public function setSliderIncrement($sliderIncrement): void {
		$this->sliderIncrement = $sliderIncrement;
	}
	
	/**
	 * Returns the sliderSteps
	 *
	 * @return array $sliderSteps
	 */
	public function getSliderSteps() {
		$base = $this->getMaxValue() - $this->getMinValue();
		$stepping = round($base / $this->getSliderIncrement());
		if (!$width = $this->getWidth()) $width = 400;
		$widthPercent = $width / $stepping;
		$left = 0;
		$step = 0;
		$steps = array();
		while ($step < $base){
			$steps[$left] = round($step);
			$step += $this->getSliderIncrement();
			$left += $widthPercent;
		}
		return $steps;
	}
	
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
	public function setWidth($width): void {
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
	public function setHeight($height): void {
		$this->height = $height;
	}
}
?>