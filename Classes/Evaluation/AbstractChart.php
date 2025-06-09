<?php
namespace Kennziffer\KeQuestionnaire\Evaluation;

use Kennziffer\KeQuestionnaire\Domain\Model\Result;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository;
use Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository;
use Kennziffer\KeQuestionnaire\View\Chart;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
class AbstractChart implements RenderChartInterface {

	/**
	 * this var will be used for template path generation
	 *
	 * @var string
	 */
	protected $libraryName = '';

	/**
  * the current result of the user
  *
  * @var Result
  */
 protected $result = NULL;

	/**
  * resultRepository
  *
  * @var ResultRepository
  */
 protected $resultRepository;

	/**
  * resultQuestionRepository
  *
  * @var ResultQuestionRepository
  */
 protected $resultQuestionRepository;

	/**
  * resultAnswerRepository
  *
  * @var ResultAnswerRepository
  */
 protected $resultAnswerRepository;

	/**
  * @var Chart
  */
 protected $view = NULL;

	/**
	 * cObj
	 *
	 * @var tslib_cObj
	 */
	protected $cObj;

	/**
	 * renderChart
	 *
	 * @var string
	 */
	protected $renderChart = RenderChartInterface::FINISHED;

	/**
	 * chartType
	 *
	 * @var string
	 */
	protected $chartType = RChartTypeInterface::PIE;

	/**
	 * settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * ContainerId
	 *
	 * @var string
	 */
	protected $containerId = 'chart_div';

	/**
	 * OuterWrap
	 *
	 * @var string
	 */
	protected $outerWrap = '[|]';

	/**
	 * InnerWrap
	 *
	 * @var string
	 */
	protected $innerWrap = '[|]';

	/**
	 * VarNameForChartData
	 *
	 * @var string
	 */
	protected $varNameForChartData = 'chartData';
 public function __construct(\Kennziffer\KeQuestionnaire\Domain\Repository\ResultRepository $resultRepository, \Kennziffer\KeQuestionnaire\Domain\Repository\ResultQuestionRepository $resultQuestionRepository, \Kennziffer\KeQuestionnaire\Domain\Repository\ResultAnswerRepository $resultAnswerRepository, \Kennziffer\KeQuestionnaire\Evaluation\View\Chart $view, \Kennziffer\KeQuestionnaire\Evaluation\tslib_cObj $cObj)
 {
     $this->resultRepository = $resultRepository;
     $this->resultQuestionRepository = $resultQuestionRepository;
     $this->resultAnswerRepository = $resultAnswerRepository;
     $this->view = $view;
     $this->cObj = $cObj;
 }





	/**
	 * Get the libraryName
	 *
	 * @return string $libraryName
	 */
	public function getLibraryName() {
		return $this->libraryName;
	}

	/**
	 * Get the renderChart
	 *
	 * @return string $renderChart
	 */
	public function getRenderChart() {
		return $this->renderChart;
	}

	/**
	 * Set the renderChart
	 *
	 * @param string $renderChart
	 * @return void
	 */
	public function setRenderChart($renderChart): void {
		if(!empty($renderChart)) {
			$this->renderChart = $renderChart;
		}
	}

	/**
  * Get the result
  *
  * @return Result $result
  */
 public function getResult() {
		return $this->result;
	}

	/**
  * Set the result
  *
  * @param Result $result
  * @return void
  */
 public function setResult(Result $result): void {
		$this->result = $result;
	}

	/**
	 * Get the chartType
	 *
	 * @return string $chartType
	 */
	public function getChartType() {
		return $this->chartType;
	}

	/**
	 * Set the chartType
	 *
	 * @param string $chartType
	 * @return void
	 */
	public function setChartType($chartType): void {
		if(!empty($chartType)) {
			$this->chartType = $chartType;
		}
	}

	/**
	 * Get the settings
	 *
	 * @return array $settings
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * Set the settings
	 *
	 * @param array $settings
	 * @return void
	 */
	public function setSettings(array $settings): void {
		$this->settings = $settings;
	}

	/**
	 * Get the containerId
	 *
	 * @return string $containerId
	 */
	public function getContainerId() {
		return $this->containerId;
	}

	/**
	 * Set the containerId
	 *
	 * @param string $containerId
	 * @return void
	 */
	public function setContainerId($containerId): void {
		$this->containerId = $containerId;
	}

	/**
	 * Get the outerWrap
	 *
	 * @return string $outerWrap
	 */
	public function getOuterWrap() {
		return $this->outerWrap;
	}

	/**
	 * Set the outerWrap
	 *
	 * @param string $outerWrap
	 * @return void
	 */
	public function setOuterWrap($outerWrap): void {
		$this->outerWrap = $outerWrap;
	}

	/**
	 * Get the innerWrap
	 *
	 * @return string $innerWrap
	 */
	public function getInnerWrap() {
		return $this->innerWrap;
	}

	/**
	 * Set the innerWrap
	 *
	 * @param string $innerWrap
	 * @return void
	 */
	public function setInnerWrap($innerWrap): void {
		$this->innerWrap = $innerWrap;
	}

	/**
	 * Get the varNameForChartData
	 *
	 * @return string $varNameForChartData
	 */
	public function getVarNameForChartData() {
		return $this->varNameForChartData;
	}

	/**
	 * Set the varNameForChartData
	 *
	 * @param string $varNameForChartData
	 * @return void
	 */
	public function setVarNameForChartData($varNameForChartData): void {
		$this->varNameForChartData = $varNameForChartData;
	}

	/**
	 * Get Options in JSON-Format
	 *
	 * @return string
	 */
	public function getOptionsAsJson() {
		return json_encode($this->settings['chart'][$this->getLibraryName()][$this->getChartType()], JSON_NUMERIC_CHECK);
	}

	/**
	 * Get the js variables
	 *
	 * @return string $jsVariables
	 */
	public function getJsVariables() {
		foreach ($this->settings['chart']['jsVariables'] as $key => $value) {
			$jsVariables[] = 'var ' . $key . ' = "' . $value . '";';
		}
		$jsVariables[] = 'var chartOptions = ' . $this->getOptionsAsJson() . ';';
		return implode(CHR(10), $jsVariables);
	}

	/**
	 * add header js for rendering chart
	 *
	 * @return string
	 */
	public function getHeaderJs() {
		$extPath = ExtensionManagementUtility::extPath('ke_questionnaire');
		$path = 'Resources/Private/Templates/Evaluation/' . $this->getLibraryName() . '/' . $this->getChartType() . '.html';
		$this->view->setTemplatePathAndFilename($extPath . $path);
		$this->view->assign('containerId', $this->getContainerId());
		$this->view->assign('variables', $this->getJsVariables());

		$methodName = 'getDataFor' . $this->getChartType();
		$this->view->assign('chartData', $this->$methodName());

		return $this->view->render();
	}

}
?>