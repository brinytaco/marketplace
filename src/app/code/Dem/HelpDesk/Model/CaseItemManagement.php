<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Exception as HelpDeskException;


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
class CaseItemManagement implements \Dem\HelpDesk\Api\CaseItemManagementInterface
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Dem\HelpDesk\Model\DepartmentRepository $departmentRepository
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger,
        \Dem\HelpDesk\Helper\Data $helper,
        \Dem\HelpDesk\Model\DepartmentRepository $departmentRepository
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
     * @param \Dem\HelpDesk\Api\CaseItemInterface $case
     * @param array $data
     * @return \Dem\HelpDesk\Api\CaseItemInterface
     * @throws \Dem\HelpDesk\Exception
     */
    public function createCase(CaseItemInterface $case, array $data)
    {
        $this->validate($data);
        $this->validateHelpDeskWebsiteById($data['website_id']);
        $this->validateDepartmentByWebsiteId($data['website_id'], $data['department_id']);

        /* @var $creator \Magento\User\Model\User */
        $creator = $this->helper->getBackendSession()->getUser();

        $case->addData($data);
        $case->addData(array(
            'creator_customer_id' => null,
            'creator_admin_id' => $creator->getId(),
            'creator_name' => sprintf('%s %s', $creator->getFirstname(), $creator->getLastname()),
            'creator_email' => $creator->getEmail(),
            'status_id' => 0,
            'remote_ip' => $_SERVER['REMOTE_ADDR'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT']
        ));

        $case->setId(null);
        return $case;
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    public function validate($data)
    {
        $requiredFields = $this->getRequiredFields();

        if (!count($requiredFields)) {
            return;
        }

        // Required fields not submitted?
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $data)) {
                throw new HelpDeskException(__('The case `%1` cannot be empty.', $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The case `%1` cannot be empty.', $field));
            }
        }
    }

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return array(
            'website_id',
            'department_id',
            'subject',
            'message',
            'priority'
        );
    }

    /**
     * Validate selected website and is helpdesk enabled
     *
     * @param int $websiteId
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    protected function validateHelpDeskWebsiteById($websiteId)
    {
        if (
            (int) $websiteId === \Dem\HelpDesk\Helper\Config::HELPDESK_WEBSITE_ID_DEFAULT
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
     * @throws \Dem\HelpDesk\Exception
     */
    protected function validateDepartmentByWebsiteId($websiteId, $departmentId)
    {
        // Default department selection is always valid
        if (! \Dem\HelpDesk\Helper\Config::isDefaultDepartment($departmentId)) {

            /* @var $department \Dem\HelpDesk\Model\Department */
            $department = $this->departmentRepository->getById($departmentId);
            if (!$department || $department->getWebsiteId() != $websiteId) {
                throw new HelpDeskException(__('Invalid department selected'));
            }
        }
    }

}
