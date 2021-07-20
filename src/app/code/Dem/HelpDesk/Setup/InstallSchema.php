<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Dem\HelpDesk\Model\DepartmentFactory;
use Dem\HelpDesk\Model\UserFactory;
use Magento\User\Model\UserFactory as AdminUserFactory;
use Dem\HelpDesk\Helper\Config;

/**
 * HelpDesk Setup - Initial Data Installer
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Dem\HelpDesk\Model\DepartmentFactory
     */
    protected $departmentFactory;

    /**
     * @var \Dem\HelpDesk\Model\UserFactory
     */
    protected $userFactory;

    /**
     * @var \Magento\User\Model\UserFactory
     */
    protected $adminFactory;

    /**
     * @param \Dem\HelpDesk\Model\DepartmentFactory $departmentFactory
     * @param \Dem\HelpDesk\Model\UserFactory $userFactory
     * @param \Magento\User\Model\UserFactory $adminFactory
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __construct(
        DepartmentFactory $departmentFactory,
        UserFactory $userFactory,
        AdminUserFactory $adminFactory
    ) {
        $this->departmentFactory = $departmentFactory;
        $this->userFactory = $userFactory;
        $this->adminFactory = $adminFactory;
    }

    /**
     * @param \Dem\HelpDesk\Model\Department $departmentFactory
     * @since 1.0.0
     *
     * @todo  REQUIRES TESTING
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $adminUser = $this->adminFactory->create();
        $adminUser->load(1);

        // Add default department user
        $userData = [
            `user_id` => 1,
            `website_id` => Config::HELPDESK_WEBSITE_ID_ADMIN,
            `admin_id` => 1,
            `email` => $adminUser->getEmail(),
            `name` => $adminUser->getName()
        ];

        $helpdeskUser = $this->userFactory->create();
        $helpdeskUser->addData($userData)->save();

        // Add default department
        $departmentData = [
            `department_id` => Config::HELPDESK_DEPARTMENT_DEFAULT_ID,
            `website_id` => Config::HELPDESK_WEBSITE_ID_ADMIN,
            `label` => 'General Request',
            `description` => 'General default department',
            `case_manager_id` => 1,
            `is_internal` => 0,
            `is_active` => 1,
            `sort_order` => 9999
        ];

        $department = $this->departmentFactory->create();
        $department->addData($departmentData)->save();
    }
}
