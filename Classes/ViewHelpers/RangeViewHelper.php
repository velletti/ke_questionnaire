<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Kennziffer\KeQuestionnaire\Domain\Model\Range;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Kennziffer\KeQuestionnaire\Domain\Repository\RangeRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire;
use Kennziffer\KeQuestionnaire\Domain\Model\Result;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

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
class RangeViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;


	
	/**
	 * @var Dispatcher
	 */
	protected $signalSlotDispatcher;

    /**
     * @var Questionnaire
     */
	private $questionnaire ;

    /**
     * @var Result
     */
	private $result ;

	/**
	 * inject signal slots
	 *
	 * @param Dispatcher $signalSlotDispatcher
	 * @return void
	 */
	public function injectSignalSlotDispatcher(Dispatcher $signalSlotDispatcher) {
			$this->signalSlotDispatcher = $signalSlotDispatcher;
	}
	
	/**
	 * ranges
	 *
	 * @var array
	 */
	protected $ranges;

    /** * Constructor *
     * @api */
    public function initializeArguments() {
        $this->registerArgument('questionnaire', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', ' The questionnaire ', true );
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', 'the Result object  ', false );
        $this->registerArgument('as', 'string', 'the string the name of the iteration variable  ', false );
        parent::initializeArguments() ;
    }

	/**
	 * Checks for the right Range Texts and stuff to be shown
	 *
	 * @return string
	 */
	public function render() {


		$this->result = $this->arguments['result'];
		$this->questionnaire = $this->arguments['questionnaire'];
		$as= $this->arguments['as'];

		$this->templateVariableContainer = $this->renderingContext->getVariableProvider();
		if ($this->result === NULL) {
			return '';
		}
		
		$output = '';
		//check the Ranges
        $ranges = $this->collectRanges() ;
        if( is_array($ranges)) {
            foreach ($ranges as $range){
                $this->templateVariableContainer->add($as, $range);
                $output .= $this->renderChildren();
                $this->templateVariableContainer->remove($as);
            }
        }

		return $output;
	}	
	
	/**
  * get the Ranges
  *
  * @return QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
  */
 private function collectRanges(){
		$all_ranges = $this->getRanges();
		if ( is_object($all_ranges) ||is_array($all_ranges)) {
            /** @var Range $range */
            foreach ($all_ranges as $range){
                if ($this->result->getPoints() >= $range->getPointsFrom() AND $this->result->getPoints() <= $range->getPointsUntil()){
                    $this->ranges[$range->getUid()] = $range;
                }
            }
        } else {
		    return false ;
        }

		
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'getPremiumRanges', array($this));
		return $this->ranges;
	}
	
	/**
  * get the Ranges
  *
  * @return QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
  */
 private function getRanges(){
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->configurationManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
		$this->contentObj = $this->configurationManager->getContentObject();
		$uid = $this->contentObj->data['uid'] ;

		$this->questionnaire = $this->questionnaire->loadFullObject($this->contentObj->data['uid']);
		/** @var RangeRepository $rep */
  $rep = $this->objectManager->get('Kennziffer\\KeQuestionnaire\\Domain\\Repository\\RangeRepository');
		
		$ranges = $rep->findForPid($this->questionnaire->getStoragePid());
		return $ranges;
	}
}
?>