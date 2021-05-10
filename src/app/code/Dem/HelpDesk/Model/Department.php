<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\DepartmentInterface;

/**
 * HelpDesk Model - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Department extends AbstractModel implements DepartmentInterface
{
    const CURRENT_KEY = 'current_department';
    const CACHE_TAG = 'helpdesk_department';
    const EVENT_PREFIX = 'helpdesk_department';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Department::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(DepartmentInterface::DEPARTMENT_ID);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(DepartmentInterface::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return DepartmentInterface
     */
    public function setWebsiteId($websiteId)
    {
        return $this;
    }

    /**
     * Get case manager id
     *
     * @return int
     */
    public function getCaseManagerId()
    {
        return $this->getData(DepartmentInterface::CASE_MANAGER_ID);
    }

    /**
     * Set case manager id
     *
     * @param int $caseManagerId
     * @return DepartmentInterface
     */
    public function setCaseManagerId($caseManagerId)
    {
        return $this;
    }

    /**
     * Get department name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(DepartmentInterface::NAME);
    }

    /**
     * Get department name
     *
     * @param int $name
     * @return string
     */
    public function setName($name)
    {
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(DepartmentInterface::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return DepartmentInterface
     */
    public function setDescription($description)
    {
        return $this;
    }

    /**
     * Get is_internal flag
     *
     * @return bool
     */
    public function getIsInternal()
    {
        return $this->getData(DepartmentInterface::IS_INTERNAL);
    }

    /**
     * Set is_internal flag
     *
     * @param int|bool $isInternal
     * @return DepartmentInterface
     */
    public function setIsInternal($isInternal)
    {
        return $this;
    }

    /**
     * Get is_active flag
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->getData(DepartmentInterface::IS_ACTIVE);
    }

    /**
     * Set is_active flag
     *
     * @param int|bool $isActive
     * @return DepartmentInterface
     */
    public function setIsActive($isActive)
    {
        return $this;
    }

    /**
     * Get sort_order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(DepartmentInterface::SORT_ORDER);
    }

    /**
     * Set sort_order
     *
     * @param int $sortOrder
     * @return DepartmentInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this;
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(DepartmentInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return DepartmentInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this;
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(DepartmentInterface::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return DepartmentInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this;
    }


}
