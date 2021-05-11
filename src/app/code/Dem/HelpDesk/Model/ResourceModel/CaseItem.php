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
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
     * @param \Dem\HelpDesk\Api\FollowerRepositoryInterface $followerRepository
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository,
        \Dem\HelpDesk\Api\FollowerRepositoryInterface $followerRepository
    ) {
        $this->date = $date;
        $this->replyRepository = $replyRepository;
        $this->followerRepository = $followerRepository;
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
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $websiteId = str_pad($object->getWebsiteId(), 3, '0', STR_PAD_LEFT);
        $caseId = str_pad($object->getCaseId(), 6, '0', STR_PAD_LEFT);
        $caseNumber = $websiteId . '-' . $caseId;
        $object->setData(\Dem\HelpDesk\Api\Data\CaseItemInterface::CASE_NUMBER, $caseNumber);
        return parent::_afterLoad($object);
    }

    /**
     *  and add protectCode for
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
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
}
