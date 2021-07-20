<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Store\Api\Data\WebsiteInterface;
use Dem\HelpDesk\Model\ReplyRepository;
use Dem\HelpDesk\Model\FollowerRepository;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\UserRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\CaseItem as CaseModel;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\User;
use Dem\HelpDesk\Helper\Data as Helper;
use Magento\AdobeStockAsset\Model\SearchResults;

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
     * @var \Dem\HelpDesk\Model\ReplyRepository
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Model\FollowerRepository
     */
    protected $followerRepository;

    /**
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Model\UserRepository
     */
    protected $userRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var Dem\HelpDesk\Model\Department
     */
    private $department;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_case', CaseModel::CASE_ID);
    }

    /**
     * Add case_number to object data after load
     *
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     */
    protected function _beforeSave(AbstractModel $object)
    {
        // New case, set protect_code value
        if (!$object->getOrigData(CaseModel::CASE_ID)) {
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
     * @codeCoverageIgnore
     */
    protected function _afterSave(AbstractModel $object)
    {
        // save replies and default followers
        $this->saveReplies($object);
        $this->saveFollowers($object);
        return parent::_afterSave($object);
    }

    /**
     * Get helper instance
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
     * Get website object by id
     *
     * @return \Magento\Store\Api\Data\WebsiteInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getWebsite($websiteId)
    {
        return $this->helper->getWebsite($websiteId);
    }

    /**
     * Save new replies
     *
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function saveReplies(CaseModel $object)
    {
        /** @var Reply $reply */
        $replies = $object->getRepliesToSave();
        foreach ($replies as $reply) {
            $reply->setCaseId($object->getId());
            $this->getReplyRepository()->save($reply);
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
    public function saveFollowers(CaseModel $object)
    {
        /** @var Follower $follower */
        $followers = $object->getFollowersToSave();
        foreach ($followers as $follower) {
            $follower->setCaseId($object->getId());
            $this->getFollowerRepository()->save($follower);
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
        $caseId = str_pad((string) $object->getCaseId(), 6, '0', STR_PAD_LEFT);
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
            if ($this->getDepartmentRepository()) {
                $this->department = $this->getDepartmentRepository()->getById($object->getDepartmentId());
            } elseif ($object->hasData('_department')) {
                $this->department = $object->getData('_department');
            } else {
                $this->department = ObjectManager::getInstance()->get(Department::class);
            }
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
        $website = $this->getWebsite($object->getWebsiteId());
        $object->setData(CaseModel::WEBSITE_NAME, $website->getName());
        return $this;
    }

    /**
     * Retrieve and set helpdesk user as case manager object
     *
     * @param CaseModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setCaseManager(AbstractModel $object)
    {
        $caseManagerId = $this->getDepartment($object)->getCaseManagerId();
        if ($this->getUserRepository()) {
            $user = $this->getUserRepository()->getById($caseManagerId);
        } else {
            $user = ObjectManager::getInstance()->get(User::class);
        }
        $object->setData(CaseModel::CASE_MANAGER, $user);
        return $this;
    }

    /**
     * Get case replies
     *
     * Sort in reverse order to always place most recent on top
     *
     * @param CaseModel $object
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     */
    public function getReplies(AbstractModel $object)
    {
        $searchCriteriaBuilder = $this->getSearchCriteriaBuilder();

        $sortOrders = [
            new SortOrder(['field' => 'reply_id', 'direction' => 'desc'])
        ];;

        $searchCriteria = $searchCriteriaBuilder
            ->addFilter(Reply::CASE_ID, $object->getId())
            ->setSortOrders($sortOrders)
            ->create();

        return $this->getRepliesList($searchCriteria);
    }

    /**
     * Retrieve default followers list
     *
     * @param SearchCriteria $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getRepliesList($searchCriteria)
    {
        return $this->getReplyRepository()->getList($searchCriteria);
    }

    /**
     * Get case followers
     *
     * @param CaseModel $object
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     */
    public function getFollowers(AbstractModel $object)
    {
        $searchCriteriaBuilder = $this->getSearchCriteriaBuilder();

        $searchCriteria = $searchCriteriaBuilder
            ->addFilter(Follower::CASE_ID, $object->getId())
            ->create();

        return $this->getFollowersList($searchCriteria);
    }

    /**
     * Retrieve default followers list
     *
     * @param SearchCriteria $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getFollowersList($searchCriteria)
    {
        return $this->getFollowerRepository()->getList($searchCriteria);
    }

    /**
     * Get ReplyRepository instance
     *
     * @return \Dem\HelpDesk\Model\ReplyRepository
     * @codeCoverageIgnore
     */
    protected function getReplyRepository()
    {
        return $this->replyRepository;
    }

    /**
     * Get FollowerRepository instance
     *
     * @return \Dem\HelpDesk\Model\FollowerRepository
     * @codeCoverageIgnore
     */
    protected function getFollowerRepository()
    {
        return $this->followerRepository;
    }

    /**
     * Get DepartmentRepository instance
     *
     * @return \Dem\HelpDesk\Model\DepartmentRepository
     * @codeCoverageIgnore
     */
    protected function getDepartmentRepository()
    {
        return $this->departmentRepository;
    }

    /**
     * Get UserRepository instance
     *
     * @return \Dem\HelpDesk\Model\UserRepository
     * @codeCoverageIgnore
     */
    protected function getUserRepository()
    {
        return $this->userRepository;
    }
}
