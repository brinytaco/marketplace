<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Api\CaseItemManagementInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Api\Data\CaseInterface;
use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Dem\HelpDesk\Helper\Config;
use Magento\Framework\Registry;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\User\Model\User;

/**
 * HelpDesk Model - CaseItem Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class CaseItemManagement implements CaseItemManagementInterface
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     *
     * @var CaseInterface
     */
    protected $caseItem;

    /**
     *
     * @var DepartmentInterface
     */
    protected $department;

    /**
     * Data constructor.
     *
     * @param Registry $coreRegistry
     * @param ManagerInterface $eventManager
     * @param LoggerInterface $logger
     * @param Helper $helper
     * @param DepartmentRepository $departmentRepository
     */
    public function __construct(
        Registry $coreRegistry,
        ManagerInterface $eventManager,
        LoggerInterface $logger,
        Helper $helper,
        DepartmentRepository $departmentRepository
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Create a new case
     *
     * @param CaseItem $case
     * @param array $data
     * @return CaseItemInterface
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function createCase(CaseItemInterface $case, array $data)
    {
        $this->validate($data);
        $this->validateHelpDeskWebsiteById($data['website_id']);
        $this->validateDepartmentByWebsiteId($data['website_id'], $data['department_id']);

        /** @var User $creator */
        $creator = $this->helper->getBackendSession()->getUser();

        $case->addData($data);
        $case->addData([
            'creator_customer_id' => null,
            'creator_admin_id' => $creator->getId(),
            'creator_name' => sprintf('%s %s', $creator->getFirstname(), $creator->getLastname()),
            'creator_email' => $creator->getEmail(),
            'status_id' => 0,
            'remote_ip' => $_SERVER['REMOTE_ADDR'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);

        $case->setId(null);
        return $case;
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
                throw new HelpDeskException(__('The case `%1` cannot be empty', $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The case `%1` cannot be empty', $field));
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
            'website_id',
            'department_id',
            'subject',
            'message',
            'priority'
        ];
    }

    /**
     * Validate selected website and is helpdesk enabled
     *
     * @param int $websiteId
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    protected function validateHelpDeskWebsiteById($websiteId)
    {
        if (
            (int) $websiteId === Config::HELPDESK_WEBSITE_ID_DEFAULT
            || !$this->helper->isEnabled($websiteId)
        ) {
            throw new HelpDeskException(__('Invalid website selected'));
        }
    }

    /**
     * Validate selected department is allowed for website
     *
     * @param int $websiteId
     * @param int $departmentId
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    protected function validateDepartmentByWebsiteId($websiteId, $departmentId)
    {
        $this->loadDepartmentById($departmentId);

        // Default department selection is always valid
        if (!Config::isDefaultDepartment($departmentId)) {
            if ($this->department->getWebsiteId() != $websiteId) {
                throw new HelpDeskException(__('Invalid department selected'));
            }
        }
    }

    /**
     * Get loaded department model
     *
     * @return DepartmentInterface
     * @since 1.0.0
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Load department model by id
     *
     * @param type $departmentId
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    protected function loadDepartmentById($departmentId)
    {
        /** @var DepartmentInterface $department */
        $this->department = $this->departmentRepository->getById($departmentId);
        if (!$this->department) {
            throw new HelpDeskException(__('Invalid department selected'));
        }
    }
}
