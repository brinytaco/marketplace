/**
 * Dem/HelpDesk/view/adminhtml/web/js/create/element/department.js
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
define([
    'underscore',
    'jquery',
    'Magento_Ui/js/form/element/select',
    'uiRegistry'
], function (_, $, select, registry) {
    'use strict';
    return select.extend({

        /**
         * On change, enable/disable form elements
         *
         * @param {int} value
         */
        onUpdate: function (value) {

            var subjectField = registry.get('index = subject');
            var priorityField = registry.get('index = priority');
            var messageField = registry.get('index = reply_text');

            if (value !== '' && typeof value !== 'undefined') {
                subjectField.disabled(false);
                priorityField.disabled(false);
                messageField.disabled(false);
            } else {
                subjectField.disabled(true);
                priorityField.disabled(true);
                messageField.disabled(true);
            }
            return this._super();
        }
    });
});
