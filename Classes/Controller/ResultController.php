<?php

namespace Kennziffer\KeQuestionnaire\Controller;

use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository;
use Kennziffer\KeQuestionnaire\Utility\Debug;
use Kennziffer\KeQuestionnaire\Domain\Repository\AnswerRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use Kennziffer\KeQuestionnaire\Domain\Model\AuthCode;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;

use Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer;
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
class ResultController extends AbstractController
{

    public $user;
    public $authCode;
    public $temp_result;
    public $oldResult;
    public $newResult;
    /**
     * The current view, as resolved by resolveView()
     *
     * @var ViewInterface
     * @api
     */
    public $view = NULL;

    /**
     * @var Result
     */
    public $result;
    /**
     * @var int
     */
    public $userUid = 0;

    public ResultQuestion|null $lastQuestion = null;

    public function __construct(QuestionnaireRepository $questionnaireRepository, private readonly PersistenceManager $persistenceManager, private readonly ConfigurationManagerInterface $configurationManagerInterface)
    {
        $this->questionnaireRepository = $questionnaireRepository;
        $this->resultRepository = GeneralUtility::makeInstance(ResultRepository::class);
        $this->questionRepository = GeneralUtility::makeInstance(QuestionRepository::class);
    }

    /**
     * initializes the actions
     */
    #[\Override]
    public function initializeAction(): void
    {
        parent::initializeAction();
        if ($this->questionnaire) {
            $this->questionnaire->settings = $this->settings;
        }


        //check a logged in user
        $this->user = 0;
        $this->userUid = 0;
        if ($this->request->getAttribute('frontend.user')) {
            $this->user = $this->request->getAttribute('frontend.user');
            if ($this->user && $this->user->user) {

                $this->userUid = $this->user->user['uid'];
            }
        }

        //check jsKey for the created js-file
        $jsKey = substr((string) $this->userUid, 0, 10) . '_keqjs';
        $this->request->getAttribute('frontend.user')->setKey('ses', 'keq_jskey', $jsKey);
        //maybe better to erase the js file every time
        $pathname = 'typo3temp/ke_questionnaire';
        $filename = $pathname . '/' . $jsKey . '.js';
        if (file_exists(Environment::getPublicPath() . '/' . $filename)) {
            unlink($filename);
        }
    }

    /**
     * Displays a form for saving a new questionnaire
     *
     * @param Result $newResult A fresh new result object
     * @param integer $requestedPage after checking the questions of currentPage redirect to this page
     *
     * @return ResponseInterface
     * @throws InvalidSlotReturnException
     * @throws InvalidSlotException
     */
    #[IgnoreValidation([])]
    public function newAction(
        Result $newResult = NULL, ?int $requestedPage = 0 ,
        QueryResultInterface $questions = NULL): ResponseInterface

    {
        $persistenceManager = $this->persistenceManager;
        $debug = [];
        if ($newResult == NULL) {
            $debug[] = 'Line ' . __LINE__ . " | "  . 'Create new Result Object ';
            /** @var Result $newResult */
            $newResult = GeneralUtility::makeinstance(Result::class);
            $newResult->setFeCruserId($this->userUid ?? 0);
            if ($this->user) {
                $newResult->setFeUser($this->user);

            }
            $pid = $this->questionnaire->getStoragePid();

            //check for the Access-Type

            switch ($this->settings['accessType']) {
                case 'feUser':
                    if (!$this->userUid > 0) {
                        return $this->redirect('feUserAccess');
                    }
                    break;
                case 'authCode':
                    //check if the authCode is valid
                    if ($newResult->getAuthCode() && $newResult->getAuthCode()->getUid() > 0) {
                        $this->authCode = $newResult->getAuthCode();
                    } else {
                        $this->authCode = null;
                        if (!$this->checkAuthCode($pid)) {
                            return $this->redirect('authCodeAccess');
                        }
                        $newResult->setAuthCode($this->authCode);
                    }

                    break;
            }

            $this->resultRepository->add($newResult);
            $persistenceManager->persistAll();

            //check if the max participations are reached
            if (!$this->checkMaxParticipations($newResult)) {
                return $this->redirect('maxParticipations');
            }
            //get the correct requested start-page
            if ($requestedPage == 0 && !$this->settings['description']) {
                $requestedPage = 1;
            }
        }
        if ( $newResult && $newResult->getUid() ) {
            $debug[] = 'Line ' . __LINE__ . " | "  . 'New Result Uid: ' . $newResult->getUid();
            /* @var \Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository $resultQuestionsRepository */
            $resultQuestionsRepository = GeneralUtility::makeinstance(ResultQuestionRepository::class);
            $resultQuestions = $resultQuestionsRepository->findByResultId($newResult->getUid());
            if( $resultQuestions && count( $resultQuestions ) > 0 ) {
                $debug[] = 'Line ' . __LINE__ . " | "  . 'Found existing ResultQuestions for Result Uid: ' . $newResult->getUid() . ' - count: ' . count( $resultQuestions );
                $questions = [];
                $pages = [];

                foreach ( $resultQuestions as $resultQuestion ) {
                    /** @var ResultQuestion $resultQuestion */
                    $page = $resultQuestion->getPage();
                    $pages[$page][] = $resultQuestion->getQuestion();
                    $debug[] = 'Line ' . __LINE__ . " | " . ' Question Uid: ' . $resultQuestion->getQuestion()->getUid() . ' - ' . $resultQuestion->getQuestion()->getTitle();
                }
                $this->questionnaire->setPage($requestedPage);
                foreach ($pages as $page => $questionsOnPage ) {
                    $this->questionnaire->setShuffledQuestionsByPage($questionsOnPage, $page );
                }

            }
        }

        if (! $questions || count($questions) === 0 ) {
            $debug [] = 'Line ' . __LINE__ . " | "  . 'Set Questions for Page: ' . $requestedPage;
            //set the requested page
            $this->questionnaire->setPage($requestedPage);
            //get the questions and add them to the questionnaire
            $questions = $this->questionRepository->findAll();
            $this->questionnaire->setQuestions($questions);
            if ($this->settings['randomQuestionsMax'] > 0) {
                $debug [] = 'Line ' . __LINE__ . " | "  . 'shuffle  Questions ' ;
                $this->shuffleQuestions();
            }
            $questions = $this->questionnaire->getQuestionsForPage($requestedPage);
            /**
             * @var ResultQuestionRepository $resultQuestionRepository
             */
            $resultQuestionRepository = GeneralUtility::makeinstance(ResultQuestionRepository::class);

            foreach ($questions as $question) {
                /** @var Question $question */
                $debug[] = 'Line ' . __LINE__ . " | "  . ' After Shuffle, / paging: Question Uid: ' . $question->getUid() . ' - ' . $question->getTitle();
                $resultQuestion = new ResultQuestion();
                $resultQuestion->setQuestion($question);
                $resultQuestion->setPid($newResult->getPid());
                $resultQuestion->setPage($this->questionnaire->getPage() );
                $resultQuestion->setResult($newResult->getUid());

                $resultQuestionRepository->add($resultQuestion);
                $persistenceManager->persistAll();
            }



        }
        if ($newResult->getCrdate() == 0) {
            $newResult->setCrdate(time());
        }

        if ($this->questionnaire->settings['startTime'] == 0) {
            $this->questionnaire->settings['startTime'] = time();
        }
        $this->settings['startTime2'] = time();

        $this->view->assign('questions', $questions);

        $this->view->assign('questionnaire', $this->questionnaire);
        $this->view->assign('newResult', $newResult);
        Debug::store(($newResult ? $newResult->getUid() : 0), $debug);
        return $this->htmlResponse();

    }


    /**
     * set StoragePid
     *
     * @param $storagePid
     * @return void
     */
    public function setStoragePid($storagePid): void
    {
        $configurationManager = $this->configurationManagerInterface;
        //fallback to current pid if no storagePid is defined
        $configuration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $currentPid['persistence']['storagePid'] = $storagePid;
        $configurationManager->setConfiguration(array_merge($configuration, $currentPid));
    }


    /**
     * initializes the XXXcreateAction
     */
    public function initializeCreateAction(): void
    {
        //to get correct updates it is needed that a clone of the actual result is created
        //the temp_result stores the current newResult-data
        $persistenceManager = $this->persistenceManager;

        if ($this->request->hasArgument("newResult")) {
            $this->temp_result = $this->request->getArgument('newResult');

            /**
             * @var AnswerRepository $answerRepository
             * @var ResultQuestionRepository $resultQuestionRepository
             * @var ResultAnswerRepository $resultAnswerRepository
             */
            $resultQuestionRepository = GeneralUtility::makeinstance(ResultQuestionRepository::class);


            $answerRepository = GeneralUtility::makeinstance(AnswerRepository::class);
            $resultAnswerRepository = GeneralUtility::makeinstance(ResultAnswerRepository::class);
            if (array_key_exists('__identity', $this->temp_result) && $this->temp_result['__identity'] > 0) {

                $debug = [];
                $debug["TempResult"] = $this->temp_result;
                /** @var  $result Result */
                $result = $this->resultRepository->findByUid($this->temp_result['__identity']);
                if ($result) {
                    $debug = ['found Result with Uid: ' . $result->getUid()];
                    $this->oldResult = clone $result;

                    if (array_key_exists('questions', $this->temp_result) && count($this->temp_result['questions']) > 0) {
                        $debug[] = "line: " . __LINE__ .' questions found in temp_result: ' . count($this->temp_result['questions']);
                        foreach ($this->temp_result['questions'] as $formquestion) {
                            /** @var ResultQuestion $resultQuestion */
                            $resultQuestion = $result->getResultQuestionByQuestionUid(intval($formquestion['question']));
                            $debug[] = "line: " . __LINE__ .' $form question ID: ' . intval($formquestion['question']);

                            if ($resultQuestion) {
                                $debug[] = 'Line ' . __LINE__ . " | "  . 'ResultQuestion found ';
                            }  else {
                                $debug[] = 'Line ' . __LINE__ . " | "  . 'Create New ResultQuestion  ' . intval($formquestion['question']);
                                $resultQuestion = new ResultQuestion();
                                $question = $this->questionRepository->findByUidFree(intval($formquestion['question']));
                                $resultQuestion->setQuestion($question);
                                $resultQuestion->setPid($result->getPid());
                                $resultQuestion->setPage($this->questionnaire->getPage() + 1);
                                $resultQuestion->setResult($result->getUid());

                                $resultQuestionRepository->add($resultQuestion);
                                $persistenceManager->persistAll();
                                $debug[] = 'added NEW $resultQuestion with ID: ' . $resultQuestion->getUid() . ' for Question Uid: ' . intval($question->getUid());

                            }

                            $debug[] = '$formquestion Answers: ' . count($formquestion['answers'] ?? 0);
                            if ( isset( $formquestion['answers'] ) && is_array($formquestion['answers'])) {
                                foreach ($formquestion['answers'] as $formAnswer) {

                                    $isAnswered = false;
                                    $answeredValue = false;
                                    if ( $formAnswer['answer'] && $formAnswer['answer'] > 0 ) {
                                        $answer = $answerRepository->findByUidFree(intval($formAnswer['answer']));
                                        $debug[] = 'Line ' . __LINE__ . " | "  . 'Answer Type : ' . $answer->getType() ;
                                        /** @var ResultAnswer $resultAnswer */
                                        $resultAnswer = new ResultAnswer();
                                        $resultAnswer->setPid($result->getPid());
                                        $resultAnswer->setResultquestion($resultQuestion);
                                        $resultAnswer->setFeCruserId($this->userUid);
                                        if ($formAnswer['additionalValue']) {
                                            $resultAnswer->setAdditionalValue( strip_tags($formAnswer['additionalValue']));
                                            $debug[] = 'Line ' . __LINE__ . " | " . 'Additional value : ' . $formAnswer['additionalValue'];
                                        } else {
                                            $resultAnswer->setAdditionalValue('');
                                        }

                                        if (
                                            $answer->getType() == Checkbox::class
                                        ) {
                                            $resultAnswer->setValue($formAnswer['answer']);
                                            if ($formAnswer['answer'] && $formAnswer['value']) {

                                                $debug[] = 'Line ' . __LINE__ . " | " . 'Special Type Checkbox : ' . $formAnswer['answer'];
                                                //   $debug[] = var_export( $formAnswer , true ) ;
                                                $debug[] = 'Line ' . __LINE__ . " | " . 'Title of  Answer used: ' . $formAnswer['answer'];
                                                $answeredValue = $answer->getTitle();
                                                $isAnswered = true;
                                            } else {
                                                $isAnswered = false;
                                              //  $debug[] = 'Line ' . __LINE__ . " | " . 'No Answer value in array ?? ' . var_export($formAnswer, true);
                                            }


                                        } else if (
                                            $answer->getType() == Radiobutton::class
                                        ) {
                                            $resultAnswer->setValue($formAnswer['answer']);
                                                $debug[] = 'Line ' . __LINE__ . " | "  . 'Special Type : ' . $formAnswer['answer'];
                                                //   $debug[] = var_export( $formAnswer , true ) ;
                                                $debug[] = 'Line ' . __LINE__ . " | "  . 'Title of  Answer used: ' . $formAnswer['answer'];
                                                $answeredValue = $answer->getTitle();
                                                $isAnswered = true;

                                        } else {
                                            $resultAnswer->setValue($formAnswer['value']);
                                            if ($formAnswer['value']) {
                                                $debug[] = 'Line ' . __LINE__ . " | "  . 'Answer value used: ' . strip_tags($formAnswer['value']);
                                                $answeredValue = strip_tags( $formAnswer['value']);
                                                $isAnswered = true;
                                            } else {
                                                $isAnswered = false;
                                               // $debug[] = 'Line ' . __LINE__ . " | "  . 'No Answer value in array ?? ' . var_export($formAnswer , true ) ;
                                            }
                                        }

                                        $oldResultAnswers = $resultAnswerRepository->findForResultQuestionAndAnswerRaw(
                                            $resultQuestion->getUid(),
                                            $answer->getUid(),
                                            false);
                                        if ($oldResultAnswers && $oldResultAnswers->getFirst() instanceof ResultAnswer) {
                                            $debug[] = 'Line ' . __LINE__ . " | " . 'removed Old Answer with Id: ' . $oldResultAnswers->getFirst()->getUid();
                                            $resultAnswerRepository->remove($oldResultAnswers->getFirst());
                                            while ($oldResultAnswer = $oldResultAnswers->next()) {
                                                $debug[] = 'Line ' . __LINE__ . " | " . 'removed Old Answer with Id: ' . $oldResultAnswer->getUid();
                                                $resultAnswerRepository->remove($oldResultAnswer);
                                            }
                                        }

                                        if ($isAnswered) {
                                            $resultAnswer->setAnswer($answer);
                                            $resultAnswer->setValue($answeredValue);
                                            $debug[] = 'Line ' . __LINE__ . " | "  . 'Adding Answer ' . (string)$answer . " with value " . $answeredValue . ' to Question: ' . $resultQuestion->getQuestion()->getUid();
                                            $debug[] = 'Line ' . __LINE__ . " | "  . 'Answer Value is: "' . $resultAnswer->getValue() . '"';
                                            $resultQuestion->addAnswer($resultAnswer);
                                        }
                                        unset($resultAnswer);
                                    }
                                    $resultQuestionRepository->update($resultQuestion);
                                    $persistenceManager->persistAll();


                                }

                            }
                            $this->lastQuestion = $resultQuestion;
                            $result->addQuestion($resultQuestion);
                            unset($resultQuestion);
                        }
                    }
                } else {
                    $debug = ['No Result found with Uid: ' . $this->temp_result['__identity']];
                }

                if ($result) {
                    if ($result->getUid()) {
                        $debug[] = 'Line ' . __LINE__ . " | "  . ' Result update : ' . $result->getUid();
                        $this->resultRepository->update($result);
                    } else {
                        $debug[] = 'Line ' . __LINE__ . " | "  . ' New Result added ';
                        $this->resultRepository->add($result);
                    }
                    $persistenceManager->persistAll();
                }
                Debug::store(($result ? $result->getUid()  : 0), $debug);
            }
        }
        $this->newResult = $result;
    }


    /**
     * Creates a new questionnaire
     *
     * @param integer $currentPage check, validate and save the results of this page
     * @param integer $requestedPage after checking the questions of currentPage redirect to this page
     * @return ResponseInterface
     * @throws StopActionException
     * @throws UnsupportedRequestTypeException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    #[IgnoreValidation([])]
    public function createAction($currentPage, $requestedPage)
    {
        $debug = [];
        /** @var Result $newResult */
        $newResult = ($this->newResult ?? $this->result);
        $debug[] = 'Line ' . __LINE__ . " | "  . 'createAction: newResult UID' . $newResult->getUid() ;
        $debug[] = ' - currentPage:' . $currentPage . ' - requestedPage:' . $requestedPage;
        //requestedPage => next Page to be shown

        $this->questionnaire->setRequestedPage($requestedPage);
        //currentPage => current Page, just send to this action
        $this->questionnaire->setPage($currentPage);
        $pid = $this->questionnaire->getStoragePid();
        //get all questions for this questionnaire
        $this->questionnaire->setQuestions($this->questionRepository->findAll());
        if ($this->user) {
            $newResult->setFeUser($this->user);
        }
        $newResult->setFeCruserId($this->userUid);
        //check for the Access-Type
        switch ($this->settings['accessType']) {
            case 'feUser':
                if (!$this->user) {
                    return $this->redirect('feUserAccess');
                }
                break;
            case 'authCode':
                //check if the authCode is valid
                if (!$this->checkAuthCode($pid)) {
                    return $this->redirect('authCodeAccess');
                }
                $newResult->setAuthCode($this->authCode);
                break;
        }
        //validate the result
        $isValid = $this->validateResult($newResult, $currentPage);
        if ($isValid !== 'valid') {
            return $this->moveToAction('new', $newResult, $currentPage, $isValid);
        }

        //rework the result so all given answers to all questions (not only current page) are used for calculation of points and result

        $result = $this->getSavedAndMergedResult($newResult);


        //calculate the points of the questions and the result
        $debug[] = "Line " . __LINE__ . " | "  . 'Calculate Points for correct Answers ';
        $debug[] = $this->calculatePoints($this->settings['reducePointsforWrongAnswers'] , $result );

        $persistenceManager = $this->persistenceManager;
        $persistenceManager->persistAll();

        //TOdo jv  2025 : check this
        $this->resultRepository->clearRATable();

        // check for last page
        if ($currentPage == $requestedPage) {
            $debug[] = "Line " . __LINE__ . " | "  . 'current Page $currentPage  == requestedPage $requestedPage ';
            //if there is a redirect page, redirect
            //else forward to endAction
            if ($this->settings['redirectFinished']) {
                $this->uriBuilder->setTargetPageUid($this->settings['redirectFinished']);
                $link = $this->uriBuilder->build();
                return $this->redirectToUri($link);
            } else {
                /* Problems with forward and RealUrl
                 $this->forward('end', NULL, NULL, array(
                    'result' => $newResult
                ));*/

                // JVE - Jörg velletti Nov 2016 Changed hardCoded Template
                $TemplateRootPaths = $this->view->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();
                foreach ($TemplateRootPaths as $templatePath) {
                    $tempTemplatePathAndFilename = $templatePath . '/Result/End.html';
                    if (is_file($tempTemplatePathAndFilename)) {
                        $templatePathAndFilename = $tempTemplatePathAndFilename;
                    }
                }

                $this->view->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename($templatePathAndFilename);
                // JVE - Jörg velletti Nov 2016 Changed hardCoded Template

                $this->view->assign('result', $newResult);
                $this->view->assign('lastQuestion', $this->lastQuestion);
                $this->view->assign('questionnaire', $this->questionnaire);
            }
            //if not last page, set all stuff for questionnaire-page
        } else {
            //set the next page
            $debug[] = "Line " . __LINE__ . " | "  . 'set Page  requestedPage  ' . $requestedPage;


            $this->questionnaire->setPage($requestedPage);
            //get questions
            $questions = $this->questionRepository->findAll();
            $this->questionnaire->setQuestions($questions);

            if ($this->questionnaire->settings['startTime'] == 0) {
                $this->questionnaire->settings['startTime'] = time();
            }
            $questions = $this->questionnaire->getQuestionsForPage($requestedPage);
            $debug[] = "Line " . __LINE__ . " | "  . 'we have  ' . $this->questionnaire->getCountPages() . ' pages in this questionnaire ';
            $this->view->assign('questions', $questions);
            $this->view->assign('questionnaire', $this->questionnaire);
            $this->view->assign('newResult', $newResult);
        }
        Debug::store(($result->getUid() ?? 0), $debug);

        return $this->htmlResponse();
    }

    /**
     * Shows an chart => needs to be checked if really needed
     *
     * @param Result $result
     * @return void
     */
    public function showAction(Result $result = NULL): ResponseInterface
    {
        if (!$result instanceof Result) {
            $this->addFlashMessage(LocalizationUtility::translate('feView.noResultError'), LocalizationUtility::translate('feView.noResultErrorTitle'), ContextualFeedbackSeverity::WARNING);
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
    public function validateResult(Result $result, $requestedPage = 1)
    {
        /* @var  \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $resultQuestion */
        //check for every question in result
        $debug[] = 'Line ' . __LINE__ . " | "  . 'validateResult: ' . $result->getUid() . ' - Page:' . $requestedPage;
        foreach ($result->getQuestions() as $resultQuestion) {
            $debug[] = 'Line ' . __LINE__ . " | "  . ' Question: ' . $resultQuestion->getQuestion()->getUid() . ' - ' . $resultQuestion->getQuestion()->getTitle() . ' - Page:' . $resultQuestion->getPage();

            if ($resultQuestion->getQuestion() && $resultQuestion->getPage() == ($requestedPage)) {
                // check if the question has to be answered correctly
                if ($resultQuestion->getQuestion()->getMustBeCorrect() && !$resultQuestion->isAnsweredCorrectly()) {
                    return 'mustBeCorrect';
                }

                // check if question is mandatory
                if ($resultQuestion->getQuestion()->getIsMandatory() && !$resultQuestion->isAnswered()) {
                    return 'mandatory';
                }
            }
        }
        $result->setFeCruserId($this->userUid);
        if ($this->user) {
            $result->setFeUser($this->user);
        }
        return 'valid';
    }

    /**
     * move to given action
     *
     * @param string $action Action to call
     * @param Result|null $result A fresh Questionnaire object
     * @param integer $page needed for page navigation
     * @param string $flashMessage Key to show a flashMessage if needed
     * @return void
     */
    public function moveToAction($action, ?Result $result, $page = 1, $flashMessage = '')
    {
        if (!empty($flashMessage)) {
            $this->addNewFlashMessage($flashMessage, ContextualFeedbackSeverity::ERROR);
        }
        return $this->redirect(($action ?? 'new'), NULL, NULL, [
            'newResult' => $result,
            'requestedPage' => $page
        ]);
    }

    /**
     * get the created or merged result object
     * we have to merge them because $newResult contains the answered questions of the last page only.
     * All other answered questions come from DB
     *
     * @param Result $newResult
     * @return Result The modified result object
     */
    public function getSavedAndMergedResult(Result $newResult)
    {
        if( $newResult->getUid() > 0 ) {
           return $this->updateResult($newResult);
        } else {
            return $this->addResult($newResult);
        }
    }

    /**
     * add the result to the DB
     *
     * @param Result $formResult This is the result from the form. NOT DB!
     * @return Result The added result object
     */
    public function addResult(Result $formResult)
    {
        $formResult = $this->modifyResultBeforeSave($formResult);
        $this->resultRepository->add($formResult);

        $persistenceManager = $this->persistenceManager;
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
        $updatedResult = $dbResult ? $this->addQuestionToDbResult($formResult, $dbResult) : $formResult;

        $updatedResult->setPoints($updatedResult->getPoints() + $formResult->getPoints());
        $updatedResult->setMaxPoints($updatedResult->getMaxPoints() + $formResult->getMaxPoints());
        //check the result
        $result = $this->modifyResultBeforeSave($updatedResult);
        $this->resultRepository->update($result);

        $persistenceManager = $this->persistenceManager;
        $persistenceManager->persistAll();

        return $result;
    }

    /**
     * here you/we make some little modifications to the result object before saving
     *
     * @param Result $result
     * @return Result
     */
    public function modifyResultBeforeSave(Result $result)
    {
        // set timestamp for finished if last page was reached
        if ($this->questionnaire && $this->questionnaire->getIsFinished()) {
            $result->setFinished(time());
        }

        return $result;
    }

    /**
     * add the questions of the form result to the db result object
     *
     * @param Result $formResult The result object which comes from the form
     * @param Result $dbResult The result object which comes from DB
     * @return Result The updated db result
     */
    public function addQuestionToDbResult(Result $formResult, Result $dbResult)
    {
        $formQuestions = $formResult->getQuestions();

        foreach ($formQuestions as $fQuestion) {
           // if ($fQuestion->getQuestion() && $fQuestion->getQuestion()->getType() == \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class) {
                $dbResult->addOrUpdateQuestion($fQuestion);
           // }
        }
        $formResult->setQuestions($dbResult->getQuestions());
        return $formResult;
    }


    /**
     * Action to show the FeUser-Access Error
     *
     * @return void
     */
    public function feUserAccessAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }


    /**
     * checks the logged in authcode
     *
     * can be: input string from Form as authCode
     * can be: authCodeId from button link as authCodeId or during the process from form
     * or: from email sent link with &authCode= in URI
     *
     *
     * @return boolean
     */
    public function checkAuthCode($pid)
    {

        $debug = [];

        // AuthCode was Checked and now is present as ID request
        if ($this->request->hasArgument('authCodeId')) {
            $debug[] = 'Line ' . __LINE__ . " | "  . 'checkAuthCodeId from getArgument: ';
            $code = $this->request->getArgument('authCodeId');
            if ($code) {
                $authCode = $this->authCodeRepository->findByAuthCodeIdForPid($code, $pid);
                $debug[] = 'Line ' . __LINE__ . " | "  . 'Load from DB : from PID:  ' . $pid . " => " . var_export(($authCode ? $authCode->getUid() : null), true);
            }
        }

        if (!$authCode) {
            //direct call with &authCode= in URI
            if ($_REQUEST['authCode'] || $this->request->hasArgument('code')) {
                $code = $this->request->hasArgument('code') ? $this->request->getArgument('code') : $_REQUEST['authCode'];
                $code = trim((string) $code);
                $debug[] = 'Line ' . __LINE__ . " | "  . 'checkAuthCode From Request URI: ' . $code;
                $authCode = $this->authCodeRepository->findByAuthCodeForPid($code, $pid);
                $debug[] = 'Line ' . __LINE__ . " | "  . 'Load from DB : from PID:  ' . $pid . " => " . var_export(($authCode ? $authCode->getUid() : null), true);
            } elseif ($this->request->hasArgument('authCode')) {
                $debug[] = 'Line ' . __LINE__ . " | "  . 'checkAuthCode from getArgument: ';
                $code = $this->request->getArgument('authCode');
                if ($code) {
                    $debug[] = 'Line ' . __LINE__ . " | "  . 'checkAuthCode From Request: ' . $code;
                    $authCode = $this->authCodeRepository->findByAuthCodeForPid($code, $pid);
                    $debug[] = 'Line ' . __LINE__ . " | "  . 'Load from DB : from PID:  ' . $pid . " => " . var_export(($authCode ? $authCode->getUid() : null), true);
                }
            }
        }

        if ($authCode) {
            $this->authCode = $authCode;
            $debug[] = 'Line ' . __LINE__ . " | "  . 'Found AuthCode: ' . $this->authCode->getUid();
            if (!$this->authCode->getFirstactive()) {
                $this->authCode->setFirstactive(time());
                $this->authCodeRepository->update($this->authCode);

                $persistenceManager = $this->persistenceManager;
                $persistenceManager->persistAll();
            }
            return true;

        } elseif ($code) {
            //if no VALID authCode is given, return false but throw also warning
            $this->addFlashMessage(LocalizationUtility::translate('reclaimAuthcode.notFoundTitle', 'KeQuestionnaire'), '', ContextualFeedbackSeverity::WARNING);
            return false;
        }
        // echo "<pre>Line: " . __LINE__ . "\n" ;
        // var_dump($debug) ;
        // die;

        return false;
    }

    /**
     * Action to show the AuthCode-Access Error
     *
     * @return void
     */
    public function authCodeAccessAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * checks the max participations allowed
     *
     * @return void
     */
    public function checkMaxParticipations($newResult)
    {
        if ($this->settings['participations']['max'] > 0) {
            if ($this->settings['accessType'] == 'free') {
                $counted = $this->resultRepository->findAll()->count();
                $debug = ['Line ' . __LINE__ . " | "  . 'AccessType: free - Counted Results: ' . $counted];
            } elseif ($this->settings['accessType'] == 'feUser') {
                $counted = $this->resultRepository->findFinishedResultsByUser($this->user)->count();
                $debug = ['Line ' . __LINE__ . " | "  . 'AccessType: feUser - Counted Results: ' . $counted];
            } elseif ($this->settings['accessType'] == 'authCode') {
                $counted = $this->resultRepository->findFinishedResultsByAuthCode($this->authCode ? $this->authCode->getUid() : null)->count();
                $debug = ['Line ' . __LINE__ . " | "  . 'AccessType: authCode ' . ($this->authCode->getUid() ?? 0 ) . ' - Counted Results: ' . $counted];
            }
            $debug[] = 'Line ' . __LINE__ . " | "  . 'Max Participations allowed: ' . $this->settings['participations']['max'];
            Debug::store(($newResult ? $newResult->getUid() : 0), $debug);
            if ($counted >= $this->settings['participations']['max']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Action to show the maxParticipations Error
     *
     * @return void
     */
    public function maxParticipationsAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * Check if the user can restart a started participation
     *
     * @param Result $result
     */
    private function checkRestart(Result $result)
    {
        if ($this->settings['accessType'] != 'free' && $this->settings['restart']) {
            //fetch the last participation of the user
            if ($result->getFeUser()) {
                $parts = $this->resultRepository->findBy(['feUser' => $result->getFeUser()])->toArray();
                if (count($parts) > 0) {
                    $last = $parts[count($parts) - 1];
                    if (!$last->getFinished()) {
                        $result = $last;
                    }
                }
                //or the authCode
            } elseif ($result->getAuthCode() instanceof AuthCode) {
                $parts = $this->resultRepository->findBy(['authCode' => $result->getAuthCode()])->toArray();
                if (count($parts) > 0) {
                    $last = $parts[count($parts) - 1];
                    if (!$last->getFinished()) {
                        $result = $last;
                    }
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
    public function endAction(Result $result = NULL): ResponseInterface
    {
        if (!$result instanceof Result) {
            $result = $this->resultRepository->findByUid($this->request->getArgument('result'));
        }
        $questionnaire = $this->questionnaireRepository->findByStoragePid($result->getPid());

        $this->view->assign('result', $result);
        $this->view->assign('questionnaire', $questionnaire);
        // $this->signalSlotDispatcher->dispatch(__CLASS__, 'endAction', array($result, $this));
        return $this->htmlResponse();
    }


    /**
     * @param \Kennziffer\KeQuestionnaire\Controller\ResultController
     * @author joergVelletti <jvelletti@allplan.com>
     */
    public function shuffleQuestions(): void
    {
        $questions = $this->questionnaire->getQuestions();
        if ($questions->count()) {
            $page = 1;
            $pages = [];
            // seperate all questions for each page
            foreach ($questions as $question) {
                if ($question->getType() == PageBreak::class) {
                    $page++;
                    continue;
                }
                $pages[$page][] = $question;
            }
            $randomQuestionsMax = $this->settings['randomQuestionsMax'];
            foreach ($pages as $page => $questions) {

                $pageStorage = GeneralUtility::makeInstance(ObjectStorage::class);
                shuffle($questions);
                $i = 0;

                foreach ($questions as $question) {
                    if ($i < $randomQuestionsMax) {
                        $pageStorage->attach($question);
                    } else {
                        break;
                    }
                    $i++;
                }
                $this->questionnaire->setShuffledQuestionsByPage($pageStorage, $page);
            }
        }
    }


    /**
     * check the points for the result object
     * @param boolean $reducePointsforWrongAnswers
     * @return array
     */
    public function calculatePoints($reducePointsforWrongAnswers=false , Result $result = NULL)
    {
        $resultQuestionRepository = GeneralUtility::makeinstance(ResultQuestionRepository::class);
        $questions = $resultQuestionRepository->findByResultId($result->getUid());
        $maxPoints = 0;
        $pointsForResult = 0;
        $group = NULL;
        $groupPoints = 0;
        $maxGroupPoints = 0;
        $totalAnsweredQuestions = 0;

        /* @var $resultQuestion \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion */
        //count for all questions in result
        $debug = [];
        foreach ( $questions as $resultQuestion) {
            if ($resultQuestion->getQuestion()) {
                $debug[] = "resultQuestion ID: " . $resultQuestion->getUid();
                // check for point calculation
                $pointsForQuestion = 0;
                $calcPoints = 0;
                $wrongAnswersForQuestion = 0;
                $givenAnswersForQuestion = 0;
                /* @var $resultAnswer \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer */
                if (count($resultQuestion->getAnswers()) > 0) {
                    $debug[] = "result Answers: " . count($resultQuestion->getAnswers());
                    $totalAnsweredQuestions++ ;
                    //calculate for each answer
                    foreach ($resultQuestion->getAnswers() as $resultAnswer) {
                        $debug[] = "result: Given Answer ID: " . $resultAnswer->getUid();
                        $givenAnswersForQuestion++;
                        if ($resultAnswer->getAnswer()) {
                            $debug[] = "result Answer value : " . $resultAnswer->getValue();
                            $debug[] = "result Answer->Answer ID value : " . $resultAnswer->getAnswer()->getUid();
                            $debug[] = "possible Points : " . $resultAnswer->getAnswer()->getPoints();
                            $calcPoints = $resultAnswer->getAnswer()->getPoints();
                            $debug[] = "calulated Points: " . $calcPoints;
                            if ( $reducePointsforWrongAnswers ) {
                                if ($calcPoints > 0) {
                                    $pointsForQuestion += $calcPoints;
                                } else {
                                    $wrongAnswersForQuestion++;
                                }
                            } else {
                                $pointsForQuestion += $calcPoints;
                            }
                            $debug[] = "pointsForQuestion: " . $pointsForQuestion;

                        }
                        $resultAnswer->setFeCruserId($this->userUid);
                    }
                    if ($resultQuestion->getQuestion()->isMaxAnswers() && $reducePointsforWrongAnswers && $wrongAnswersForQuestion > 0) {
                        $pointsForQuestion = round($pointsForQuestion / (($givenAnswersForQuestion + $wrongAnswersForQuestion) / 2), 0);
                        $debug[] = "reduced  to : " . $pointsForQuestion;
                    }
                    if ($pointsForQuestion < 0 && $reducePointsforWrongAnswers ) {
                        $debug[] = "Points for this question set to minimum = 0 ";
                        $pointsForQuestion = 0;
                    }
                    $debug[] = "Points for this question  : " . $pointsForQuestion;
                    $pointsForResult += $pointsForQuestion;
                    $debug[] = "----" ;
                    $debug[] = "Current total Points  : " . $pointsForResult;
                    $debug[] = "----" ;
                }

                //set the points for this questions
                $resultQuestion->setPoints($pointsForQuestion);
                $resultQuestion->setFeCruserId( $this->userUid );

                //maxPoints are the maximum points for all the questions already part of this result
                $maxPoints += $resultQuestion->getMaxPoints();
                $resultQuestionRepository->update($resultQuestion);
            }
        }

        $debug[] = "--------" ;
        $debug[] = "set result Points total " . $pointsForResult ;
        $result->setPoints($pointsForResult);
        $debug[] = "set Max Points total " . $maxPoints ;
        $debug[] = "Total Answered Questions" . $totalAnsweredQuestions ;
        $result->setMaxPoints($maxPoints);

        $this->resultRepository->update($result);

        return $debug ;
    }
}