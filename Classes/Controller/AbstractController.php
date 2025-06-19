<?php
namespace Kennziffer\KeQuestionnaire\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire;
use Kennziffer\KeQuestionnaire\Domain\Model\ExtConf;
use Kennziffer\KeQuestionnaire\Utility\Localization;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3Fluid\Fluid\View\ViewInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
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
 protected $resultRepository;

	/**
  * questionRepository
  *
  * @var QuestionRepository
  */
 var $questionRepository;
    
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
 var $authCodeRepository;

	/**
  * questionnaire
  *
  * @var Questionnaire
  */
 var $questionnaire;

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


	/**
  * injectResultRepository
  *
  * @param ResultRepository $resultRepository
  * @return void
  */
 public function injectResultRepository(ResultRepository $resultRepository) {
		$this->resultRepository = $resultRepository;
	}

	/**
  * injectQuestionRepository
  *
  * @param QuestionRepository $questionRepository
  * @return void
  */
 public function injectQuestionRepository(QuestionRepository $questionRepository) {
		$this->questionRepository = $questionRepository;
	}
	
	/**
  * injectAuthCodeRepository
  *
  * @param AuthCodeRepository $authCodeRepository
  * @return void
  */
 public function injectAuthCodeRepository(AuthCodeRepository $authCodeRepository) {
		$this->authCodeRepository = $authCodeRepository;
	}

	/**
  * injectQuestionnaire
  *
  * @param Questionnaire $questionnaire
  * @return void
  */
 public function injectQuestionnaire(Questionnaire $questionnaire) {
		$this->questionnaire = $questionnaire;
	}
    
    /**
  * injectQuestionnaireRepository
  *
  * @param QuestionnaireRepository $questionnaireRepository
  * @return void
  */
 public function injectQuestionnaireRepository(QuestionnaireRepository $questionnaireRepository) {
		$this->questionnaireRepository = $questionnaireRepository;
	}

	/**
  * inject extConf
  *
  * @param ExtConf $extConf
  * @return void
  */
 public function injectExtConf(ExtConf $extConf) {
		$this->extConf = $extConf;
	}


	/**
  * inject localization
  *
  * @param Localization $extConf
  */
 public function injectLocalization(Localization $localization) {
		$this->localization = $localization;
	}

	/**
  * inject steps
  *
  * @param ObjectStorage $steps
  * @return void
  */
 public function injectSteps(ObjectStorage $steps) {
			$this->steps = $steps;
	}


	/**
	 * initializes the actions
	 */
	public function initializeAction() {
		parent::initializeAction();
		if (!is_object($this->questionnaireRepository)) {
            $this->questionnaireRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\QuestionnaireRepository');
        }

		// initialize steps
        // todo V12
		if ($this->steps AND $this->steps->count() == 0  ) {
			if (is_array($this->settings['--No-working-steps--not-working']) && count($this->settings['steps'])) {
				/* @var $dataMapper \Kennziffer\KeQuestionnaire\Object\DataMapper */
				$dataMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Object\DataMapper');
				$steps = $dataMapper->map('Kennziffer\KeQuestionnaire\Domain\Model\Step', $this->settings['steps']);
				foreach ($steps as $step) {
					$this->steps->attach($step);
				}
			}
		}
	}

	/**
  * initializes the view with additional placeholders/markers
  *
  * @param ViewInterface $view
  * @return void
  */
 public function initializeView(ViewInterface $view) {
		if($this->extConf->getEnableFeUserMarker() && is_array($this->user)) {
			$view->assign('feUser', $this->user);
		}
	}

	/**
	 * Override getErrorFlashMessage to present
	 * nice flash error messages.
	 *
	 * @return string
	 */
	protected function getErrorFlashMessage() {
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
	public function addNewFlashMessage($action, $severity = AbstractMessage::OK) {
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
		$this->$method($nextStep->getAction(), $nextStep->getController(), $nextStep->getExtension(), array('result' => $result));
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