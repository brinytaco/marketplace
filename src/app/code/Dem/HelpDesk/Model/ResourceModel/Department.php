<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

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
class Department extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Dem\HelpDesk\Api\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var \Dem\HelpDesk\Api\DepartmentUserRepositoryInterface
     */
    protected $deptUserRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository
     * @param \Dem\HelpDesk\Api\DepartmentUserRepositoryInterface $deptUserRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository,
        \Dem\HelpDesk\Api\DepartmentUserRepositoryInterface $deptUserRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
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
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
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
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        // Get case manager user
        $user = $this->userRepository->getById($object->getCaseManagerId());
        $object->setCaseManagerName($user->getName());
        $object->setCaseManagerEmail($user->getEmail());

        // Get default follower user ids

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('department_id', $object->getId())
            ->addFilter('is_follower', 1)
            ->create();

        $deptFollowers = $this->deptUserRepository->getList($searchCriteria);
        $defaultFollowers = [];
        foreach ($deptFollowers->getItems() as $follower) {
            $defaultFollowers[] = $follower->getUserId();
        }

        $object->setDefaultFollowers($defaultFollowers);

        parent::_afterLoad($object);
    }
}
