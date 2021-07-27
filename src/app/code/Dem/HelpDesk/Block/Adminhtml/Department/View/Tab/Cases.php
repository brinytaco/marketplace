<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Department\View\Tab;

use Dem\HelpDesk\Block\Adminhtml\Department\View\Tabs;
use Magento\Framework\Phrase;

/**
 * HelpDesk Block - Adminhtml Department View Tab Cases
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Cases extends Tabs
{
    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return __('Cases');
    }

    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Cases');
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/department/cases', ['department_id' => $this->getDepartment()->getId()]);
    }

    /**
     * Tab should be loaded through Ajax call
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isAjaxLoaded()
    {
        return true;
    }

}
