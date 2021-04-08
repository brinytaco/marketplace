<?php

namespace Dem\HelpDesk\Block\Adminhtml\Grid\Column\Renderer;

use Magento\Framework\DataObject;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/*
 * Adminhtml Grid Column Renderer Website Name
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class WebsiteName extends AbstractRenderer
{
    /**
     * @var \Magento\Store\Model\Website $websiteModel
     */
    protected $websiteModel;

    /**
     * @param \Magento\Store\Model\Website $websiteModel
     */

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\Website $websiteModel,
        array $data = []
    ) {
        $this->websiteModel = $websiteModel;
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
        $websiteId = $row->getWebsiteId();
        $website = $this->websiteModel->load($websiteId, 'website_id');
        return $website->getName();
    }
}