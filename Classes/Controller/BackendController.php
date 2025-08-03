<?php

declare(strict_types=1);

namespace Kennziffer\KeQuestionnaire\Controller;

use Kennziffer\KeQuestionnaire\Domain\Model\AuthCode;
use Kennziffer\KeQuestionnaire\Utility\EmConfigurationUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \Kennziffer\KeQuestionnaire\Domain\Repository\QuestionnaireRepository;
use \Kennziffer\KeQuestionnaire\Domain\Repository\AuthCodeRepository;
use TYPO3\CMS\Core\Messaging\FlashMessageRendererResolver;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Kennziffer\KeQuestionnaire\Utility\Mail;
use TYPO3\CMS\Fluid\View\StandaloneView;

class BackendController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected QuestionnaireRepository $questionnaireRepository;
    protected AuthCodeRepository $authCodeRepository;
    protected FlashMessageQueue $flashMessageQueue;
    protected FlexFormService $flexFormService;
    CONST MESSAGE_QUEUE_IDENTIFIER = 'kequestionnairebe';

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
                'authCodesMail' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.mail')
                ),
                'export' => htmlspecialchars(
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:export')
                ),
                'analyse' => htmlspecialchars(
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
            case 'authCodes':
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
            case 'authcodesmail':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:menu.mail')
                );
                return $this->authCodesMailAction($request, $moduleTemplate);

            case 'export':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.export')
                );
                return $this->exportAction($request, $moduleTemplate);
            case 'analyse':
                $moduleTemplate->setTitle(
                    $title,
                    $languageService->sL('LLL:EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xlf:module.menu.analyse')
                );
                return $this->analyseAction($request, $moduleTemplate);
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

        return $this->authCodesAction($request, $view);
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


    public function createAuthCodesAction(ServerRequestInterface $request): ResponseInterface
    {
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
                $persistenceManager->persistAll();
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

        return $this->authCodesAction($request, $view);
    }
    
    
    

    public function exportAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        // Implement logic for the export action
        return $moduleTemplate->renderContent('Export action executed.');
    }

    public function analyseAction(ServerRequestInterface $request, $view): ResponseInterface
    {
        // Implement logic for the analyse action
        return $moduleTemplate->renderContent('Analyse action executed.');
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