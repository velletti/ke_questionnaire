<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
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
 * get the result answer for the matrix
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GetResultAnswerMatrixViewHelper extends AbstractViewHelper {


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
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', ' The result ', true );
        $this->registerArgument('questionUid', 'int', 'the question id  ', true );
        $this->registerArgument('rowUid', 'int', 'the rowUid id  ', true );
        $this->registerArgument('columnUid', 'int', 'the columnUid id  ', true );
        $this->registerArgument('matrixType', 'string', 'the matrixType id  ', false ,  'normal' );
        $this->registerArgument('radio', 'boolean', 'is radio ', false ,  false );
        $this->registerArgument('clone', 'int', 'is clone / to be cloned  ?? ', false ,  0 );
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
        $rowUid = $this->arguments['rowUid'] ;
        $columnUid = $this->arguments['columnUid'] ;
        $matrixType = $this->arguments['matrixType'] ;
        $radio = $this->arguments['radio'] ;
        $clone = $this->arguments['clone'] ;

		if (!$radio){
			$rAnswer = $result->getAnswer($questionUid, $rowUid, $columnUid);
			if ($rAnswer) {
				return $rAnswer;
			}
		} else {
			$rAnswer = $result->getRadioAnswer($questionUid, $rowUid, $columnUid);
			return $rAnswer;
		}
		return NULL;
	}
}
?>