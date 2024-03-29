<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;

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
 * get the cloned row
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GetCloneableMatrixRowViewHelper extends AbstractViewHelper {

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
    public function initializeArguments() {
        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader', ' The answerType ', true );
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', 'the question object  ', false );
        parent::initializeArguments() ;
    }

	/**
	 * Adds the needed Javascript-File to Additional Header Data
	 *
	 * @return MatrixRow
	 */
	public function render() {
        /** @var Question $question */
        $question = $this->arguments['question'] ;

        /** @var MatrixHeader $answer */
        $answer = $this->arguments['answer'] ;

        return $answer->getCloneableRow($question);
	}
}
?>