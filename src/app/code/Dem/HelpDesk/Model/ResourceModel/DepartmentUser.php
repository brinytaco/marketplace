<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Dem\HelpDesk\Model\UserRepository;

/**
 * HelpDesk Resource Model - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class DepartmentUser extends AbstractDb
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
     * @param DateTime $date
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        Context $context,
        DateTime $date,
        UserRepository $userRepository
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
        $this->_init('dem_helpdesk_department_user', 'dept_user_id');
    }

    /**
     *  Set created_at for saving
     *
     * @param \Dem\HelpDesk\Model\DepartmentUser $object
     * @return $this
     * @since 1.0.0
     */
    protected function _beforeSave(AbstractModel $object)
    {
        // New case, set protect_code value
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->gmtDate());
        } else {
            // Set "updated_at"
            $object->setUpdatedAt($this->date->gmtDate());
        }

        return parent::_beforeSave($object);
    }
}
