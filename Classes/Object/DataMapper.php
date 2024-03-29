<?php
namespace Kennziffer\KeQuestionnaire\Object;

use TYPO3\CMS\Extbase\Object\Container\Container;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use Kennziffer\KeQuestionnaire\Exception;
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
class DataMapper {

	/**
  * @var Container
  */
 protected $objectContainer;

	/**
  * @var ObjectManager
  */
 protected $objectManager;

	/**
  * @var ReflectionService
  */
 protected $reflectionService;

	/**
  * @var ValidatorResolver
  */
 protected $validatorResolver;





	/**
  * injects the object manager
  *
  * @param ObjectManager $objectManager
  * @return void
  */
 public function injectObjectManager(ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
  * Injects object container
  *
  * @param Container $objectContainer
  * @return void
  */
 public function injectObjectContainer(Container $objectContainer) {
		$this->objectContainer = $objectContainer;
	}

	/**
	 * Injects the Reflection Service
	 *
	 * @param \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 * @return void
	 */
	public function injectReflectionService(ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * Injects validator resolver
	 *
	 * @param \TYPO3\CMS\Extbase\Validation\ValidatorResolver
	 * @return void
	 */
	public function injectValidatorResolver(ValidatorResolver $validatorResolver) {
		$this->validatorResolver = $validatorResolver;
	}
	
	/**
	 * Maps the given rows on objects
	 *
	 * @param string $className The name of the class
	 * @param array $rows An array of arrays with field_name => value pairs
	 * @return array An array of objects of the given class
	 */
	public function map($className, array $rows) {
		$objectStorage = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		foreach ($rows as $row) {
			/* @var $objectStorage \TYPO3\CMS\Extbase\Persistence\ObjectStorage */
			$objectStorage->attach($this->mapSingleRow($className, $row));
		}
		return $objectStorage;
	}

	/**
	 * Maps a single row on an object of the given class
	 *
	 * @param string $className The name of the target class
	 * @param array $row A single array with field_name => value pairs
	 * @return object An object of the given class
	 */
	public function mapSingleRow($className, array $row) {
		$object = $this->createEmptyObject($className);
		$this->mapProperties($object, $row);
		return $object;
	}

	/**
	 * Creates a skeleton of the specified object
	 *
	 * @param string $className Name of the class to create a skeleton for
	 * @return object The object skeleton
	 */
	public function createEmptyObject($className) {
		return $this->objectContainer->getEmptyObject($className);
	}

	/**
	 * Sets the given properties on the object.
	 *
	 * @param mixed $object The object to set properties on
	 * @param array $row
	 * @return object
	 */
	public function mapProperties($object, array $row) {
		$className = get_class($object);
		foreach ($row as $propertyName => $propertyValue) {
			$method = 'set' . ucfirst($propertyName);
			if (!method_exists($object, $method)) continue;
			$propertyData = $this->reflectionService->getClassSchema($className)->getProperty($propertyName);
			$propertyValue = NULL;
			if ($row[$propertyName] !== NULL) {
				switch ($propertyData->getType()) {
					case 'integer':
						$propertyValue = (int) $row[$propertyName];
					break;
					case 'float':
						$propertyValue = (float) $row[$propertyName];
					break;
					case 'boolean':
						$propertyValue = (boolean) $row[$propertyName];
					break;
					case 'string':
						$propertyValue = (string) $row[$propertyName];
					break;
					case 'array':
						$propertyValue = (array) $row[$propertyName];
					break;
					default:
						$propertyValue = $row[$propertyName];
					break;
				}
			}

			if ($propertyValue !== NULL) {
				$validators = $this->validatorResolver->getBaseValidatorConjunction($className);
				/* @var $validator \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator */
				foreach ($validators as $validator) {
					$error = $validator->validate($propertyValue);
					if ($error->hasErrors()) {
						$errorMessage = array(
							'className' => $className,
							'propertyName' => $propertyName,
							'code' => $error->getFirstError()->getCode(),
							'message' => $error->getFirstError()->getMessage(),
						);
						throw new Exception('dataMapperValidation', 1354704589, $errorMessage);
					}
				}
				$object->$method($propertyValue);
			}
		}
		return $object;
	}
}
?>