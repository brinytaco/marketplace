<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Magento\Framework\App\ObjectManager;
use Dem\HelpDesk\Controller\Adminhtml\CaseItem as Controller;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\CaseItem as CaseModel;
use Magento\User\Model\User;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * HelpDesk Controller - Adminhtml Case Create New (Save)
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
     * Save new case action
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

        $data = $this->getParam('case');

        try {

            $creator = $this->getAdminUser();

            $case = $this->buildCase($creator, $data);
            $caseManagerName = $case->getCaseManager()->getName();

            // Translate immediately for saving
            $systemMessage = __('New case created and assigned to `%1`', $caseManagerName)->render();
            $systemReply = $this->buildSystemReply($case, $systemMessage);
            $initialReply = $this->buildInitialReply($case, $creator, $data);
            $case->addReplyToSave($initialReply);
            $case->addReplyToSave($systemReply);

            /** @var Department $department */
            $department = $this->getCaseItemManager()->getDepartment();

            $this->prepareDefaultFollowers($case, $department);

            $this->getCaseItemRepository()->save($case);
            $this->getNotificationService()->sendNewCaseNotifications($case);

            $this->getCoreRegistry()->register(CaseModel::CURRENT_KEY, $case);
            $this->getMessageManager()->addSuccessMessage($systemMessage);

            $resultRedirect->setPath('*/*/view', ['case_id' => $case->getId()]);

        } catch (HelpDeskException $exception) {
            $this->getMessageManager()->addExceptionMessage(
                $exception,
                $exception->getMessage()
            );
            $resultRedirect->setPath('*/*/');
        } catch (\Exception $exception) {
            $this->getMessageManager()->addExceptionMessage(
                $exception,
                $exception->getMessage()
            );
            $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect;
    }

    /**
     * Get new case instance with provided data
     *
     * @param \Magento\User\Model\User $creator
     * @param array $data
     * @return \Dem\HelpDesk\Model\CaseItem
     */
    public function buildCase(User $creator, $data = [])
    {
        return $this->getCaseItemManager()->createCase(
            $this->getCaseItemFactory()->create(),
            $creator,
            $data
        );
    }

    /**
     * Get new reply instance with provided data
     *
     * @param \Dem\HelpDesk\Model\CaseItem $case
     * @param \Magento\User\Model\User $creator
     * @param array $data
     * @return \Dem\HelpDesk\Model\Reply
     */
    public function buildInitialReply(CaseModel $case, User $creator, $data = [])
    {
        return $this->getReplyManager()->createInitialReply(
            $this->getReplyFactory()->create(),
            $case,
            $creator->getId(),
            $data['reply_text']
        );
    }


    /**
     * Get new reply instance with provided data
     *
     * @param \Dem\HelpDesk\Model\CaseItem $case
     * @param string $message
     * @return \Dem\HelpDesk\Model\Reply
     */
    public function buildSystemReply(CaseModel $case, $message)
    {
        return $this->getReplyManager()->createSystemReply(
            $this->getReplyFactory()->create(),
            $case,
            $message
        );
    }

    /**
     * Prepare default followers (if any) for saving
     *
     * @param \Dem\HelpDesk\Model\CaseItem $case
     * @param \Dem\HelpDesk\Model\Department $department
     * @return \Dem\HelpDesk\Model\CaseItem
     */
    public function prepareDefaultFollowers(CaseModel $case, Department $department)
    {
        $defaultFollowers = $department->getDefaultFollowers();
        $followerFactory = $this->getFollowerFactory();
        $followerManager = $this->getFollowerManager();

        /** @var Follower $follower */
        foreach ($defaultFollowers as $userId) {
            $follower = $followerManager->createFollower(
                $followerFactory->create(),
                $case,
                $userId
            );
            $case->addFollowerToSave($follower);
        }

        return $case;
    }
}
