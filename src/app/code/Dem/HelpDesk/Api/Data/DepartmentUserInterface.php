<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - DepartmentUser
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface DepartmentUserInterface
{
    const DEPT_USER_ID          = 'dept_user_id';
    const DEPARTMENT_ID         = 'department_id';
    const USER_ID               = 'user_id';
    const IS_FOLLOWER           = 'is_follower';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get Department id
     *
     * @return int
     */
    public function getDepartmentId();

    /**
     * Set Department id
     *
     * @param int $departmentId
     * @return DepartmentUserInterface
     */
    public function setDepartmentId($departmentId);

    /**
     * Get User id
     *
     * @return string|null
     */
    public function getUserId();

    /**
     * Set User id
     *
     * @param int $userId
     * @return DepartmentUserInterface
     */
    public function setUserId($userId);

    /**
     * Get is follower flag
     *
     * @return string|null
     */
    public function getIsFollower();

    /**
     * Set is follower flag
     *
     * @param bool $isFollower
     * @return DepartmentUserInterface
     */
    public function setIsFollower($isFollower);

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
     * @return DepartmentUserInterface
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
     * @return DepartmentUserInterface
     */
    public function setUpdatedAt($updatedAt);
}
