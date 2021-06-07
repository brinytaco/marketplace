<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchResultsInterface;
use Dem\HelpDesk\Api\ReplyRepositoryInterface;
use Dem\HelpDesk\Api\FollowerRepositoryInterface;
use Dem\HelpDesk\Api\DepartmentRepositoryInterface;
use Dem\HelpDesk\Api\UserRepositoryInterface;
use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\Data\FollowerInterface;
use Dem\HelpDesk\Helper\Data as Helper;

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
class CaseItem extends AbstractDb
{
    /**
     * @var ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var FollowerRepositoryInterface
     */
    protected $followerRepository;

    /**
     * @var DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var DepartmentInterface
     */
    private $department;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param ReplyRepositoryInterface $replyRepository
     * @param FollowerRepositoryInterface $followerRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param UserRepositoryInterface $userRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Helper $helper
     * @return void
     */
    public function __construct(
        Context $context,
        DateTime $date,
        ReplyRepositoryInterface $replyRepository,
        FollowerRepositoryInterface $followerRepository,
        DepartmentRepositoryInterface $departmentRepository,
        UserRepositoryInterface $userRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Helper $helper
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
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $this->setCaseNumber($object);
            $this->setDepartmentName($object);
            $this->setWebsiteName($object);
            $this->setCaseManager($object);
        }
        return parent::_afterLoad($object);
    }

    /**
     *  and add protectCode for
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    protected function _beforeSave(AbstractModel $object)
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
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    protected function _afterSave(AbstractModel $object)
    {
        // save replies and default followers
        $this->saveReplies($object);
        $this->saveFollowers($object);
        return parent::_afterSave($object);
    }

    /**
     * Save new replies
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveReplies(AbstractModel $object)
    {
        /** @var array $replies */
        /** @var ReplyInterface $reply */
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
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveFollowers(AbstractModel $object)
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
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseNumber(AbstractModel $object)
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
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    public function setDepartmentName(AbstractModel $object)
    {
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::DEPARTMENT_NAME, $this->getDepartment($object)->getName());
        return $this;
    }

    /**
     * Fetch department instance for this object
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    public function getDepartment(AbstractModel $object)
    {
        if (!isset($this->department)) {
            $this->department = $this->departmentRepository->getById($object->getDepartmentId());
        }
        return $this->department;
    }

    /**
     * Retrieve and set website name value
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    public function setWebsiteName(AbstractModel $object)
    {
        /* @var $website \Magento\Store\Api\Data\WebsiteInterface */
        $website = $this->helper->getWebsite($object->getWebsiteId());
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::WEBSITE_NAME, $website->getName());
        return $this;
    }

    /**
     * Retrieve and set case manager name value
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseManager(AbstractModel $object)
    {
        $caseManagerId = $this->getDepartment($object)->getCaseManagerId();
        $user = $this->userRepository->getById($caseManagerId);
        $object->setData(CaseItemInterface::CASE_MANAGER, $user);
        return $this;
    }

    /**
     * Get case replies
     *
     * Sort in reverse order to always place most recent on top
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return SearchResultsInterface
     */
    public function getReplies(AbstractModel $object)
    {
        $this->searchCriteriaBuilder
            ->addFilter(ReplyInterface::CASE_ID, $object->getId());

        $sortOrders = [
            new SortOrder(['field' => 'reply_id', 'direction' => 'desc'])
        ];

        $searchCriteria = $this->searchCriteriaBuilder
            ->setSortOrders($sortOrders)
            ->create();

        return $this->replyRepository->getList($searchCriteria);
    }

    /**
     * Get case followers
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return SearchResultsInterface
     */
    public function getFollowers(AbstractModel $object)
    {
        $this->searchCriteriaBuilder
            ->addFilter(FollowerInterface::CASE_ID, $object->getId());

        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $this->followerRepository->getList($searchCriteria);
    }
}
