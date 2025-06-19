<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
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
class AuthCode extends AbstractEntity {

	/**
	 * AuthCode
	 *
	 * @var string
	 */
	protected $authCode;
	
	/**
	 * EMail
	 *
	 * @var string
	 */
	protected $email;
	
	/**
  * Participations
  *
  * @var ObjectStorage<Result>
  */
 #[Lazy]
 protected $participations;
        
        /**
  *
  * @var int|null
  */
 protected $feUser;
        
  /**
  * TtAddress
  * @var FriendsOfTYPO3\TtAddress\Domain\Model\Address|null
  */
 protected $ttAddress;
        
        /**
	 * lastreminder
	 *
	 * @var integer
	 */
	protected $lastreminder;
        
        /**
	 * firstactive
	 *
	 * @var integer
	 */
	protected $firstactive;
        
        /**
	 * crdate
	 *
	 * @var integer
	 */
	protected $crdate;
        

	/**
	 * Returns the authCode
	 *
	 * @return string $authCode
	 */
	public function getAuthCode() {
		return $this->authCode;
	}

	/**
	 * Sets the authCode
	 *
	 * @param string $authCode
	 * @return void
	 */
	public function setAuthCode($authCode): void {
		$this->authCode = $authCode;
	}
	
	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email): void {
		$this->email = $email;
	}
	
	/**
	 * generate a single and unique AuthCode
	 * 
	 * @param integer $length
	 * @param integer §pid
	 */
	public function generateAuthCode($length, $pid){
        $length = ( $length ?? 10 );
		//get the existent authcodes so no duplicates are created
		$ac_rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\AuthCodeRepository');
		// Generate authcode
		$loop = 1;
		while($loop){
			$key = '';
			list($usec, $sec) = explode(' ', microtime());
			mt_srand((float) $sec + ((float) $usec * 100000));

            $inputs = array_merge(
                range('z','p'),
                range('h','a'),

                range(2,9),
                range('A','H'),
                range('P','Z'),
                array("m" , "M" , "n" , "N" , "k" , "K" , "j" , "J")
            );

            for($i=0; $i<$length; $i++)
            {
                $key .= $inputs[mt_rand(0,count($inputs)-1)];
                if( round(($i+1)/($length/2),0) == ($i+1)/($length/2) ) {
                    $key .= "-" ;
                }
            }
            $key = rtrim($key , "-") ;

			$existent = $ac_rep->findByAuthCodeForPid($key,$pid);
			if ($existent) $loop = 0;
		}
		$this->setAuthCode($key);
		return $key;
	}
	
	/**
  * Returns the results
  *
  * @return ObjectStorage $results
  */
 public function getParticipations() {
		return $this->participations;
	}
        
    /**
	 * Loads the results of this authCode for the be-module
	 */
	public function getAndLoadParticipations() {
            if (!$this->participations ){
                    $rep = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\ResultRepository');
                    $this->participations = $rep->findForAuthCode($this);
            }
            return $this->participations;
	}
        
        /**
  * Setter for feUser
  *
  * @param int|null $feUser feUser
  * @return void
  */
 public function setFeUser(?int $feUser): void {
     if(is_array($feUser)) {
         $feUser = ($feUser['uid'] ?? null);
     } else  {
        $feUser = null;
     }
	}

	/**
  * Getter for feUser
  *
  * @return int|null feUser
  */
 public function getFeUser(): ?int
 {
		return $this->feUser;
	}
        
  /**
  * Setter for ttAddress
  *
  * @return void
  */
 public function setTtAddress(  $ttAddress): void {
		$this->ttAddress = $ttAddress;
	}

	/**
  * Getter for ttAddress
  *
  */
 public function getTtAddress()
 {
		return $this->ttAddress;
	}
        
        /**
	 * Returns the lastreminder
	 *
	 * @return integer $lastreminder
	 */
	public function getLastreminder() {
		return $this->lastreminder;
	}
        
        /**
	 * Returns the lastreminder
	 *
	 * @return string $lastreminder
	 */
	public function getLastreminderDatestring() {
		return date('d.m.Y H:i',$this->lastreminder);
	}

	/**
	 * Sets the lastreminder
	 *
	 * @param integer $lastreminder
	 * @return void
	 */
	public function setLastreminder($lastreminder): void {
		$this->lastreminder = $lastreminder;
	}
        
        /**
	 * Returns the firstactive
	 *
	 * @return integer $firstactive
	 */
	public function getFirstactive() {
		return $this->firstactive;
	}
        
        /**
	 * Returns the firstactive
	 *
	 * @return string $firstactive
	 */
	public function getFirstactiveDatestring() {
		return date('d.m.Y H:i',$this->firstactive);
	}

	/**
	 * Sets the firstactive
	 *
	 * @param integer $firstactive
	 * @return void
	 */
	public function setFirstactive($firstactive): void {
		$this->firstactive = $firstactive;
	}
        
        
        /**
	 * Returns the crdate
	 *
	 * @return integer $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}
        
        /**
	 * Returns the crdate
	 *
	 * @return string $crdate
	 */
	public function getCrdateDatestring() {
		return date('d.m.Y H:i',$this->crdate);
	}

	/**
	 * Sets the crdate
	 *
	 * @param integer $crdate
	 * @return void
	 */
	public function setCrdate($crdate): void {
		$this->crdate = $crdate;
	}
}
?>