/**
 * Dem/HelpDesk/view/adminhtml/web/js/department.js
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
*/
require(['jquery'], function ($) {

    $(document).ajaxComplete(function ()
    {
        showDepartmentInputs();
    });

    function showDepartmentInputs()
    {
        /*
         * Enable the sort_order, active, and is_internal inputs
         * for this department if not default
         */
        var fieldIsDefaultDepartment = $('input[name="general[is_default_department]"]').val();

        if (!parseInt(fieldIsDefaultDepartment)) {
            $('.field_sort_order')
                .removeClass('_disabled')
                .find('input')
                .prop('disabled', false);
            $('.field_is_active')
                .removeClass('_disabled')
                .find('input')
                .prop('disabled', false);
            $('.field_is_internal')
                .removeClass('_disabled')
                .find('input')
                .prop('disabled', false);
        }
    }
});
