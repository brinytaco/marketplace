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
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

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
     * @var DateTime
     */
    protected $date;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var DepartmentUserRepository
     */
    protected $deptUserRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param UserRepository $userRepository
     * @param DepartmentUserRepository $deptUserRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
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
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if (!$object->getId()) {
            return $this;
        }
        // Get case manager user
        /** @var User $user */
        $user = $this->userRepository->getById($object->getCaseManagerId());
        $object->setData(DepartmentModel::CASE_MANAGER_NAME, $user->getName());
        $object->setData(DepartmentModel::CASE_MANAGER_EMAIL, $user->getEmail());

        // Get default follower user ids
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('department_id', $object->getId())
            ->addFilter('is_follower', 1)
            ->create();

        $deptFollowers = $this->deptUserRepository->getList($searchCriteria);
        $defaultFollowers = [];
        /** @var Follower $follower */
        foreach ($deptFollowers->getItems() as $follower) {
            $defaultFollowers[] = $follower->getUserId();
        }

        $object->setData(DepartmentModel::DEFAULT_FOLLOWERS, $defaultFollowers);

        return parent::_afterLoad($object);
    }
}
