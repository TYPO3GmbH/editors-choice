<?php
/************************************************************************
 * Extension Manager/Repository config file for ext "editors_choice".
 ************************************************************************/
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Editors Choice',
    'description' => 'Improvements for TYPO3 Editors',
    'category' => 'extension',
    'constraints' => array(
        'depends' => array(
            'typo3' => '8.6.0-9.5.99'
        ),
        'conflicts' => array(),
    ),
    'autoload' => array(
        'psr-4' => array(
            'T3G\\AgencyPack\\EditorsChoice\\' => 'Classes',
        ),
    ),
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'TYPO3 GmbH',
    'author_email' => 'info@typo3.com',
    'author_company' => 'TYPO3 GmbH',
    'version' => '2.0.0',
);
