<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

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
class Reply extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
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
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @since 1.0.0
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setCreatedAt($this->date->gmtDate());

        return parent::_beforeSave($object);
    }
}
