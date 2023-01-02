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
        'subclasses' => ['Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript', 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath'],
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Question',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PageBreak',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\ConditionalJump',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\PlausiCheck',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Group',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Html',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Text',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\Typo3Content',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScript',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\QuestionType\TypoScriptPath',
        'tableName' => 'tx_kequestionnaire_domain_model_question',
    ],


    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\AbstractAnswerType::class => [
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
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
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy::class => [
        'recordType' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy',
        'tableName' => 'tx_kequestionnaire_domain_model_answer',
    ],
    \Kennziffer\KeQuestionnaire\Domain\Model\Answer::class => [
        'subclasses' => [
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Radiobutton',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Checkbox',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleInput',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MultiInput',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SingleSelect',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeText',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTextDD',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\ClozeTerm',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaImage',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSequence',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDAreaSimpleScale',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DDImage',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingTerm',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingInput',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingOrder',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\RankingSelect',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixHeader',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\MatrixRow',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\Slider',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\SemanticDifferential',
            'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy' => 'Kennziffer\KeQuestionnaire\Domain\Model\AnswerType\DataPrivacy'],
    ],

];