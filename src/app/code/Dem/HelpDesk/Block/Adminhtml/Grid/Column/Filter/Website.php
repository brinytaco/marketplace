<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Grid\Column\Filter;

use Magento\Framework\Option\ArrayInterface;

/**
 * HelpDesk Block - Adminhtml Grid Column Filter Website
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Website implements ArrayInterface
{
    /**
     * @var ListInterface
     */
    protected $list;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(
        \Dem\HelpDesk\Helper\Data $helper,
        \Magento\Store\Model\System\Store $store
    ) {
        $this->helper = $helper;
        $this->store = $store;
    }

    /**
     * Return array of scope names
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->store->getWebsiteValuesForForm($empty = false, $all = true);

        foreach ($options as $key => $option) {
            if (!$this->helper->isEnabled($option['value'])) {
                unset($options[$key]);
            }
        }

        // Change the label of the Admin website
        $options[\Dem\HelpDesk\Helper\Config::HELPDESK_WEBSITE_ID_ADMIN]['label'] = __('DE INTERNAL*');

        return $options;
    }
}
