<?php
namespace Kennziffer\KeQuestionnaire\Ajax;

use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
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
 * This class exists in the TER version to enable the signal/slot functionality needed for the autoSave-Function in the premium Version
 *
 * @package ke_questionnaire
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AutoSave extends AbstractAjax {
	
    /**
  * @var Dispatcher
  */
 protected $signalSlotDispatcher;
    
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
	 * process an ajax request
	 *
	 * @param array $arguments If you want, you can add some arguments to your object
	 * @return string In most cases JSON
	 */
	public function processAjaxRequest(array $arguments) {
		$this->info = array();
        $this->signalSlotDispatcher->dispatch(__CLASS__, 'ajaxAutoSave', array($this->convertAjaxFormArray($arguments), $this));
		$json = $this->convertValueToJson($this->info['resultUid']);
		return trim($json);
	}
}
?>