/**
 * Dem/HelpDesk/view/adminhtml/web/js/case.js
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
require(['jquery'], function($){

    /**
     * Common link to active "all replies" tab in case view
     *
     * Note: Must use the "on" method bound to a static parent object
     * to listen for dynamically created elements. For ui-component
     * driven pages, most all the content under the #container div
     * is dynamic.
     */
    $('#container').on("click", '.view-all-replies', function () {
        $('#tab_dem_helpdesk_caseitem_tab_replies_content').click();
    });

});
