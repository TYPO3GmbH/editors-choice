<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
    [\T3G\AgencyPack\EditorsChoice\FormEngine\DataProvider\ReferenceProvider::class] = [
        'after' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseSystemLanguageRows::class,
        ],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1486488059] = [
        'nodeName' => 'ReferencesToThisRecordWizard',
        'priority' => 40,
        'class' => \T3G\AgencyPack\EditorsChoice\FormEngine\FieldWizard\ReferencesToThisRecordWizard::class,
    ];

    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'] = [];
    }
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
        \T3G\AgencyPack\EditorsChoice\Hook\PageViewRecordsElement::class;

    $GLOBALS['TYPO3_CONF_VARS']['BE']['ContextMenu']['ItemProviders'][1487692223] =
        \T3G\AgencyPack\EditorsChoice\ContextMenu\PageViewRecordsElementItemProvider::class;
});