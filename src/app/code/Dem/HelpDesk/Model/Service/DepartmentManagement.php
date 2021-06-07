<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Api\DepartmentManagementInterface;
use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Magento\Framework\Registry;
use Magento\Framework\Event\ManagerInterface;


/**
 * HelpDesk Model - DepartmentUser Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class DepartmentManagement implements DepartmentManagementInterface
{

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Data constructor.
     *
     * @param Registry $coreRegistry
     * @param ManagerInterface $eventManager
     * @param Helper $helper
     */
    public function __construct(
        Registry $coreRegistry,
        ManagerInterface $eventManager,
        Helper $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->eventManager = $eventManager;
        $this->helper = $helper;
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws HelpDeskException
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
                throw new HelpDeskException(__('The department `%1` cannot be empty', $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The department `%1` cannot be empty', $field));
            }
        }

        /** @todo Check for 10 word minimum in message body */
    }

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return [
            'website_id',
            'case_manager_id',
            'name'
        ];
    }

}
