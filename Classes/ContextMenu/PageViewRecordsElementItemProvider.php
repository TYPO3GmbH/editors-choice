<?php

/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\EditorsChoice\ContextMenu;

use TYPO3\CMS\Backend\ContextMenu\ItemProviders\AbstractProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A context menu item provider to add the "dereference" item to
 * records content element in page module.
 */
class PageViewRecordsElementItemProvider extends AbstractProvider
{
    /**
     * @var array
     */
    protected $itemsConfiguration = [
        'dereference' => [
            'type' => 'item',
            'label' => 'LLL:EXT:editors_choice/Resources/Private/Language/locallang_references.xlf:dereference',
            'iconIdentifier' => 'actions-merge',
            'callbackAction' => 'deReference'
        ]
    ];

    /**
     * Returns the provider priority which is used for determining the order in which providers are adding items
     * to the result array. Highest priority means provider is evaluated first.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 40;
    }

    /**
     * Whether this provider can handle given request (usually a check based on table, uid and context)
     *
     * @return bool
     */
    public function canHandle(): bool
    {
        $result = false;
        if (strpos($this->context, 'pageModuleReferenceElement') === 0 && $this->table === 'tt_content') {
            $result = true;
        }
        return $result;
    }

    /**
     * Add "dereference" item
     *
     * @param array $items
     * @return array
     */
    public function addItems(array $items): array
    {
        $localItems = $this->prepareItems($this->itemsConfiguration);
        $items += $localItems;
        return $items;
    }

    /**
     * Load JS requireJS module
     *
     * @param string $itemName
     * @return array
     */
    protected function getAdditionalAttributes(string $itemName): array
    {
        $referenceElementUid = GeneralUtility::trimExplode('-', $this->context);
        $result = [
            'data-callback-module' => 'TYPO3/CMS/EditorsChoice/ContextMenuActions',
            'data-reference-element-uid' => $referenceElementUid[1],
        ];
        return $result;
    }
}
