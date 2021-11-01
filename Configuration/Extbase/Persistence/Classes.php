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
];