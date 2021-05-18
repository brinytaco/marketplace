<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

/**
 * HelpDesk Resource Model - Case
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class CaseItem extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Dem\HelpDesk\Api\ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Api\FollowerRepositoryInterface
     */
    protected $followerRepository;

    /**
     * @var \Dem\HelpDesk\Api\DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Api\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dem\HelpDesk\Api\Data\DepartmentInterface
     */
    private $department;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
     * @param \Dem\HelpDesk\Api\FollowerRepositoryInterface $followerRepository
     * @param \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository
     * @param \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository,
        \Dem\HelpDesk\Api\FollowerRepositoryInterface $followerRepository,
        \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository,
        \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->date = $date;
        $this->replyRepository = $replyRepository;
        $this->followerRepository = $followerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->userRepository = $userRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_case', 'case_id');
    }

    /**
     * Add case_number to object data after load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @since 1.0.0
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->setCaseNumber($object);
        $this->setDepartmentName($object);
        $this->setWebsiteName($object);
        $this->setCaseManagerName($object);

        return parent::_afterLoad($object);
    }

    /**
     *  and add protectCode for
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // New case, set protect_code value
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->gmtDate());
            $object->setProtectCode(sha1(microtime()));
        } else {
            // Set "updated_at"
            $object->setUpdatedAt($this->date->gmtDate());
        }
        return parent::_beforeSave($object);
    }


    /**
     * After save, perform additional actions
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveReplies($object);
        $this->saveFollowers($object);

        // save replies and default followers
        return parent::_afterSave($object);
    }

    /**
     * Save new replies
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveReplies(\Magento\Framework\Model\AbstractModel $object)
    {
        /* @var $replies array */
        /* @var $reply \Dem\HelpDesk\Api\Data\ReplyInterface */
        $replies = $object->getRepliesToSave();
        foreach ($replies as $reply) {
            $reply->setCaseId($object->getId());
            $this->replyRepository->save($reply);
        }
        $object->clearRepliesToSave();
        return $this;
    }

    /**
     * Save added/removed followers
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveFollowers(\Magento\Framework\Model\AbstractModel $object)
    {
        /* @var $followers array */
        /* @var $follower \Dem\HelpDesk\Api\Data\FollowerInterface */
        $followers = $object->getFollowersToSave();
        foreach ($followers as $follower) {
            $follower->setCaseId($object->getId());
            $this->followerRepository->save($follower);
        }
        $object->clearFollowersToSave();
        return $this;
    }

    /**
     * Build and set case number value
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseNumber(\Magento\Framework\Model\AbstractModel $object)
    {
        $websiteId = str_pad($object->getWebsiteId(), 3, '0', STR_PAD_LEFT);
        $caseId = str_pad($object->getCaseId(), 6, '0', STR_PAD_LEFT);
        $caseNumber = $websiteId . '-' . $caseId;
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::CASE_NUMBER, $caseNumber);
        return $this;
    }

    /**
     * Retrieve and set department_name value
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    public function setDepartmentName(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::DEPARTMENT_NAME, $this->getDepartment($object)->getName());
        return $this;
    }

    /**
     * Fetch department instance for this object
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    public function getDepartment(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!isset($this->department)) {
            $this->department = $this->departmentRepository->getById($object->getDepartmentId());
        }
        return $this->department;
    }

    /**
     * Retrieve and set website name value
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    public function setWebsiteName(\Magento\Framework\Model\AbstractModel $object)
    {
        /* @var $website \Magento\Store\Api\Data\WebsiteInterface */
        $website = $this->helper->getWebsite($object->getWebsiteId());
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::WEBSITE_NAME, $website->getName());
        return $this;
    }

    /**
     * Retrieve and set case manager name value
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseManagerName(\Magento\Framework\Model\AbstractModel $object)
    {
        $caseManagerId = $this->getDepartment($object)->getCaseManagerId();
        $user = $this->userRepository->getById($caseManagerId);
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::CASE_MANAGER_NAME, $user->getName());
        return $this;
    }

    /**
     * Get case replies
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getReplies(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->searchCriteriaBuilder
            ->addFilter(\Dem\HelpDesk\Api\Data\ReplyInterface::CASE_ID, $object->getId());

        $sortOrders = [
            new \Magento\Framework\Api\SortOrder(['field' => 'created_at', 'direction' => 'desc'])
        ];

        $searchCriteria = $this->searchCriteriaBuilder
            ->setSortOrders($sortOrders)
            ->create();

        return $this->replyRepository->getList($searchCriteria);
    }
}
