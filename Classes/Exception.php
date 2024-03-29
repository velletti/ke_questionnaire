<?php
namespace Kennziffer\KeQuestionnaire;

use TYPO3\CMS\Extbase\Object\ObjectManager;
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
class Exception extends \TYPO3\CMS\Core\Exception {

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Construct the exception
	 *
	 * @link http://php.net/manual/en/exception.construct.php
	 * @param string $key The key from the LOCAL_LANG array for which to return the value.
	 * @param $code [optional]
	 * @param array $arguments the arguments of the extension, being passed over to vsprintf
	 * @param $previous [optional]
	 */
	public function __construct ($key = '', $code = 0, $arguments = NULL, $previous = NULL) {
		$objectManager = new ObjectManager;
		$localization = $objectManager->get('Kennziffer\KeQuestionnaire\Utility\Localization');
		/* @var $localization \Kennziffer\KeQuestionnaire\Utility\Localization */
		$message = $localization->translate($key, 'exception.xml', $arguments);
		if(empty($message)) $message = $key;
		parent::__construct($message, $code, $previous);
	}
}
?>