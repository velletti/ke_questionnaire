<?php
namespace Kennziffer\KeQuestionnaire\Controller;

use Psr\Http\Message\ResponseInterface;
use Kennziffer\KeQuestionnaire\Validation\Email;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Utility\Mail;
use Kennziffer\KeQuestionnaire\ValidationAbstractValidation;
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
class QuestionnaireController extends ActionController {
    /**
	 * The current view, as resolved by resolveView()
	 *
	 * @var ViewInterface
	 * @api
	 */
	public $view = NULL;
        
    /**
  * questionnaireRepository
  *
  * @var QuestionnaireRepository
  */
 protected $questionnaireRepository;
	
	/**
  * authCodeRepository
  *
  * @var AuthCodeRepository
  */
 protected $authCodeRepository;
	
	/**
  * resultRepository
  *
  * @var ResultRepository
  */
 protected $resultRepository;
	
	/**
  * @var Mail
  */
 protected $mailSender;
 public function __construct(QuestionnaireRepository $questionnaireRepository, AuthCodeRepository $authCodeRepository, ResultRepository $resultRepository, Mail $mailSender)
 {
     $this->questionnaireRepository = $questionnaireRepository;
     $this->authCodeRepository = $authCodeRepository;
     $this->resultRepository = $resultRepository;
     $this->mailSender = $mailSender;
 }

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction(): ResponseInterface {		
            $this->view->assign('questionnaires',$this->getQuestionnaires());
            return $this->htmlResponse();
	}
	
	/**
	 * action reclaimAuthcode
	 *
	 * @return void
	 */
	public function reclaimAuthCodeAction(): ResponseInterface
 {
     return $this->htmlResponse();
 }
	
	/**
	 * Checks if the value is valid email
	 *
	 * @param string $value value
	 * @return boolean
	 */
	public function isValidEmail($value){
		$class = Email::class;
		if (class_exists($class)) {
			$validator = GeneralUtility::makeinstance($class);
			if ($validator instanceof ValidationAbstractValidation) {
				/* @var $validator \Kennziffer\KeQuestionnaire\ValidationAbstractValidation */
				return $validator->isValid($value, $this);
			} else {
                return false;
            }
		} else {
            return false;
        }
	}
    
    
    /**
     * get the selected Questionnaires
     * 
     * @return array
     */
    private function getQuestionnaires(){
        if ($this->settings['questionnaires']) {
            $questionnaires = $this->questionnaireRepository->findForUids($this->settings['questionnaires']);
        } else {
            $questionnaires = $this->questionnaireRepository->findAll();
        }
		
		$list = [];
		if ($this->request->getAttribute('frontend.user')) {
            $user_id = $this->request->getAttribute('frontend.user')->user['uid'];
        }
		switch ($this->settings['listType']){
			case 'all':
					$list = $questionnaires;
				break;
			case 'used':
					if ($user_id){
						foreach ($questionnaires as $questionnaire){
							if (count($questionnaire->getUserResults($user_id)) > 0){
								$list[] = $questionnaire;
							}
						}
					}
				break;
			case 'unused':
					if ($user_id){
						foreach ($questionnaires as $questionnaire){
							if (count($questionnaire->getUserResults($user_id)) === 0){
								$list[] = $questionnaire;
							}
						}
					}
				break;
		}
		
        return $list;
    }
    
    /**
	 * Create getSettings
	 *
	 * @return array
	 */
	public function getSettings() {
		return $this->settings;
	}

}