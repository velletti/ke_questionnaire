<?php
namespace Kennziffer\KeQuestionnaire\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Kennziffer\KeQuestionnaire\Domain\Model\AuthCode;
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
class ResultRepository extends Repository {
    public function initializeObject(): void {
        /** @var $defaultQuerySettings Tx_Extbase_Persistence_Typo3QuerySettings */
        //$defaultQuerySettings = $this->objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
        // go for $defaultQuerySettings = $this->createQuery()->getQuerySettings(); if you want to make use of the TS persistence.storagePid with defaultQuerySettings(), see #51529 for details
        $defaultQuerySettings = $this->createQuery()->getQuerySettings();
 
        // don't add sys_language_uid constraint
        $defaultQuerySettings->setRespectSysLanguage(FALSE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }
    
	/**
  * find all finished results
  *
  * @return QueryResultInterface All finished results
  */
 public function findFinishedResults() {
		$query = $this->createQuery();

		return $query->matching(
			$query->greaterThan('finished', 0)
		)->execute();
	}

    /**
     * find all finished results
     *
     * @return QueryResultInterface All finished results
     */
    public function findFinishedResultsByUser( $FeUser ) {
        $query = $this->createQuery();

        return $query->matching(
            $query->logicalAnd( [
            $query->greaterThan('finished', 0) ,
            $query->equals('FeUser', intval($FeUser ) )
                    ]
            )
        )->execute();
    }

	
	/**
	 * find all results for pid
	 * 
	 * @param integer $pid
	 * @return Query Result
	 */
	public function findAllForPid($pid) {		
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('pid', $pid));
		return $query->execute();
	}
	
	/**
	 * find all results for pid
	 * 
	 * @param integer $pid
	 * @param integer $interval
	 * @param integer $position
	 * @return Query Result
	 */
	public function findAllForPidInterval($pid, $interval, $position) {	
		$interval = intval($interval);
		$position = intval($position);
		if ($interval == 0) $interval = 1;
		
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('pid', $pid));
		$query->setLimit($interval);
		$query->setOffset($position);
		return $query->execute();
	}
	
	/**
	 * find all results for pid
	 * 
	 * @param integer $pid
	 * @return Query Result
	 */
	public function findAllForPidRaw($pid) {
		$query = $this->createQuery();
		
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		
		$query->matching($query->equals('pid', $pid));
		
		return $query->execute(true);
	}
        
        /**
	 * find all results for pid
	 * 
	 * @param integer $pid
	 * @param integer $interval
	 * @param integer $position
	 * @return Query Result
	 */
	public function findAllForPidIntervalRaw($pid, $interval, $position) {	
		$interval = intval($interval);
		$position = intval($position);
		if ($interval == 0) $interval = 1;
		
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('pid', $pid));
		$query->setLimit($interval);
		$query->setOffset($position);
		return $query->execute(true);
	}
    
    /**
	 * find all results for pid
	 * 
	 * @param integer $pid
	 * @return Query Result
	 */
	public function countAllForPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('pid', $pid));
		return $query->count();
	}
	
	/**
  * find all finished results for pid
  *
  * @param integer $pid
  * @return QueryResultInterface All finished results
  */
 public function findFinishedForPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->matching(
				$query->logicalAnd(
                    $query->greaterThan('finished', 0),
                    $query->equals('pid', $pid)
                ))->execute();
	}
        
        /**
  * find all finished results for pid
  *
  * @param integer $pid
  * @return QueryResultInterface All finished results
  */
 public function findFinishedForPidRaw($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching(
				$query->logicalAnd(
                    $query->greaterThan('finished', 0)
                    , $query->equals('pid', $pid)
                ))->execute();
                return $query->execute(true);
	}
	
	/**
  * find all finished results for pid
  *
  * @param integer $pid
  * @param integer $interval
  * @param integer $position
  * @return QueryResultInterface All finished results
  */
 public function findFinishedForPidInterval($pid, $interval, $position) {
		$interval = intval($interval);
		$position = intval($position);
		if ($interval == 0) $interval = 1;
		//\TYPO3\CMS\Core\Utility\GeneralUtility::devlog('arguments','keq',2,array($pid, $interval, $position));
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->setLimit($interval);
		$query->setOffset($position);
		return $query->matching(
				$query->logicalAnd(
                    $query->greaterThan('finished', 0)
                    , $query->equals('pid', $pid)
                ))->execute();
	}
        
        /**
  * find all finished results for pid
  *
  * @param integer $pid
  * @param integer $interval
  * @param integer $position
  * @return QueryResultInterface All finished results
  */
 public function findFinishedForPidIntervalRaw($pid, $interval, $position) {
		$interval = intval($interval);
		$position = intval($position);
		if ($interval == 0) $interval = 1;
		//\TYPO3\CMS\Core\Utility\GeneralUtility::devlog('arguments','keq',2,array($pid, $interval, $position));
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->setLimit($interval);
		$query->setOffset($position);
		return $query->matching(
				$query->logicalAnd(
                    $query->greaterThan('finished', 0)
                    , $query->equals('pid', $pid)
                ))->execute(true);
	}
    
    /**
  * find all finished results for pid
  *
  * @param integer $pid
  * @return QueryResultInterface All finished results
  */
 public function countFinishedForPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->matching(
				$query->logicalAnd(
                    $query->greaterThan('finished', 0)
                    , $query->equals('pid', $pid)
                ))->count();
	}        
    
    
    /**
  * find all finished results for pid
  *
  * @param integer $pid
  * @return QueryResultInterface All finished results
  */
 public function countConnectedForPid($pid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->matching(
				$query->logicalAnd(
                    $query->logicalOr(
                        $query->greaterThan('fe_user', 0)
                        , $query->greaterThan('auth_code',0)
                    )
                    , $query->equals('pid', $pid)
                ))->count();
	}
    
    
    /**
  * find all finished results for pid
  *
  * @param integer $user_id
  * @param integer $pid
  * @return QueryResultInterface All finished results
  */
 public function findByFeUserAndPid($userId,$pid) {
        $query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
        $constraint = $query->logicalAnd(
            $query->greaterThan('finished', 0)
            , $query->equals('pid', $pid));
        $constraint = $query->logicalAnd(
            $query->equals('fe_user',$userId), $constraint);
		return $query->matching(...$constraint)->execute();
	}
	
	/**
  * find all results for pid
  *
  * @param AuthCode $authCode
  * @return Query Result
  */
 public function findForAuthCode(AuthCode $authCode) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching($query->equals('auth_code', $authCode->getUid()));
		return $query->execute();
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function clearRATable(): void {
        $query = $this->createQuery();
        $query->statement("DELETE from tx_kequestionnaire_domain_model_resultanswer WHERE resultquestion = 0 ")->execute() ;
	}
	
	/**
	 * 
	 * @param integer $resultId
	 */
	public function collectRAnswersForCSVRBExport($resultId){
		$query = $this->createQuery();
		$query->statement('SELECT tx_kequestionnaire_domain_model_resultquestion.uid as rq_uid, tx_kequestionnaire_domain_model_resultanswer.uid as ra_uid,
tx_kequestionnaire_domain_model_resultanswer.value as ra_value,
tx_kequestionnaire_domain_model_resultanswer.additional_value as ra_add_value,
(SELECT tx_kequestionnaire_domain_model_question.uid FROM tx_kequestionnaire_domain_model_question WHERE tx_kequestionnaire_domain_model_resultquestion.question = tx_kequestionnaire_domain_model_question.uid) as q_uid,
(SELECT tx_kequestionnaire_domain_model_answer.uid FROM tx_kequestionnaire_domain_model_answer WHERE tx_kequestionnaire_domain_model_resultanswer.answer = tx_kequestionnaire_domain_model_answer.uid) as a_uid
FROM 
tx_kequestionnaire_domain_model_resultanswer 
Left join
tx_kequestionnaire_domain_model_resultquestion on
tx_kequestionnaire_domain_model_resultanswer.resultquestion = tx_kequestionnaire_domain_model_resultquestion.uid
where 
tx_kequestionnaire_domain_model_resultquestion.result ='.$resultId);
		return $query->execute(true);
	}
}
?>