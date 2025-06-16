<?php
namespace Kennziffer\KeQuestionnaire\Utility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;
use Mpdf\Mpdf;
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
class PdfExport {
	/**
     * Create PDF
     * 
     * @param string $html
     * @param string $filename
     */
    public function createPdfFromHTML($html,$filename = "ke_questionnaire.pdf"): void{        
        $this->createAndCheckTmpFile();
        
        $pdf = new Mpdf();
        $pdf->WriteHtml($html);
        $pdf->Output($filename, 'D');
        //$pdf->Output();
        exit;
    }
    
    /**
     * Try to write check file to typo3temp folder
     */
    protected function createAndCheckTmpFile() {
        $this->tmpFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf/TEST';
        if(!is_dir(Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf')) {
            @mkdir(Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf');
        }
        //create htaccess file
        $htaccess = '
<IfModule !mod_authz_core.c>
    # Apache 2.0 and 2.2
    Order Deny,Allow
    Deny from all
    Allow from 127.0.0.1
</IfModule>

<IfModule mod_authz_core.c>
    # Apache 2.4 and later
    Require all denied
    Require ip 127.0.0.1
</IfModule>

<FilesMatch ".*\.(css|js)$">
    <IfModule !mod_authz_core.c>
        # Apache 2.0 and 2.2
        Order Allow,Deny
        Allow from all
    </IfModule>

    <IfModule mod_authz_core.c>
        # Apache 2.4 and later
        Require all granted
    </IfModule>
</FilesMatch>';
        $htaccessFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/.htaccess';
        GeneralUtility::writeFileToTypo3tempDir($htaccessFileAndPath, $htaccess);        
        $htaccessFileAndPath = Environment::getPublicPath() . '/' . 'typo3temp/ke_questionnaire/pdf/.htaccess';
        GeneralUtility::writeFileToTypo3tempDir($htaccessFileAndPath, $htaccess);        
    }
}

?>