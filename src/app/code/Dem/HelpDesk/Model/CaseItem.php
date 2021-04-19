<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

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
 * @todo: Do I need to prevent "setter" methods?
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
class CaseItem extends \Magento\Framework\Model\AbstractModel implements
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
     * @var \Dem\HelpDesk\Model\Department
     */
    protected $department;

    /**
     * @var Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Dem\HelpDesk\Model\Department $department
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Dem\HelpDesk\Model\Department $department,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->department = $department;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\CaseItem::class);
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
     * @return \Dem\HelpDesk\Model\CaseItem
     */
    public function loadByCode(string $protectCode)
    {
        return $this->load($protectCode, 'protect_code');
    }

    /**
     * After load
     *
     * @return \Dem\HelpDesk\Model\CaseItem
     */
    public function afterLoad()
    {
        return parent::afterLoad();
    }

    /**
     * Set case number dynamically
     *
     * @return \Dem\HelpDesk\Model\CaseItem
     */
//    public function getCaseNumber()
//    {
//        if (!$this->hasData('case_number')) {
//            $websiteId = str_pad($this->getWebsiteId(), 3, '0', STR_PAD_LEFT);
//            $caseId = str_pad($this->getCaseId(), 6, '0', STR_PAD_LEFT);
//            $caseNumber = $websiteId . '-' . $caseId;
//            $this->setData('case_number', $caseNumber);
//        }
//        return $this->getData('case_number');
//    }

    public function getDepartment()
    {
        if (!$this->department->getId()) {
            $this->department->load($this->getDepartmentId());
        }
        return $this->department;
    }

}
