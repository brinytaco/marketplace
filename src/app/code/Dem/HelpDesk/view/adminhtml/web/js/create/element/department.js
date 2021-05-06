define([
    'underscore',
    'jquery',
    'Magento_Ui/js/form/element/select',
    'uiRegistry'
], function (_, $, select, registry) {
    'use strict';
    return select.extend({

        initialize: function() {
            return this._super();
        },

        /**
         * On change, enable/disable form elements
         *
         * @param {int} value
         */
        onUpdate: function (value) {

            var subjectField = registry.get('index = subject');
            var priorityField = registry.get('index = priority');
            var messageField = registry.get('index = message');

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