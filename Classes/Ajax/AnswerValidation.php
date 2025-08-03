<?php
namespace Kennziffer\KeQuestionnaire\Ajax;
use Kennziffer\KeQuestionnaire\Utility\Localization;
use TYPO3\CMS\Core\Database\Connection;
use Egulias\EmailValidator\Exception\InvalidEmail;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
 *
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AnswerValidation extends AbstractAjax {

	/**
  * lokalization
  *
  * @var Localization
  */
 protected $localization;	
	

	public function __construct(\Kennziffer\KeQuestionnaire\Utility\Localization $localization, private \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
 {
     $this->localization = $localization;
 }
		
	/**
	 * process an ajax request
	 *
	 * @param array $arguments If you want, you can add some arguments to your object
	 * @return string In most cases JSON
	 */
	public function processAjaxRequest(array $arguments) {
        $isValid = false ;
        // the validation Array should contain
        // error => 0 no error / 1 error
        // info => textmessage to be displayed
        $validation = array();

        /** @var ConnectionPool $connectionPool */
        $connectionPool = $this->connectionPool;
        $queryBuilder = $connectionPool->getConnectionForTable('tx_kequestionnaire_domain_model_answer')->createQueryBuilder();
        $queryBuilder->select('validation_type') ->from('tx_kequestionnaire_domain_model_answer') ;
        $expr = $queryBuilder->expr();
        $queryBuilder->where( $expr->eq('uid',
            $queryBuilder->createNamedParameter($arguments['answerUid'],
                Connection::PARAM_INT)) ) ;
        $response = $queryBuilder->execute()->fetchAssociative();



        if ( $response ) {
		    $validationType = $response['validation_type'] ;

            if ( !$this->settings['answer']['validation'] ) {
                $this->settings['answer']['validation'] = [
                    "date" => "d.m.Y" , "numeric" => "," , "email" => "name@domain.end" , "string" => "1 char" ,  "string2char" => "2 chars or more" , ] ;
            }
            $pattern = $this->settings['answer']['validation'][$response['validation_type']];

            switch($validationType) {
                case "email":
                    try {
                        if ( GeneralUtility::validEmail($arguments['value']) ) {
                            if (filter_var($arguments['value'], FILTER_VALIDATE_EMAIL)) {
                                $isValid = true;
                            }
                        }
                    } catch (InvalidEmail $invalid) {
                        $isValid = false ;
                    }

                    break;

                case "date":
                    if( strpos( $pattern , ".")) {
                        $split = "." ;
                    } else {
                        $split = "-" ;
                    }
                    $dateArray =  GeneralUtility::trimExplode($split , $arguments['value'] , true) ;

                    switch($pattern) {
                        case "d-m-Y" :
                        case "d.m.Y" :
                            $isValid = checkdate( $dateArray[1] , $dateArray[0] , $dateArray[2] ) ;
                            break;

                        case "m-d-Y" :
                        case "m.d.Y" :
                            $isValid = checkdate( $dateArray[0] , $dateArray[1] , $dateArray[2] ) ;
                            break;

                        case "Y-m-d" :
                        case "Y.m.d" :
                            $isValid = checkdate( $dateArray[1] , $dateArray[2] , $dateArray[0] ) ;
                            break;
                    }

                    break ;

                case "numeric":
                    if( is_numeric( $arguments['value'] )) {
                        $isValid = true;
                    }
                    break ;
                case "string":
                    $isValid = ( strlen( $arguments['value'] ) > 0 )  ;
                    break ;

                case "string2chars":
                    $isValid = ( strlen( $arguments['value'] ) > 1 )  ;
                    break ;

            }

        }

        if( !$this->localization ) {
            $this->localization = GeneralUtility::makeInstance( "Kennziffer\\KeQuestionnaire\\Utility\\Localization" ) ;
        }

		if ($isValid){
			$validation['error'] = 0;
			$validation['info'] = '';
		} else {
			$validation['error'] = 1;
			$validation['info'] = $this->localization->translate('answerValidation.' . $response['validation_type'] ) .' ' . $pattern ;
		}
		
		$json = $this->convertValueToJson($validation);
		return trim($json);
	}


}
?>