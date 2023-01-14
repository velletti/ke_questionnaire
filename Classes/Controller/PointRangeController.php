<?php
namespace Kennziffer\KeQuestionnaire\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Kennziffer\KeQuestionnaire\Domain\Repository\RangeRepository;
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
 * This Class renders the valuation charts
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PointRangeController extends ActionController {

	/**
  * @var RangeRepository
  */
 protected $rangeRepository;
	
	/**
  * inject range repository
  *
  * @param RangeRepository $rangeRepository
  * @return void
  */
 public function injectRangeRepository(RangeRepository $rangeRepository) {
		$this->rangeRepository = $rangeRepository;
	}
	
	/**
  * action show text for range
  *
  * @param Result $newResult A fresh new result object
  * @return void
  */
 public function showTextAction(Result $result) {
		$ranges = $this->rangeRepository->findAll();
		if ($ranges !== NULL) {
			/* @var $range \Kennziffer\KeQuestionnaire\Domain\Model\Range */
			foreach ($ranges as $range) {
				if ($result->getPoints() >= $range->getPointsFrom() && $result->getPoints() <= $range->getPointsUntil()) {
					$resultText = $range->getText();
					break;
				}
			}
		}
		$this->view->assign('result', $result);
		$this->view->assign('resultText', $resultText);
	}
}
?>