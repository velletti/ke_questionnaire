<?php
namespace Kennziffer\KeQuestionnaire\Utility;

use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use Kennziffer\KeQuestionnaire\Domain\Repository\QuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;
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
 protected $signalSlotDispatcher;
	
	
	/**
  * injectQuestionRepository
  *
  * @param QuestionRepository $questionRepository
  * @return void
  */
 public function injectQuestionRepository(QuestionRepository $questionRepository) {
		$this->questionRepository = $questionRepository;
	}
	
	/**
  * injectResultRepository
  *
  * @param ResultRepository $resultRepository
  * @return void
  */
 public function injectResultRepository(ResultRepository $resultRepository) {
		$this->resultRepository = $resultRepository;
	}
	
	/**
  * injectResultQuestionRepository
  *
  * @param ResultQuestionRepository $resultQuestionRepository
  * @return void
  */
 public function injectResultQuestionRepository(ResultQuestionRepository $resultQuestionRepository) {
		$this->resultQuestionRepository = $resultQuestionRepository;
	}
	
	/**
  * injectResultAnswerRepository
  *
  * @param ResultAnswerRepository $resultAnswerRepository
  * @return void
  */
 public function injectResultAnswerRepository(ResultAnswerRepository $resultAnswerRepository) {
		$this->resultAnswerRepository = $resultAnswerRepository;
	}
        
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
	public function setSeparator($separator){
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
	public function setText($text){
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
	public function setSingleMarker($singleMarker){
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
 public function setResults(QueryResult  $results){
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
	public function setResultsRaw(array  $results){
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
	public function setShowQText($showQText){
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
	public function setShowAText($showAText){
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
	public function setTotalPoints($totalPoints){
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
	public function setQuestionPoints($questionPoints){
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
	 * @param array $plugin
	 * @return string
	 */
	public function createResultBased($plugin){
		$csv = '';
		
		$csv .= $this->createRBHeader($plugin);
		$csv .= $this->newline;
				
		$csv .= $this->createRBLines($plugin);
				
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
                
                //SignalSlot for Action
                $this->signalSlotDispatcher->dispatch(__CLASS__, 'createAuthCodes', array($this,$authCodes));
						
		return $this->csv;
	}
	
	/**
	 * create the header infos
	 * 
	 * @param array $plugin
	 * @return string
	 */
	protected function createQBHeader($plugin){
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
	protected function createRBHeader($plugin){
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
		$questions = $this->getQuestions($plugin);
		foreach ($questions as $question){
			if ($question->getShortType() == 'Question'){
				$this->RBStruct[$question->getUid()] = array();
				//$qL[] = $question->getUid();
				//$qL2[] = $this->text.$question->getTitle().$this->text;
				
				foreach ($question->getAnswers() as $answer){
					if ($answer->exportInCsv()){
						$this->RBStruct[$question->getUid()][$answer->getUid()] = 1;
						$qL[] = $question->getUid();
						$qL2[] = $this->text.$question->getTitle().$this->text;
						$aL[] = $answer->getUid();
						$aL2[] = $this->text.$answer->getTitle().$this->text;
					}
				}
			}
		}
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
	protected function createTotalPointsLine(){
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
 protected function createQuestionPointsLine(Question $question, $qL){
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
	protected function createQBLines($plugin){
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
	protected function createRBLines($plugin){
		$lines = '';
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->RBStruct, 'struct');
		foreach ($this->resultsRaw as $result){
			$rL = array();
			$rL[] = $result['uid'];
			$rL[] = '';			
			$rAnswers = $this->resultRepository->collectRAnswersForCSVRBExport($result['uid']);
			//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($rAnswers, 'ranswers');
			foreach ($this->RBStruct as $questionId => $answers){
				//$resultQuestion = $this->resultQuestionRepository->findByQuestionIdAndResultIdRaw($questionId, $result['uid']);
				//$resultQuestion = $resultQuestion[0];
				//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($resultQuestion, 'rQ');
				/*foreach ($answers as $answerId => $val){
					$resultAnswer = $this->resultAnswerRepository->findForResultQuestionAndAnswerRaw($resultQuestion['uid'],$answerId);
					if ($resultAnswer['value'] == $answerId) $rL[] = $this->getSingleMarker();
					else $rL[] = '';
				}*/
				$exportAnswers = array();
				foreach($rAnswers as $rA){
					if ($rA['q_uid'] == $questionId){
						if ($answers[$rA['a_uid']] == 1){
							$exportAnswers[$rA['a_uid']] = $rA['ra_value'];
							if ($rA['ra_add_value']){
								if ($rA['a_uid'] == $rA['ra_value']) $exportAnswers[$rA['a_uid']] = $rA['ra_add_value'];
								else $exportAnswers[$rA['a_uid']] .= ' ('.$rA['ra_add_value'].')';
							}
						}
					}
				}
				//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($exportAnswers, 'Eanswers');
				
				foreach ($answers as $answerId => $aVal){
					$val = '';
					if ($exportAnswers[$answerId]) {
						if ($exportAnswers[$answerId] == $answerId) $val = $this->getSingleMarker();
						else $val = $this->getText().$exportAnswers[$answerId].$this->getText();
					}
					$rL[] = $val;
				}
			}
			$lines .= implode($this->separator,$rL).$this->newline;
		}
		return $lines;
	}
	
	/**
	 * get the Questions for the questionnaire
	 * 
	 * @param array $plugin
	 * @return array|\TYPO3\CMS\Extbase\Persistence\Generic\QueryResultInterface
	 */
	protected function getQuestions($plugin) {
		$pids = explode(',',$plugin['pages']);
		$storagePid = $pids[0];
		
		$questions = $this->questionRepository->findAllForPidtoExport($storagePid);
		
		return $questions;
	}
	
	/**
	 * start the interval Export
	 * 
	 * @param array $plugin
	 * @param string $csvString
	 */
	public function finishIntervalExport($plugin, $csvString){
		$csv = '';
		$csvData = $this->parseCsv($csvString);
        $csv .= $this->createQBHeader($plugin);
		if ($this->getTotalPoints()) $csv .= $this->createTotalPointsLine();
		$csv .= $this->newline;
		$csv .= $this->createQuestionColumns($plugin, $csvData);
				
		return $csv;
	}
	
	/**
	 * start the interval Export
	 * 
	 * @param array $plugin
	 * @param string $csvString
	 */
	public function finishRbIntervalExport($plugin, $csvString){
		$csv = $this->createRBHeader($plugin);
		//if ($this->getTotalPoints()) $csv .= $this->createTotalPointsLine();
		$csv .= $csvString;
				
		return $csv;
	}
	
	/**
	 * start the interval Export
	 * 
	 * @param array $oldContent
	 */
	public function processQbIntervalExport($plugin, $oldContent){
        $oldArray = array();
		if ($oldContent) $oldArray = $this->parseCsv($oldContent);
		$lines = '';
		$questions = $this->getQuestions($plugin);
		$counter = 0;
		
		foreach ($questions as $question){			
			if ($question->getShortType() == 'Question'){
				foreach ($question->getAnswers() as $answer){
					if ($answer->exportInCsv()){
						$options = array();
						$options['marker'] = $this->getSingleMarker();
						$options['separator'] = $this->getSeparator();
						$options['textMarker'] = $this->getText();
						$options['newline'] = $this->newline;
						$options['emptyFields'] = 0;
						$options['showAText'] = $this->getShowAText();
						if ($answer->getShortType() == 'MatrixHeader' OR $answer->getShortType() == 'ExtendedMatrixHeader'){
							$answerLine = $answer->getCsvLineValues($this->results,$question, $options, $oldArray, $counter);							
							$addLines = explode($this->newline,$answerLine);							
							foreach ($addLines as $mLineId => $mLineValue){
								if (!is_numeric($mLineValue)) $mAnswerLine = $this->getText().$mLineValue.$this->getText().$this->newline;
								else $mAnswerLine = $mLineValue.$this->newline;
								$oldValues = '';
								if (is_array($oldArray[$counter])){
									//$oldValues = implode($this->getSeparator(),$oldArray[$counter]);
									foreach ($oldArray[$counter] as $col => $val){
										$oldValues .= $this->getText().$val.$this->getText();
										$oldValues .= $this->getSeparator ();
									}
								}
								if ($oldValues != '') $lines .= $oldValues.$mAnswerLine;
								else $lines .= $mAnswerLine;
								$counter ++;
							}
						} else {
							$answerLine = $answer->getCsvLineValues($this->resultsRaw,$question, $options);	
							$oldValues = '';
							if (is_array($oldArray[$counter])){
								//$oldValues = implode($this->getSeparator(),$oldArray[$counter]);
								foreach ($oldArray[$counter] as $col => $val){
									$oldValues .= $this->getText().$val.$this->getText();
									$oldValues .= $this->getSeparator ();
								}
							}
							if ($oldValues != '') $lines .= $oldValues.$answerLine;
							else $lines .= $answerLine;
							$counter ++;
						}
					}
				}
			}
		}
		return $lines;
	}
	
	/**
	 * start the interval Export
	 * 
	 * @param array $oldContent
	 */
	public function processRbIntervalExport($plugin, $oldContent){
		$header = $this->createRBHeader($plugin);
        $lines = $oldContent.$this->createRBLines($plugin);
		return $lines;
	}
	
	/**
	 * process the old CSV Content
	 * @param string $d
	 */
	protected function parseCsv($data){
		$convert = $this->dos2unix($data);
		$return_rows = $this->csvstring_to_array($convert, $this->getSeparator(), $this->getText(), $this->newline);
		return $return_rows;
	}
	
	protected function dos2unix($s) {
		$s = str_replace("\r\n", "\n", $s);
		$s = str_replace("\r", "\n", $s);
		$s = preg_replace("/\n{2,}/", "\n\n", $s);
		return $s;
	}
	
	function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = PHP_EOL) {
		// @author: Klemen Nagode
		$array = array();
		$size = strlen($string);
		$columnIndex = 0;
		$rowIndex = 0;
		$fieldValue="";
		$isEnclosured = false;
		for($i=0; $i<$size;$i++) {

			$char = $string{$i};
			$addChar = "";

			if($isEnclosured) {
				if($char==$enclosureChar) {

					if($i+1<$size && $string{$i+1}==$enclosureChar){
						// escaped char
						$addChar=$char;
						$i++; // dont check next char
					}else{
						$isEnclosured = false;
					}
				}else {
					$addChar=$char;
				}
			}else {
				if($char==$enclosureChar) {
					$isEnclosured = true;
				}else {

					if($char==$separatorChar) {

						$array[$rowIndex][$columnIndex] = $fieldValue;
						$fieldValue="";

						$columnIndex++;
					}elseif($char==$newlineChar) {
						echo $char;
						$array[$rowIndex][$columnIndex] = $fieldValue;
						$fieldValue="";
						$columnIndex=0;
						$rowIndex++;
					}else {
						$addChar=$char;
					}
				}
			}
			if($addChar!=""){
				$fieldValue.=$addChar;

			}
		}

		if($fieldValue) { // save last field
			$array[$rowIndex][$columnIndex] = $fieldValue;
		}
		return $array;
	}
	
			
	/**
	 * create the lines of the csv
	 * @param array $plugin
	 * @param array $csvData
	 * @return string
	 */
	protected function createQuestionColumns($plugin, $csvData){
		$lines = '';
		$questions = $this->getQuestions($plugin);
		$answerCount = 0;
		foreach ($questions as $question){
			if ($question->getShortType() == 'Question'){
				$qL = array();
				$qL[] = $question->getUid();
				$qL[] = $this->text.$question->getTitle().$this->text;
				if ($this->getShowQText()) $qL[] = $this->text.strip_tags($question->getText()).$this->text;
				if ($this->getQuestionPoints()) $qL = $this->createQuestionPointsLine($question, $qL);			
				$questionLine = implode($this->separator,$qL).$this->newline;
				$lines .= $questionLine;
				
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
						$answerLine = $answer->getCsvLineHeader($question, $options);
						if ($answer->getShortType() == 'MatrixHeader' OR $answer->getShortType() == 'ExtendedMatrixHeader'){
							$answerLines = explode($this->newline,$answerLine);
							foreach ($answerLines as $mLineId => $mAnswerLine){
								$oldValues = '';
								if ($mAnswerLine != ''){
									if (is_array($csvData[$answerCount])){
										foreach ($csvData[$answerCount] as $col => $val){
											$oldValues .= $this->getText().$val.$this->getText();                                                                
											$oldValues .= $this->getSeparator();
										}
									}
									$mAnswerLine .= $this->separator.$oldValues.$this->newline;
									$lines .= $mAnswerLine;
									$answerCount ++;
								}
							}
						} else {
							$oldValues = '';
							if (is_array($csvData[$answerCount])){
								foreach ($csvData[$answerCount] as $col => $val){
									$oldValues .= $this->getText().$val.$this->getText();                                                                
									$oldValues .= $this->getSeparator();
								}
							}
							$answerLine .= $oldValues.$this->newline;
							$lines .= $answerLine;
							$answerCount ++;
						}
					} else $answerCount ++;
				}
			}
		}
		return $lines;
	}
}
?>