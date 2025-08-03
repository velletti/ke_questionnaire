<?php
namespace Kennziffer\KeQuestionnaire\ViewHelpers;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
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
 * render standard content view inside questionnaire
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ContentElementViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

	/**
  * @var ConfigurationManagerInterface
  */
 protected $configurationManager;

	/**
  * @var ContentObjectRenderer Object
  */
 protected $cObj;
 public function __construct(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager, private \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
 {
     $this->configurationManager = $configurationManager;
 }


    /** * Constructor *
     * @api */
    public function initializeArguments(): void {
        $this->registerArgument('uid', 'int', ' UID of any content element ', false );
        $this->registerArgument('sysLanguageUid', 'int', ' Language UID that should be rendered', false );
        parent::initializeArguments() ;
    }

    /**
     * Parse a content element
     *
     * @return 	string		Parsed Content Element
     */
    public function render() {
        $uid = intval( $this->arguments['uid'] );
        if ( $uid == 0 ) {
            return '' ;
        }
        if( $this->hasArgument('sysLanguageUid')) {
            $sysLanguageUid = (integer)$this->arguments['sysLanguageUid'];
        }
        // If a sysLanguageUid is set, get the translated record
        if(intval($sysLanguageUid) > 0){

            /** @var ConnectionPool $connectionPool */
            $connectionPool = $this->connectionPool;

            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $connectionPool->getConnectionForTable('tt_content')->createQueryBuilder();
            $queryBuilder->select('uid')
                ->from('tt_content') ;

            $expr = $queryBuilder->expr();
            $queryBuilder->where(
                $expr->eq('uid', $queryBuilder->createNamedParameter(intval($uid), Connection::PARAM_INT))
            )->orWhere(
                $expr->eq('l18n_parent', $queryBuilder->createNamedParameter(intval($uid), Connection::PARAM_INT))
            )->andWhere(
                $expr->eq('sys_language_uid', $queryBuilder->createNamedParameter(intval($sysLanguageUid), Connection::PARAM_INT))
            ) ;


            $result = $queryBuilder->execute()->fetch();

            if(is_array( $result) && isset($result['uid'])){
                $uid = $result['uid'];
            }

        }

		$conf = array( // config
			'tables' => 'tt_content',
			'source' => $uid,
			'dontCheckPid' => 1
		);
		return $this->cObj->cObjGetSingle('RECORDS', $conf);
    }
}
?>