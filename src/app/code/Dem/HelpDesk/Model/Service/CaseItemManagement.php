<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Helper\Config;
use Magento\Framework\Registry;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\User\Model\User;

/**
 * HelpDesk Service Model - CaseItem Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class CaseItemManagement
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
     * @var Case
     */
    protected $caseItem;

    /**
     *
     * @var Department
     */
    protected $department;

    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = 'case';

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
     * @return CaseItem
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function createCase(CaseItem $case, array $data)
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
     * @param int|string $websiteId
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    protected function validateHelpDeskWebsiteById($websiteId)
    {
        if (
            Config::isDefaultWebsite($websiteId)
            || !$this->helper->isEnabled($websiteId)
        ) {
            throw new HelpDeskException(__('Invalid website selected'));
        }
    }

    /**
     * Validate selected department is allowed for website
     *
     * @param int|string $websiteId
     * @param int|string $departmentId
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
     * @return Department
     * @since 1.0.0
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Load department model by id
     *
     * @param int|string $departmentId
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    protected function loadDepartmentById($departmentId)
    {
        $this->department = $this->departmentRepository->getById($departmentId);
        if (!$this->department) {
            throw new HelpDeskException(__('Invalid department selected'));
        }
    }
}
