<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Department as DeptModel;
use Dem\HelpDesk\Model\Source\SourceOptions;
use Magento\Store\Api\Data\WebsiteInterface;
use Dem\HelpDesk\Helper\Config;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\Search\FilterGroup;

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
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteria
     */
    protected $searchCriteria;

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

        $searchCriteria = $this->getSearchCriteria();
        $this->addWebsiteFilter($searchCriteria);
        $this->addActiveFilter($searchCriteria);

        $departmentList = $this->getDepartmentRepository()->getList($searchCriteria);

        if ($departmentList->getItems()) {
            /** @var DeptModel $department */
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
        if (!isset($this->currentWebsite)) {

            /** @var WebsiteInterface $website */
            if ($this->getHelper()->getIsAdminArea()) {
                $this->currentWebsite = $this->getRegistry()->registry('current_website');
            } else {
                $this->currentWebsite = $this->getHelper()->getWebsite();
            }
        }
        if (!$this->currentWebsite) {
            return false;
        }
        return $this->currentWebsite->getId();
    }

    /**
     * Create website filter group (OR condition)
     *
     * @param SearchCriteria $searchCriteria
     * @param int $websiteId
     * @return $this
     * @since 1.0.0
     */
    public function addWebsiteFilter(&$searchCriteria)
    {
        $websiteId = $this->getCurrentWebsiteId();

        if ($websiteId !== false) {

            // Apply current website condition
            $filter1 = $this->getFilter()
                ->setField('main_table.' . DeptModel::WEBSITE_ID)
                ->setConditionType("eq")
                ->setValue((int) $websiteId);

            // Apply default department condition
            $filter2 = $this->getFilter()
                ->setField('main_table.' . DeptModel::DEPARTMENT_ID)
                ->setConditionType("eq")
                ->setValue(Config::HELPDESK_DEPARTMENT_DEFAULT_ID);

            // Filter group (OR)
            /** @var FilterGroup $filterGroup */
            $filterGroup = $this->getFilterGroup()
                ->setFilters([$filter1, $filter2]);

            $searchCriteria->setFilterGroups([$filterGroup]);
        }

        return $this;
    }

    /**
     * Set active filter
     *
     * @param SearchCriteria $searchCriteria
     * @return $this
     * @since 1.0.0
     */
    public function addActiveFilter(&$searchCriteria)
    {
        $websiteId = $this->getCurrentWebsiteId();

        if ($websiteId !== false) {

            $filterGroups = $searchCriteria->getFilterGroups();
            $activeFilter = $this->getFilter()
                ->setField('main_table.' . DeptModel::IS_ACTIVE)
                ->setConditionType("eq")
                ->setValue((int) 1);

            // Filter group (AND)
            /** @var FilterGroup $filterGroup */
            $activeFilterGroup = $this->getFilterGroup()
                ->setFilters([$activeFilter]);

            $filterGroups[] = $activeFilterGroup;

            $searchCriteria->setFilterGroups($filterGroups);
        }

        return $this;
    }

    /**
     * Get DepartmentRepository instance
     * @return \Dem\HelpDesk\Model\DepartmentRepository
     * @codeCoverageIgnore
     */
    public function getDepartmentRepository()
    {
        if (!$this->departmentRepository) {
            $this->departmentRepository = ObjectManager::getInstance()->get(DepartmentRepository::class);
        }
        return $this->departmentRepository;
    }

}
