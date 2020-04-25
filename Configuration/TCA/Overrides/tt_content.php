<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $GLOBALS['TCA']['tt_content']['ctrl']['container'] = [
        'outerWrapContainer' => [
            'fieldWizard' => [
                'ReferencesToThisRecordWizard' => [
                    'renderType' => 'ReferencesToThisRecordWizard',
                ],
            ],
        ],
    ];

    // Add a field to pages table that allows setting a checkbox. If set, this content element
    // can be referenced in an insert records element
    $additionalColumns = [
        'enable_reference' => [
            'label' => 'LLL:EXT:editors_choice/Resources/Private/Language/locallang_references.xlf:tt_content_enable_reference',
            'config' => [
                'type' => 'check',
                // TODO: Enable once we drop support for v8
                // 'renderType' => 'checkboxToggle',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:editors_choice/Resources/Private/Language/locallang_references.xlf:labels.enabled'
                    ],
                ],
            ],
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
    $types = array_keys($GLOBALS['TCA']['tt_content']['types']);
    $types = array_diff($types, ['shortcut']);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'enable_reference', implode(',', $types), 'after:editlock');

    // Consider field in insert records element
    $GLOBALS['TCA']['tt_content']['columns']['records']['config']['suggestOptions']['default']['addWhere'] = 'enable_reference=1';

    // Disable insert records element browser and clipboard
    $GLOBALS['TCA']['tt_content']['columns']['records']['config']['fieldControl']['elementBrowser']['disabled'] = true;
    $GLOBALS['TCA']['tt_content']['columns']['records']['config']['fieldControl']['insertClipboard']['disabled'] = true;
});
