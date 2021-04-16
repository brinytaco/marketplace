<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Helper;

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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Check if website is default
     *
     * @param \Magento\Store\Api\Data\WebsiteInterface|int $website
     * @return bool
     */
    public static function isDefaultWebsite($website)
    {
        if ($website instanceof \Magento\Store\Api\Data\WebsiteInterface) {
            $website = $website->getId();
        }
        return ((int)$website === self::HELPDESK_WEBSITE_ID_DEFAULT);
    }

    /**
     * Check if website is admin
     *
     * @param \Magento\Store\Api\Data\WebsiteInterface|int $website
     * @return boolean
     */
    public static function isAdminWebsite($website)
    {
        if ($website instanceof \Magento\Store\Api\Data\WebsiteInterface) {
            $website = $website->getId();
        }

        return ((int)$website === self::HELPDESK_WEBSITE_ID_ADMIN);
    }
}
