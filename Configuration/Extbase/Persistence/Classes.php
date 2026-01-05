<?php
declare(strict_types=1);

return [
    \Kennziffer\KeQuestionnaire\Domain\Model\Questionnaire::class => [
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
        ],
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\Question::class => [
        'subclasses' => ['Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question',
            'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak',
        ],
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],

    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\Answer::class => [
        'subclasses' => [
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect',
        ],
    ],

];