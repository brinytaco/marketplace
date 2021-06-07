<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface DepartmentInterface
{
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
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return DepartmentInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Get case manager id
     *
     * @return int
     */
    public function getCaseManagerId();

    /**
     * Set case manager id
     *
     * @param int $caseManagerId
     * @return DepartmentInterface
     */
    public function setCaseManagerId($caseManagerId);

    /**
     * Get department name
     *
     * @return string
     */
    public function getName();

    /**
     * Get department name
     *
     * @param string $name
     * @return string
     */
    public function setName($name);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return DepartmentInterface
     */
    public function setDescription($description);

    /**
     * Get is_internal flag
     *
     * @return bool
     */
    public function getIsInternal();

    /**
     * Set is_internal flag
     *
     * @param int|bool $isInternal
     * @return DepartmentInterface
     */
    public function setIsInternal($isInternal);

    /**
     * Get is_active flag
     *
     * @return bool
     */
    public function getIsActive();

    /**
     * Set is_active flag
     *
     * @param int|bool $isActive
     * @return DepartmentInterface
     */
    public function setIsActive($isActive);

    /**
     * Get sort_order
     *
     * @return int
     */
    public function getSortOrder();

    /**
     * Set sort_order
     *
     * @param int $sortOrder
     * @return DepartmentInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return DepartmentInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return DepartmentInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get default followers
     *
     * @return array int
     */
    public function getDefaultFollowers();

    /**
     * set default followers
     *
     * @param $followerIds
     * @return DepartmentInterface
     */
    public function setDefaultFollowers($followerIds);
}
