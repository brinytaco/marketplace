<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Grid\Column\Renderer;

use Magento\Framework\DataObject;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/**
 * HelpDesk Block - Adminhtml Grid Column Renderer WebsiteName
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class WebsiteName extends AbstractRenderer
{
    /**
     * @param Context $context
     * @param \Magento\Store\Model\Website $websiteModel
     * @param array $data
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get website name from website_id value
     *
     * @param  DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        return $row->getWebsite()->getName();
    }
}
