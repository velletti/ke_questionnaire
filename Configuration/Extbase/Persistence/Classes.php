<?php
declare(strict_types=1);

use Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire;
use Kennziffer\KeQuestionnaire\Domain\Model\Question;
use Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput;
use Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect;
use Kennziffer\KeQuestionnaire\Domain\Model\Answer;

return [
    Questionnaire::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'uid' => [
                'fieldName' => 'uid'
            ],
            'pid' => [
                'fieldName' => 'pid'
            ],
            'sorting' => [
                'fieldName' => 'sorting'
            ],
            'contentType' => [
                'fieldName' => 'CType'
            ],
            'header' => [
                'fieldName' => 'header'
            ],
            'headerLink' => [
                'fieldName' => 'header_link'
            ],
            'bodytext' => [
                'fieldName' => 'bodytext'
            ],

            'image' => [
                'fieldName' => 'image'
            ],
            'imageLink' => [
                'fieldName' => 'image_link'
            ],
            'colPos' => [
                'fieldName' => 'colPos'
            ],
            'piFlexForm' => [
                'fieldName' => 'pi_flexform'
            ],
            'pages' => [
                'fieldName' => 'pages'
            ],
            'crdate' => [
                'fieldName' => 'crdate'
            ],
            'starttime' => [
                'fieldName' => 'starttime'
            ],
            'endtime' => [
                'fieldName' => 'endtime'
            ],
            'hidden' => [
                'fieldName' => 'hidden'
            ],
        ],
    ],
    Question::class => [
        'subclasses' => [\Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class => \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class,
            PageBreak::class => PageBreak::class,
        ],
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class => [
        'recordType' => \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class,
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    PageBreak::class => [
        'recordType' => PageBreak::class,
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],

    Radiobutton::class => [
        'recordType' => Radiobutton::class,
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    Checkbox::class => [
        'recordType' => Checkbox::class,
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    SingleInput::class => [
        'recordType' => SingleInput::class,
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    MultiInput::class => [
        'recordType' => MultiInput::class,
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    SingleSelect::class => [
        'recordType' => SingleSelect::class,
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    Answer::class => [
        'subclasses' => [
            Radiobutton::class => Radiobutton::class,
            Checkbox::class => Checkbox::class,
            SingleInput::class => SingleInput::class,
            MultiInput::class => MultiInput::class,
            SingleSelect::class => SingleSelect::class,
        ],
    ],

];
