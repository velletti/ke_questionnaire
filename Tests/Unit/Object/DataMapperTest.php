<?php

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
class Tx_KeQuestionnaire_Object_DataMapperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_KeQuestionnaire_Object_DataMapper
	 */
	protected $dataMapper;





	public function setUp() {
		$this->dataMapper = $this->objectManager->get('Tx_KeQuestionnaire_Object_DataMapper');
	}

	public function tearDown() {
		unset($this->dataMapper);
	}





	/**
	 * @test
	 */
	public function createEmptyObject() {
		$object = $this->dataMapper->createEmptyObject('Tx_KeQuestionnaire_Domain_Model_Step');

		$this->assertInstanceOf('Tx_KeQuestionnaire_Domain_Model_Step', $object);
	}

	/**
	 * @test
	 */
	public function mapProperties() {
		$row = array(
			'type' => 'forward',
			'action' => 'mail',
			'controller' => 'Mailing',
			'extension' => 'KeQuestionnaire'
		);
		$step = $this->dataMapper->createEmptyObject('Tx_KeQuestionnaire_Domain_Model_Step');
		$object = $this->dataMapper->mapProperties($step, $row);
		/* @var $object Tx_KeQuestionnaire_Domain_Model_Step */
		$this->assertEquals('forward', $object->getType());
		$this->assertEquals('mail', $object->getAction());
		$this->assertEquals('Mailing', $object->getController());
		$this->assertEquals('KeQuestionnaire', $object->getExtension());
	}

	/**
	 * @test
	 * @expectedException Tx_KeQuestionnaire_Exception
	 */
	public function mapPropertiesMissingAction() {
		$row = array(
			'type' => 'forward',
			'action' => '',
			'controller' => 'Mailing',
			'extension' => 'KeQuestionnaire'
		);
		$step = $this->dataMapper->createEmptyObject('Tx_KeQuestionnaire_Domain_Model_Step');
		$object = $this->dataMapper->mapProperties($step, $row);
		/* @var $object Tx_KeQuestionnaire_Domain_Model_Step */
		$exception = $this->getExpectedException();
	}

	/**
	 * @test
	 * @expectedException Tx_KeQuestionnaire_Exception
	 */
	public function mapPropertiesMissingController() {
		$row = array(
			'type' => 'forward',
			'action' => 'mail',
			'controller' => '',
			'extension' => 'KeQuestionnaire'
		);
		$step = $this->dataMapper->createEmptyObject('Tx_KeQuestionnaire_Domain_Model_Step');
		$object = $this->dataMapper->mapProperties($step, $row);
		/* @var $object Tx_KeQuestionnaire_Domain_Model_Step */
		$exception = $this->getExpectedException();
	}

	/**
	 * @test
	 * @expectedException Tx_KeQuestionnaire_Exception
	 */
	public function mapPropertiesMissingExtension() {
		$row = array(
			'type' => 'forward',
			'action' => 'mail',
			'controller' => 'Mailing',
			'extension' => ''
		);
		$step = $this->dataMapper->createEmptyObject('Tx_KeQuestionnaire_Domain_Model_Step');
		$object = $this->dataMapper->mapProperties($step, $row);
		/* @var $object Tx_KeQuestionnaire_Domain_Model_Step */
		$exception = $this->getExpectedException();
	}

}
?>