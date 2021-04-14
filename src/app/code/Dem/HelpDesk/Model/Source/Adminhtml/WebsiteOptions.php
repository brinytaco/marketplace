<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\Adminhtml;

use Magento\Framework\Option\ArrayInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * HelpDesk Helper - Data
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class WebsiteOptions implements ArrayInterface
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
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(\Dem\HelpDesk\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Return array of scope names
     *
     * @return array
     */
    public function toOptionArray()
    {
        /* @var $store Magento\Store\Model\System\Store */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $objectManager->get('Magento\Store\Model\System\Store');

        $options = $store->getWebsiteValuesForForm($empty = false, $all = true);

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
