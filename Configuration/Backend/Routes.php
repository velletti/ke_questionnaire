<?php
/**
 * Definitions for routes provided by EXT:ke_questionnaire
 * Contains all "regular" routes for entry points
 */
return [
    /** Wizards */
    'keQuestionnaireBe' => [
        'path' => '/keQuestionnaireBe/KeQuestionnaireAuthcode',
        'target' => Kennziffer\KeQuestionnaire\Controller\BackendController::class . '::createAndMailAuthCodesAction'
    ] ,

];
