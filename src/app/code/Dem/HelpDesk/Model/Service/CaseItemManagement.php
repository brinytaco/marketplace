<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Helper\Config;
use Magento\User\Model\User;
use Magento\Customer\Model\Customer;

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
class CaseItemManagement extends AbstractManagement
{
    /**
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     *
     * @var \Dem\HelpDesk\Model\CaseItem
     */
    protected $caseItem;

    /**
     *
     * @var \Dem\HelpDesk\Model\Department
     */
    protected $department;

    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = 'case';

    /**
     * Get DepartmentRepository instance
     * @return \Dem\HelpDesk\Model\DepartmentRepository
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getDepartmentRepository()
    {
        return $this->departmentRepository;
    }

    /**
     * Create a new case
     *
     * @param CaseItem $case
     * @param User|Customer $creator
     * @param array $data
     * @return \Dem\HelpDesk\Model\CaseItem
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function createCase(CaseItem $case, $creator, array $data)
    {
        $this->validate($data);
        $case->addData($data);

        $this->validateHelpDeskWebsiteById($case);
        $this->validateDepartmentByWebsiteId($case);

        $case->addData([
            CaseItem::CASE_ID               => null,
            CaseItem::CREATOR_CUSTOMER_ID   => $this->getIsCreatorTypeAdmin($creator) ? null : $creator->getId(),
            CaseItem::CREATOR_ADMIN_ID      => $this->getIsCreatorTypeAdmin($creator) ? $creator->getId() : null,
            CaseItem::CREATOR_NAME          => sprintf('%s %s', $creator->getFirstname(), $creator->getLastname()),
            CaseItem::CREATOR_EMAIL         => $creator->getEmail(),
            CaseItem::STATUS_ID             => 0,
            CaseItem::REMOTE_IP             => @$_SERVER['REMOTE_ADDR'],
            CaseItem::HTTP_USER_AGENT       => @$_SERVER['HTTP_USER_AGENT']
        ]);

        return $case;
    }

    /**
     * Test if creator object is of type User (Backend)
     *
     * @param User|Customer $creator
     * @return bool
     * @since 1.0.0
     */
    public function getIsCreatorTypeAdmin($creator)
    {
        return ($creator instanceof User);
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
            CaseItem::WEBSITE_ID,
            CaseItem::DEPARTMENT_ID,
            CaseItem::SUBJECT,
            CaseItem::PRIORITY,
            Reply::REPLY_TEXT
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
        return [];
    }

    /**
     * Load department model by id
     *
     * @param int|string $departmentId
     * @return Department
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function getDepartmentById($departmentId)
    {
        if (!isset($this->department)) {
            $this->department = $this->getDepartmentRepository()->getById($departmentId);
            if (!$this->department->getId()) {
                throw new HelpDeskException(__('Invalid department selected'));
            }
        }
        return $this->department;
    }

    /**
     * Validate selected website and is helpdesk enabled
     *
     * @param \Dem\HelpDesk\Model\CaseItem
     * @return $this
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function validateHelpDeskWebsiteById(CaseItem $case)
    {
        $websiteId = $case->getWebsiteId();

        if (
            Config::isDefaultWebsite($websiteId)
            || !$this->getHelper()->isEnabled($websiteId)
        ) {
            throw new HelpDeskException(__('Invalid website selected: %1', $websiteId));
        }
        return $this;
    }

    /**
     * Validate selected department is allowed for website
     *
     * @param \Dem\HelpDesk\Model\CaseItem
     * @return $this
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function validateDepartmentByWebsiteId(CaseItem $case)
    {
        $this->department = $this->getDepartmentById($case->getDepartmentId());

        if (!$this->department->getId()) {
            throw new HelpDeskException(__('Invalid department selected'));
        }
        // Default department selection is always valid
        if (!Config::isDefaultDepartment($this->department->getId())) {
            if ($this->department->getWebsiteId() != $case->getWebsiteId()) {
                throw new HelpDeskException(__('Invalid department selected'));
            }
        }
        return $this;
    }
}
