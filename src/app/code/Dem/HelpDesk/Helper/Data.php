<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Helper;

use Dem\HelpDesk\Helper\Config;
use Magento\Framework\App\ObjectManager;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;

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
     * Current app state
     *
     * @var \Magento\Framework\App\State
     */
    protected $currentState;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param Session $authSession
     * @param StoreManagerInterface $storeManager
     * @param State $currentState
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        Session $authSession,
        StoreManagerInterface $storeManager,
        State $currentState,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->authSession = $authSession;
        $this->storeManager = $storeManager;
        $this->currentState = $currentState;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Get helpdesk enabled for given website.
     *
     * @param WebsiteInterface|int $websiteId
     * @return bool
     * @since 1.0.0
     */
    public function isEnabled($websiteId)
    {
        if ($websiteId instanceof WebsiteInterface) {
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
     * Get remote ip address from SERVER ENV
     *
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function getServerRemoteIp()
    {
        return @$_SERVER['REMOTE_ADDR'];
    }

    /**
     * Fetch current backend session user
     *
     * @return \Magento\Backend\Model\Auth\Session
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getBackendSession()
    {
        return $this->authSession;
    }

    /**
     * Fetch instance of StoreManagerInterface
     *
     * @return \Magento\Store\Model\StoreManagerInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }
    /**
     * Get ScopeConfigInterface instance
     *
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * Get selected or current website by id
     *
     * @param int $websiteId
     * @return \Magento\Store\Api\Data\WebsiteInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getWebsite($websiteId = null)
    {
        return $this->getStoreManager()->getWebsite($websiteId);
    }

    /**
     * Get list of websites (excluding default)
     *
     * @return \Magento\Store\Api\Data\WebsiteInterface[]
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getWebsites()
    {
        return $this->getStoreManager()->getWebsites();
    }

    /**
     * Get current state
     *
     * @return \Magento\Framework\App\State
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * Get current area (admin|frontend)
     *
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getCurrentArea()
    {
        try {
            $areaCode = $this->getCurrentState()->getAreaCode();
        } catch (\Exception $e) {
            return false;
        }
        return $areaCode;
    }

    /**
     * Check if current area is admin
     *
     * @return bool
     * @since 1.0.0
     */
    public function getIsAdminArea()
    {
        return ($this->getCurrentArea() == Area::AREA_ADMINHTML);
    }

    /**
     * Check if current area is frontend
     *
     * @return bool
     * @since 1.0.0
     */
    public function getIsFrontendArea()
    {
        return ($this->getCurrentArea() == Area::AREA_FRONTEND);
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
     * @since 1.0.0
     */
    public function getConfiguredEnabledFlag($websiteId = null)
    {
        return $this->getScopeConfig()->isSetFlag(
            Config::XML_PATH_HELPDESK_STATUS_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk frontend label by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return string
     * @since 1.0.0
     */
    public function getConfiguredFrontendLabel($websiteId = null)
    {
        return $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_FRONTEND_LABEL,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk sender email by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return string
     * @since 1.0.0
     */
    public function getConfiguredSenderEmail($websiteId = null)
    {
        return $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_SENDER_EMAIL,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk default department label by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return string
     * @since 1.0.0
     */
    public function getConfiguredDepartmentLabel($websiteId = null)
    {
        return $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_DEFAULT_DEPT_LABEL,
            ScopeInterface::SCOPE_WEBSITE,
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
     * @since 1.0.0
     */
    public function getConfiguredNotifyActiveFlag($websiteId = null)
    {
        return $this->getScopeConfig()->isSetFlag(
            Config::XML_PATH_HELPDESK_NOTIFY_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk session timeout interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return int
     * @since 1.0.0
     */
    public function getConfiguredSessionTimeoutMinutes($websiteId = null)
    {
        return (int) $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_SESSION_TIMEOUT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk auto inactive user interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return int
     * @since 1.0.0
     */
    public function getConfiguredInactiveIntervalDays($websiteId = null)
    {
        return (int) $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_INACTIVE_INTERVAL,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get helpdesk auto archive interval by website.
     *
     * If websiteId is empty, will check current website
     *
     * @param null|int|string $websiteId
     * @return int
     * @since 1.0.0
     */
    public function getConfiguredArchiveIntervalDays($websiteId = null)
    {
        return (int) $this->getScopeConfig()->getValue(
            Config::XML_PATH_HELPDESK_ARCHIVE_INTERVAL,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

}
