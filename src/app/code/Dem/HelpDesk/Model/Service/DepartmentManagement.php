<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\Department;


/**
 * HelpDesk Service Model - DepartmentUser Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class DepartmentManagement extends AbstractManagement
{
    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = 'department';

    /**
     * createDepartment.
     *
     * @author    Toby Crain
     * @since    1.0.0
     * @param    int    $test
     * @return    boolean
     */
    public function createDepartment(int $test)
    {
        return false;
    }

    /**
     * Unset submitted data that's not editable
     *
     * @param array &$data
     * @return $data
     * @since 1.0.0
     */
    public function filterEditableData(&$data)
    {
        $editableFields = $this->getEditableFields();
        foreach ($data as $field => $value) {
            if (!in_array($field, $editableFields)) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function validate(array $data)
    {
        $requiredFields = $this->getRequiredFields();

        if (!count($requiredFields)) {
            return;
        }

        // Required fields not submitted?
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $data)) {
                throw new HelpDeskException(__('The %1 `%2` cannot be empty', $this->objectName, $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The %1 `%2` cannot be empty', $this->objectName, $field));
            }
        }
    }

    /**
     * Get required fields array
     *
     * @return array
     * @since 1.0.0
     */
    public function getRequiredFields()
    {
        return [
            Department::WEBSITE_ID,
            Department::CASE_MANAGER_ID,
            Department::NAME
        ];
    }

    /**
     * Get editable fields array
     *
     * @return array
     * @since 1.0.0
     */
    public function getEditableFields()
    {
        return [
            Department::NAME,
            Department::DESCRIPTION,
            Department::IS_ACTIVE,
            Department::IS_INTERNAL,
            Department::SORT_ORDER,
            Department::CASE_MANAGER_ID
        ];
    }

}
