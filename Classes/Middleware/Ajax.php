<?php

namespace Kennziffer\KeQuestionnaire\Middleware;

use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Ajax
 * @package JVE\JvEvents\Middleware
 */
class Ajax implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws InvalidExtensionNameException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $_gp = $request->getQueryParams();
        // examples:
        // https://wwwv10.allplan.com.ddev.site/?id=110&L=1&tx_jvevents_ajax[event]=4308&tx_jvevents_ajax[action]=eventList&tx_jvevents_ajax[controller]=Ajax&tx_jvevents_ajax[eventsFilter][categories]=14&tx_jvevents_ajax[eventsFilter][sameCity]=1&tx_jvevents_ajax[eventsFilter][skipEvent]=&tx_jvevents_ajax[eventsFilter][startDate]=30&tx_jvevents_ajax[mode]=onlyValues
        // https://tangov10.ddev.site/?id=110&L=1&tx_jvevents_ajax[event]=4308&tx_jvevents_ajax[action]=eventList&tx_jvevents_ajax[controller]=Ajax&tx_jvevents_ajax[eventsFilter][categories]=14&tx_jvevents_ajax[eventsFilter][sameCity]=1&tx_jvevents_ajax[eventsFilter][skipEvent]=&tx_jvevents_ajax[eventsFilter][startDate]=30&tx_jvevents_ajax[mode]=onlyValues


        $path = $request->getUri()->getPath() ;

        if( is_array($_gp) && key_exists("tx_kequestionnaire_questionnaire" ,$_gp ) && strpos("..." . $path , "keAnswerValidation.json" ) > 1 ) {
            $arguments = $_gp['tx_kequestionnaire_questionnaire']  ;
            $type = $arguments['type'] ;
            $requestedClassName = 'Kennziffer\\KeQuestionnaire\\Ajax\\' . $type;
            if (class_exists($requestedClassName)) {
                $object = GeneralUtility::makeInstance($requestedClassName) ;
                $object->settings = $this->settings;
                $result = $object->processAjaxRequest($arguments['arguments']);
            } else {
                $result = '';
            }


                $body = new Stream('php://temp', 'rw');
                $body->write($result);
                return (new Response())
                    ->withHeader('content-type',  'text/json; charset=utf-8')
                    ->withBody($body)
                    ->withStatus(200);


        }
        return $handler->handle($request);
    }




}
