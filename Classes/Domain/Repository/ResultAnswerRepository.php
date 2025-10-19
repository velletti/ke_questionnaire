<?php
namespace Kennziffer\KeQuestionnaire\Domain\Repository;
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

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Result;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;

/**
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ResultAnswerRepository extends Repository
{
    /**
     * get a raw result => no object, only array
     */
    public function findForResultQuestionRaw($questionId)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('resultquestion', $questionId));

        return $query->execute(true);
    }

    /**
     * get a raw result => no object, only array
     */
    public function findForResultQuestionAndAnswerRaw($questionId, $answerId, $returnRaw = true)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $constraint = $query->logicalAnd(
            $query->equals('resultquestion', $questionId)
            , $query->equals('answer', $answerId)
        );
        $query->matching($constraint);
        $this->log(__METHOD__, $query);
        return $query->execute($returnRaw);
    }

    /**
     * find resultanswer for answer
     */
    public function findForAnswer($answer)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('answer', $answer));

        return $query->execute();
    }

    /**
     * get a raw result => no object, only array
     */
    public function findForAnswerRaw($answer)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('answer', $answer));

        return $query->execute(true);
    }

    /**
     * count given answers
     * @param Answer $answer
     */
    public function countResultAnswersForAnswer(Answer $answer)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('answer', $answer));

        return $query->count();
    }

    /**
     * count given answers
     * @param Answer $answer
     */
    public function countResultAnswersForAnswerAndValue(Answer $answer)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('answer', $answer),
                $query->equals('value', $answer->getUid())
            ));

        return $query->count();
    }

    /**
     * getspecific Result Answer
     * @param Question $question
     * @param Result $result
     */
    public function getResultAnswersForQuestionAndResult(Question $question, Result $result)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('question', $question));

        return $query->execute();
    }

    /**
     *
     * @param Answer $row
     * @param string $value
     * @return integer
     */
    public function countResultAnswersForRowAndValue(Answer $row, $value)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('answer', $row)
                , $query->equals('value', $value)
            ));

        return $query->count();
    }

    /**
     *
     * @param Answer $row
     * @param string $value
     * @return integer
     */
    public function countResultAnswersForRowAndCol(Answer $row, $value)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('answer', $row)
                , $query->equals('col', $value)
            ));

        return $query->count();
    }

    /**
     *
     * @param Answer $row
     * @param string $value
     * @return integer
     */
    public function getResultAnswersForRowAndCol(Answer $row, $value)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('answer', $row)
                , $query->equals('col', $value)
            ));

        return $query->execute();
    }

    /**
     *
     * @param Answer $row
     * @param string $value
     * @return integer
     */
    public function getResultAnswersForRowAndColRaw(Answer $row, $value)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('answer', $row)
                , $query->equals('col', $value)
            ));

        return $query->execute(true);
    }

    /**
     * get related result answers to answer
     * @param Answer $answer
     * @return array|QueryResultInterface
     */
    public function getResultAnswersForAnswer(Answer $answer)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching($query->equals('answer', $answer));

        return $query->execute();
    }
    private function log( $method , \TYPO3\CMS\Extbase\Persistence\QueryInterface $query ){
        $queryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);

        $sqlquery = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL() ;
        $values = ($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters()) ;
        foreach (array_reverse( $values ) as $key => $value) {
            $from[] = ":" .$key ;
            $to[] = "'" . $value . "'" ;
        }
        $sqlFinalQuery = str_replace($from , $to , (string) $sqlquery ) ;
        $this->logToFile( 'Method: "' . $method . '" SQL: ' . PHP_EOL . $sqlFinalQuery ) ;
    }
    public function logToFile(  $text ){
        if ( str_ends_with($_SERVER['SERVER_NAME'] , 'ddev.site' ) || file_exists(Environment::getProjectPath() . '/var/log/_ENABLE_KEQLOG_') ){
            $fn =fopen( Environment::getProjectPath() . '/var/log/keq.log' , 'a+' );
            if ( $fn ){
                fwrite( $fn , date('Y-m-d H:i:s') . ' ' . $text . PHP_EOL . PHP_EOL  );
                fclose( $fn );
            }
        }
    }
}