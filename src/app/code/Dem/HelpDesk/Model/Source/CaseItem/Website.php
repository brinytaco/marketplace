<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

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
class Website implements \Magento\Framework\Data\OptionSourceInterface
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
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $optionArray = [];

    /**
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Magento\Store\Model\System\Store $store
     * @param \Magento\Framework\App\RequestInterface $request
     * @return void
     */
    public function __construct(
            \Dem\HelpDesk\Helper\Data $helper,
            \Magento\Store\Model\System\Store $store,
            \Magento\Framework\App\RequestInterface $request
    ) {
        $this->helper = $helper;
        $this->store = $store;
        $this->request = $request;
    }

    /**
     * Return array of website names
     *
     * @return array
     */
    public function toOptionArray()
    {
        // Should add empty option
        $addEmptyOption = $this->getShouldAddEmptyOption();

        // Adminhtml view, so include Admin website
        $includeAdmin = $this->helper->getIsAdminArea();

        $this->optionArray = $this->store->getWebsiteValuesForForm($addEmptyOption, $includeAdmin);

        $this->filterDefaultWebsite();
        $this->filterDisabledWebsites();

        return $this->optionArray;
    }

    /**
     * Should add empty select option under specific circumstances
     *
     * @return boolean
     */
    protected function getShouldAddEmptyOption()
    {
        return ($this->request->getControllerName() == 'caseitem'
                && $this->request->getActionName() == 'create');
    }

    /**
     * Remove default website from optionArray
     * @return void
     */
    protected function filterDefaultWebsite()
    {
        foreach ($this->optionArray as $key => $option) {
            if ($option['value'] == \Dem\HelpDesk\Helper\Config::HELPDESK_WEBSITE_ID_DEFAULT) {
                unset($this->optionArray[$key]);
            }
        }
    }

    /**
     * Remove non-helpdesk-enabled websites from optionArray
     *
     * @return void
     */
    protected function filterDisabledWebsites()
    {
        foreach ($this->optionArray as $key => $option) {
            if ($option['value'] == \Dem\HelpDesk\Helper\Config::HELPDESK_WEBSITE_ID_ADMIN) {
                continue;
            }
            if (!$this->helper->isEnabled($option['value'])) {
                unset($this->optionArray[$key]);
            }
        }
    }
}
