<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Department\View\Tab;

use Dem\HelpDesk\Block\Adminhtml\Department\View\Tabs;
use Magento\Framework\Phrase;

/**
 * HelpDesk Block - Adminhtml Department View Tab Users
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Users extends Tabs
{
    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return __('Users');
    }

    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Users');
    }

}
