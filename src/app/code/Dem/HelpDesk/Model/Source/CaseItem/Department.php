<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\Api\Search\FilterGroup;
use Dem\HelpDesk\Model\Source\SourceOptions;
use Magento\Store\Api\Data\WebsiteInterface;
use Dem\HelpDesk\Helper\Config;

/**
 * HelpDesk Source Model - CaseItem Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Department extends SourceOptions
{
    /**
     * Return array of available departments for all websites
     *
     * @return array
     * @since 1.0.0
     */
    public function toOptionArray($addEmpty = true)
    {
        if ($addEmpty) {
            parent::toOptionArray();
        }

        $websiteId = $this->getCurrentWebsiteId();

        // Website must be set
        if ($websiteId !== false) {

            // Add website filters
            $websiteFilter = $this->addWebsiteFilter($websiteId);

            $searchCriteria = $this->searchCriteriaBuilder
                ->setFilterGroups([$websiteFilter])
                ->create();

            $departmentList = $this->departmentRepository->getList($searchCriteria);

            foreach ($departmentList->getItems() as $department) {
                $this->optionArray[] = [
                    'label' => $department->getName(),
                    'value' => $department->getId()
                ];
            }
        }

        return $this->optionArray;
    }

    /**
     * Get current website. If adminhtml area, get registry value.
     *
     * @return int|bool
     * @since 1.0.0
     */
    public function getCurrentWebsiteId()
    {
        /** @var WebsiteInterface $website */
        if ($this->helper->getIsAdminArea()) {
            $website = $this->coreRegistry->registry('current_website');
        } else {
            $website = $this->helper::getWebsite();
        }
        if ($website) {
            return $website->getId();
        }
        return false;
    }

    /**
     * Create website filter group (OR condition)
     *
     * @param int $websiteId
     * @return FilterGroup
     * @since 1.0.0
     */
    protected function addWebsiteFilter($websiteId)
    {
        // Apply current website condition
        $filter1 = $this->filterBuilder
            ->setField(\Dem\HelpDesk\Model\Department::WEBSITE_ID)
            ->setConditionType("eq")
            ->setValue($websiteId)
            ->create();

        // Apply default department condition
        $filter2 = $this->filterBuilder
            ->setField(\Dem\HelpDesk\Model\Department::DEPARTMENT_ID)
            ->setConditionType("eq")
            ->setValue(Config::HELPDESK_DEPARTMENT_DEFAULT_ID)
            ->create();

        // Filter group (OR)
        $websiteFilterGroup = $this->filterGroupBuilder
            ->addFilter($filter1)
            ->addFilter($filter2)
            ->create();

        return $websiteFilterGroup;
    }

}
