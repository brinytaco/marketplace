<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Dem\HelpDesk\Model\User;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Department as DepartmentModel;
use Dem\HelpDesk\Model\UserRepository;
use Dem\HelpDesk\Model\DepartmentUserRepository;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;

/**
 * HelpDesk Resource Model - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Department extends AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var Dem\HelpDesk\Model\UserRepository
     */
    protected $userRepository;

    /**
     * @var Dem\HelpDesk\Model\DepartmentUserRepository
     */
    protected $deptUserRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param UserRepository $userRepository
     * @param DepartmentUserRepository $deptUserRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        DateTime $date,
        UserRepository $userRepository,
        DepartmentUserRepository $deptUserRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->date = $date;
        $this->userRepository = $userRepository;
        $this->deptUserRepository = $deptUserRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    /**
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_department', 'department_id');
    }

    /**
     *  Set updated_at for saving
     *
     * @param DepartmentModel $object
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->gmtDate());
        } else {
            // Set "updated_at"
            $object->setUpdatedAt($this->date->gmtDate());
        }

        return parent::_beforeSave($object);
    }

    /**
     * Perform actions after object load
     *
     * @param DepartmentModel $object
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if (!$object->getId()) {
            return $this;
        }
        // Get case manager user
        /** @var User $user */
        if ($this->userRepository) {
            $this->setCaseManagerData($object);
        }

        $this->setDefaultFollowers($object);

        return parent::_afterLoad($object);
    }

    /**
     * Get SearchCriteriaBuilder instance
     *
     * @return \Magento\Framework\Api\SearchCriteriaBuilder
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * Retrieve and set case manager data values
     *
     * @param DepartmentModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseManagerData(AbstractModel $object)
    {
        $user = $this->getUserByCaseManagerId($object->getCaseManagerId());
        $object->setData(DepartmentModel::CASE_MANAGER_NAME, $user->getName());
        $object->setData(DepartmentModel::CASE_MANAGER_EMAIL, $user->getEmail());
        return $this;
    }

    /**
     * Retrieve User by case manager id
     *
     * @param int $userId
     * @return \Dem\HelpDesk\Model\User
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getUserByCaseManagerId($userId)
    {
        return $this->userRepository->getById($userId);
    }

    /**
     * Retrieve and set default followers array values
     *
     * @param DepartmentModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setDefaultFollowers(AbstractModel $object)
    {
        $searchCriteriaBuilder = $this->getSearchCriteriaBuilder();

        $defaultFollowers = [];
        // Get default follower user ids
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter('department_id', $object->getId())
            ->addFilter('is_follower', 1)
            ->create();

        $deptFollowers = $this->getDefaultFollowersList($searchCriteria);
        /** @var Follower $follower */
        foreach ($deptFollowers->getItems() as $follower) {
            $defaultFollowers[] = $follower->getUserId();
        }

        $object->setData(DepartmentModel::DEFAULT_FOLLOWERS, $defaultFollowers);
        return $this;
    }

    /**
     * Retrieve default followers list
     *
     * @param SearchCriteria $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getDefaultFollowersList($searchCriteria)
    {
        return $this->deptUserRepository->getList($searchCriteria);
    }
}
