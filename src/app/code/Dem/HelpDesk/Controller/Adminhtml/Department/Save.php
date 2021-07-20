<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Department;

use Magento\Framework\App\ObjectManager;
use Dem\HelpDesk\Controller\Adminhtml\Department as Controller;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * HelpDesk Controller - Adminhtml Department Create New (Save)
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Save extends Controller
{
    /**
     * Save new department action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Dem\HelpDesk\Exception
     */
    public function execute()
    {
        $resultRedirect = $this->getRedirect();
        $resultRedirect->setPath('*/*/');

        if (!$this->isValidPostRequest()) {
            return $resultRedirect;
        }

        $continueEdit = ($this->getParam('back') == 'edit');

        $data = $this->getParam('general');
        $isObjectNew = true;

        try {
            // Validate required fields new/existing
            $this->getDepartmentManager()->validate($data);

            $deptId = (isset($data['department_id'])) ? $data['department_id'] : null;

            /** @var Department $department */
            $department = $this->getDepartmentRepository()->getById($deptId);

            // If existing, unset non-editable fields
            if ($department->getId()) {
                $isObjectNew = false;
                $this->getDepartmentManager()->filterEditableData($data);
            }

            // Set changed data values
            $department->addData($data);

            // Set department users
            // If user data changed, flag data as changed

            // Set case_manager_id

            // Save department data

            // After save events save department users

            $this->getDepartmentRepository()->save($department);

            $typeId = __('Department');
            $actionMessage = ($isObjectNew) ? __('created successfully') : __('saved successfully');
            $this->getMessageManager()->addSuccessMessage(sprintf('%s %s', $typeId, $actionMessage));
            $this->getCoreRegistry()->register(Department::CURRENT_KEY, $department);

            if ($continueEdit) {
                $resultRedirect->setPath('*/*/edit', ['department_id' => $department->getId()]);
            }

        } catch (HelpDeskException $exception) {
            $this->getMessageManager()->addExceptionMessage(
                $exception,
                $exception->getMessage()
            );
        } catch (\Exception $exception) {
            $this->getMessageManager()->addExceptionMessage(
                $exception,
                $exception->getMessage()
            );
        }
        return $resultRedirect;
    }

    /**
     * Get new department instance with provided data
     *
     * @param array $data
     * @return \Dem\HelpDesk\Model\Department
     */
    public function buildDepartment($data = [])
    {
        return $this->getDepartmentManager()->createDepartment(
            $this->getDepartmentFactory()->create(),
            $data
        );
    }
}
