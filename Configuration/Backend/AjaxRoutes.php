<?php
use T3G\AgencyPack\EditorsChoice\Controller;

return [
    // Dereference and element from a reference element ajax controller
    'editors-choice-dereference-element' => [
        'path' => '/context-menu/editorschoice/dereference',
        'target' => Controller\DereferenceElementAjaxController::class . '::deReferenceAction'
    ],
];
