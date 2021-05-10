<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\DepartmentUserInterface;

/**
 * HelpDesk Model - DepartmentUser
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentUser extends AbstractModel implements DepartmentUserInterface
{
    const EVENT_PREFIX = 'helpdesk_department_user';

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
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\DepartmentUser::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(DepartmentUserInterface::DEPT_USER_ID);
    }

    /**
     * Get Department id
     *
     * @return int
     */
    public function getDepartmentId()
    {
        return $this->getData(DepartmentUserInterface::DEPARTMENT_ID);
    }

    /**
     * Set Department id
     *
     * @param int $departmentId
     * @return DepartmentUserInterface
     */
    public function setDepartmentId($departmentId)
    {
        return $this->setData(DepartmentUserInterface::DEPARTMENT_ID, $departmentId);
    }

    /**
     * Get User id
     *
     * @return string|null
     */
    public function getUserId()
    {
        return $this->getData(DepartmentUserInterface::USER_ID);
    }

    /**
     * Set User id
     *
     * @param int $userId
     * @return DepartmentUserInterface
     */
    public function setUserId($userId)
    {
        return $this->setData(DepartmentUserInterface::USER_ID, $userId);
    }

    /**
     * Get is follower flag
     *
     * @return string|null
     */
    public function getIsFollower()
    {
        return $this->getData(DepartmentUserInterface::IS_FOLLOWER);
    }

    /**
     * Set is follower flag
     *
     * @param bool $isFollower
     * @return DepartmentUserInterface
     */
    public function setIsFollower($isFollower)
    {
        return $this->setData(DepartmentUserInterface::IS_FOLLOWER, $isFollower);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(DepartmentUserInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return DepartmentUserInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(DepartmentUserInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(DepartmentUserInterface::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return DepartmentUserInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(DepartmentUserInterface::UPDATED_AT, $updatedAt);
    }

}
