<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Helper;

use \Dem\HelpDesk\Helper\Config as Config;

/**
 * HelpDesk Helper - Data
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
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
     * Get helpdesk enabled for given website.
     *
     * @param \Magento\Store\Api\Data\WebsiteInterface|int $websiteId
     * @return bool
     */
    public function isEnabled($websiteId)
    {
        if ($websiteId instanceof \Magento\Store\Api\Data\WebsiteInterface) {
            $websiteId = $websiteId->getId();
        }

        // Admin website always enabled in admin area
        if (Config::isAdminWebsite($websiteId)) {
            return $this->getIsAdminArea();
        }

        // Default website is never available
        if (Config::isDefaultWebsite($websiteId)) {
            return false;
        }

        return $this->getConfiguredEnabledFlag($websiteId);
    }

    /**
     * Get selected or current website by id
     *
     * @param int $websiteId
     * @return \Magento\Store\Api\Data\WebsiteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWebsite($websiteId = null)
    {
        if ($websiteId) {
            return $this->storeManager->getWebsite($websiteId);
        }

        return $this->storeManager->getWebsite();
    }

    /**
     * Get list of websites (excluding default)
     *
     * @return \Magento\Store\Api\Data\WebsiteInterface[]
     */
    public function getWebsites()
    {
        return $this->storeManager->getWebsites();
    }

    /**
     * Get current area (admin|frontend)
     *
     * @return string
     */
    public function getCurrentArea()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $area = $objectManager->get('Magento\Framework\App\State');
        return $area->getAreaCode();
    }

    /**
     * Check if current area is admin
     *
     * @return bool
     */
    public function getIsAdminArea()
    {
        return ($this->getCurrentArea() == \Magento\Framework\App\Area::AREA_ADMINHTML);
    }

    /**
     * Check if current area is frontend
     *
     * @return bool
     */
    public function getIsFrontendArea()
    {
        return ($this->getCurrentArea() == \Magento\Framework\App\Area::AREA_FRONTEND);
    }

    /* ====================================================================== */
    /* Configuration values
    /* ====================================================================== */

    /**
     * Get helpdesk enabled flag by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredEnabledFlag($websiteId = null)
    {
        return $this->scopeConfig->isSetFlag(
            Config::XML_PATH_HELPDESK_STATUS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk frontend label by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredFrontendLabel($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_FRONTEND_LABEL,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk sender email by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredSenderEmail($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_SENDER_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk default department label by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredDepartmentLabel($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_DEFAULT_DEPT_LABEL,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk notify_active flag by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredNotifyActiveFlag($websiteId = null)
    {
        return $this->scopeConfig->isSetFlag(
            Config::XML_PATH_HELPDESK_NOTIFY_ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk session timeout interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredSessionTimeoutMinutes($websiteId = null)
    {
        return (int) $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_SESSION_TIMEOUT,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk auto inactive user interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredInactiveIntervalDays($websiteId = null)
    {
        return (int) $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_INACTIVE_INTERVAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk auto archive interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return bool
     */
    public function getConfiguredArchiveIntervalDays($websiteId = null)
    {
        return (int) $this->scopeConfig->getValue(
            Config::XML_PATH_HELPDESK_ARCHIVE_INTERVAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
