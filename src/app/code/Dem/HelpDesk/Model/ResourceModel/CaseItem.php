<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Dem\HelpDesk\Api\Data\CaseItemInterface;

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
     * @var \Dem\HelpDesk\Model\ResourceModel\Reply
     */
    protected $replyResource;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Dem\HelpDesk\Model\ResourceModel\Reply $replyResource
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Dem\HelpDesk\Model\ResourceModel\Reply $replyResource
    ) {
        $this->date = $date;
        $this->replyResource = $replyResource;
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
        $replies = $object->getRepliesToSave();
        if (count($replies)) {
            /* $reply \Dem\HelpDesk\Api\Data\ReplyInterface */
            foreach ($replies as $reply) {
                $reply->setCaseId($object->getId());
                $this->replyResource->save($reply);
            }
        }
        return $this;
    }
}
