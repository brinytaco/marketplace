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
     *
     * @var \Dem\HelpDesk\Api\Data\CaseInterface
     */
    protected $caseItem;

    /**
     *
     * @var \Dem\HelpDesk\Api\Data\DepartmentInterface
     */
    protected $department;

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
        $this->loadDepartmentById($departmentId);

        // Default department selection is always valid
        if (! \Dem\HelpDesk\Helper\Config::isDefaultDepartment($departmentId)) {
            if ($this->department->getWebsiteId() != $websiteId) {
                throw new HelpDeskException(__('Invalid department selected'));
            }
        }
    }

    /**
     * Get loaded department model
     *
     * @return \Dem\HelpDesk\Api\Data\DepartmentInterface
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
     */
    protected function loadDepartmentById($departmentId)
    {
        /* @var $department \Dem\HelpDesk\Api\Data\DepartmentInterface */
        $this->department = $this->departmentRepository->getById($departmentId);
        if (!$this->department) {
            throw new HelpDeskException(__('Invalid department selected'));
        }
    }




    /**
     * Add default department followers to new/transfer case follower collection
     * and prepare for saving (_afterSave)
     *
     * @param Dem_HelpDesk_Model_Case $case
     * @param boolean $clear
     * @return Dem_HelpDesk_Model_Resource_Follower_Collection
     */
//    public function addDefaultFollowers($case, $clear = false)
//    {
//        /* @var $dept Dem_HelpDesk_Model_Department */
//        $dept = $case->getDepartment();
//
//        $isDefaultDept = (boolean) ((int)$dept->getId() === Dem_HelpDesk_Model_Case::DEPARTMENT_DEFAULT_ID);
//
//        /* @var $deptFollowers Varien_Data_Collection */
//        $deptFollowers = $dept->getDefaultFollowers();
//
//        // Set user type for follower entries
//        $userType = ($isDefaultDept || !(int)$case->getWebsiteId()) ? Dem_HelpDesk_Model_Case::USER_TYPE_ADMIN : Dem_HelpDesk_Model_Case::USER_TYPE_CUSTOMER;
//
//        // When clear is set to true, this is a dept transfer/escalation.
//        // Retain existing followers that ARE users of the new department, or
//        // when the new department is the default (anyone can follow)
//        if ($clear && !$isDefaultDept) {
//            $this->_removeCaseFollowers($case, $dept);
//        }
//
//        /* @var $followersCollection Dem_HelpDesk_Model_Resource_Follower_Collection */
//        $followersCollection = $case->getFollowersCollection();
//
//        if ($deptFollowers->getSize()) {
//
//            // $deptFollowers are qualified admin/customer entities
//            foreach ($deptFollowers as $follower) {
//
//                // Creator and case manager can't be added as followers, since they are always included in notifications
//                if ($follower->getUserId() === $case->getCreatorId() || $follower->getUserId() === $case->getCaseManager()->getId()) {
//                    self::log(sprintf('Unable to add user #%s as a follower. User is creator or case manager', $follower->getUserId()));
//                    continue;
//                }
//
//                // Unique users only
//                elseif (!in_array($follower->getUserId(), $followersCollection->getColumnValues('user_id'))) {
//
//                    /* @var $item Dem_HelpDesk_Model_Follower */
//                    $item = Mage::getModel('helpdesk/follower')->setData(array(
//                        'case_id' => $case->getId(),
//                        'user_id' => $follower->getUserId(),
//                        'user_type' => $userType,
//                        'name' => $follower->getName(),
//                        'email' => $follower->getEmail()
//                    ));
//
//                    $followersCollection->addItem($item);
//                }
//            }
//        }
//
//        return $followersCollection;
//    }

}
