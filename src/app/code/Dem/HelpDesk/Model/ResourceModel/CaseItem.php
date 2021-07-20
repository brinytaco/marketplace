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
use Magento\Store\Api\Data\WebsiteInterface;
use Dem\HelpDesk\Model\ReplyRepository;
use Dem\HelpDesk\Model\FollowerRepository;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\UserRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\CaseItem as CaseModel;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Follower;
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
     * @var ReplyRepository
     */
    protected $replyRepository;

    /**
     * @var FollowerRepository
     */
    protected $followerRepository;

    /**
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var UserRepository
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
     * @var Department
     */
    private $department;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param ReplyRepository $replyRepository
     * @param FollowerRepository $followerRepository
     * @param DepartmentRepository $departmentRepository
     * @param UserRepository $userRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Helper $helper
     * @return void
     */
    public function __construct(
        Context $context,
        DateTime $date,
        ReplyRepository $replyRepository,
        FollowerRepository $followerRepository,
        DepartmentRepository $departmentRepository,
        UserRepository $userRepository,
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
     * @param CaseModel $object
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
     * @param CaseModel $object
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
     * @param CaseModel $object
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
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveReplies(CaseModel $object)
    {
        /** @var Reply $reply */
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
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    protected function saveFollowers(CaseModel $object)
    {
        /** @var Follower $follower */
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
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseNumber(AbstractModel $object)
    {
        $websiteId = str_pad((string) $object->getWebsiteId(), 3, '0', STR_PAD_LEFT);
        $caseId = str_pad($object->getCaseId(), 6, '0', STR_PAD_LEFT);
        $caseNumber = $websiteId . '-' . $caseId;
        $object->setData(CaseModel::CASE_NUMBER, $caseNumber);
        return $this;
    }

    /**
     * Retrieve and set department_name value
     *
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setDepartmentName(AbstractModel $object)
    {
        $object->setData(CaseModel::DEPARTMENT_NAME, $this->getDepartment($object)->getName());
        return $this;
    }

    /**
     * Fetch department instance for this object
     *
     * @param CaseModel $object
     * @return Department
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
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setWebsiteName(AbstractModel $object)
    {
        /** @var WebsiteInterface $website */
        $website = $this->helper->getWebsite($object->getWebsiteId());
        $object->setData(CaseModel::WEBSITE_NAME, $website->getName());
        return $this;
    }

    /**
     * Retrieve and set case manager name value
     *
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseManager(AbstractModel $object)
    {
        $caseManagerId = $this->getDepartment($object)->getCaseManagerId();
        $user = $this->userRepository->getById($caseManagerId);
        $object->setData(CaseModel::CASE_MANAGER, $user);
        return $this;
    }

    /**
     * Get case replies
     *
     * Sort in reverse order to always place most recent on top
     *
     * @param CaseModel $object
     * @return SearchResultsInterface
     */
    public function getReplies(AbstractModel $object)
    {
        $this->searchCriteriaBuilder
            ->addFilter(Reply::CASE_ID, $object->getId());

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
     * @param CaseModel $object
     * @return SearchResultsInterface
     */
    public function getFollowers(AbstractModel $object)
    {
        $this->searchCriteriaBuilder
            ->addFilter(Follower::CASE_ID, $object->getId());

        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $this->followerRepository->getList($searchCriteria);
    }
}
