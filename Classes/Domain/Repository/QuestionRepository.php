<?php
namespace Kennziffer\KeQuestionnaire\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
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
class QuestionRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'sorting' => QueryInterface::ORDER_ASCENDING
	);
	
	/**
  * find all questions for pid
  *
  * @param integer $pid
  * @return QueryResultInterface|array Query  Result
  */
 public function findAllForPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('pid', $pid));
		return $query->execute();
	}

    /**
     * find all questions for pid
     *
     * @param integer $pid
     * @return QueryResultInterface|array Query  Result
     */
    public function findAllForPidtoExport($pid) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $constraints[] = $query->equals('pid', $pid) ;
        $constraints[] = $query->logicalNot(
            $query->equals('type', "Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak") ) ;

        $query->matching($query->logicalAnd(...$constraints));
        return $query->execute();
    }

    /**
	 * find one question without check the pid
	 * 
	 * @param integer $pid
	 * @return Query Result
	 */
	public function findByUidFree($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('uid', $uid));
        $result = $query->execute();
        return ($result[0]);
	}
	
	/**
	 * find questions for ids
	 * 
	 * @param array $ids
	 * @return Query Result
	 */
	public function findByUids($ids) {
		$query = $this->createQuery();
		$query->matching($query->in('uid', $ids));
        return $query->execute();
	}
	
	/**
	* find questions for uids
	* 
	* @param string $uids
	* @return questions
	*/
   public function findForUids($uids) {
		$uids = explode(',',$uids);
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->in('uid', $uids));
		return $query->execute();	   
   }
   
   /**
	* find question for uid
	* 
	* @param integer $uid
	* @return questionnaire
	*/
   public function findForUid($uid) {
	   $query = $this->createQuery();
	   $query->getQuerySettings()->setRespectStoragePage(FALSE);

	   $constraint = $query->equals('uid',$uid);

	   $query->matching($constraint);
	   $questions = $query->execute();
	   $question = $questions[0];

	   return $question;
   }
}
?>