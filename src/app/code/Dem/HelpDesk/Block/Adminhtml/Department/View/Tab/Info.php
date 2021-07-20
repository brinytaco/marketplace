<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Department\View\Tab;

use Dem\HelpDesk\Block\Adminhtml\Department\View\Tabs;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Phrase;
use Magento\Framework\Data\Collection;
use Dem\HelpDesk\Model\User as HelpDeskUser;

/**
 * HelpDesk Block - Adminhtml Department View Tab Info
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Info extends Tabs
{
    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Department Information');
    }

    /**
     * Get case website name
     *
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getWebsiteName()
    {
        return $this->getDepartment()->getWebsiteName();
    }

    /**
     * Get case manager name
     *
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getCaseManagerName()
    {
        return $this->getCaseManager()->getName();
    }

    /**
     * Get case manager (User)
     *
     * @return HelpDeskUser
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getCaseManager()
    {
        return $this->getDepartment()->getCaseManager();
    }
}
