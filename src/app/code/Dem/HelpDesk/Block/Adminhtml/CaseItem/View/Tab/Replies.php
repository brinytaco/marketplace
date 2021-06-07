<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab;

use Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs;
use Magento\Framework\Phrase;

/**
 * HelpDesk Block - Adminhtml CaseItem View Tab Replies
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Replies extends Tabs
{
    /**
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('All Replies');
    }

    /**
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('All Replies');
    }

}
