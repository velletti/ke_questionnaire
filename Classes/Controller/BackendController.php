<?php

declare(strict_types=1);

namespace Kennziffer\KeQuestionnaire\Controller;

use Kennziffer\KeQuestionnaire\Domain\Model\AuthCode;
use Kennziffer\KeQuestionnaire\Utility\EmConfigurationUtility;
use Kennziffer\KeQuestionnaire\Utility\CsvExport;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository;
use Kennziffer\KeQuestionnaire\Utility\Mail;
use \Kennziffer\KeQuestionnaire\Utility\Debug;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Core\Messaging\FlashMessageRendererResolver;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

use TYPO3\CMS\Fluid\View\StandaloneView;


class BackendController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected QuestionnaireRepository $questionnaireRepository;
    protected AuthCodeRepository $authCodeRepository;
    protected FlashMessageQueue $flashMessageQueue;

    protected FlexFormService $flexFormService;
    CONST MESSAGE_QUEUE_IDENTIFIER = 'kequestionnairebe';
    protected $pathName = 'typo3temp/ke_questionnaire';

    public function __construct(        ModuleTemplateFactory $moduleTemplateFactory,
                                        PageRenderer $pageRenderer,
                                        BackendUserAuthentication $backendUser)

    {

        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->pageRenderer = $pageRenderer;
        $this->backendUser = $backendUser;
        $this->flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

        $this->questionnaireRepository = GeneralUtility::makeInstance(QuestionnaireRepository::class);
        $this->authCodeRepository = GeneralUtility::makeInstance(AuthCodeRepository::class);
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $this->flashMessageQueue = $flashMessageService->getMessageQueueByIdentifier(self::MESSAGE_QUEUE_IDENTIFIER);

    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $languageService = $this->getLanguageService();

        $allowedOptions = [
            'function' => [
                'authCodes' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:authCodes')
                ),
                'authCodesSimple' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.simple')
                ),
                'createAuthCodes' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:simple.title')
                ),
                'createAndMailAuthCodes' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:mail.title')
                ),

                'authCodesMail' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.mail')
                ),
                'export' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:export')
                ),
                'download' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:download')
                ),
                'analyse' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:analyse')
                ),
                'analyseInterval' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:analyse')
                ),
            ],
        ];

        $moduleData = $request->getAttribute('moduleData');
        if ($moduleData->cleanUp($allowedOptions)) {
            $this->getBackendUser()->pushModuleData($moduleData->getModuleIdentifier(), $moduleData->toArray());
        }


        $moduleTemplate = $this->moduleTemplateFactory->create($request);
        $moduleTemplate->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
        $this->setUpDocHeader($request, $moduleTemplate);
        $function = strtolower( $moduleData->get('function') ?? $moduleData->getModuleIdentifier() );
        $title = $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:mlang_tabs_tab');
        switch ($function) {
            case 'authcodes':
            case 'kequestionnairebe_authcodes':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.authCodes')
                );
                return $this->authCodesAction($request, $moduleTemplate);
            case 'authcodessimple':
            case 'kequestionnairebe_authcodessimple':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.simple')
                );
                return $this->authCodesSimpleAction($request, $moduleTemplate);

            case 'createauthcodes':
            case 'kequestionnairebe_createauthcodes':


                return $this->createAuthCodesAction($request);

            case 'authcodesmail':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.mail')
                );
                return $this->authCodesMailAction($request, $moduleTemplate);



            case 'export':
            case 'kequestionnairebe_export':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.export')
                );
                return $this->exportAction($request, $moduleTemplate);
            case 'exportcsv':
            case 'kequestionnairebe_exportcsv':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.exportcsv')
                );
                return $this->exportCsvAction($request, $moduleTemplate);
            case 'exportcsvinterval':
            case 'kequestionnairebe_exportcsvinterval':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.exportcsv')
                );
                return $this->exportCsvIntervalAction($request, $moduleTemplate);
            case 'download':
            case 'kequestionnairebe_download':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.download')
                );
                return $this->downloadAction($request, $moduleTemplate);

            case 'analyse':
            case 'kequestionnairebe_analyse':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.analyse')
                );
                return $this->analyseAction($request, $moduleTemplate);
            case 'analyseinterval':
            case 'kequestionnairebe_analyseinterval':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.analyse')
                );
                return $this->analyseIntervalAction($request, $moduleTemplate);
            default:
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.index')
                );
                return $this->indexAction($request, $moduleTemplate);
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    protected function setUpDocHeader(ServerRequestInterface $request, $view): void
    {
        // Set up the document header (e.g., buttons, breadcrumbs, etc.)
    }

    public function authCodesAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        $query = $request->getQueryParams();

        if (is_array($query)) {
            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                    'uid' => $query['uid'] ?? '0',
                ],
            );
            if (isset($query['uid'])) {
                /** @var \Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire $plugin */
                $plugin = $this->questionnaireRepository->findByUid($query['uid']);
                $view->assign('plugin', $plugin);
                if( $plugin) {
                    $offset = (int)($query['offset'] ?? 0);
                    $limit = 20 ;
                    $total = $this->authCodeRepository->countAllForPid($plugin->getStoragePid());
                    $view->assign('authCodeCount', $total);
                    $view->assign('authCodes', $this->authCodeRepository->findForPid($plugin->getStoragePid() ,$limit , $offset));
                    $view->assign('limit', $limit );
                    $i = 1 ;
                    while ($total > $limit) {

                        $pages[] = [
                            'offset' => ($i - 1) * $limit,
                            'label' => $i ,
                            'active' => (($i - 1 ) * $limit == $offset ? true : false )
                        ];
                        $i++ ;
                        $total -= $limit;
                    }
                    $pages[] = [
                        'offset' => ($i - 1) * $limit,
                        'label' => $i ,
                        'active' => (($i - 1 ) * $limit == $offset ? true : false )
                    ];
                    $view->assign('pages', $pages );
                } else {
                    $message = GeneralUtility::makeInstance(FlashMessage::class,
                        $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.questionnaireNotFound', true),
                        '',
                        FlashMessage::ERROR
                    );
                    $this->flashMessageQueue->addMessage($message);
                }
                return $view->renderResponse('Backend/AuthCodes');
            }
        }
        return $this->indexAction($request, $view);
    }

    public function authCodesMailAction(ServerRequestInterface $request, $view = null): ResponseInterface
    {
        if ( !$view ) {
            $view = $this->moduleTemplateFactory->create($request);
            $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
            $this->setUpDocHeader($request, $view);
        }
        $settings = EmConfigurationUtility::getEmConf(false);
        $view->assign("authCodeLength" , (int)($settings['authCodeLength'] ?? 10));
        $query = $request->getQueryParams();
        if (is_array($query)) {
            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                    'uid' => $query['uid'] ?? '0',
                ],
            );
            if (isset($query['uid'])) {
                $view->assign('questionnaire', $this->questionnaireRepository->findByUid($query['uid']));
                return $view->renderResponse('Backend/AuthCodesMail');
            }
        }
        return $this->indexAction($request, $view);
    }

    public function createAndMailAuthCodesAction(ServerRequestInterface $request): ResponseInterface
    {
        $settings = EmConfigurationUtility::getEmConf(false);
        $defaultAuthCodeLength = (int)($settings['authCodeLength'] ?? 10);

        $query = $request->getQueryParams();
        $form = $request->getParsedBody();
        if (is_array($form)) {

            $emails = strip_tags($form['emails'] ?? '');
            if (strpos($emails, ',') !== false) {
                $emails = GeneralUtility::trimExplode(',', $emails, true);
            } elseif (strpos($emails, ',') !== false) {
                $emails = GeneralUtility::trimExplode(';', $emails, true);
            } else {
                $emails = GeneralUtility::trimExplode("\n", $emails, true);
            }
            foreach ($emails as $key => $email) {
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    unset($emails[$key]);
                }
            }
            $amount = count($emails) ;
            $authCodeLength = (int)((int)$form['length'] > 3 ? $form['length'] : $defaultAuthCodeLength);
            $pid = (int)($form['id'] ?? 0);

            $generated = 0 ;

            /* @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager */
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
            foreach ($emails as $key => $email) {

                if (GeneralUtility::validEmail($email)) {
                    /** @var AuthCode $newAuthCode */
                    $newAuthCode = new AuthCode() ;

                    $newAuthCode->generateAuthCode($authCodeLength, $pid);
                    $newAuthCode->setPid($pid);
                    $newAuthCode->setEmail($email);

                    $this->authCodeRepository->add($newAuthCode);
                    $mailSender = GeneralUtility::makeInstance(Mail::class);
                    $mailSender->setPlugin( $this->questionnaireRepository->findByUid($query['uid'] ));
                    $mailSender->init($email , $newAuthCode) ;
                    $mailSender->sendMail();

                    $generated++;
                    $persistenceManager->persistAll();
                }

            }

            $message = GeneralUtility::makeInstance(FlashMessage::class,
                $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.authCodesCreated', true) . ': ' . $generated,
                '',
                FlashMessage::OK
            );
            $this->flashMessageQueue->addMessage($message);
        }

        $view = $this->moduleTemplateFactory->create($request);
        $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
        $this->setUpDocHeader($request, $view);

        /** @var \Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire $plugin */
        $plugin = $this->questionnaireRepository->findByUid($query['uid'] ?? 0 );
        $view->assign('plugin', $plugin);
        if( $plugin) {
            $view->assign('authCodes', $this->authCodeRepository->findAllForPid($plugin->getStoragePid()));
        } else {
            $message = GeneralUtility::makeInstance(FlashMessage::class,
                $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.questionnaireNotFound', true),
                '',
                FlashMessage::ERROR
            );
            $this->flashMessageQueue->addMessage($message);
        }
        return $view->renderResponse('Backend/AuthCodes');
    }




    public function remindAndMailAuthCodesAction(ServerRequestInterface $request, $view = null): ResponseInterface
    {
        $view = $this->moduleTemplateFactory->create($request);
        $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
        $this->setUpDocHeader($request, $view);

        $query = $request->getQueryParams();
        if (is_array($query) && isset($query['code'])) {

            $AuthCode = $this->authCodeRepository->findByUid((int)$query['code']);
            if (!$AuthCode) {
                $message = GeneralUtility::makeInstance(FlashMessage::class,
                    $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.authCodeNotFound', true),
                    '',
                    FlashMessage::ERROR
                );
                $this->flashMessageQueue->addMessage($message);
                return $this->authCodesAction($request, $view);
            }
            $mailSender = GeneralUtility::makeInstance(Mail::class);
            $mailSender->setPlugin( $this->questionnaireRepository->findByUid($query['uid'] ));
            $mailSender->init($AuthCode->getEmail() , $AuthCode->getAuthCode()) ;
            $mailSender->sendMail();

        }

        return $this->authCodesAction($request, $view);
    }

    public function authCodesSimpleAction(ServerRequestInterface $request, $view = null): ResponseInterface
    {
        if ( !$view ) {
            $view = $this->moduleTemplateFactory->create($request);
            $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
            $this->setUpDocHeader($request, $view);
        }
        $settings = EmConfigurationUtility::getEmConf(false);
        $view->assign("authCodeLength" , (int)($settings['authCodeLength'] ?? 10));
        $query = $request->getQueryParams();
        if (is_array($query)) {
            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                    'uid' => $query['uid'] ?? '0',
                ],
            );
            if (isset($query['uid'])) {
                $view->assign('questionnaire', $this->questionnaireRepository->findByUid($query['uid']));
                return $view->renderResponse('Backend/AuthCodesSimple');
            }
        }
        return $this->indexAction($request, $view);
    }


    public function createAuthCodesAction(ServerRequestInterface $request ): ResponseInterface
    {
        $view = $this->moduleTemplateFactory->create($request);
        $title =  $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:mlang_tabs_tab');
        $view->setTitle(
            $title,
            $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authorize.xlf:simple.title')
        );
        $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
        $settings = EmConfigurationUtility::getEmConf(false);
        $defaultAuthCodeLength = (int)($settings['authCodeLength'] ?? 10);
        
        $query = $request->getQueryParams();
        $form = $request->getParsedBody();
        if (is_array($form)) {

            $amount = ($form['amount'] ? (int)$form['amount'] :  1);
            $authCodeLength = (int)((int)$form['length'] > 3 ? $form['length'] : $defaultAuthCodeLength);
            $pid = (int)($form['id'] ?? 0);

            /* @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager */
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');

            //create the codes and store them in the storagepid of the plugin
            $generated = 0 ;
            for ($i = 0; $i < $amount; $i++){
                /** @var AuthCode $newAuthCode */
                $newAuthCode = new AuthCode() ;

                $newAuthCode->generateAuthCode($authCodeLength,$pid);
                $newAuthCode->setPid($pid);

                $this->authCodeRepository->add($newAuthCode);
                $generated++;

            }
            $persistenceManager->persistAll();

            $message = GeneralUtility::makeInstance(FlashMessage::class,
                $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.authCodesCreated', true) . ': ' . $generated,
                '',
                FlashMessage::OK
            );
            $this->flashMessageQueue->addMessage($message);
        }


        $this->setUpDocHeader($request, $view);
        /** @var \Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire $plugin */
        $plugin = $this->questionnaireRepository->findByUid($query['uid'] ?? 0 );
        $view->assign('plugin', $plugin);
        if( $plugin) {
            $view->assign('authCodes', $this->authCodeRepository->findAllForPid($plugin->getStoragePid()));
        } else {
            $message = GeneralUtility::makeInstance(FlashMessage::class,
                $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod_authcode.xlf:message.questionnaireNotFound', true),
                '',
                FlashMessage::ERROR
            );
            $this->flashMessageQueue->addMessage($message);
        }
        return $view->renderResponse('Backend/AuthCodes');
    }
    
    /*   #############################################################################################################
        * Export action to handle exporting data, e.g., to CSV or other formats.
        *
        * @param ServerRequestInterface $request
        * @param $view
        * @return ResponseInterface
        *
        * Note: The actual export logic should be implemented here.
    */
    

    public function exportAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        $query = $request->getQueryParams();
        if (is_array($query)) {

            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                ],
            );
        }
        $view->assign('questionnaires',$this->questionnaireRepository->findByStoragePid($query['id']));
        return $view->renderResponse('Backend/Export');
    }

    public function exportCsvAction(ServerRequestInterface $request, $view = null): ResponseInterface
    {
        if (!file_exists(Environment::getPublicPath() . '/' . $this->pathName)) {
            mkdir(Environment::getPublicPath() . '/' . $this->pathName, 0777);
            chmod(Environment::getPublicPath() . '/' . $this->pathName, 0777);
        }
        if ( !$view ) {
            $view = $this->moduleTemplateFactory->create($request);
            $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
            $this->setUpDocHeader($request, $view);
        }
        $settings = EmConfigurationUtility::getEmConf(false);
        $query = $request->getQueryParams();
        if (is_array($query)) {
            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                    'uid' => $query['uid'] ?? '0',
                ],
            );
            if (isset($query['uid'])) {
                $view->assign('plugin', $this->questionnaireRepository->findByUid($query['uid']));
                /** @var ResultRepository $resultRepository */
                $resultRepository = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository::class);

                $view->assign('all', $resultRepository->countFinishedForPid($query['id'],  -1) );
                $view->assign('finished', $resultRepository->countFinishedForPid($query['id'],  0) );

                $fileName = 'export_' . $query['uid'] . '_' . date('Ymd_His') . '.csv';

                $view->assign('fileName', $fileName);
                return $view->renderResponse('Backend/ExportCsv');
            }
        }

        return $this->exportAction($request, $view);
    }

    public function exportCsvIntervalAction(ServerRequestInterface $request, $view = null): ResponseInterface
    {
        if ( !$view ) {
            $view = $this->moduleTemplateFactory->create($request);
            $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
            $this->setUpDocHeader($request, $view);
        }
        $settings = EmConfigurationUtility::getEmConf(false);
        $query = $request->getQueryParams();
        $body = $request->getParsedBody();
        $moduleData = $request->getAttribute('moduleData');
        $responseFactory = GeneralUtility::makeInstance(ResponseFactoryInterface::class) ;
        if (is_array($query)) {

            // plugin uid and page id must given
            if (isset($query['uid']) && isset($query['id'])) {

                $fileNameCheck = 'export_' . $query['uid'] . '_' . date('Ymd') ;
                Debug::store($query['uid'], $query , "debug_csv_interval_export");
                $pluginObj = $this->questionnaireRepository->findByUid($query['uid']);
                $plugin['pages'] = $query['id'];
                $plugin['header'] = $pluginObj->getHeader();

                if ( str_starts_with( $query['target'] ?? '' , $fileNameCheck ) ) {
                    $fileName = $query['target'] ;
                    /** @var ResultRepository $resultRepository */
                    $resultRepository = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository::class);

                    /** @var CsvExport $csvExport */
                    $csvExport = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Utility\CsvExport::class);
                    $csvExport->init() ;
                    $fileWithPath = Environment::getPublicPath() . '/' . $this->pathName ."/". $fileName;
                    if (isset($query['current']) && isset($query['max'])) {
                        $current = (int)$query['current'];
                        $max = (int)$query['max'];

                        if (!file_exists(Environment::getPublicPath() . '/' . $this->pathName)) {
                            mkdir(Environment::getPublicPath() . '/' . $this->pathName, 0777);
                            chmod(Environment::getPublicPath() . '/' . $this->pathName, 0777);
                        }
                        $csvContent= '';
                        $resultUid= 0;
                        $questions = $csvExport->getQuestions($plugin) ;
                        if( $current == 0 ) {
                            // write headline s to file
                            $csvContent = $csvExport->createRBHeader($plugin , $questions);
                        } else {
                            //add results
                            if( $query['onlyFinished'] ) {
                                $rowForCsv = $resultRepository->findFinishedForPidIntervalRaw($query['id'],  $current -1 , 0) ;
                            } else {
                                $rowForCsv = $resultRepository->findFinishedForPidIntervalRaw($query['id'],  $current -1, -1) ;
                            }
                            $resultUid = (int)($rowForCsv[0]['uid'] ?? 0);
                            if( $resultUid ) {
                                $csvContent = $csvExport->createRBLines($rowForCsv[0], $questions);
                            }
                        }

                        $csvFile = fopen($fileWithPath, 'a+');
                        //write the js
                        if( $csvFile ) {
                            if($csvContent) {
                                fwrite($csvFile, $csvContent);
                            }
                            fclose($csvFile);
                            chmod($fileWithPath, 0777);

                        } else {
                            $data = [
                                'current' => $current ,
                                'resultuid' => $resultUid ,
                                'max' => $max,
                                'message' => ($csvFile ? 'ERROR: no content for row' :'ERROR: could not open file for writing ' . $fileName ) ,
                                'finished' => true ,
                                'success' => false,
                            ] ;
                            $response = $responseFactory->createResponse()
                                ->withHeader('Content-Type', 'application/json; charset=utf-8');
                            $response->getBody()->write(json_encode($data));
                            return $response;
                        }

                        $current++ ;
                    } else {
                        $current = 0;
                        $max = 0;
                    }
                    $data = [
                        'current' => $current ,
                        'resultuid' => $resultUid ,
                        'max' => $max,
                        'message' => ( $current <= $max ? 'running' : 'finished' ),
                        'finished' => ( $current <= $max ? false : true ),
                        'success' => true,
                        'length' => (filesize($fileWithPath) ?? 0 )
                    ] ;

                } else {
                    $data = [
                        'current' => $current ,
                        'resultuid' => 0 ,
                        'max' => $max,
                        'message' => 'ERROR: given filename ' . $query['target']  . ' should start with ' . $fileNameCheck ,
                        'finished' => true ,
                        'success' => false,
                    ] ;
                }


                $response = $responseFactory->createResponse()
                    ->withHeader('Content-Type', 'application/json; charset=utf-8');
                $response->getBody()->write(json_encode($data));
                return $response;
            }
        }
        return $this->exportAction($request, $view);
    }

    public function downloadAction(ServerRequestInterface $request ): ResponseInterface
    {
        $view = $this->moduleTemplateFactory->create($request);
        $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
        $this->setUpDocHeader($request, $view);

        $settings = EmConfigurationUtility::getEmConf(false);
        $query = $request->getQueryParams();
        $body = $request->getParsedBody();
        $moduleData = $request->getAttribute('moduleData');
        // plugin uid  must given
        if (isset($query['uid']) ) {
            $fileNameCheck = 'export_' . $query['uid'] . '_' . date('Ymd') ;
            if ( str_starts_with( $query['target'] ?? '' , $fileNameCheck ) ) {
                $fileName = $query['target'] ;
                $filePath = Environment::getPublicPath() . '/' . $this->pathName ."/". $fileName;
                if (file_exists($filePath)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filePath));
                    readfile($filePath);
                    unlink($filePath);
                    exit;
                } else {
                    $message = GeneralUtility::makeInstance(FlashMessage::class,
                        $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:message.fileNotFound', true) . ': ' . $fileName,
                        '',
                        FlashMessage::ERROR
                    );
                    $this->flashMessageQueue->addMessage($message);
                }
            } else {
                $message = GeneralUtility::makeInstance(FlashMessage::class,
                    $this->getLanguageService()->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:message.fileNameInvalid', true) . ': ' . ($query['target'] ?? '')  . ' should start with ' . $fileNameCheck,
                    '',
                    FlashMessage::ERROR
                );
                $this->flashMessageQueue->addMessage($message);
            }
        }

        return $this->exportAction($request, $view);
    }

    public function analyseAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        $query = $request->getQueryParams();
        $id = 0 ;
        $uid = 0 ;
        if (is_array($query)) {
            $id = $query['id'] ?? '0';
            $uid = $query['uid'] ?? '0';

            $view->assignMultiple(
                [
                    'id' => $id,
                    'uid' => $uid,
                ],
            );
        }
        if ( $uid ) {
            $questionnaire =  $this->questionnaireRepository->findForUid($uid) ?? null ;
            if( $questionnaire) {
                $view->assign('questionnaire',$questionnaire );
                $questionRepository = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository::class);
                $questions = $questionRepository->findAllForPidtoExport($id);
                if ( $questions ) {
                    $view->assign('questions', $questions);
                    $view->assign('all', $questions->count() );
                }
                $resultRepository = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository::class);

                $view->assign('results', $resultRepository->countFinishedForPid($id,  -1) );

            }




        } else  {
            $view->assign('questionnaires', $this->questionnaireRepository->findByStoragePid($id ) ?? null);

        }

        return $view->renderResponse('Backend/Analyse');
    }

    public function analyseIntervalAction(ServerRequestInterface $request, $view = null ): ResponseInterface
    {
        if ( !$view ) {
            $view = $this->moduleTemplateFactory->create($request);
            $view->assign("flashMessageQueueIdentifier" , self::MESSAGE_QUEUE_IDENTIFIER);
            $this->setUpDocHeader($request, $view);
        }
        $settings = EmConfigurationUtility::getEmConf(false);
        $query = $request->getQueryParams();
        $body = $request->getParsedBody();
        $moduleData = $request->getAttribute('moduleData');
        $responseFactory = GeneralUtility::makeInstance(ResponseFactoryInterface::class) ;
        if (is_array($query)) {

            // Question uid and page id must given
            if (isset($query['uid']) && isset($query['id'])) {
                $max = $query['max'];
                $questionUid = $query['uid'];
                $current = $query['current'];

                /** @var ResultQuestionRepository $questionRepository */
                $questionRepository = GeneralUtility::makeInstance(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository::class);
                $results = $questionRepository->findByQuestionId($questionUid);
                $answers = [];
                $total = 0 ;
                /** @var \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion $result */
                foreach ( $results as $result ) {
                    $tempAnswers = [];
                    $temp = $result->getAnswers();
                    if( $temp ) {
                        foreach ($temp as $answer) {
                            $total ++ ;
                            if( isset($answers[$answer->getAnswer()->getUid()])) {
                                $answers[$answer->getAnswer()->getUid()]['value'] += 1 ;
                            } else {
                                $answers[$answer->getAnswer()->getUid()] = [ 'value'  => 1 , 'uid' => $answer->getAnswer()->getUid() ] ;
                            }
                        }
                    }
                }
                $finalAnswers = [];
                if ($total >0 && is_array($answers) ) {
                    foreach ($answers as $answer) {
                        $finalAnswers[] = [
                            'uid' => $answer['uid'],
                            'value' => $answer['value'],
                            'html' => $answer['value'],
                            'width' => (int)(($answer['value'] / $total )*100) ."%",
                        ];
                    }
                }

                $current ++ ;
                $data = [
                    'current' => $current  ,
                    'questionuid' => $questionUid ,
                    'total' => $total ,
                    'answers' => $finalAnswers ,
                    'max' => $max,
                    'message' => ( $current <= $max ? 'running' : 'finished' ),
                    'finished' => ( $current <= $max ? false : true ),
                    'success' => true,
                ] ;



                $response = $responseFactory->createResponse()
                    ->withHeader('Content-Type', 'application/json; charset=utf-8');
                $response->getBody()->write(json_encode($data));
                return $response;
            }
        }
        return $this->exportAction($request, $view);
    }

    public function indexAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        $query = $request->getQueryParams();
        if (is_array($query)) {

            $view->assignMultiple(
                [
                    'id' => $query['id'] ?? '0',
                ],
            );
        }
        $view->assign('questionnaires',$this->questionnaireRepository->findAll());
        return $view->renderResponse('Backend/Index');
    }
}