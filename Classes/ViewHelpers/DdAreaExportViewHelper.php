<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\Result;
use TYPO3\CMS\Core\Core\Environment;

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
 * ViewHelper for the image in pdf-export of ddarea-image
 * creates an image-file with the drag-images positioned inside the areas
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class DdAreaExportViewHelper extends AbstractViewHelper {


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
        $this->registerArgument('answer', '\Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage', ' The question ', true );
        $this->registerArgument('question', '\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', ' The question ', true );
        $this->registerArgument('result', '\Kennziffer\KeQuestionnaire\Domain\Model\Result', 'the Result object  ', false );
        $this->registerArgument('as', 'string', 'the string the name of the iteration variable  ', false );
        parent::initializeArguments() ;
    }

	/**
	 * @return string filename
	 */
	public function render( ) {

        /** @var Result $result */
        $result = $this->arguments['result'] ;

        /** @var Question $question */
        $question = $this->arguments['question'] ;

        /** @var DDAreaImage $answer */
        $answer = $this->arguments['answer'] ;
        $as = $this->arguments['as'] ;

	    //area image
		$main = $answer->getImage();
		//d&d images
		$images = $this->getImages($question, $answer);
		
		$templateVariableContainer = $this->renderingContext->getVariableProvider();
		if ($question === NULL) {
			return '';
		}
		
		//create the temp-image
		$image = $this->createExportImage($main, $images, $result, $question, $answer);
		//image to be rendered => $as should be the filename in the typo3temp/ke_questionnaire folder
		$templateVariableContainer->add($as, $image);
		$output = $this->renderChildren();
		$templateVariableContainer->remove($as);
		return $output;
	}
	
	/**
  * Create the output-image
  *
  * @param string $main main-image
  * @param array $images d&d images
  * @param Result $result
  * @param Question $question the images are in
  * @param DDAreaImage $answer Answer to be rendered
  * @return string
  */
 private function createExportImage($main,
                                       array $images,
                                       Result$result,
                                       Question $question,
                                       DDAreaImage $answer){
		$width = 0;
        $height = 0;
		$filename = 'test.png';
		$main_infos = array();
        
		//get image size of main image
		$size = getimagesize(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$main, $main_infos);
		$width = $size[0];
		$height = $size[1];
        $mainImage = NULL ;
		switch ($size[2]){
			case 1: //IMAGETYPE_GIF
				$mainImage = imagecreatefromgif(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$main);
				break;
			case 2: //IMAGETYPE_JPEG
				$mainImage = imagecreatefromjpeg(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$main);
				break;
			case 3: //IMAGETYPE_PNG
				$mainImage = imagecreatefrompng(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$main);
				break;				
		}	
		if( !$mainImage ) {
		    return '' ;
        }
		$areas = $answer->getCoords();
        $ddImage = NULL ;
		foreach ($images as $image){
			$resultAnswer = $this->getResultAnswer($result, $question->getUid(), $image->getUid());
			$info = getimagesize(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$image->getImage());
			switch ($info[2]){
				case 1: //IMAGETYPE_GIF
					$ddImage = imagecreatefromgif(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$image->getImage());
					break;
				case 2: //IMAGETYPE_JPEG
					$ddImage = imagecreatefromjpeg(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$image->getImage());
					break;
				case 3: //IMAGETYPE_PNG
					$ddImage = imagecreatefrompng(Environment::getPublicPath() . '/'.'uploads/tx_kequestionnaire/'.$image->getImage());
					break;				
			}
			if ($resultAnswer->getValue() && $ddImage  ){
				$x = 0;
				$y = 0;
				$area_coords = array();
				foreach ($areas as $aid => $area){
					if ($area['key'] == $resultAnswer->getValue()){
						$area['base_key'] = $aid;
						$area_coords = $area;
					}
				}
				if ($area_coords['amount'] > 0){
					$x = $area_coords['left'] + $image->getWidth() + 2;
					$y = $area_coords['y1'];
					if (($x + $image->getWidth()) > $area_coords['x2']){
						$x = $area_coords['x1'];
						$y = $area_coords['top'] + $image->getHeight() + 2;
					}					
				} else {
					$x = $area_coords['x1'];
					$y = $area_coords['y1'];
				}
				$areas[$area_coords['base_key']]['amount'] ++;
				$areas[$area_coords['base_key']]['left'] = $x;
				$areas[$area_coords['base_key']]['top'] = $y;
				if ($image->getWidth() or $image->getHeight()){
					$newWidth = $image->getWidth();
					$newHeight = $image->getHeight();
					imagecopyresized($mainImage,$ddImage,$x,$y,0,0,$newWidth, $newHeight, $info[0],$info[1]);
				} else {
					imagecopy($mainImage,$ddImage,$x,$y,0,0,$info[0],$info[1]);
				}
			}
			imagedestroy($ddImage);
		}
        
        $save_file = Environment::getPublicPath() . '/'.'typo3temp/ke_questionnaire/'.$filename;
		imagepng($mainImage,$save_file);
        imagedestroy($mainImage);
		
		return $filename;
	}
	
	/**
  * Returns a requested question from result record
  *
  * @param Result $result
  * @param integer $questionUid
  * @param integer $answerUid
  * @return
  */
 public function getResultAnswer($result, $questionUid, $answerUid) {
		$resultQuestions = $result->getQuestions();
		/* @var $resultQuestion \Kennziffer\KeQuestionnaire\Domain\Model\ResultQuestion */
		foreach ($resultQuestions as $resultQuestion) {
			if ($resultQuestion->getQuestion() AND $questionUid == $resultQuestion->getQuestion()->getUid()) {
				/* @var $resultAnswer \Kennziffer\KeQuestionnaire\Domain\Model\ResultAnswer */
				foreach ($resultQuestion->getAnswers()->toArray() as $resultAnswer) {
					if ($resultAnswer->getAnswer() AND $answerUid == $resultAnswer->getAnswer()->getUid()) {
						return $resultAnswer;
					}
				}
			}
		}
		return NULL;
	}
	
	/**
	 * Gets the Images
	 * 
	 * @param Question $question the terms are in
	 * @return array
	 */
	public function getImages($question, $header){
		$terms = array();
		
		// workaround for pointer in question, so all following answer-objects are rendered.
		$addIt = false;
		$answers = $question->getAnswers();
        
		foreach ($answers as $answer){
			//Add only after the correct Header is found, only following rows will be added.
			if ((
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale'
					) AND $answer === $header) $addIt = true;
			elseif (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' OR
					get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale') $addIt = false;
			if ($addIt){
				if (get_class($answer) == 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage'){
					$terms[] = $answer;
				}
			}
		}
		
		return $terms;
	}
}
?>