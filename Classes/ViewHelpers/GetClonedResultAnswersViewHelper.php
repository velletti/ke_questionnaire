<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use Kennziffer\KeQuestionnaire\Domain\Model\Result;
use Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer;
use Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
 * get the cloned result-part
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GetClonedResultAnswersViewHelper extends AbstractViewHelper {


    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;


    /** * Constructor *
     * @api */
    public function initializeArguments(): void {
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', ' The result ', true );
        $this->registerArgument('questionUid', 'integer', 'the question id  ', true );
        $this->registerArgument('answerUid', 'integer', 'the answer id  ', true );
        parent::initializeArguments() ;
    }
	/**
	 * Returns a requested question from result record
	 *
	 * @return
	 */
    public function render() {

        /** @var Result $result */
        $result = $this->arguments['result'] ;
        $questionUid = $this->arguments['questionUid'] ;
        $answerUid = $this->arguments['answerUid'] ;


        $resultQuestions = $result->getQuestions();
        /* @var $resultQuestion ResultQuestion */
		foreach ($resultQuestions as $resultQuestion) {
            if ($questionUid == $resultQuestion->getQuestion()->getUid()) {
				/* @var $resultAnswer ResultAnswer */
				return $resultQuestion->getClonedAnswers();
			}
		}
        return NULL;
	}
}
?>