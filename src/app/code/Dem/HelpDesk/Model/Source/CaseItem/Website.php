<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Dem\HelpDesk\Model\Source\SourceOptions;
use Dem\HelpDesk\Helper\Config;

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
     * @since 1.0.0
     */
    public function toOptionArray()
    {
        // Should add empty option
        $addEmptyOption = $this->getShouldAddEmptyOption();
        if ($addEmptyOption) {
            parent::toOptionArray();
        }

        // Adminhtml view, so include Admin website
        $includeAdmin = $this->getHelper()->getIsAdminArea();

        $websiteOptions = $this->getStore()->getWebsiteValuesForForm(false, $includeAdmin);

        $this->optionArray = array_merge($this->optionArray, $websiteOptions);

        $this->changeAdminWebsiteName($this->optionArray);
        $this->filterDefaultWebsite($this->optionArray);
        $this->filterDisabledWebsites($this->optionArray);
        return $this->optionArray;
    }

    /**
     * Should add empty select option under specific circumstances
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getShouldAddEmptyOption()
    {
        return ($this->getRequest()->getControllerName() == 'caseitem'
                && $this->getRequest()->getActionName() == 'create');
    }

    /**
     * Change default website display name
     * @return array
     * @since 1.0.0
     */
    public function changeAdminWebsiteName(&$options = [])
    {
        foreach ($options as $key => $option) {
            if ((string)$option['value'] !== '' && Config::isAdminWebsite($option['value'])) {
                $options[$key]['label'] = __('DE INTERNAL');
            }
        }
        return $options;
    }

    /**
     * Remove default website from optionArray
     * @return array
     * @since 1.0.0
     */
    public function filterDefaultWebsite(&$options = [])
    {
        foreach ($options as $key => $option) {
            if (Config::isDefaultWebsite($option['value'])) {
                unset($options[$key]);
            }
        }
        return $options;
    }

    /**
     * Remove non-helpdesk-enabled websites from optionArray
     *
     * @return array
     * @since 1.0.0
     */
    public function filterDisabledWebsites(&$options = [])
    {
        foreach ($options as $key => $option) {
            if (Config::isAdminWebsite($option['value'])) {
                continue;
            }
            if (!$this->getHelper()->isEnabled($option['value'])) {
                unset($options[$key]);
            }
        }
        return $options;
    }
}
