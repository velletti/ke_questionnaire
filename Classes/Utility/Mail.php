<?php
namespace Kennziffer\KeQuestionnaire\Utility;

use TYPO3\CMS\Core\Mail\MailMessage;
use Kennziffer\KeQuestionnaire\Domain\Model\ExtConf;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Kennziffer\KeQuestionnaire\Exception;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Fluid\View\StandaloneView;

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
class Mail {

	/**
  * SwiftMailer
  *
  * @var MailMessage
  */
 protected $message = NULL;
 protected $flexform = NULL;
 protected $plugin = NULL;
 protected $mailRenderer = NULL;

	/**
  * @var ExtConf
  */
 protected $extConf;
 public function __construct(\TYPO3\CMS\Core\Mail\MailMessage $message, \Kennziffer\KeQuestionnaire\Domain\Model\ExtConf $extConf)
 {
     $this->message = $message;
     $this->extConf = $extConf;
 }

    /**
     * @return array
     */
    public function getFlexform()
    {
        return ($this->flexform ?? []);
    }

    /**
     * @param mixed $flexform
     */
    public function setPlugin($plugin): void
    {
        $this->plugin = $plugin ;
        $this->flexform = ($plugin && $plugin->getPiFlexForm()['settings']['email']['invite'] ? $plugin->getPiFlexForm()['settings']['email']['invite'] : null );
    }

    public function init($email , $authCode) {
        // get standAlone mail renderer
        $this->mailRenderer = GeneralUtility::makeInstance(StandaloneView::class);
        $this->mailRenderer->setLayoutRootPaths(
            [GeneralUtility::getFileAbsFileName('EXT:ke_questionnaire/Resources/Private/Layouts/')]
        );
        $this->mailRenderer->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:ke_questionnaire/Resources/Private/Templates/Backend/CreatedMail.html')
        );

        $settings = EmConfigurationUtility::getEmConf(false);
        $settings['fontFamily'] = $settings['fontFamily'] ?? 'Arial, Helvetica, sans-serif';
        $settings['FontColor'] = $settings['FontColor2'] ?? '#FF221a';
        $settings['FontColor2'] = $settings['FontColor2'] ?? '#333333';
        $settings['BackGroundColorBody'] = $settings['BackGroundColorBody'] ?? '#FFFFFF';
        $settings['BackGroundColorBody2'] = $settings['BackGroundColorBody2'] ?? '#FFFFFF';
        $settings['BackGroundColor'] = $settings['BackGroundColor'] ?? '#F1F1F1';
        $settings['BackGroundColor2'] = $settings['BackGroundColor2'] ?? '#A1A1A1';

        $this->mailRenderer->assign("settings", $settings);
        $this->mailRenderer->assign("signature", false);

        $pid = $this->plugin->getPid();

        $subject =  $this->flexform['subject'];
        $text =  $this->flexform['text'];

        $field = 'Email';
        $text['before'] = str_replace('###' . ($field) . '###', $email, $text['before']);
        $text['before'] = str_replace('###' . strtoupper($field) . '###', $email, $text['before']);
        $text['before'] = str_replace('###' . strtolower($field) . '###', $email, $text['before']);
        $text['after'] = str_replace('###' . ($field) . '###', $email, $text['after']);
        $text['after'] = str_replace('###' . strtoupper($field) . '###', $email, $text['after']);
        $text['after'] = str_replace('###' . strtolower($field) . '###', $email, $text['after']);

        try {
            /** @var UriBuilder $uriBuilder */

            $uriBuilder = GeneralUtility::makeInstance( UriBuilder::class ) ;
            $uri = $uriBuilder->reset()
                ->setTargetPageUid($pid)
                ->setAbsoluteUriScheme('https')
                ->setCreateAbsoluteUri(true)
                ->buildFrontendUri() ;

        } catch  ( \Exception ) {
            $uri = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . GeneralUtility::getIndpEnv('TYPO3_SITE_PATH') . 'index.php?id=' . $pid . '&authCode=' . $authCode;
        }



        $this->mailRenderer->assign('uri',$uri);
        $this->mailRenderer->assign('authCode',$authCode);
        $this->mailRenderer->assign('text',$text);

        $plainText = strip_tags( $text['before']) . "\n" . $authCode . "\n" . strip_tags( $text['after'] );
        $htmlText = $this->mailRenderer->render() ;
        $this->setSubject($subject);
        $this->addReceiver($email);
        $this->setHtml($htmlText ) ;
        $this->setBody($plainText ) ;
    }


	/**
	 * Returns the body
	 *
	 * @return string|array $body
	 */
	public function getBody() {
		if(!$this->message->getBody()) {
			// if body is not try to get attached children like HTML parts
			if(count($this->message->getChildren())) {
				return $this->message->getChildren();
			} else return '';
		} else return $this->message->getBody();
	}

	/**
	 * Sets the body
	 *
	 * @param string $body
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function setBody($body) {
	    $body = strip_tags( str_replace(  "</p>" , "\n" , $body) ) ;
		$this->message->text($body , 'utf-8');
		return $this;
	}

	/**
	 * Sets the html
	 *
	 * @param string $html
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function setHtml($html) {
		$this->message->html($html, 'utf-8');
		return $this;
	}

	/**
	 * Returns the subject
	 *
	 * @return string $subject
	 */
	public function getSubject() {
		return $this->message->getSubject();
	}

	/**
	 * Sets the subject
	 *
	 * @param string $subject
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function setSubject($subject) {
		$this->message->setSubject($subject);
		return $this;
	}

	/**
	 * Returns the from
	 *
	 * @return string $from
	 */
	public function getFrom() {
		return $this->message->getFrom();
	}

	/**
	 * Sets the from
	 *
	 * @param string $from The from email address
	 * @param string $name The from name
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function setFrom($from, $name = NULL) {
		$this->message->setFrom($from, $name);
		return $this;
	}

	/**
	 * Returns the receivers
	 *
	 * @return array $receivers
	 */
	public function getReceivers() {
		return $this->message->getTo();
	}

	/**
	 * Sets the receivers
	 * By using this method the key should be the mail address
	 *
	 * @param array $receivers
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function setReceivers($receivers) {
		$this->message->setTo($receivers);
		return $this;
	}

	/**
	 * Add a receiver
	 *
	 * @param string $receiver The receivers email address
	 * @param string $name The receivers name
	 * @return \Kennziffer\KeQuestionnaire\Utility\Mail
	 */
	public function addReceiver($receiver, $name = NULL) {
		$this->message->addTo($receiver, $name);
		return $this;
	}

	/**
	 * send mail
	 *
	 * @return integer amount of valid receivers
	 */
	public function sendMail() {
		if($this->validateMessage()) {
			$number = $this->message->send();
			$this->message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
			return $number;
		} else return 0;
	}

	/**
	 * validate message
	 *
	 * @return boolean
	 */
	protected function validateMessage() {
		if(!$this->getFrom()) {
			// first: try to get an alternative mail address and sender name from extConf
			if($this->extConf->getEmailAddress()) {
				if($this->extConf->getEmailSender()) {
					$this->setFrom($this->extConf->getEmailAddress(), $this->extConf->getEmailSender());
				} else {
					$this->setFrom($this->extConf->getEmailAddress());
				}
			} else {
				// second: try to get an alternative mail address and sender name from INSTALL_TOOL
				if (empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'])) {
					// third: no email address found. EXIT
					throw new Exception('mailNoFrom', 1349702098);
				} else {
					if (empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'])) {
						$this->setFrom($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress']);
					} else {
						$this->setFrom($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'], $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName']);
					}
				}
			}
		}
		if(count($this->getReceivers()) === 0) {
			throw new Exception('mailNoReceivers', 1349702831);
		}
		if(!$this->getSubject()) {
			throw new Exception('mailNoSubject', 1349702835);
		}
		if(!$this->getBody()) {
			throw new Exception('mailNoBody', 1349702840);
		}
		return true;
	}
}
?>