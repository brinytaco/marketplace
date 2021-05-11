<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - Follower
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface FollowerInterface
{
    const FOLLOWER_ID         = 'follower_id';
    const CASE_ID             = 'case_id';
    const USER_ID             = 'user_id';
    const LAST_READ           = 'last_read';
    const CREATED_AT          = 'created_at';
    const UPDATED_AT          = 'updated_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get case id
     *
     * @return int|null
     */
    public function getCaseId();

    /**
     * Set case id
     *
     * @param int $caseId
     * @return FollowerInterface
     */
    public function setCaseId($caseId);

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId();

    /**
     * Set user id
     *
     * @param int $userId
     * @return FollowerInterface
     */
    public function setUserId($userId);

    /**
     * Get last read reply id
     *
     * @return int|null
     */
    public function getLastRead();

    /**
     * Set last read reply id
     *
     * @param int $lastRead
     * @return FollowerInterface
     */
    public function setLastRead($lastRead);

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
     * @return FollowerInterface
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
     * @return FollowerInterface
     */
    public function setUpdatedAt($updatedAt);
}
