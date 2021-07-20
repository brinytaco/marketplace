<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\DataProvider;

use Dem\HelpDesk\Model\Department as DeptModel;

/**
 * HelpDesk DataProvider - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Department extends AbstractProvider
{
    /**
     * Format date field values
     *
     * @param array $departmentData
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function formatDateValues(&$itemData)
    {
        // we're getting the raw string instead of the already calculated value from the model
        $itemData[DeptModel::CREATED_AT] = $this->baseHelper->formatDate($itemData[DeptModel::CREATED_AT], \IntlDateFormatter::MEDIUM, true);
        $itemData[DeptModel::UPDATED_AT] = $this->baseHelper->formatDate($itemData[DeptModel::UPDATED_AT], \IntlDateFormatter::MEDIUM, true);
        return $this;
    }
}
