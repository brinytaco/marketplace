<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\DataProvider;

use Dem\HelpDesk\Model\Department as DeptModel;
use Magento\Framework\App\ObjectManager;

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
class Department extends \Dem\Base\Model\DataProvider\AbstractProvider
{

    /**
     * Additional constructor
     *
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->collection = ObjectManager::getInstance()->get(\Dem\HelpDesk\Model\ResourceModel\Department\Collection::class);
        return parent::_construct();
    }

    /**
     * Populate form data by fieldset
     *
     * @return array
     * @since 1.0.0
     */
    public function getData()
    {
        parent::getData();

        // Set is_default_department flag to allow for form manipulation
        foreach ($this->loadedData as $itemId => $item) {
            $isDefaultDepartment = \Dem\HelpDesk\Helper\Config::isDefaultDepartment($itemId);
            $this->loadedData[$itemId]['general']['is_default_department'] = (int)$isDefaultDepartment;
        }
        return $this->loadedData;
    }

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
        $itemData[DeptModel::CREATED_AT] = $this->getBaseHelper()->formatDate($itemData[DeptModel::CREATED_AT], \IntlDateFormatter::MEDIUM, true);
        $itemData[DeptModel::UPDATED_AT] = $this->getBaseHelper()->formatDate($itemData[DeptModel::UPDATED_AT], \IntlDateFormatter::MEDIUM, true);
        return $this;
    }
}
