<?php

/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\EditorsChoice\Hook;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Override default rendering of reference element in page view
 */
class PageViewRecordsElement implements PageLayoutViewDrawItemHookInterface
{
    /**
     * Rendering of "records" content element with additional context.
     *
     * Create the context menu with an additional context variable to add an item
     * in this case.
     *
     * @param PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     * @return void
     */
    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {
        if ($row['CType'] === 'shortcut') {
            $drawItem = false;
            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            if (!empty($row['records'])) {
                $shortcutContent = [];
                $recordList = explode(',', $row['records']);
                foreach ($recordList as $recordIdentifier) {
                    $split = BackendUtility::splitTable_Uid($recordIdentifier);
                    $tableName = empty($split[0]) ? 'tt_content' : $split[0];
                    $shortcutRecord = BackendUtility::getRecord($tableName, $split[1]);
                    if (is_array($shortcutRecord)) {
                        $icon = $iconFactory->getIconForRecord($tableName, $shortcutRecord, Icon::SIZE_SMALL)->render();
                        $icon = BackendUtility::wrapClickMenuOnIcon(
                            $icon,
                            $tableName,
                            $shortcutRecord['uid'],
                            'pageModuleReferenceElement-' . $row['uid']
                        );
                        $shortcutContent[] = $icon
                            . htmlspecialchars(BackendUtility::getRecordTitle($tableName, $shortcutRecord));
                    }
                }
                $itemContent = implode('<br />', $shortcutContent) . '<br />';
            }
        }
    }
}
