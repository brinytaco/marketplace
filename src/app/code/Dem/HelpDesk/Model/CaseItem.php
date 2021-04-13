<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\App\ObjectManager;
use Dem\HelpDesk\Model\Department;

/**
 * HelpDesk Model - Case
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 * @method getId()
 * @method getProtectCode()
 * @method getWebsiteId()
 * @method getDepartmentId()
 * @method getCreatorCustomerId()
 * @method getCreatorAdminId()
 * @method getCreatorName()
 * @method getCreatorEmail()
 * @method getCreatorLastRead()
 * @method getSubject()
 * @method getPriority()
 * @method getStatusId()
 * @method getRemoteIp()
 * @method getHttpUserAgent()
 * @method getCreatedAt()
 * @method getUpdatedAt()
 * @method getUpdatedBy()
 *
 */
class Caseitem extends \Magento\Framework\Model\AbstractModel implements
    \Magento\Framework\DataObject\IdentityInterface
{
    const CURRENT_KEY = 'current_case';

    const CACHE_TAG = 'helpdesk_case';
    /**
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_case';

    /**
     * @var \Magento\Store\Model\Website
     */
    protected $website;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Caseitem::class);
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function getWebsite()
    {
        if (!isset($this->website)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->website = $objectManager->create('\Magento\Store\Model\Website')->load($this->getWebsiteId());
        }
        return $this->website;
    }

    /**
     * Load case by protect code (frontend)
     *
     * @param string $protectCode
     * @return \Dem\HelpDesk\Model\Caseitem
     */
    public function loadByCode(string $protectCode)
    {
        return $this->load($protectCode, 'protect_code');
    }

    /**
     * After load
     *
     * @return \Dem\HelpDesk\Model\Caseitem
     */
    public function afterLoad()
    {
        $this->setCaseNumber();
        $this->setDepartmentName();

        return $this;
    }

    /**
     * Set case number dynamically
     *
     * @return \Dem\HelpDesk\Model\Caseitem
     */
    protected function setCaseNumber()
    {
        $websiteId = str_pad($this->getWebsiteId(), 3, '0', STR_PAD_LEFT);
        $caseId = str_pad($this->getCaseId(), 6, '0', STR_PAD_LEFT);
        $caseNumber = $websiteId . '-' . $caseId;
        $this->setData('case_number', $caseNumber);
    }

    /**
     * Set case number dynamically
     *
     * @return \Dem\HelpDesk\Model\Caseitem
     */
    protected function setDepartmentName()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /* @var $department \Dem\HelpDesk\Model\Department */
        $department = $objectManager->create('\Dem\HelpDesk\Model\Department')->load($this->getDepartmentId());
        $this->setData('department_name', $department->getName());
        return $this;
    }

    public function getUserSuffix($userId = null)
    {

    }
}
