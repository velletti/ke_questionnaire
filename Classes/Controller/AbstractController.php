<?php
namespace Kennziffer\KeQuestionnaire\Controller;

use Kennziffer\KeQuestionnaire\Object\DataMapper;
use Kennziffer\KeQuestionnaire\Domain\Model\Step;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire;
use Kennziffer\KeQuestionnaire\Domain\Model\ExtConf;
use Kennziffer\KeQuestionnaire\Utility\Localization;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AbstractController extends ActionController {

	/**
  * resultRepository
  *
  * @var ResultRepository
  */
 public $resultRepository;

	/**
  * questionRepository
  *
  * @var QuestionRepository
  */
 public $questionRepository;
    
    /**
  * questionnaireRepository
  *
  * @var QuestionnaireRepository
  */
    public $questionnaireRepository;
	
	/**
  * authCodeRepository
  *
  * @var AuthCodeRepository
  */
 public $authCodeRepository;

	/**
  * questionnaire
  *
  * @var Questionnaire
  */
 public $questionnaire;

	/**
  * @var ExtConf
  */
 protected $extConf;

	/**
  * @var Localization
  */
 protected $localization;

	/**
  * @var ObjectStorage
  */
 protected $steps;
 public function __construct(ResultRepository $resultRepository, 
                             QuestionRepository $questionRepository, 
                             AuthCodeRepository $authCodeRepository, 
                             Questionnaire $questionnaire, 
                             QuestionnaireRepository $questionnaireRepository, 
                             ExtConf $extConf, 
                             Localization $localization, 
                             ObjectStorage $steps
 )
 {
     $this->resultRepository = $resultRepository;
     $this->questionRepository = $questionRepository;
     $this->authCodeRepository = $authCodeRepository;
     $this->questionnaire = $questionnaire;
     $this->questionnaireRepository = $questionnaireRepository;
     $this->extConf = $extConf;
     $this->localization = $localization;
     $this->steps = $steps;
 }


	/**
	 * initializes the actions
	 */
	public function initializeAction(): void {
		parent::initializeAction();
		if (!is_object($this->questionnaireRepository)) {
            $this->questionnaireRepository = GeneralUtility::makeinstance(QuestionnaireRepository::class);
        }
        if (!is_object($this->authCodeRepository)) {
            $this->authCodeRepository = GeneralUtility::makeinstance(AuthCodeRepository::class);
        }

        if (!$this->questionnaire) {
            $pageArguments = $this->request->getAttribute('routing');
            $this->questionnaire = $this->questionnaireRepository->findForPid( $pageArguments ? $pageArguments->getPageId() : 0) ;
        }

		// initialize steps
        // todo V12
		if (($this->steps and $this->steps->count() == 0) && (is_array($this->settings['--No-working-steps--not-working']) && count($this->settings['steps']))) {
            /* @var $dataMapper \Kennziffer\KeQuestionnaire\Object\DataMapper */
            $dataMapper = GeneralUtility::makeinstance(DataMapper::class);
            $steps = $dataMapper->map(Step::class, $this->settings['steps']);
            foreach ($steps as $step) {
					$this->steps->attach($step);
				}
        }
       
	}


	/**
	 * Override getErrorFlashMessage to present
	 * nice flash error messages.
	 *
	 * @return string
	 */
	#[\Override]
    protected function getErrorFlashMessage(): bool|string
    {
        $defaultFlashMessage = parent::getErrorFlashMessage();
		$locallangKey = sprintf('error.%s.%s', $this->request->getControllerName(), $this->actionMethodName);
                
		return $this->translate($locallangKey, $defaultFlashMessage);
	}

	/**
	 * helper function to render localized flashmessages
	 *
	 * @param string $action
	 * @param integer $severity optional severity code. One of the \TYPO3\CMS\Core\Messaging\FlashMessage constants
	 * @return void
	 */
	public function addNewFlashMessage($action, $severity = ContextualFeedbackSeverity::OK): void {
		$messageLocallangKey = sprintf('flashmessage.%s.%s', $this->request->getControllerName(), $action);
		$localizedMessage = $this->translate($messageLocallangKey, '');
		$titleLocallangKey = sprintf('%s.title', $messageLocallangKey);
		$localizedTitle = $this->translate($titleLocallangKey, '');

        $this->addFlashMessage($localizedMessage, $localizedTitle, $severity)  ;
	}

	/**
	 * helper function to use localized strings in BlogExample controllers
	 *
	 * @param string $key locallang key
	 * @param string $default the default message to show if key was not found
	 * @return string
	 */
	protected function translate($key, $defaultMessage = '') {
		$message = $this->localization->translate($key);
		if ($message === NULL) {
			$message = $defaultMessage;
		}
		return $message;
	}

	/**
  * calls the next step as defined in TS (plugin.kequestionnaire.steps)
  * Sample:
  * - open questionnaire
  * - logging
  * - mailing
  * - Evaluation
  *
  * @param Result $result
  */
 protected function nextStep(Result $result) {
		// get current environment vars
		$action = $this->request->getControllerActionName();
		$controller = $this->request->getControllerName();
		$extension = $this->request->getControllerExtensionName();

		// search for current step in $this->steps
		/* @var $step \Kennziffer\KeQuestionnaire\Domain\Model\Step */
		foreach ($this->steps as $key => $step) {
			if ($step->getAction() == $action && $step->getController() == $controller && $step->getExtension() == $extension) {
				$this->steps->next();
				$nextStep = $this->steps->current();
				break;
			}
		}

		$method = $nextStep->getType();
		$this->$method($nextStep->getAction(), $nextStep->getController(), $nextStep->getExtension(), ['result' => $result]);
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
?>