<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Dem\HelpDesk\Model\Source\SourceOptions;

/**
 * HelpDesk Source Model - CaseItem Website
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Website extends SourceOptions
{
    /**
     * Return array of website names
     *
     * @return array
     */
    public function toOptionArray()
    {
        // Should add empty option
        $addEmptyOption = $this->getShouldAddEmptyOption();
        if ($addEmptyOption) {
            parent::toOptionArray();
        }

        // Adminhtml view, so include Admin website
        $includeAdmin = $this->helper->getIsAdminArea();

        $websiteOptions = $this->store->getWebsiteValuesForForm(false, $includeAdmin);

        $this->optionArray = array_merge($this->optionArray, $websiteOptions);

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
