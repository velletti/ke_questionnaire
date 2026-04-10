<?php
namespace Kennziffer\KeQuestionnaire\Utility;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;

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
class CsvExport {
	/**
	 * Field separator
	 * @var string
	 */
	protected $separator;
	
	/**
	 * text separator
	 * @var string
	 */
	protected $text;
	
	/**
	 * singleMarker
	 * @var string
	 */
	protected $singleMarker;
	
	/**
  * results
  * @var QueryResult 
  */
 protected $results;
        
        /**
	 * resultsRaw
	 * @var array
	 */
	protected $resultsRaw;
	
	/**
  * questionRepository
  *
  * @var QuestionRepository
  */
 protected $questionRepository;
	
	/**
  * resultRepository
  *
  * @var ResultRepository
  */
 protected $resultRepository;
	
	/**
  * resultQuestionRepository
  *
  * @var ResultQuestionRepository
  */
 protected $resultQuestionRepository;
	
	/**
  * resultAnswerRepository
  *
  * @var ResultAnswerRepository
  */
 protected $resultAnswerRepository;
	
	/**
	 * show Question Text
	 * @var boolean
	 */
	protected $showQText = true;
	
	/**
	 * show Answer Text
	 * @var boolean
	 */
	protected $showAText = true;
	
	/**
	 * show Points
	 * @var boolean
	 */
	protected $totalPoints = true;
	
	/**
	 * show Points
	 * @var boolean
	 */
	protected $questionPoints = true;
	
	/**
	 * New Line for csv
	 * @var string
	 */
	var $newline = "\n";
        
        /**
  * @var Dispatcher
  */
 public function __construct(\Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository $questionRepository, \Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository $resultRepository, \Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository $resultQuestionRepository, \Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository $resultAnswerRepository)
 {
     $this->questionRepository = $questionRepository;
     $this->resultRepository = $resultRepository;
     $this->resultQuestionRepository = $resultQuestionRepository;
     $this->resultAnswerRepository = $resultAnswerRepository;
 }

 public function init() {
        // todo: rebuild to read it from settings array
        $this->setSeparator(';');
        $this->setText('"');
        $this->setSingleMarker('X');
        $this->setShowQText(true);
        $this->setShowAText(true);
        $this->setTotalPoints(true);
        $this->setQuestionPoints(true);
 }
	
	/**
	 * Getter Separator
	 * @return string
	 */
	public function getSeparator(){
		return $this->separator;
	}
	
	/**
	 * Setter Separator
	 * @param string $separator
	 */
	public function setSeparator($separator): void{
		$this->separator = $separator;
	}
	
	/**
	 * Getter Text
	 * @return string
	 */
	public function getText(){
		return $this->text;
	}
	
	/**
	 * Setter Text
	 * @param string $text
	 */
	public function setText($text): void{
		$this->text = $text;
	}
	
	/**
	 * Getter SingleMarker
	 * @return string
	 */
	public function getSingleMarker(){
		return $this->singleMarker;
	}
	
	/**
	 * Setter SingleMarker
	 * @param string $singleMarker
	 */
	public function setSingleMarker($singleMarker): void{
		$this->singleMarker = $singleMarker;
	}
	
	/**
  * Getter Separator
  * @return QueryResult 
  */
 public function getResults(){
		return $this->results;
	}
	
	/**
  * Setter Results
  * @param QueryResult $results
  */
 public function setResults(QueryResult  $results): void{
		$this->results = $results;
	}
        
        /**
	 * Getter Separator
	 * @return array
	 */
	public function getResultsRaw(){
		return $this->resultsRaw;
	}
        
        /**
	 * Setter Results
	 * @param array  $results
	 */
	public function setResultsRaw(array  $results): void{
		$this->resultsRaw = $results;
	}
	
	/**
	 * Getter showQText
	 * @return boolean
	 */
	public function getShowQText(){
		return (boolean) $this->showQText;
	}
	
	/**
	 * Setter showQText
	 * @param boolean $showQText
	 */
	public function setShowQText($showQText): void{
		$this->showQText = $showQText;
	}
	
	/**
	 * Getter showAText
	 * @return boolean
	 */
	public function getShowAText(){
		return (boolean) $this->showAText;
	}
	
	/**
	 * Setter showAText
	 * @param boolean $showAText
	 */
	public function setShowAText($showAText): void{
		$this->showAText = $showAText;
	}
	
	/**
	 * Getter points
	 * @return boolean
	 */
	public function getTotalPoints(){
		return (boolean) $this->totalPoints;
	}
	
	/**
	 * Setter points
	 * @param boolean $totalPoints
	 */
	public function setTotalPoints($totalPoints): void{
		$this->totalPoints = $totalPoints;
	}	
	
	/**
	 * Getter points
	 * @return boolean
	 */
	public function getQuestionPoints(){
		return (boolean) $this->questionPoints;
	}
	
	/**
	 * Setter points
	 * @param boolean $questionPoints
	 */
	public function setQuestionPoints($questionPoints): void{
		$this->questionPoints = $questionPoints;
	}
	
	/**
	 * create the CSV string
	 * 
	 * @param array $plugin
	 * @return string
	 */
	public function createQuestionBased($plugin){
		$csv = '';
		
		$csv .= $this->createQBHeader($plugin);
		if ($this->getTotalPoints()) $csv .= $this->createTotalPointsLine();
		$csv .= $this->newline;
				
		$csv .= $this->createQBLines($plugin);
				
		return $csv;
	}

	
	/**
	 * create the CSV string
	 * 
	 * @param array $authCodes
	 * @return string
	 */
	public function createAuthCodes($authCodes){
		$this->csv = '';
		
        foreach ($authCodes as $code){
			$this->csv .= $code->getAuthCode();
			$this->csv .= $this->newline;
		}
                
		return $this->csv;
	}
	
	/**
	 * create the header infos
	 * 
	 * @param array $plugin
	 * @return string
	 */
	public function createQBHeader($plugin){
		$header = '';
		
		$header .= $this->text.$plugin['header'].$this->text;
		$header .= $this->newline.$this->newline;
		
		$header .= 'Question ID'.$this->getSeparator();
		$header .= 'Question-Title'.$this->getSeparator();
		if ($this->getShowQText()) $header .= 'Question-Text'.$this->getSeparator();
		$header .= 'Answer-Title'.$this->getSeparator();
		if ($this->getShowAText()) $header .= 'Answer-Text'.$this->getSeparator();
		$header .= $this->newline;
		
		if ($this->getShowQText()) $header .= $this->getSeparator();
		$header .= $this->getSeparator();
		$header .= 'Participation';
		$header .= $this->getSeparator();
		if ($this->getShowAText()) $header .= $this->getSeparator();
		foreach ($this->resultsRaw as $result){
			$header .= $this->getSeparator();
			$header .= $result['uid'];
		}
		$header.= $this->newline;

        if ($this->getShowQText()) $header .= $this->getSeparator();
        $header .= $this->getSeparator();
        $header .= 'AuthCode';
        $header .= $this->getSeparator();
        if ($this->getShowAText()) $header .= $this->getSeparator();
        foreach ($this->resultsRaw as $result){
            $header .= $this->getSeparator();
            $header .= $result['auth_code'];
        }
        $header.= $this->newline;

        if ($this->getShowQText()) $header .= $this->getSeparator();
        $header .= $this->getSeparator();
        $header .= 'FeUser ID';
        $header .= $this->getSeparator();
        if ($this->getShowAText()) $header .= $this->getSeparator();
        foreach ($this->resultsRaw as $result){
            $header .= $this->getSeparator();
            $header .= $result['fe_user'];
        }
        $header.= $this->newline;

        if ($this->getShowQText()) $header .= $this->getSeparator();
        $header .= $this->getSeparator();
        $header .= 'finished';
        $header .= $this->getSeparator();
        if ($this->getShowAText()) $header .= $this->getSeparator();
        foreach ($this->resultsRaw as $result){
            $header .= $this->getSeparator();
            $header .= date( "d.m. H:i" ,  $result['finished'] );
        }
        $header.= $this->newline;
		
		return $header;
	}
	
	/**
	 * create the header infos
	 * 
	 * @param array $plugin
	 * @return string
	 */
	public function createRBHeader($plugin , $questions = []){
		$this->RBStruct = array();
		$header = '';
		
		$header .= $this->text.$plugin['header'].$this->text;
		$header .= $this->newline.$this->newline;
		
		$header .= 'Result ID'.$this->getSeparator();
		$header .= 'Question ID'.$this->getSeparator();
		
		$empty_cols = 1;
		$qL = array();
		$qL2 = array();		
		$aL = array();
		$aL2 = array();
		for ($i = 0; $i < $empty_cols; $i++){
			$qL2[] = '';
			$aL[] = '';
			$aL2[] = '';
		}
		$qL2[] = 'Question Title';
		$aL[] = 'Answer ID';
		$aL2[] = 'Answer Title';
		// $questions = $this->getQuestions($plugin);
		foreach ($questions as $question){
			if ($question->getShortType() == 'Question'){
				$this->RBStruct[$question->getUid()] = array();
				$lastQuestionId = -1;
				foreach ($question->getAnswers() as $answer){
					if ($answer->exportInCsv()){
						$this->RBStruct[$question->getUid()][$answer->getUid()] = 1;
                        if ($lastQuestionId == $question->getUid()) {
                            $qL[] = 0;
                            $qL2[] = $this->text."".$this->text;
                        } else {
                            $qL[] = $question->getUid();
                            $qL2[] = $this->text.$question->getTitle().$this->text;
                            $lastQuestionId = $question->getUid();
                        }

						$aL[] = $answer->getUid();
						$aL2[] = $this->text.$answer->getTitle().$this->text;
					}
				}
			}
		}
        $qL[] = 'Points';
        $qL[] = 'Finished';
		$questionHeader = implode($this->separator,$qL).$this->newline;
		$header .= $questionHeader;				
		$questionHeader2 = implode($this->separator,$qL2).$this->newline;
		$header .= $questionHeader2;				
		$answerHeader = implode($this->separator,$aL).$this->newline;
		$header .= $answerHeader;				
		$answerHeader2 = implode($this->separator,$aL2).$this->newline;
		$header .= $answerHeader2;				
		
		return $header;
	}
	
	/**
	 * create the header infos
	 * 
	 * @return string
	 */
	public function createTotalPointsLine(){
		$line = '';
		$aL = array();
		
		$aL[] = '';
		$aL[] = 'Total Points';
		if ($this->getShowQText()) $emptyFields = 2;
		else $emptyFields = 1;
		for ($i = 0; $i < $emptyFields; $i++){
			$aL[] = '';
		}		
		if ($this->getShowAText()) {
			$aL[] = '';
		}		
		foreach ($this->results as $result){
			if ($result->getPoints() == 0) $result->calculatePoints();
			$aL[] = $result->getPoints();
		}

		foreach ($aL as $nr => $value){
			if (!is_numeric($value)) $aL[$nr] = $this->getText().$value.$this->getText();
		}
		$line = implode($this->separator,$aL).$this->newline;
		return $line;
	}	
	
	/**
  * create the header infos
  *
  * @param Question $question
  * @param array $qL
  * @return string
  */
 public function createQuestionPointsLine(Question $question, $qL){
		$qL[] = 'Points';
		if ($this->getShowQText()) $emptyFields = 1;
		else $emptyFields = 0;
		for ($i = 0; $i < $emptyFields; $i++){
			$qL[] = '';
		}		
		if ($this->getShowAText()) {
			$qL[] = '';
		}		
		if( $this->results && $question ) {
            foreach ($this->results as $result){
                if ( $result->getQuestions() ) {
                    foreach ($result->getQuestions() as $rquestion){
                        if ($rquestion && $rquestion->getQuestion() && $rquestion->getQuestion()->getUid() == $question->getUid()) $qL[] = $rquestion->getPoints();
                    }
                }

            }
        }

		return $qL;
	}
	
	/**
	 * create the lines of the csv
	 * @param array $plugin
	 * @return string
	 */
	public function createQBLines($plugin){
		$lines = '';
		$questions = $this->getQuestions($plugin);
		foreach ($questions as $question){
			if ($question->getShortType() == 'Question'){
				$qL = array();
				$qL[] = $question->getUid();
				$qL[] = $this->text.$question->getTitle().$this->text;
				if ($this->getShowQText()) $qL[] = $this->text.strip_tags($question->getText()).$this->text;
				if ($this->getQuestionPoints()) $qL = $this->createQuestionPointsLine($question, $qL);			
				$questionLine = implode($this->separator,$qL).$this->newline;
				$lines .= $questionLine;
				
				/** @var Answer $answer */
    foreach ($question->getAnswers() as $answer){
					if ($answer->exportInCsv()){
						$options = array();
						$options['marker'] = $this->getSingleMarker();
						$options['separator'] = $this->getSeparator();
						$options['textMarker'] = $this->getText();
						$options['newline'] = $this->newline;
						if ($this->getShowQText()) $options['emptyFields'] = 3;
						else $options['emptyFields'] = 2;
						$options['showAText'] = $this->getShowAText();

						$answerLine = $answer->getCsvLine($this->resultsRaw,$question, $options);
						$lines .= $answerLine;
					}
				}
			}
		}
		return $lines;
	}
	
	/**
	 * create the lines of the csv
	 * @param array $plugin
	 * @return string
	 */
	public function createRBLines($result , $questions ){
        $resultUid = (int) $result['uid'] ??  0 ;
        if (!$resultUid || !$questions) return '';

        $rL = [];
        $rL[] = $resultUid ;  //6542
        $rL[] = '';

        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance( "TYPO3\\CMS\\Core\\Database\\ConnectionPool");


        foreach ($questions as $question){
            /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
            $queryBuilder = $connectionPool->getConnectionForTable('tx_kequestionnaire_domain_model_resultquestion')->createQueryBuilder();
            $rows = $queryBuilder->select('rq.uid as rq_uid' , 'rq.question as rq_question' , 'ra.uid as ra_uid' , 'ra.answer as ra_answer' , 'ra.value as ra_value' , 'ra.additional_value as ra_additional_value')
                ->from('tx_kequestionnaire_domain_model_resultquestion' , 'rq')
                ->leftJoin('rq' , 'tx_kequestionnaire_domain_model_resultanswer' , 'ra' ,
                    $queryBuilder->expr()->eq('ra.resultquestion', 'rq.uid') )
                ->where(
                    $queryBuilder->expr()->eq('rq.result', $queryBuilder->createNamedParameter($resultUid, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('rq.question', $queryBuilder->createNamedParameter($question->getUid(), \PDO::PARAM_INT))
                )->executeQuery()->fetchAllAssociative();

            foreach ( $question->getAnswers() as $answer ) {
                $val = '';
                if ( $rows ) {
                    foreach ($rows as $row){
                        if ($row['ra_answer'] == $answer->getUid()){
                            if ($answer->getUid() == $row['ra_value']) {
                                $val = $this->getSingleMarker();
                            } else {
                                $val = $this->getText().$row['ra_value'].$this->getText();
                            }
                            if ($row['ra_additional_value']){
                                if ($val != '') {
                                    $val .= ' ('.$row['ra_additional_value'].')';
                                } else {
                                    $val = $this->getText().$row['ra_additional_value'].$this->getText();
                                }
                            }
                        }
                    }
                }
                $rL[] = $val;
            }
        }
        $rL[] = $result['points'] ?? 0;
        $rL[] = $result['finished'] ? date( "d.m.Y H:i" ,  $result['finished'] ) : '';
        return implode($this->separator,$rL).$this->newline;

	}
	
	/**
	 * get the Questions for the questionnaire
	 * 
	 * @param array $plugin
	 * @return array|\TYPO3\CMS\Extbase\Persistence\Generic\QueryResultInterface
	 */
	public function getQuestions($plugin) {
		$pids = explode(',',$plugin['pages']);
		$storagePid = $pids[0];
		
		$questions = $this->questionRepository->findAllForPidtoExport($storagePid);
		return $questions;
	}



    
}