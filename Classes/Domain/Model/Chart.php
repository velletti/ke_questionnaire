<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
class Chart extends AbstractEntity {

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title;

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
	 * Data
	 *
	 * @var Array
	 */
	protected $data;

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
	public function setTitle($title): void {
		$this->title = $title;
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

	/**
	 * Returns the data
	 *
	 * @param string $outerWrap
	 * @param string $innerWrap
	 * @return string $data
	 */
	public function getData($varName = 'chartData', $outerWrap = '[|]', $innerWrap = '[|]') {
		return $this->data;
	}

	/**
	 * Sets the data
	 *
	 * @param Array $data
	 * @return void
	 */
	public function setData($data): void {
		$this->data = $data;
	}

	/**
	 * Add a data row
	 * If an array entry exists $value will be added to this entry
	 * If not $value will be the new value of the array entry
	 *
	 * @param string $title
	 * @param integer $value
	 * @return void
	 */
	public function addData($title, $value = 1): void {
		if(isset($this->data[$key])) {
			$this->data[$key] += $value;
		} else $this->data[$key] = $value;
	}

	public function getVariables() {
		return '
			var title = ' . $this->title . ';' . CHR(10) . '
			var width = ' . $this->width . ';' . CHR(10) . '
			var height = ' . $this->height . ';' . CHR(10) . '
			var chartData = ' . $this->getData() . ';' . CHR(10) . '
		';
	}

}
?>