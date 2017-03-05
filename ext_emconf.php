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
            'typo3' => '8.6.0-8.99.99'
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
    'author' => 'Susanne Moog',
    'author_email' => 'susanne.moog@typo3.com',
    'author_company' => 'TYPO3 GmbH',
    'version' => '1.0.0',
);
