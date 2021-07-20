<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Dem\HelpDesk\Model\UserRepository;
use Magento\Framework\Model\AbstractModel;

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
     * @var DateTime
     */
    protected $date;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param Context $context
     * @param UserRepository $userRepository
     * @param DateTime $date
     * @return void
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
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_case_reply', 'reply_id');
    }

    /**
     *  Set created_at for saving
     *
     * @param \Dem\HelpDesk\Model\Reply $object
     * @return $this
     * @since 1.0.0
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $object->setCreatedAt($this->date->gmtDate());
        return parent::_beforeSave($object);
    }
}
