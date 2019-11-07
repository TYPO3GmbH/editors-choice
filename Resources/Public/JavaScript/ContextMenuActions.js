/*
 * This file is part of the package t3g/editors-choice.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

/**
 * Module: TYPO3/CMS/EditorsChoice/ContextMenuActions
 *
 * JavaScript to handle "dereference" click in context menu of reference content elements in page module.
 * @exports TYPO3/CMS/EditorsChoice/ContextMenuActions
 */
define(function () {
    'use strict';

    var ContextMenuActions = {};

    /**
     * Dereference an item from reference element
     *
     * @param {string} table
     * @param {int} uid of the element
     */
    ContextMenuActions.deReference = function (table, uid) {
        $.ajax({
            type: 'POST',
            url: TYPO3.settings.ajaxUrls['editors-choice-dereference-element'],
            data: {
                'tableName': table,
                'contentElementUid': uid,
                'referenceElementUid': $(this).data('reference-element-uid')
            }
        }).always(function (data) {
            top.list_frame.location.reload(true);
        });
    };

    return ContextMenuActions;
});
