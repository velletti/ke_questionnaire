<?php
namespace Kennziffer\KeQuestionnaire\Domain\Repository;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
class AnswerRepository extends Repository {
	
	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'sorting' => QueryInterface::ORDER_ASCENDING
	);
	
	/**
  * find all answers for question
  *
  * @param Question $question
  * @return QueryResultInterface Result
  */
 public function findByQuestion(Question $question) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(TRUE);
		if ($question->getLocalizedUid()){
			$constraint = $query->equals('question', $question->getLocalizedUid());
		} else {
			$constraint = $query->equals('question', $question);
		}
		
		$query->matching($constraint);
		return $query->execute();
	}
	
	/**
  * find all results for pid
  *
  * @param Question $question
  * @return QueryResultInterface Result
  */
 public function findByQuestionWithoutPid(Question $question) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->matching($query->equals('question', $question));
		return $query->execute();
	}
	
	/**
  * find all results for pid
  *
  * @param integer $pid
  * @return Answer Result
  */
 public function findByUidFree($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('uid', $uid));
        $result = $query->execute();
        return ($result[0]);
	}
}
?>