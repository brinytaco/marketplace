define([
    'underscore',
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/url',
    'uiRegistry'
], function (_, $, select, url, registry) {
    'use strict';
    return select.extend({

        /**
         * On change, update available department list
         *
         * @param {int} value
         */
        onUpdate: function (value) {

            url.setBaseUrl(BASE_URL);
            var listUrl = url.build('caseitem/departmentlist');
            var departmentId = registry.get('index = department_id');

            if (value !== '' && typeof value !== 'undefined') {
                $.ajax({
                    showLoader: true,
                    url: listUrl,
                    data: 'website_id=' + value,
                    type: "GET",
                    dataType: 'json'
                }).done(function (data) {
                    // Hide loader
                    $('body').trigger('processStop');
                    departmentId.options(data);
                    departmentId.disabled(false);
                });
            } else {
                departmentId.disabled(true);
            }
            return this._super();
        }
    });
});
