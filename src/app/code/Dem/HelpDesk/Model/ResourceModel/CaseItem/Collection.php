<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\CaseItem;

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
    protected $_idFieldName = 'case_id';

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_case_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'case_collection';


    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param mixed $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\CaseItem::class,
            \Dem\HelpDesk\Model\ResourceModel\CaseItem::class
        );
        
        // Add department_name alias to grid filter
        $this->addFilterToMap('department_name', 'd.name');
        $this->addFilterToMap('case_number', 'case_id');
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
     * Load additional object data when loading collection
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_load_after', [$this->_eventObject => $this]);
        return parent::_afterLoad();
    }


    /**
     * Add extra fields as output columns
     * department_name
     * case_manager_name
     *
     */
    protected function _initSelect()
    {

        parent::_initSelect();

        // Add department name to select
        $this->getSelect()->join(
            ['d' => $this->getTable('dem_helpdesk_department')],
            'd.department_id = main_table.department_id',
            ['department_name' => 'name']
        );

        // Dynamically retrieve the "case number" string
        $this->addExpressionFieldToSelect(
            'case_number',
            $this->getCaseNumberExpressionSelect(),
            []
        );

        return $this;
    }

    public function getCaseNumberExpressionSelect()
    {
        return "CONCAT_WS('-', LPAD(main_table.website_id, 3, '0'), LPAD(main_table.case_id, 6, '0'))";
    }

}
