<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Department;

/**
 * HelpDesk Resource Model - Case Collection
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'department_id';

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_department_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'department_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\Department::class,
            \Dem\HelpDesk\Model\ResourceModel\Department::class
        );
    }

    /**
     * Before collection load
     *
     * @return $this
     */
    protected function _beforeLoad()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_load_before', [$this->_eventObject => $this]);
        return parent::_beforeLoad();
    }

    /**
     * After collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_load_after', [$this->_eventObject => $this]);

        return parent::_afterLoad();
    }

    /**
     * Get department collection by website
     *
     * @param integer $websiteId
     * @return Dem_HelpDesk_Model_Resource_Department_Collection
     */
    public function getWebsiteDepartments($websiteId = null)
    {
        if (is_null($websiteId)) {
            $websiteId = Mage::app()->getWebsite()->getId();
        }

        // There can be multiple admin-level departments (website 0),
        // but the #1 General is available to all websites.
        if (!isset($this->_websiteDepartments)) {
            $this->_websiteDepartments = $this->getCollection()
                ->addFieldToFilter('website_id', array('in' => array($websiteId, self::WEBSITE_ID_DEFAULT)))
                ->setOrder('sort_order', 'asc');
        }

        return $this->_websiteDepartments;
    }
}
