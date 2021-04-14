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
     * Configuration
     */
    const XML_PATH_HELPDESK_STATUS_ENABLED = 'helpdesk/status/enabled';

    const HELPDESK_WEBSITE_ID_DEFAULT = 1;
    const HELPDESK_WEBSITE_ID_ADMIN = 0;

    const HELPDESK_DEPARTMENT_DEFAULT_ID = 1;

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
