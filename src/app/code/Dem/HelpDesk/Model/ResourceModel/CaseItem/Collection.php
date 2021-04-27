<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\CaseItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

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
class Collection extends AbstractCollection
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
    protected $_eventPrefix = 'helpdesk_case_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_case_collection';

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
