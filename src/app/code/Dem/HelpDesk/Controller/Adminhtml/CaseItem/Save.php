<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;
use Dem\HelpDesk\Exception as HelpDeskException;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\RequestInterface;
use Dem\HelpDesk\Model\Service\CaseItemManagement;
use Dem\HelpDesk\Model\CaseItem as CaseObject;
use Magento\User\Model\User;
use Dem\HelpDesk\Model\Service\ReplyManagement;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\CaseItemRepository;
use Dem\HelpDesk\Model\Service\FollowerManagement;
use Dem\HelpDesk\Model\Follower;

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
class Save extends CaseItem
{
    /**
     * Save new case action
     *
     * @todo Refactor for fewer lines/more methods
     *       Should be using $this->caseItemManager
     *
     * @return Redirect
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->isValidPostRequest()) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        $data = $this->getRequest()->getPostValue('case');

        if ($data) {
            try {

                /** @var CaseItemManagement $caseItemManager */
                /** @var CaseObject $case */
                $case = $this->caseItemManager->createCase(
                    $this->caseItemFactory->create(),
                    $data
                );

                /** @var User $creator */
                $creator = $this->helper->getBackendSession()->getUser();

                /** @var ReplyManagement $replyManager */
                /** @var Reply $initialReply */
                $initialReply = $this->replyManager->createInitialReply(
                    $this->replyFactory->create(),
                    $case,
                    $creator->getId(),
                    $data['message']
                );

                $case->addReplyToSave($initialReply);

                /** @var Department $department */
                $department = $this->caseItemManager->getDepartment();

                /** @var string $caseManagerName */
                $caseManagerName = $department->getCaseManagerName();

                // Translate immediately for saving
                $systemMessage = __('New case created and assigned to `%1`', $caseManagerName)->render();

                /** @var Reply $systemReply */
                $systemReply = $this->replyManager->createSystemReply(
                    $this->replyFactory->create(),
                    $case,
                    $systemMessage
                );

                $case->addReplyToSave($systemReply);

                $this->prepareDefaultFollowers($case, $department);

                /** @var CaseItemRepository $caseItemRepository */
                $this->caseItemRepository->save($case);

                // Send notifications
                $this->notificationService->sendNewCaseNotifications($case);

                // Done Saving customer, finish save action
                $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY, $case);
                $this->messageManager->addSuccessMessage($systemMessage);

                $resultRedirect->setPath('*/*/view', ['case_id' => $case->getId()]);

            } catch (HelpDeskException $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    $exception->getMessage()
                );
                $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    $exception->getMessage()//__('Something went wrong while saving the case')
                );
                $resultRedirect->setPath('*/*/');
            }
        }

        return $resultRedirect;
    }

    /**
     * Prepare default followers (if any) for saving
     *
     * @param CaseObject $case
     * @param Department $department
     * @return void
     */
    protected function prepareDefaultFollowers(CaseObject $case, Department $department)
    {
        $defaultFollowers = $department->getDefaultFollowers();

        /** @var FollowerManagement $followerManager */
        /** @var Follower $follower */
        foreach ($defaultFollowers as $userId) {
            $follower = $this->followerManager->createFollower(
                $this->followerFactory->create(),
                $case,
                $userId
            );
            $case->addFollowerToSave($follower);
        }
    }
}
