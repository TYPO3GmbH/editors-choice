<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
    [\T3G\AgencyPack\EditorsChoice\FormEngine\DataProvider\ReferenceProvider::class] = [
        'after' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
        ],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1486488059] = [
        'nodeName' => 'ReferencesToThisRecordWizard',
        'priority' => 40,
        'class' => \T3G\AgencyPack\EditorsChoice\FormEngine\ReferencesToThisRecordWizard::class,
    ];

});