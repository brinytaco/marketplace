<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\Department as Resource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;

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
class Department extends AbstractModel
{
    const CURRENT_KEY = 'current_department';
    const CACHE_TAG = 'helpdesk_department';
    const EVENT_PREFIX = 'helpdesk_department';

    const DEPARTMENT_ID         = 'department_id';
    const WEBSITE_ID            = 'website_id';
    const CASE_MANAGER_ID       = 'case_manager_id';
    const NAME                  = 'name';
    const DESCRIPTION           = 'description';
    const IS_INTERNAL           = 'is_internal';
    const IS_ACTIVE             = 'is_active';
    const SORT_ORDER            = 'sort_order';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';

    const DEFAULT_FOLLOWERS     = '_default_followers';
    const CASE_MANAGER_NAME     = '_case_manager_name';
    const CASE_MANAGER_EMAIL     = '_case_manager_email';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * Get resource instance
     *
     * Added here for proper PHPDoc return of Resource class
     *
     * @throws LocalizedException
     * @return \Dem\HelpDesk\Model\ResourceModel\Department
     * @codeCoverageIgnore
     */
    public function getResource()
    {
        return parent::getResource();
    }

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::DEPARTMENT_ID, $id);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::DEPARTMENT_ID);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * Get case manager id
     *
     * @return int
     */
    public function getCaseManagerId()
    {
        return $this->getData(self::CASE_MANAGER_ID);
    }

    /**
     * Set case manager id
     *
     * @param int $caseManagerId
     * @return $this
     */
    public function setCaseManagerId($caseManagerId)
    {
        return $this->setData(self::CASE_MANAGER_ID, $caseManagerId);
    }

    /**
     * Get department name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get department name
     *
     * @param int $name
     * @return string
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get is_internal flag
     *
     * @return bool
     */
    public function getIsInternal()
    {
        return $this->getData(self::IS_INTERNAL);
    }

    /**
     * Set is_internal flag
     *
     * @param int|bool $isInternal
     * @return $this
     */
    public function setIsInternal($isInternal)
    {
        return $this->setData(self::IS_INTERNAL, $isInternal);
    }

    /**
     * Get is_active flag
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set is_active flag
     *
     * @param int|bool $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get sort_order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set sort_order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /******************************************************************/
    /* Dynamic properties
    /******************************************************************/

    /**
     * Get default followers
     *
     * @return int[]
     * @codeCoverageIgnore
     */
    public function getDefaultFollowers()
    {
        if (!$this->hasData(self::DEFAULT_FOLLOWERS)) {
            $this->getResource()->setDefaultFollowers($this);
        }
        return $this->getData(self::DEFAULT_FOLLOWERS);
    }


    /**
     * set default followers
     *
     * This value is set dynamically on load,
     * it should not be set here
     *
     * @param $followerIds
     * @return $this
     */
    public function setDefaultFollowers($followerIds)
    {
        return $this;
    }

    /**
     * Get case manager name
     *
     * @return string
     */
    public function getCaseManagerName()
    {
        if (!$this->hasData(self::CASE_MANAGER_NAME)) {
            $this->getResource()->setCaseManagerData($this);
        }
        return $this->getData(self::CASE_MANAGER_NAME);
    }

    /**
     * Set case manager name
     *
     * This value is set dynamically on load,
     * it should not be set here
     *
     * @param string $name
     * @return $this
     */
    public function setCaseManagerName($name)
    {
        return $this;
    }

    /**
     * Get case manager email
     *
     * @return string
     */
    public function getCaseManagerEmail()
    {
        return $this->getData(self::CASE_MANAGER_EMAIL);
    }

    /**
     * Set case manager email
     *
     * This value is set dynamically on load,
     * it should not be set here
     *
     * @param string $name
     * @return $this
     */
    public function setCaseManagerEmail($name)
    {
        return $this;
    }

}
