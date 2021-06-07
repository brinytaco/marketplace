<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Api\Data\WebsiteInterface;
use Dem\HelpDesk\Api\Data\DepartmentInterface;

/**
 * HelpDesk Helper - Config
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Configuration paths
     */
    const XML_PATH_HELPDESK_STATUS_ENABLED      = 'dem_helpdesk/general/enabled';
    const XML_PATH_HELPDESK_FRONTEND_LABEL      = 'dem_helpdesk/general/frontend_label';
    const XML_PATH_HELPDESK_SENDER_EMAIL        = 'dem_helpdesk/general/sender_email';
    const XML_PATH_HELPDESK_DEFAULT_DEPT_LABEL  = 'dem_helpdesk/general/department_label';
    const XML_PATH_HELPDESK_NOTIFY_ACTIVE       = 'dem_helpdesk/cron/notify_active';
    const XML_PATH_HELPDESK_SESSION_TIMEOUT     = 'dem_helpdesk/cron/session_timeout';
    const XML_PATH_HELPDESK_INACTIVE_INTERVAL   = 'dem_helpdesk/cron/auto_inactive_interval';
    const XML_PATH_HELPDESK_ARCHIVE_INTERVAL    = 'dem_helpdesk/cron/auto_archive_interval';

    /**
     * Default references
     */
    const HELPDESK_WEBSITE_ID_DEFAULT           = 1;
    const HELPDESK_WEBSITE_ID_ADMIN             = 0;
    const HELPDESK_DEPARTMENT_DEFAULT_ID        = 1;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Config constructor
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Check if website is default
     *
     * @param WebsiteInterface|int $website
     * @return bool
     * @since 1.0.0
     */
    public static function isDefaultWebsite($website)
    {
        if ($website instanceof WebsiteInterface) {
            $website = $website->getId();
        }
        return ((int)$website === self::HELPDESK_WEBSITE_ID_DEFAULT);
    }

    /**
     * Check if website is admin
     *
     * @param WebsiteInterface|int $website
     * @return boolean
     * @since 1.0.0
     */
    public static function isAdminWebsite($website)
    {
        if ($website instanceof WebsiteInterface) {
            $website = $website->getId();
        }
        return ((int)$website === self::HELPDESK_WEBSITE_ID_ADMIN);
    }

    /**
     * Check if website is admin
     *
     * @param DepartmentInterface|int $department
     * @return boolean
     * @since 1.0.0
     */
    public static function isDefaultDepartment($department)
    {
        if ($department instanceof DepartmentInterface) {
            $department = $department->getId();
        }
        return ((int)$department === self::HELPDESK_DEPARTMENT_DEFAULT_ID);
    }
}
