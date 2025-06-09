<?php
namespace Kennziffer\KeQuestionnaire\Controller;
use Kennziffer\KeQuestionnaire\Utility\CsvExport;
use Kennziffer\KeQuestionnaire\Utility\BackendTsfe;
use Kennziffer\KeQuestionnaire\Utility\PdfExport;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Backend Controller
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ExportController extends  BackendController {
	
	/**
  * @var CsvExport
  */
 protected $csvExport;
	
	/**
  * inject csvExport
  *
  * @param CsvExport $csvExport
  */
 public function injectCsvExport(CsvExport $csvExport) {
		$this->csvExport = $csvExport;
	}

	protected $pdfExport;
	

	
	/**
  * @var BackendTsfe
  */
 protected $backendTsfe;
	
	/**
  * inject backendTsfe
  *
  * @param BackendTsfe $backendTsfe
  */
 public function injectBackendTsfe(BackendTsfe $backendTsfe) {
		$this->backendTsfe = $backendTsfe;
	}
    /**
     * initialize Action
     */
    public function initializeAction() {
        parent::initializeAction();


        try {
            if( class_exists("Kennziffer\\KeQuestionnaire\\Utility\\PdfExport")) {
                /**
                 * @param PdfExport $pdfExport
                 */
                $this->pdfExport = GeneralUtility::makeInstance("Kennziffer\\KeQuestionnaire\\Utility\\PdfExport") ;

            } else {
                $this->pdfExport = false ;
            }

        } catch(\Exception $e) {
            // Composer setp is not correct so pDF class may not be found .. Ignore it .
        }
    }
        /**
	 * action index
	 */
	public function indexAction() {
		$this->view->assign('questionnaires',$this->questionnaireRepository->findAll());
	}
	
	/**
  * CSV Action
  * display for csv export
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function csvAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		//count all the participations for display in header
		$counter = $this->countParticipations();
		//check if there is an interval
		$interval = $this->extConf->getCsvExportInterval();
		//$interval = 1;
		if ($interval AND $counter['all'] > $interval){
			session_start();
			$_SESSION['progval'] = 0;
			$_SESSION['fileName'] = 'csv_temp_'.time();
			session_write_close ();
		}
		$this->view->assign('csvExportInterval', $interval);
		$this->view->assign('counter',$counter);
		$this->view->assign('plugin',$this->plugin);
	}
	
	/**
	 * CSV Interval Action
	 * @param int $pluginUid
	 * @param int $interval
	 */
	public function csvIntervalAction($pluginUid, $interval) {
		//standard path for interval-file
		$pathName = 'typo3temp/ke_questionnaire';
		//initialize the export data
		$this->iniCsvExport();
		//get the questionnaire-data from tt_content element
		$this->questionnaire = $this->questionnaireRepository->findByUid($this->plugin['uid']);
		//check if only finished participations should be exported and get the results
		if ($this->request->getArgument('finished') == 'finished'){
			$resultCount = $this->questionnaire->countResults(true);
		} else {
			$resultCount = $this->questionnaire->countResults(false);
		}
		$counter = 0;
		//create the interval
		while ($counter <= $resultCount){
			/* Increase counter stored in session variable */
			session_start();
			$csvTempFile = $_SESSION['fileName'];
			$interval = $this->request->getArgument('interval');
			$fileName = $pathName . '/' . $csvTempFile;
			//when the progval is 0 => create datafile
			if ($_SESSION['progval'] == 0){
				if (!file_exists(Environment::getPublicPath() . '/' . $pathName)) {
					mkdir(Environment::getPublicPath() . '/' . $pathName, 0777);
					chmod(Environment::getPublicPath() . '/' . $pathName, 0777);
				}				
			} else {
				//else open file and add data
				//get old file content
				$oldContent = '';
				if(file_exists(Environment::getPublicPath() . '/' . $fileName)) {
					$oldContent = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
				}				
			}
			//Load the interval batch
			$correct_interval = $interval;
			if (($correct_interval + $counter) > $resultCount) $correct_interval = $resultCount - $_SESSION['progval'];
            if ($this->request->getArgument('finished') == 'finished'){
				$this->csvExport->setResults($this->resultRepository->findFinishedForPidInterval($this->storagePid, $correct_interval, $_SESSION['progval']));
				$this->csvExport->setResultsRaw($this->resultRepository->findFinishedForPidIntervalRaw($this->storagePid, $correct_interval, $_SESSION['progval']));
			} else {
				$this->csvExport->setResults($this->resultRepository->findAllForPidInterval($this->storagePid, $correct_interval, $_SESSION['progval']));
                $this->csvExport->setResultsRaw($this->resultRepository->findAllForPidIntervalRaw($this->storagePid, $correct_interval, $_SESSION['progval']));
			}
			$csvContent = $this->csvExport->processQbIntervalExport($this->plugin, $oldContent);
			//clear the file
			$csvFile = fopen(Environment::getPublicPath() . '/' . $fileName, 'w+b');
			//write the js
			fwrite($csvFile, $csvContent);
			fclose($csvFile);
			chmod(Environment::getPublicPath() . '/' . $fileName, 0777);

			if ($correct_interval != $interval) {
				$_SESSION['progval'] = $resultCount;                            
				$counter = $resultCount + 1;
			} else {
				$_SESSION['progval'] = $counter;
				$counter += $correct_interval;	
			}			
			session_write_close ();
			sleep(1);
		}
		/* Reset the counter in session variable to 0 */
		session_start();
		
		//delete file when everything is done and send the data back
		$finishedFileName = $pathName . '/csv_finished_'.time();
		//get old file content
		$oldContent = '';
		if(file_exists(Environment::getPublicPath() . '/' . $fileName)) {
			$oldContent = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
			//load all results for uids


			if ($this->request->getArgument('finished') == 'finished'){
				$this->csvExport->setResults($this->resultRepository->findFinishedForPid($this->storagePid));
			} else {
				$this->csvExport->setResults($this->resultRepository->findAllForPid($this->storagePid));
			}


			//create first rows and first cols and fill in the content
			$csvContent = $this->csvExport->finishIntervalExport($this->plugin, $csvContent);				
			//write this content	
			//clear the file
			$finishedCsvFile = fopen(Environment::getPublicPath() . '/' . $finishedFileName, 'w+b');
			//write the js
            fwrite($finishedCsvFile, $csvContent);
			fclose($finishedCsvFile);
            chmod(Environment::getPublicPath() . '/' . $finishedFileName, 0777);
			//delete the temp file
			if (file_exists(Environment::getPublicPath() . '/'.$fileName)) unlink(Environment::getPublicPath() . '/'.$fileName);
		}		
		$_SESSION['progval'] = 0;
		
		session_write_close ();
		return json_encode($finishedFileName);
	}
	
	/**
	 * CSV Download Interval Action
	 * @param string $filename
	 */
	public function downloadCsvIntervalAction($fileName) {
		$csvdata = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
		unlink(Environment::getPublicPath() . '/'.$fileName);
        $encoding = "utf-8" ;
		if ($encoding != mb_detect_encoding($csvdata)) $csvdata = mb_convert_encoding($csvdata, $encoding, mb_detect_encoding($csvdata));

		if( strtolower($encoding) == "utf-8") {
			$csvdata =  pack("CCC", 0xef, 0xbb, 0xbf) . $csvdata ;
			header("content-type: application/csv-tab-delimited-table; Charset=utf-8");
		} else {
			header("content-type: application/csv-tab-delimited-table;");
		}

		header("content-length: ".strlen($csvdata));
		header("content-disposition: attachment; filename=\"csv_export.csv\"");

		print $csvdata;
		exit;
	}
	
	/**
	 * CSV Check Interval Action
	 * @param int $pluginUid
	 * @param int $interval
	 */
	public function csvCheckIntervalAction($pluginUid, $interval) {
		session_start();
		if (!isset($_SESSION['progval']) OR $_SESSION['progval'] == 0) {
			$_SESSION['progval'] = 0;
		}
		session_write_close ();
		return json_encode($_SESSION['progval']);
	}
	
	/**
  * CSV Result Based Action
  * display for csv export
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function csvRbAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		//count all the participations for display in header
		$counter = $this->countParticipations();
		//check if there is an interval
		$interval = $this->extConf->getCsvExportInterval();
		//$interval = 1;
		if ($interval AND $counter['all'] > $interval){
			session_start();
			$_SESSION['progval'] = 0;
			$_SESSION['fileName'] = 'csv_temp_'.time();
			session_write_close ();
		}
		$this->view->assign('csvExportInterval', $interval);
		$this->view->assign('counter',$counter);
		$this->view->assign('plugin',$this->plugin);
	}
	
	/**
	 * CSV Interval Action
	 * @param int $pluginUid
	 * @param int $interval
	 */
	public function csvRbIntervalAction($pluginUid, $interval) {
		//standard path for interval-file
		$pathName = 'typo3temp/ke_questionnaire';
		//initialize the export data
		$this->iniCsvExport();
		//get the questionnaire-data from tt_content element
		$this->questionnaire = $this->questionnaireRepository->findByUid($this->plugin['uid']);
		//check if only finished participations should be exported and get the results
		if ($this->request->getArgument('finished') == 'finished'){
			$resultCount = $this->questionnaire->countResults(true);
		} else {
			$resultCount = $this->questionnaire->countResults(false);
		}
		$counter = 0;
		//create the interval
		while ($counter <= $resultCount){
			/* Increase counter stored in session variable */
			session_start();
			$csvTempFile = $_SESSION['fileName'];
			$interval = $this->request->getArgument('interval');
			$fileName = $pathName . '/' . $csvTempFile;
			//when the progval is 0 => create datafile
			if ($_SESSION['progval'] == 0){
				if (!file_exists(Environment::getPublicPath() . '/' . $pathName)) {
					mkdir(Environment::getPublicPath() . '/' . $pathName, 0777);
					chmod(Environment::getPublicPath() . '/' . $pathName, 0777);
				}				
			} else {
				//else open file and add data
				//get old file content
				$oldContent = '';
				if(file_exists(Environment::getPublicPath() . '/' . $fileName)) {
					$oldContent = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
				}				
			}
			//Load the interval batch
			$correct_interval = $interval;
			if (($correct_interval + $counter) > $resultCount) $correct_interval = $resultCount - $_SESSION['progval'];
            if ($this->request->getArgument('finished') == 'finished'){
				$this->csvExport->setResults($this->resultRepository->findFinishedForPidInterval($this->storagePid, $correct_interval, $_SESSION['progval']));
				$this->csvExport->setResultsRaw($this->resultRepository->findFinishedForPidIntervalRaw($this->storagePid, $correct_interval, $_SESSION['progval']));
			} else {
				$this->csvExport->setResults($this->resultRepository->findAllForPidInterval($this->storagePid, $correct_interval, $_SESSION['progval']));
                $this->csvExport->setResultsRaw($this->resultRepository->findAllForPidIntervalRaw($this->storagePid, $correct_interval, $_SESSION['progval']));
			}
			$csvContent = $this->csvExport->processRbIntervalExport($this->plugin, $oldContent);
			//clear the file
			$csvFile = fopen(Environment::getPublicPath() . '/' . $fileName, 'w+b');
			//write the js
			fwrite($csvFile, $csvContent);
			fclose($csvFile);
			chmod(Environment::getPublicPath() . '/' . $fileName, 0777);

			if ($correct_interval != $interval) {
				$_SESSION['progval'] = $resultCount;                            
				$counter = $resultCount + 1;
			} else {
				$_SESSION['progval'] = $counter;
				$counter += $correct_interval;	
			}			
			session_write_close ();
			sleep(1);
		}
		/* Reset the counter in session variable to 0 */
		session_start();
		
		//delete file when everything is done and send the data back
		$finishedFileName = $pathName . '/csv_finished_'.time();
		//get old file content
		$oldContent = '';
		if(file_exists(Environment::getPublicPath() . '/' . $fileName)) {
			$oldContent = file_get_contents(Environment::getPublicPath() . '/' . $fileName);
			//load all results for uids
			if ($this->request->getArgument('finished') == 'finished'){
				$this->csvExport->setResults($this->resultRepository->findFinishedForPid($this->storagePid));
			} else {
				$this->csvExport->setResults($this->resultRepository->findAllForPid($this->storagePid));
			}
			//create first rows and first cols and fill in the content
			$csvContent = $this->csvExport->finishRbIntervalExport($this->plugin, $csvContent);				
			//write this content	
			//clear the file
			$finishedCsvFile = fopen(Environment::getPublicPath() . '/' . $finishedFileName, 'w+b');
			//write the js
            fwrite($finishedCsvFile, $csvContent);
			fclose($finishedCsvFile);
            chmod(Environment::getPublicPath() . '/' . $finishedFileName, 0777);
			//delete the temp file
			if (file_exists(Environment::getPublicPath() . '/'.$fileName)) unlink(Environment::getPublicPath() . '/'.$fileName);
		}		
		$_SESSION['progval'] = 0;
		
		session_write_close ();
		return json_encode($finishedFileName);
	}
	
    
    /**
  * PDF Action
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function pdfAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		$this->view->assign('results',$this->resultRepository->findAllForPid($this->storagePid));
		$this->view->assign('counter',$this->countParticipations());
		$this->view->assign('plugin',$this->plugin);
	}
	
	/**
  * Download CSV Action
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function downloadCsvAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;


		$this->iniCsvExport();
		//load the results
		if ($this->request->hasArgument( 'finished') && $this->request->getArgument('finished') == 'finished'){
            $this->csvExport->setResultsRaw($this->resultRepository->findFinishedForPidRaw($this->storagePid));
			$this->csvExport->setResults($this->resultRepository->findFinishedForPid($this->storagePid));
		} else {
			$this->csvExport->setResultsRaw($this->resultRepository->findAllForPidRaw($this->storagePid));
            $this->csvExport->setResults($this->resultRepository->findAllForPid($this->storagePid));
		}
		//create the csvdata
		$csvdata = $this->csvExport->createQuestionBased($this->plugin);
        $encoding = "utf-8" ;
		if ($encoding != mb_detect_encoding($csvdata)) $csvdata = mb_convert_encoding($csvdata, $encoding, mb_detect_encoding($csvdata));
		if( strtolower($encoding) == "utf-8") {
			$csvdata =  pack("CCC", 0xef, 0xbb, 0xbf) . $csvdata ;
			header("content-type: application/csv-tab-delimited-table; Charset=utf-8");
		} else {
			header("content-type: application/csv-tab-delimited-table;");
		}

		header("content-length: ".strlen($csvdata));
		header("content-disposition: attachment; filename=\"csv_export.csv\"");

		print $csvdata;
		exit;
	}
	
	/**
  * Download CSV Result Based Action
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function downloadCsvRbAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		$this->iniCsvExport();
		//load the results
		if ($this->request->getArgument('finished') == 'finished'){
            $this->csvExport->setResultsRaw($this->resultRepository->findFinishedForPidRaw($this->storagePid));
			$this->csvExport->setResults($this->resultRepository->findFinishedForPid($this->storagePid));
		} else {
			$this->csvExport->setResultsRaw($this->resultRepository->findAllForPidRaw($this->storagePid));
            $this->csvExport->setResults($this->resultRepository->findAllForPid($this->storagePid));
		}
		//create the csvdata
		$csvdata = $this->csvExport->createResultBased($this->plugin);
        $encoding = "utf-8" ;
	    if ($encoding != mb_detect_encoding($csvdata)) $csvdata = mb_convert_encoding($csvdata, $encoding, mb_detect_encoding($csvdata));
		if( strtolower($encoding) == "utf-8") {
			$csvdata =  pack("CCC", 0xef, 0xbb, 0xbf) . $csvdata ;
			header("content-type: application/csv-tab-delimited-table; Charset=utf-8");
		} else {
			header("content-type: application/csv-tab-delimited-table;");
		}
		header("content-length: ".strlen($csvdata));
		header("content-disposition: attachment; filename=\"csv_export.csv\"");

		print $csvdata;
		exit;
	}
	
	/**
  * Download CSV Action
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function downloadAuthCodesCsvAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		
		//load the AuthCodes
		$authCodes = $this->authCodeRepository->findAllForPid($this->storagePid)->toArray();
		//create the csvdata
                $this->csvExport->extConf = $this->extConf;
		$csvdata = $this->csvExport->createAuthCodes($authCodes);
        $encoding = "utf-8" ;
		if ($encoding != mb_detect_encoding($csvdata)) $csvdata = mb_convert_encoding($csvdata, $encoding, mb_detect_encoding($csvdata));
		if( strtolower($encoding) == "utf-8") {
			$csvdata =  pack("CCC", 0xef, 0xbb, 0xbf) . $csvdata ;
			header("content-type: application/csv-tab-delimited-table; Charset=utf-8");
		} else {
			header("content-type: application/csv-tab-delimited-table;");
		}

		header("content-length: ".strlen($csvdata));
		header("content-disposition: attachment; filename=\"csv_export.csv\"");

		print $csvdata;
		exit;
	}
	
	/**
	 * Initialize the CSV Object
	 */
	private function iniCsvExport(){
		$csvdata = '';
		
		if (ExtensionManagementUtility::isLoaded('ke_questionnaire_premium')){
			$this->csvExport = $this->objectManager->get('Kennziffer\KeQuestionnairePremium\Utility\CsvExport');
			
			if ($this->request->hasArgument('averagePoints')) $this->csvExport->setAveragePoints($this->request->getArgument('averagePoints'));
			else $this->csvExport->setAveragePoints($this->plugin->ffdata['settings']['csv']['averagePoints']);
			if ($this->request->hasArgument('averagePointsAll')) $this->csvExport->setAveragePointsAll($this->request->getArgument('averagePointsAll'));
			else $this->csvExport->setAveragePointsAll($this->plugin->ffdata['settings']['csv']['averagePointsAll']);
                        if ($this->request->hasArgument('additionalParameter')) $this->csvExport->setAdditionalParameter($this->request->getArgument('additionalParameter'));
			else $this->csvExport->setAdditionalParameter($this->plugin->ffdata['settings']['csv']['additionalParameter']);
		}
		if ($this->request->hasArgument('separator')) $this->csvExport->setSeparator($this->request->getArgument('separator'));
		else $this->csvExport->setSeparator($this->plugin->ffdata['settings']['csv']['separator']);
		if ($this->request->hasArgument('text')) $this->csvExport->setText($this->request->getArgument('text'));
		else $this->csvExport->setText($this->plugin->ffdata['settings']['csv']['text']);
		if ($this->request->hasArgument('singleMarker')) $this->csvExport->setSingleMarker($this->request->getArgument('singleMarker'));
		else $this->csvExport->setSingleMarker($this->plugin->ffdata['settings']['csv']['singleMarker']);
		if ($this->request->hasArgument('showQText')) $this->csvExport->setShowQText($this->request->getArgument('showQText'));
		else $this->csvExport->setShowQText($this->plugin->ffdata['settings']['csv']['showQText']);
		if ($this->request->hasArgument('showAText')) $this->csvExport->setShowAText($this->request->getArgument('showAText'));
		else $this->csvExport->setShowAText($this->plugin->ffdata['settings']['csv']['showAText']);
		if ($this->request->hasArgument('encoding')) $encoding = $this->request->getArgument('encoding');
		else $encoding = $this->plugin->ffdata['settings']['csv']['encoding'];
		if ($this->request->hasArgument('totalPoints')) $this->csvExport->setTotalPoints($this->request->getArgument('totalPoints'));
		else $this->csvExport->setTotalPoints($this->plugin->ffdata['settings']['csv']['totalPoints']);
		if ($this->request->hasArgument('questionPoints')) $this->csvExport->setQuestionPoints($this->request->getArgument('questionPoints'));
		else $this->csvExport->setQuestionPoints($this->plugin->ffdata['settings']['csv']['questionPoints']);		
	}
    
    /**
  * Download PDF Action
  *
  * @param integer $storage
  * @param array $plugin
  * @IgnoreValidation
  */
 public function downloadPdfAction($storage = false, $plugin = false) {
		if ($storage) $this->storagePid = $storage;
		if ($plugin) $this->plugin = $plugin;
		//check the pdf type 
        if ($this->request->hasArgument('pdfType')) $pdfType = $this->request->getArgument('pdfType');
        else $pdfType = 'empty';

        switch ($pdfType){
			//only questions
            case 'empty':
                    $this->createPdf();
                break;
			//filled with a participation
			case 'filled':
					$this->createPdf($this->request->getArgument('selectedResult'));
				break;
			//compared to the given correct answers
			case 'compared':
					$this->createPdf($this->request->getArgument('selectedResult'),true);
				break;
            default:
                    $this->forward('pdf');
                break;
        }
        //exit;
	}
    
    /**
     * Create Empty Pdf
	 * 
	 * @param integer $resultId
	 * @param boolean $compared
     */
    public function createPdf($resultId = NULL, $compared = false){
		$requestedPage = 0;		
		
		if ($this->request->hasArgument('questionnaire')){
			$this->plugin = BackendUtility::getRecord('tt_content',$this->request->getArgument('questionnaire'));
		}
		
		//the tsfe data is needed
		$this->backendTsfe->buildTSFE();

		//load the questionnaire
		$this->questionnaire = $this->questionnaireRepository->findByUid($this->plugin['uid']);
		$this->view->assign('questionnaire',$this->questionnaire);
				
		//load the result if there is a resuldId given
		if ($resultId) {
            $result = $this->resultRepository->findByUid($resultId);
        } else {
            $result = $this->objectManager->get('Kennziffer\KeQuestionnaire\Domain\Model\Result');
        }
		$this->view->assign('result',$result);
				
		//if there should be a comparision load the compare-Result
		if ($compared) $this->view->assign('compare',$this->questionnaire->getCompareResult());
		
		//load the css-data for the pdf
		$css_filename = GeneralUtility::getFileAbsFileName('EXT:'.$this->request->getControllerExtensionKey().'/Resources/Public/Css/KeQuestionnaire.css');
		$css_filename2 = GeneralUtility::getFileAbsFileName(  'EXT:'.$this->request->getControllerExtensionKey().'/Resources/Public/Css/PDF.css');
        $css = '<style>'.file_get_contents($css_filename)."\n".file_get_contents($css_filename2).'</style>';
        //render the pdf-html-data
		$content = $this->view->render();
		//remove the js-scripts
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
		
		//replace image urls in Backend, Frontend may need other code
		$base = str_replace("/typo3",'',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$base = str_replace("/mod.php",'',$base);
		$content = str_replace('<img src="fileadmin/','<img src="https://'.$base.'/fileadmin/',$content);
		//create the pdf
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content, 'pdf');	
        if( $this->pdfExport && class_exists("Kennziffer\\KeQuestionnaire\\Utility\\PdfExport")) {
		    $this->pdfExport->createPdfFromHTML($css.'<br>'.$content);
        }
	
    }

	/**
  * get the Questions for the questionnaire
  *
  * @param array $plugin
  * @IgnoreValidation
  * @return QueryResultInterface|array
  */
 private function getQuestions($plugin) {
		$pids = explode(',',$plugin['pages']);
		$storagePid = $pids[0];
		
		$questions = $this->questionRepository->findAllForPid($storagePid);
		
		return $questions;
	}
}
?>