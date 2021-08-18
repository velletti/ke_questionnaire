<?php
namespace Kennziffer\KeQuestionnaire\Controller\Wizard;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Kennziffer.com <info@kennziffer.com>, www.kennziffer.com
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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This Class renders the ImageAreaSelectWizard
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ImageAreaSelectController extends \TYPO3\CMS\Backend\Controller\Wizard\AbstractWizardController {
    /**
     * Wizard parameters, coming from FormEngine linking to the wizard.
     *
     * @var array
     */
    public $wizardParameters;
    
    /**
     * Serialized functions for changing the field...
     * Necessary to call when the value is transferred to the FormEngine since the form might
     * need to do internal processing. Otherwise the value is simply not be saved.
     *
     * @var string
     */
    public $fieldChangeFunc;

    /**
     * @var string
     */
    protected $fieldChangeFuncHash;

    /**
     * Form name (from opener script)
     *
     * @var string
     */
    public $fieldName;

    /**
     * Field name (from opener script)
     *
     * @var string
     */
    public $formName;

    /**
     * ID of element in opener script for which to set color.
     *
     * @var string
     */
    public $md5ID;
    
    /**
     * @var string
     */
    public $areaImage = '';

    /**
     * Document template object
     *
     * @var DocumentTemplate
     */
    public $docTemplate;

    /**
     * @var string
     */
    public $renderedContent;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
		$this->getLanguageService()->includeLLFile('EXT:ke_questionnaire/Resources/Private/Language/locallang_mod.xml');
        $GLOBALS['SOBE'] = $this;

        $this->init();
    }
    
    /**
     * Initialises the Class
     *
     * @return void
     */
    protected function init()
    {
        // Setting GET vars (used in frameset script):
        $this->wizardParameters = GeneralUtility::_GP('P');
        // Setting GET vars (used in colorpicker script):
        $this->fieldChangeFunc = $this->wizardParameters['fieldChangeFunc'];
        $this->fieldChangeFuncHash = $this->wizardParameters['fieldChangeFuncHash'];
		$this->fieldName = $this->wizardParameters['field'];
        $this->formName = $this->wizardParameters['formName'];
        $this->md5ID = $this->wizardParameters['md5ID'];
        // Resolving image (checking existence etc.)
        $this->imageError = '';
		$this->getAreaImage();
        
		$update = array();
        if ($this->areFieldChangeFunctionsValid()) {
            // Setting field-change functions:
            $fieldChangeFuncArr = unserialize($this->fieldChangeFunc);
            unset($fieldChangeFuncArr['alert']);
            foreach ($fieldChangeFuncArr as $v) {
                $update[] = 'parent.opener.' . $v;
            }
        }
        // Initialize document object:
        $this->docTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);       
        // Start page:
        $this->renderedContent .= $this->docTemplate->startPage($this->getLanguageService()->getLL('colorpicker_title'));
    }
    
    /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the main() method, it is rather simple for now
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->wizardParameters, 'c');	
		$this->main($request);
		$response->getBody()->write($this->renderedContent);
        return $response;        
    }
	
	public function getAreaImage(){
		$this->areaImage = '';
		$this->answer = BackendUtility::getRecord($this->wizardParameters['table'], $this->wizardParameters['uid']);
		$this->areaImage = $this->answer['image'];		
	}
    
    public function main(ServerRequestInterface $request)
    {		
		$baseurl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        if ($this->docTemplatelose) {
            return $this->closeWindow;
        } else {    
		// Putting together the items into a form:
			$this->renderedContent .= '<link rel="stylesheet" type="text/css" href="'.$baseurl.'typo3conf/ext/ke_questionnaire/Resources/Public/Css/imgareaselect-animated.css" media="all">';
			$this->renderedContent .= '<script src="'.$baseurl.'typo3conf/ext/ke_questionnaire/Resources/Public/Script/jquery-1.11.3.min.js" type="text/javascript"></script>
								<script src="'.$baseurl.'typo3conf/ext/ke_questionnaire/Resources/Public/Script/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
								<script src="'.$baseurl.'typo3conf/ext/ke_questionnaire/Resources/Public/Script/jquery-migrate-1.2.1.js" type="text/javascript"></script>
								<script src="'.$baseurl.'typo3conf/ext/ke_questionnaire/Resources/Public/Script/jquery.imgareaselect.min.js" type="text/javascript"></script>
								';
            $this->renderedContent .= '<div class=ke_questionnaire" style="padding: 5px;">';
            $this->renderedContent .= '<h2>' . $this->getLanguageService()->getLL('imageAreaSelectHeader') . '</h2>';
			$this->renderedContent .= '<p>' . $this->getLanguageService()->getLL('imageAreaSelectInfo') . '</p>';
			$this->renderedContent .= '<hr /><br/>';
			$this->renderedContent .= '<img id="imageAreaSource" src="'.$baseurl.'uploads/tx_kequestionnaire/'.$this->areaImage.'" alt="areaImage" title="areaImage" ';
			if ($this->answer['width']) $this->renderedContent .= ' width="'.$this->answer['width'].'px" ';
			if ($this->answer['height']) $this->renderedContent .= ' width="'.$this->answer['height'].'px" ';
			$this->renderedContent .= '/><br/>';
			$this->renderedContent .= '<input type="hidden" name="x1" value="" />
								<input type="hidden" name="y1" value="" />
								<input type="hidden" name="x2" value="" />
								<input type="hidden" name="y2" value="" />';
			$this->renderedContent .= '<hr /><br/>';
			$this->renderedContent .= '<div>
									<button id="setArea">'.$this->getLanguageService()->getLL('imageAreaSelectSave').'</button>
									<button id="closeWizard" onclick="javascipt:window.close();">'.$this->getLanguageService()->getLL('imageAreaSelectClose' ).'</button>
								</div>';
			$this->renderedContent .= '</div>';
			$this->renderedContent .= '<script type="text/javascript">
								var formName = \'{P.formName}\';
								var itemName = \'{P.itemName}\';
									jQuery(\'#imageAreaSource\').imgAreaSelect({
										onSelectEnd: function (img, selection) {
											jQuery(\'input[name="x1"]\').val(selection.x1);
											jQuery(\'input[name="y1"]\').val(selection.y1);
											jQuery(\'input[name="x2"]\').val(selection.x2);
											jQuery(\'input[name="y2"]\').val(selection.y2);
										}
									});

									jQuery(\'#setArea\').click(function(){
										coords = jQuery(\'input[name="x1"]\').val()+\',\'+jQuery(\'input[name="y1"]\').val()+\',\'+jQuery(\'input[name="x2"]\').val()+\',\'+jQuery(\'input[name="y2"]\').val();
										//alert(coords);
										parentForm = window.opener.document.editform;
										val = jQuery(parentForm).find(\'[name="'.$this->wizardParameters['itemName'].'"]\').val();
										if (val.length > 0)	newVal = val+"\n"+coords;
										else newVal = coords;
										jQuery(parentForm).find(\'[name="'.$this->wizardParameters['itemName'].'"]\').val(newVal);
									});
							</script>';
		}
    }
    

    /**
     * Determines whether submitted field change functions are valid
     * and are coming from the system and not from an external abuse.
     *
     * @return bool Whether the submitted field change functions are valid
     */
    protected function areFieldChangeFunctionsValid()
    {
        return $this->fieldChangeFunc && $this->fieldChangeFuncHash && $this->fieldChangeFuncHash === GeneralUtility::hmac($this->fieldChangeFunc);
    }

    /**
     * @return PageRenderer
     */
    protected function getPageRenderer()
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }
    
}