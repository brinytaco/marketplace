<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Grid\Column\Filter\CaseItem;

use Magento\Framework\Option\ArrayInterface;

/**
 * HelpDesk Block - Adminhtml Grid Column Filter CaseItem Website
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
     * @return void
     */
    public function __construct(
            \Dem\HelpDesk\Helper\Data $helper,
            \Magento\Store\Model\System\Store $store
    ) {
        $this->helper = $helper;
        $this->store = $store;
    }

    /**
     * Return array of website names
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @param bool $includeAdmin Adds "admin" website to list if true
     * @return array
     */
    public function toOptionArray($includeAdmin = true)
    {
        $options = $this->store->getWebsiteValuesForForm($addEmptyOption = false, $includeAdmin);

        foreach ($options as $key => $option) {
            if (!$this->helper->isEnabled($option['value'])) {
                unset($options[$key]);
            }
        }

        // Change the label of the Admin website
        $options[\Dem\HelpDesk\Helper\Config::HELPDESK_WEBSITE_ID_ADMIN]['label'] = __('DE INTERNAL');

        return $options;
    }
}
