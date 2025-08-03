<?php
namespace Kennziffer\KeQuestionnaire\Controller;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;

use Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer;
use Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire;
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
class ResultController extends AbstractController {
	
	/**
	 * The current view, as resolved by resolveView()
	 *
	 * @var ViewInterface
	 * @api
	 */
	var $view = NULL;

    /**
     * @var Result
     */
	var $result ;
    /**
     * @var int
     */
	var $userUid = 0 ;
	
	public function __construct( \Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository $questionnaireRepository)
 {
     $this->questionnaireRepository = $questionnaireRepository;
     $this->resultRepository = GeneralUtility::makeInstance(ResultRepository::class);
     $this->questionRepository = GeneralUtility::makeInstance(QuestionRepository::class);
 }

	/**
	 * initializes the actions
	 */
	public function initializeAction(): void {
		parent::initializeAction();
        if ($this->questionnaire) {
            $this->questionnaire->settings = $this->settings;
        }


		//check a logged in user
        $this->user = 0 ;
        $this->userUid = 0 ;
        if ($this->request->getAttribute('frontend.user')) {
            $this->user = $this->request->getAttribute('frontend.user');
            if ($this->user && $this->user->user) {

                $this->userUid = $this->user->user['uid'];
            }
        }

		//check jsKey for the created js-file
		$jsKey = substr($this->userUid,0,10).'_keqjs';
		$GLOBALS['TSFE']->fe_user->setKey('ses','keq_jskey',$jsKey);
		//maybe better to erase the js file every time
		$pathname = 'typo3temp/ke_questionnaire';
		$filename = $pathname.'/'.$jsKey.'.js';
		if (file_exists(Environment::getPublicPath() . '/'.$filename)) unlink($filename);
	}

    /**
  * Displays a form for saving a new questionnaire
  *
  * @param Result $newResult A fresh new result object
  * @param integer $requestedPage after checking the questions of currentPage redirect to this page
  *
  * @throws InvalidSlotException
  * @throws InvalidSlotReturnException
  * @return void
  */
 #[IgnoreValidation([])]
 public function newAction(Result $newResult = NULL, $requestedPage = 0) {
		if ($newResult == NULL) { // workaround for fluid bug ##5636
		    /** @var Result $newResult */
			$newResult = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Domain\Model\Result');
		}
        $newResult->setFeCruserId($this->userUid);
		if ($this->user) {
		    $newResult->setFeUser($this->user);

        }
		//check for the Access-Type
		switch ($this->settings['accessType']){
            case 'feUser':
                if (!$this->userUid > 0 ){
                    return $this->redirect('feUserAccess');
                }
                break;
            case 'authCode':
                //check if the authCode is valid
                if( $newResult->getAuthCode() && $newResult->getAuthCode()->getUid() > 0 ) {
                    $this->authCode = $newResult->getAuthCode();
                } else {
                    $this->authCode = null;
                    if ( !$this->checkAuthCode()) {
                        return $this->redirect('authCodeAccess');
                    }
                    $newResult->setAuthCode($this->authCode);
                }

                break;
		}
		//check questionnaire-dependancy
        //  todo V12
		$this->checkDependancy();
		//check for restart
		// $newResult = $this->checkRestart($newResult);
		//check if the max participations are reached
		if( !$this->checkMaxParticipations() ) {
            return $this->redirect('maxParticipations');
        }

		//get the correct requested start-page
		if ($requestedPage == 0 && !$this->settings['description']) $requestedPage = 1;

		//set the requested page
		$this->questionnaire->setPage($requestedPage);
		//get the questions and add them to the questionnaire
		$questions = $this->questionRepository->findAll();
		$this->questionnaire->setQuestions($questions);

        $this->signalResult = $newResult;
		// $this->signalSlotDispatcher->dispatch(__CLASS__, 'newAction', array($this));
        $newResult = $this->signalResult;



        if(  $this->signalResult->getCrdate() == 0 ) {
            $this->signalResult->setCrdate(time() ) ;
        }

        if(  $this->questionnaire->settings['startTime'] == 0 ) {
            $this->questionnaire->settings['startTime'] = time()  ;
        }
        $this->settings['startTime2'] = time()  ;
        if( $this->settings['randomQuestionsMax'] > 0 ) {
            $this->shuffleQuestions() ;
        }
        if( $newResult->getQuestions()  && count( $newResult->getQuestions()) < 1  && $requestedPage > 0 ) {
            $pages = $this->questionnaire->getCountPages() ;
            for ( $page=1 ; $page<= $pages ; $page++ ) {
                $questions = $this->questionnaire->getQuestionsForPage($page) ;
                if( $questions) {
                    foreach ($questions as $question) {
                        /** @var ResultQuestion $resultQuestion */
                        $resultQuestion = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion');
                        $resultQuestion->setQuestion($question);
                        $resultQuestion->setPage($page);
                        $newResult->addQuestion($resultQuestion);
                    }
                }

            }

            // Propertimapping in LTS 9 is not Working correctly with old Model. AND it is needed again to be changed in LTS 10
            $this->resultRepository->add($newResult) ;
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
            $persistenceManager->persistAll();

        }


		$this->view->assign('questions', $this->questionnaire->getQuestionsForPage($requestedPage));
		$this->view->assign('questionnaire', $this->questionnaire);
		$this->view->assign('newResult', $newResult);
        return $this->htmlResponse();

    }

	

	/**
	 * set StoragePid
	 *
	 * @param $storagePid
	 * @return void
	 */
	public function setStoragePid($storagePid): void {
		$configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		//fallback to current pid if no storagePid is defined
        $configuration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$currentPid['persistence']['storagePid'] = $storagePid;
		$configurationManager->setConfiguration(array_merge($configuration, $currentPid));
	}


	/**
	 * initializes the createAction
	 */
	public function initializeCreateAction(): void {
		//to get correct updates it is needed that a clone of the actual result is created
		//the temp_result stores the current newResult-data
        if ( $this->request->hasArgument("newResult") ) {
            $this->temp_result = $this->request->getArgument('newResult');
        }
        // $this->signalSlotDispatcher->dispatch(__CLASS__, 'initializeCreateAction', array($this, $this->arguments));
		//check for autoSave
		//Premium function needs to be checked here anyway. The autosave Action is part of the premium
        if ($this->settings['ajaxAutoSave'] == 1 && $this->userUid > 0){
			if ($this->temp_result['__identity'] > 0){
				$result = $this->resultRepository->findByUid($this->temp_result['__identity']);	
				$this->request->setArgument('newResult',$result);
			}
		}
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');


        //if the result is not new create the clone of the stored before it is updated
		//if the old-result is not stored, given answers of other pages will be deleted
        if( is_array( $this->temp_result )) {
            /** @var \Kennziffer\KeQuestionnaire\Domain\Repository\AnswerRepository $answerRepository */
            /** @var ResultAnswerRepository $resultAnswerRepository */
            $answerRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Domain\Repository\AnswerRepository');
            $resultAnswerRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository');
            if (array_key_exists('__identity' , $this->temp_result ) && $this->temp_result['__identity'] > 0) {

                $debug = [] ;
                /** @var  $result Result */
                $result = $this->resultRepository->findByUid($this->temp_result['__identity']);
                if ($result) {
                    $this->oldResult = clone $result;
                }
                if (array_key_exists('questions' , $this->temp_result ) && count( $this->temp_result['questions']) > 0)  {
                    if( $result->getQuestions() ) {
                        /** @var ResultQuestion $resultQuestion */
                        foreach ($result->getQuestions()  as $resultQuestion) {
                            if( $this->temp_result['questions'] ) {

                                foreach ( $this->temp_result['questions'] as $formquestion ) {

                                    if($formquestion['question'] == $resultQuestion->getQuestion()->getUid() ) {
                                        // first remove all old answers for this Question!
                                        $resultQuestion->removeAnswers();


                                        $resultQuestion->setPoints(0);
                                        $resultQuestion->setMaxPoints(0);

                                        $persistenceManager->persistAll();

                                        $debug[] = var_export( $formquestion , true );

                                        foreach( $formquestion['answers'] as $formAnswer) {
                                            $isAnswered = false ;
                                             if( $formAnswer['value'] || $formAnswer['answer'] ) {
                                                $answer = $answerRepository->findByUidFree( intval($formAnswer['answer'] )) ;
                                                /** @var ResultAnswer $resultAnswer */
                                                $resultAnswer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer');
                                                $resultAnswer->setPid($result->getPid()) ;
                                                $resultAnswer->setResultquestion($resultQuestion);
                                                $resultAnswer->setFeCruserId(   $this->userUid );
                                                if( $formAnswer['additional_value'] ) {
                                                    $resultAnswer->setAdditionalValue($formAnswer['additional_value']);
                                                } else {
                                                    $resultAnswer->setAdditionalValue('');
                                                }

                                                if( $answer->getType() == "Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton") {
                                                    $resultAnswer->setValue( $formAnswer['answer']);
                                                    if ( $formAnswer['answer'] ) {
                                                        $isAnswered = true ;
                                                    }
                                                } else {
                                                    $resultAnswer->setValue( $formAnswer['value']);
                                                    if ( $formAnswer['value'] ) {
                                                        $isAnswered = true ;
                                                    }
                                                }

                                                $oldResultAnswer = $resultAnswerRepository->findForResultQuestionAndAnswerRaw(
                                                    $resultQuestion->getUid() ,
                                                    $answer->getUid() ,
                                                false ) ;
                                                if( $oldResultAnswer && $oldResultAnswer->getFirst() instanceof  ResultAnswer) {
                                                    $resultAnswerRepository->remove($oldResultAnswer->getFirst() ) ;
                                                }
                                                 $resultAnswer->setAnswer($answer);
                                                 if( $isAnswered ) {
                                                    $resultQuestion->addAnswer($resultAnswer);
                                                }
                                            }

                                        }

                                    }

                                }
                            }
                        }
                    }
                }
                if ($result) {
                    $this->resultRepository->update($result) ;
                    $persistenceManager->persistAll();
                }

            }
        }
        $this->newResult =  $result ;
	}


    /**
  * Creates a new questionnaire
  *
  * @param integer $currentPage check, validate and save the results of this page
  * @param integer $requestedPage after checking the questions of currentPage redirect to this page
  * @return \Psr\Http\Message\ResponseInterface
  * @throws StopActionException
  * @throws UnsupportedRequestTypeException
  * @throws IllegalObjectTypeException
  * @throws UnknownObjectException
  * @throws InvalidSlotException
  * @throws InvalidSlotReturnException
  */
 #[IgnoreValidation([])]
 public function createAction( $currentPage, $requestedPage) {
	    /** @var Result $newResult */
	    $newResult = $this->newResult ;
                //requestedPage => next Page to be shown
		$this->questionnaire->setRequestedPage($requestedPage);
		//currentPage => current Page, just send to this action
		$this->questionnaire->setPage($currentPage);
		//get all questions for this questionnaire
		$this->questionnaire->setQuestions($this->questionRepository->findAll());
		if ($this->user) $newResult->setFeUser($this->user);
        $newResult->setFeCruserId($this->userUid);
		//check for the Access-Type
		switch ($this->settings['accessType']){
			case 'feUser':
                if (!$this->user){
                    return $this->redirect('feUserAccess');
                }
				break;
			case 'authCode':
                //check if the authCode is valid
                if ( !$this->checkAuthCode()) {
                    return $this->redirect('authCodeAccess');
                }
                $newResult->setAuthCode($this->authCode);
				break;
		}
        //validate the result
		$isValid = $this->validateResult($newResult, $currentPage);
        if ($isValid !== 'valid') {
            return $this->moveToAction('new', $newResult, $currentPage , $isValid);
        }

        //rework the result so all given answers to all questions (not only current page) are stored
		$result = $this->getSavedAndMergedResult($newResult);
		//calculate the points of the questions and the result
		$debug = $result->calculatePoints($this->settings['reducePointsforWrongAnswers']);

		$this->resultRepository->update($result);


		$persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
		
		$this->resultRepository->clearRATable();
		
		// check for last page
		if($currentPage == $requestedPage) {
			//if there is a redirect page, redirect
			//else forward to endAction
			if ($this->settings['redirectFinished']){
				$this->uriBuilder->setTargetPageUid($this->settings['redirectFinished']);   
				$link = $this->uriBuilder->build();
				return $this->redirectToUri($link);
			} else {
				/* Problems with forward and RealUrl
				 $this->forward('end', NULL, NULL, array(
					'result' => $newResult
				));*/

				// JVE - Jörg velletti Nov 2016 Changed hardCoded Template
				$TemplateRootPaths = $this->view->getTemplateRootPaths() ;
				foreach ($TemplateRootPaths as $templatePath ) {
					$tempTemplatePathAndFilename = $templatePath . '/Result/End.html' ;
					if (is_file($tempTemplatePathAndFilename)) {
                        $templatePathAndFilename = $tempTemplatePathAndFilename ;
					}
				}

				$this->view->setTemplatePathAndFilename($templatePathAndFilename );
				// JVE - Jörg velletti Nov 2016 Changed hardCoded Template

				$this->view->assign('result', $newResult);
				$this->view->assign('questionnaire', $this->questionnaire);
				$temp = false;
				// $this->signalSlotDispatcher->dispatch(__CLASS__, 'endAction', array($newResult, $this, &$temp));
				if ($temp) return $this->redirectToUri ($temp);
			}
		//if not last page, set all stuff for questionnaire-page
		} else {            
			//set the next page
			$this->questionnaire->setPage($requestedPage);
			//get questions
			$questions = $this->questionRepository->findAll();
			$this->questionnaire->setQuestions($questions);

            if( is_object($this->signalResult)) {
                if(  $this->signalResult->getCrdate() == 0 ) {
                    $this->signalResult->setCrdate(time() ) ;
                }
            }

            if(  $this->questionnaire->settings['startTime'] == 0 ) {
                $this->questionnaire->settings['startTime'] = time()  ;
            }

			//$this->signalSlotDispatcher->dispatch(__CLASS__, 'createAction', array($this));

			$this->view->assign('questions', $newResult->getQuestionsForPage($requestedPage));
			$this->view->assign('questionnaire', $this->questionnaire);
			$this->view->assign('newResult', $newResult);
		}
        return $this->htmlResponse();
	}

	/**
	 * Shows an chart => needs to be checked if really needed
	 *
	 * @param Result $result
	 * @return void
	 */
	public function showAction(Result $result = NULL): \Psr\Http\Message\ResponseInterface {
		if (!$result) {
			$this->addFlashMessage(LocalizationUtility::translate('feView.noResultError' ), LocalizationUtility::translate('feView.noResultErrorTitle' ), \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
		} else {
			$questionnaire = $this->questionnaireRepository->findByStoragePid($result->getPid());
			$this->view->assign('questionnaire', $questionnaire[0]);
			$this->view->assign('result', $result);
		}
        return $this->htmlResponse();
	}

	/**
	 * validate the result object
	 * check for mandatory and correct answers
	 *
	 * @param Result $result
	 * @param integer $requestedPage after checking the questions redirect to this page
	 * @return void
	 */
	public function validateResult(Result $result, $requestedPage = 1) {
		/* @var  \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $resultQuestion  */
		//check for every question in result
        $debug[] = 'validateResult: ' . $result->getUid() . ' - Page:' . $requestedPage ;
		foreach ($result->getQuestions() as $resultQuestion) {
            $debug[] = ' Question: ' . $resultQuestion->getQuestion()->getUid() .  ' - ' . $resultQuestion->getQuestion()->getTitle() . ' - Page:' . $resultQuestion->getPage() ;

            if ($resultQuestion->getQuestion() && $resultQuestion->getPage() == ($requestedPage) ) {
                // check if the question has to be answered correctly
                if ($resultQuestion->getQuestion()->getMustBeCorrect()) {
                    if (!$resultQuestion->isAnsweredCorrectly()) {
                        return     'mustBeCorrect' ;
                    }
                }

                // check if question is mandatory
                if ($resultQuestion->getQuestion()->getIsMandatory() && !$resultQuestion->getQuestion()->IsDependant()) {
                    if (!$resultQuestion->isAnswered()) {

                        return  'mandatory' ;
                    }
                }
            }
		}
		$result->setFeCruserId($this->userUid);
		if ($this->user) {
            $result->setFeUser($this->user);
        }
        return 'valid' ;
	}
    
	/**
	 * move to given action
	 *
	 * @param string $action Action to call
	 * @param Result $result A fresh Questionnaire object
	 * @param integer $page needed for page navigation
	 * @param string $flashMessage Key to show a flashMessage if needed
	 * @return void
	 */
	public function moveToAction($action , Result $result, $page = 1, $flashMessage = '') {
		if(!empty($flashMessage)) $this->addNewFlashMessage($flashMessage , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
		return $this->redirect(($action ?? 'new' ), NULL, NULL, array(
			'newResult' => $result,
			'requestedPage' => $page
		));
	}

	/**
	 * get the created or merged result object
	 * we have to merge them because $newResult contains the answered questions of the last page only.
	 * All other answered questions come from DB
	 *
	 * @param Result $newResult
	 * @return Result The modified result object
	 */
	public function getSavedAndMergedResult(Result $newResult) {
		$uid = $newResult->getUid();
		if ($uid === NULL) {
			$result = $this->addResult($newResult);
		} else {
			$result = $this->updateResult($newResult);			
		}
		// $this->signalSlotDispatcher->dispatch(__CLASS__, 'getSavedAndMergedResult', array($result, $this));
		return $result;
	}

	/**
	 * add the result to the DB
	 *
	 * @param Result $formResult This is the result from the form. NOT DB!
	 * @return Result The added result object
	 */
	public function addResult(Result $formResult) {
		$formResult = $this->modifyResultBeforeSave($formResult);
		$this->resultRepository->add($formResult);
		
		foreach ($formResult->getQuestions() as $rquestion){
			$formResult->checkMatrixType($rquestion);
		}
       
		$persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
		
		return $formResult;
	}

	/**
	 * if uid is set, we have to merge the given $newResult object with the existing record in DB
	 *
	 * @param Result $formResult This is the result from the form. NOT DB!
	 * @return Result The updated result object
	 */
	public function updateResult(Result $formResult)
    {
        //merge the old and the new result-data
        $dbResult = $this->oldResult;
        if ($dbResult) {
            $updatedResult = $this->addQuestionToDbResult($formResult, $dbResult);
        } else {
            $updatedResult = $formResult;
        }

		$updatedResult->setPoints($updatedResult->getPoints() + $formResult->getPoints());
		$updatedResult->setMaxPoints($updatedResult->getMaxPoints() + $formResult->getMaxPoints());
		//check the result
		$result = $this->modifyResultBeforeSave($updatedResult);
		$this->resultRepository->update($result);
		
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
				
		return $result;
	}

	/**
	 * here you/we make some little modifications to the result object before saving
	 *
	 * @param Result $result
	 * @return Result
	 */
	public function modifyResultBeforeSave(Result $result) {
		// set timestamp for finished if last page was reached
		if ($this->questionnaire AND $this->questionnaire->getIsFinished()) {
			$result->setFinished(time());
		}
		// $this->signalSlotDispatcher->dispatch(__CLASS__, 'modifyResultBeforeSave', array($result, $this));

		return $result;
	}

	/**
	 * add the questions of the form result to the db result object
	 *
	 * @param Result $formResult The result object which comes from the form
	 * @param Result $dbResult The result object which comes from DB
	 * @return Result The updated db result
	 */
	public function addQuestionToDbResult(Result $formResult, Result $dbResult) {
		$formQuestions = $formResult->getQuestions();
		
		foreach ($formQuestions as $fQuestion){
			if ($fQuestion->getQuestion() AND $fQuestion->getQuestion()->getType() == 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question'){
				$dbResult->addOrUpdateQuestion($fQuestion);				
			}
		}
		$formResult->setQuestions($dbResult->getQuestions());		
		return $formResult;
	}
	

    /**
	 * Action to show the FeUser-Access Error
	 *
	 * @return void
	 */
	public function feUserAccessAction(): \Psr\Http\Message\ResponseInterface {
        return $this->htmlResponse();
	}
    
    /**
     * checkes the dependancy of the Questionnaire
     */
    public function checkDependancy(): void {
        // $this->signalSlotDispatcher->dispatch(__CLASS__, 'checkDependancy', array($this));
    }
	
	/**
  * Action to show the FeUser-Access Error
  *
  * @param Questionnaire $questionnaire
  * @return void
  */
 public function dependancyAccessAction(Questionnaire $questionnaire): \Psr\Http\Message\ResponseInterface {
     $this->view->assign('questionnaire',$questionnaire);
     return $this->htmlResponse();
	}
	
	/**
	 * checks the logged in autchode
	 *
	 * @return boolean
	 */
	public function checkAuthCode() {
		//direct call with &authCode= in URI
        $debug = [] ;
		if ($_REQUEST['authCode'] || $this->request->hasArgument('code') ) {
            if ($this->request->hasArgument('code')) {
                $code = $this->request->getArgument('code');
            } else {
                $code = $_REQUEST['authCode'];
            }
            $code = trim($code) ;

            $code = $this->authCodeRepository->findOneBy(['authCode' => $code]);
            $debug[] = 'checkAuthCode From Request URI: ' . $code ;
            if ($code) {
                $this->authCode = $code;
                $debug[] = 'found AuthCode: ' . $this->authCode->getUid() ;
                if (!$this->authCode->getFirstactive()) {
                    $this->authCode->setFirstactive(time());
                    $this->authCodeRepository->update($this->authCode);

                    $persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
                    $persistenceManager->persistAll();
                }
                return true;
            }


        } else {
            if ($this->request->hasArgument('authCode')) {
                $debug[] = 'checkAuthCode from getArgument: ' ;
                $code = $this->request->getArgument('authCode');
            }
            if ( $code ) {
                $code = $this->authCodeRepository->findByUid($this->request->getArgument('authCode'));
                $debug[] = 'checkAuthCode From Request: ' . $code ;
                if ($code) {
                    $this->authCode = $code;
                    $debug[] = 'found AuthCode: ' . $this->authCode->getUid() ;
                    if (!$this->authCode->getFirstactive()) {
                        $this->authCode->setFirstactive(time());
                        $this->authCodeRepository->update($this->authCode);

                        $persistenceManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
                        $persistenceManager->persistAll();
                    }
                    return true;
                }
            }
        }

        //if no VALID authCode is given, return false but throw also warning
        $this->addFlashMessage(LocalizationUtility::translate('reclaimAuthcode.notFoundTitle' , 'KeQuestionnaire'), '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);

        return false;
	}
	
	/**
	 * Action to show the AuthCode-Access Error
	 *
	 * @return void
	 */
	public function authCodeAccessAction(): \Psr\Http\Message\ResponseInterface {
        return $this->htmlResponse();
	}
	
	/**
	 * checks the max participations allowed
	 *
	 * @return void
	 */
	public function checkMaxParticipations() {
		if ($this->settings['participations']['max'] > 0){
			if ($this->settings['accessType'] == 'free'){				
				$counted = $this->resultRepository->findAll()->count();
			} elseif ($this->settings['accessType'] == 'feUser') {
				$counted = $this->resultRepository->findFinishedResultsByUser($this->user)->count();
			} elseif ($this->settings['accessType'] == 'authCode') {
				$counted = $this->resultRepository->findFinishedResultsByAuthCode( $this->authCode ? $this->authCode->getUid() : null )->count();
			}
			if ($counted >= $this->settings['participations']['max']) {
                return false ;
            }
		}
        return true;
	}
	
	/**
	 * Action to show the maxParticipations Error
	 *
	 * @return void
	 */
	public function maxParticipationsAction(): \Psr\Http\Message\ResponseInterface {
        return $this->htmlResponse();
	}
	
	/**
	 * Check if the user can restart a started participation
	 * 
	 * @param Result $result
	 */
	private function checkRestart(Result $result)
    {
        if ($this->settings['accessType'] != 'free' AND $this->settings['restart']) {
            //fetch the last participation of the user
            if ($result->getFeUser()){
                $parts = $this->resultRepository->findBy(['feUser' => $result->getFeUser()])->toArray();
                if (count($parts) > 0){
                    $last = $parts[count($parts)-1];
                    if (!$last->getFinished()) $result = $last;
                }
                //or the authCode
            } elseif ($result->getAuthCode()) {
                $parts = $this->resultRepository->findBy(['authCode' => $result->getAuthCode()])->toArray();
                if (count($parts) > 0){
                    $last = $parts[count($parts)-1];
                    if (!$last->getFinished()) $result = $last;
                }
            }
        }
        return $result;
	}
	
	/**
  * Action to show the End Page of the questionnaire
  *
  * @param Result $result
  * @return void
  */
 #[IgnoreValidation([])]
 public function endAction(Result $result = NULL): \Psr\Http\Message\ResponseInterface {
    if (!$result) $result = $this->resultRepository->findByUid($this->request->getArgument('result'));
		$questionnaire = $this->questionnaireRepository->findByStoragePid($result->getPid());
		
		$this->view->assign('result', $result);
		$this->view->assign('questionnaire', $questionnaire);
		// $this->signalSlotDispatcher->dispatch(__CLASS__, 'endAction', array($result, $this));
     return $this->htmlResponse();
	}


    /**
     * @author joergVelletti <jvelletti@allplan.com>
     * @param \Kennziffer\KeQuestionnaire\Controller\ResultController
     */
    public function shuffleQuestions(): void {
        $questions = $this->questionnaire->getQuestions() ;
        if($questions->count()) {
            $page = 1;
            $pages = array() ;
            // seperate all questions for each page
            foreach($questions as $question) {
               // echo "<br>Page: " . $page . " Question id " . $question->getUid() . " " . $question->getType() ;

                if($question->getType() == "Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak" ) {
                    $page++;
                    continue;
                }
                $pages[$page][] = $question ;
            }
            $randomQuestionsMax = $this->settings['randomQuestionsMax'] ;
            foreach ($pages as $page => $questions ) {

                $pageStorage = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
                shuffle($questions);

                $i = 0 ;

                foreach($questions as $question) {
                    if($i < $randomQuestionsMax ) {
                        $pageStorage->attach($question);
                    } else {
                        break ;
                    }
                    $i++ ;
                }

                // $this->questionnaire->questionsByPage[$page] = $pageStorage;
                $this->questionnaire->setShuffledQuestionsByPage($pageStorage , $page) ;
            }


        }

    }
}