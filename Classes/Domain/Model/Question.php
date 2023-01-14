<?php
namespace Kennziffer\KeQuestionnaire\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
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
class Question extends AbstractEntity {

	/**
	 * Type
	 *
	 * @var string
	 */
	protected $type;
	
	/**
	 * Numbering
	 *
	 * @var string
	 */
	protected $numbering;
	
	/**
	 * Page
	 *
	 * @var integer
	 */
	protected $page;
	
	/**
  * Group
  *
  * @var Group
  */
 protected $group;

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Show title
	 *
	 * @var string
	 */
	protected $showTitle;
	/**
	 * Text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * Help Text
	 *
	 * @var string
	 */
	protected $helpText;

    /**
     * random answers
     *
     * @var boolean
     */
    protected $randomAnswers = FALSE;

    /**
     * Column Count
     *
     * @var integer
     */
    protected $columnCount;

	/**
  * Image
  *
  * @var FileReference
  */
 protected $image = null;


	/**
	 * Image position
	 *
	 * @var string
	 */
	protected $imagePosition;

	/**
	 * Is mandatory
	 *
	 * @var boolean
	 */
	protected $isMandatory = FALSE;

	/**
	 * Have the question to be answered correctly?
	 *
	 * @var boolean
	 */
	protected $mustBeCorrect = FALSE;

	/**
  * Answers
  *
  * @var ObjectStorage<Answer>
  * @Lazy
  * @Cascade("remove")
  */
 protected $answers;
    
    /**
  * Dependancies
  *
  * @var ObjectStorage<Dependancy>
  * @Lazy
  * @Cascade("remove")
  */
 protected $dependancies;

    /**
    * Min answers
    *
    * @var int
	 */
	protected $minAnswers = 0 ;

    /**
     * Max answers
     *
     * @var int
     */
    protected $maxAnswers = 0 ;


    /**
	 * Css
	 *
	 * @var string
	 */
	protected $css;
    
    /**
	 * Template
	 *
	 * @var string
	 */
	protected $template;
    
    /**
	 * Default constructor.
	 */
	public function __construct() {
		// Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->answers = new ObjectStorage();
		$this->dependancies = new ObjectStorage();

        $this->image = new ObjectStorage();
	}
	
	/**
	 * Returns the _localizedUid
	 *
	 * @return integer $_localizedUid
	 */
	public function getLocalizedUid() {
		return $this->_localizedUid;
	}
	
	/**
	 * Returns the type
	 *
	 * @return string $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Returns the short version of type
	 * type is the complete class name, but for partials it's better to have shorter type names
	 *
	 * @return string $shortType
	 */
	public function getShortType() {
		return substr (strrchr ($this->type, '\\'), 1);
	}
	
	/**
	 * Returns the page
	 *
	 * @return integer $page
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * Sets the page
	 *
	 * @param integer $page
	 * @return void
	 */
	public function setPage($page) {
		$this->page = $page;
	}
	
	/**
	 * Returns the numbering
	 *
	 * @return string $numbering
	 */
	public function getNumbering() {
		return $this->numbering;
	}

	/**
	 * Sets the numbering
	 *
	 * @param string $numbering
	 * @return void
	 */
	public function setNumbering($numbering) {
		$this->numbering = $numbering;
	}
	
	/**
  * Returns the group
  *
  * @return Group $group
  */
 public function getGroup() {
		return $this->group;
	}

	/**
  * Sets the group
  *
  * @param Group $group
  * @return void
  */
 public function setGroup($group) {
		$this->group = $group;
	}

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the showTitle
	 *
	 * @return string $showTitle
	 */
	public function getShowTitle() {
		return $this->showTitle;
	}

	/**
	 * Sets the showTitle
	 *
	 * @param string $showTitle
	 * @return void
	 */
	public function setShowTitle($showTitle) {
		$this->showTitle = $showTitle;
	}
	
	/**
	 * Returns the text
	 *
	 * @return string $text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Sets the text
	 *
	 * @param string $text
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Returns the helpText
	 *
	 * @return string $helpText
	 */
	public function getHelpText() {
		return $this->helpText;
	}

	/**
	 * Sets the helpText
	 *
	 * @param string $helpText
	 * @return void
	 */
	public function setHelpText($helpText) {
		$this->helpText = $helpText;
	}

    /**
     * @return int
     */
    public function getColumnCount(): int
    {
        return $this->columnCount;
    }

    /**
     * @param int $columnCount
     */
    public function setColumnCount(int $columnCount): void
    {
        $this->columnCount = $columnCount;
    }

    /**
     * Returns the columnPercentage
     *
     * @return integer $columnPercent
     */
    public function getColumnPercent() {
        return round(100 / $this->columnCount ) ;
    }


	/**
  * Returns the image
  *
  * @return FileReference $image
  */
 public function getImage() {
		return $this->image;
	}

	/**
  * Sets the image
  *
  * @param FileReference $image
  * @return void
  */
 public function setImage(FileReference $image) {
		$this->image = $image;
	}

	/**
	 * Returns the imagePosition
	 *
	 * @return string $imagePosition
	 */
	public function getImagePosition() {
		return $this->imagePosition;
	}

	/**
	 * Sets the imagePosition
	 *
	 * @param string $imagePosition
	 * @return void
	 */
	public function setImagePosition($imagePosition) {
		$this->imagePosition = $imagePosition;
	}

	/**
	 * Returns the isMandatory
	 *
	 * @return boolean $isMandatory
	 */
	public function getIsMandatory() {
		return (boolean) $this->isMandatory;
	}

	/**
	 * Sets the isMandatory
	 *
	 * @param boolean $isMandatory
	 * @return void
	 */
	public function setIsMandatory($isMandatory) {
		$this->isMandatory = $isMandatory;
	}

	/**
	 * Returns the boolean state of isMandatory
	 *
	 * @return boolean
	 */
	public function isIsMandatory() {
		return $this->getIsMandatory();
	}

	/**
	 * Returns the mustBeCorrect
	 *
	 * @return boolean $mustBeCorrect
	 */
	public function getMustBeCorrect() {
		return $this->mustBeCorrect;
	}

	/**
	 * Sets the mustBeCorrect
	 *
	 * @param boolean $mustBeCorrect
	 * @return void
	 */
	public function setMustBeCorrect($mustBeCorrect) {
		$this->mustBeCorrect = $mustBeCorrect;
	}

	/**
	 * Returns the boolean state of mustBeCorrect
	 *
	 * @return boolean
	 */
	public function isMustBeCorrect() {
		return $this->getMustBeCorrect();
	}

	/**
  * Adds a Answer
  *
  * @param Answer $answer
  * @return void
  */
 public function addAnswer(Answer $answer) {
		$this->answers->attach($answer);
	}

	/**
  * Removes a Answer
  *
  * @param Answer $answerToRemove The Answer to be removed
  * @return void
  */
 public function removeAnswer(Answer $answerToRemove) {
		$this->answers->detach($answerToRemove);
	}

	/**
  * Returns the answers
  *
  * @return ObjectStorage|array $answers
  */
 public function getAnswers() {
        if ($this->isRandomAnswers()){
            $answers = $this->answers->toArray();
            shuffle($answers);
            return $answers;
        } else {
            return $this->answers;
        }
	}

    /**
     * @return bool
     */
    public function isRandomAnswers(): bool
    {
        return $this->randomAnswers;
    }

    /**
     * @param bool $randomAnswers
     */
    public function setRandomAnswers(bool $randomAnswers): void
    {
        $this->randomAnswers = $randomAnswers;
    }

    /**
     * @return bool
     */
    public function isMaxAnswers()
    {
        return $this->maxAnswers > 0 ;
    }



    /**
     * Returns the answer count
     *
     * @return int
     */
    public function getAnswerCount() {
        if ($this->answers ) {
            return count($this->answers);
        }
        return 0 ;
    }

	/**
  * Sets the answers
  *
  * @param ObjectStorage $answers
  * @return void
  */
 public function setAnswers(ObjectStorage $answers) {
		$this->answers = $answers;
	}
    
    /**
  * Adds a Dependancy
  *
  * @param Dependancy $dependancy
  * @return void
  */
 public function addDependancy(Dependancy $dependancy) {
		$this->dependancies->attach($dependancy);
	}

	/**
  * Removes a Dependancy
  *
  * @param Dependancy $dependancyToRemove The Dependancy to be removed
  * @return void
  */
 public function removeDependancy(Dependancy $dependancyToRemove) {
		$this->dependancies->detach($dependancyToRemove);
	}

	/**
  * Returns the dependancies
  *
  * @return ObjectStorage $dependancies
  */
 public function getDependancies() {
		return $this->dependancies;
	}

	/**
  * Sets the dependancies
  *
  * @param ObjectStorage $dependancies
  * @return void
  */
 public function setDependancies(ObjectStorage $dependancies) {
		$this->dependancies = $dependancies;
	}
    
    /**
	 * Checks the dependancies
	 *
	 * @return boolean
	 */
	public function IsDependant() {
            return (boolean) $this->getIsDependant();
	}
        public function getIsDependant() {
            if (count($this->dependancies) > 0) return (boolean) true;
            else return (boolean) false;
	}
    
    /**
	 * Returns the css
	 *
	 * @return string $css
	 */
	public function getCss() {
		return $this->css;
	}

	/**
	 * Sets the css
	 *
	 * @param string $css
	 * @return void
	 */
	public function setCss($css) {
		$this->css = $css;
	}

    /**
     * @return int
     */
    public function getMinAnswers()
    {
        return $this->minAnswers;
    }

    /**
     * @param int $minAnswers
     */
    public function setMinAnswers($minAnswers)
    {
        $this->minAnswers = $minAnswers;
    }

    /**
     * @return int
     */
    public function getMaxAnswers()
    {
        return $this->maxAnswers;
    }

    /**
     * @param int $maxAnswers
     */
    public function setMaxAnswers($maxAnswers)
    {
        $this->maxAnswers = $maxAnswers;
    }



    /**
	 * Returns the template
	 *
	 * @return string $template
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Sets the template
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	/**
  *
  * @param Result $result
  * @return boolean
  */
 public function fullfillsDependancies(Result $result){
		if ($this->isDependant()){
			$full = false;
			$fullcount = 0;
			//check all dependancies
			foreach ($this->getDependancies() as $id => $dependancy){
				//get the resultQuestion
				$rQuestion = $result->getResultQuestionForQuestion($dependancy->getQuestion());
				if ($rQuestion){
					//get the conditions
					$relConditions = $dependancy->getRelationCondition();
					//check the conditions for all given answers of this resultQuestion
					foreach ($rQuestion->getAnswers() as $rAnswer){
						$fullcount ++;
						if ($rAnswer->getValue() == $relConditions['compareToAnswer']) {
							switch ($relConditions['type']){
								case 'none': $full = true;
									break;
								case 'and': 
										if ($full AND $fullcount > 1) $full = true;
										else $full = false;
									break;
								case 'or':
										$full = true;
									break;
							}						
						}
					}
				}
			}
			return $full;
		} else return true;
	}
}
?>