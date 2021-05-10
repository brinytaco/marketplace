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
     * @var \Dem\HelpDesk\Model\ResourceModel\ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
    ) {
        $this->date = $date;
        $this->replyRepository = $replyRepository;
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
        $this->saveNewReplies($object);

        // save replies and default followers
        return parent::_afterSave($object);
    }

    /**
     * Save new replies
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function saveNewReplies(\Magento\Framework\Model\AbstractModel $object)
    {
        /* @var $reply \Dem\HelpDesk\Api\Data\ReplyInterface */
        $replies = $object->getRepliesToSave();
        if (count($replies)) {
            foreach ($replies as $reply) {
                $reply->setCaseId($object->getId());
                $this->replyRepository->save($reply);
            }
        }
        $object->clearRepliesToSave();
        return $this;
    }
}
