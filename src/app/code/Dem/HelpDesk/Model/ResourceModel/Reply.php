<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Dem\HelpDesk\Model\UserRepository;
use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Model\Reply as ReplyModel;
use Dem\HelpDesk\Model\User;

/**
 * HelpDesk Resource Model - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Reply extends AbstractDb
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Dem\HelpDesk\Model\UserRepository
     */
    protected $userRepository;

    /**
     * @param Context $context
     * @param UserRepository $userRepository
     * @param DateTime $date
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        UserRepository $userRepository,
        DateTime $date
    ) {
        $this->date = $date;
        $this->userRepository = $userRepository;
        parent::__construct($context);
    }

    /**
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_case_reply', 'reply_id');
    }

    /**
     *  Set created_at for saving
     *
     * @param ReplyModel $object
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $object->setCreatedAt($this->date->gmtDate());
        return parent::_beforeSave($object);
    }

    /**
     * Set Author name
     *
     * @param ReplyModel $object
     * @return $this
     * @since 1.0.0
     */
    public function setAuthorName(AbstractModel $object)
    {
        if ($object->getAuthorId()) {
            $user = $this->getUserById($object->getAuthorId());
        }
        $object->setData(ReplyModel::AUTHOR_NAME, $user->getName());
        return $this;
    }

    /**
     * Get User by id
     *
     * @param int $userId
     * @return \Dem\HelpDesk\Model\User
     * @codeCoverageIgnore
     */
    protected function getUserById($userId)
    {
        return $this->userRepository->getById($userId);
    }
}
