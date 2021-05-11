<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;
use Dem\HelpDesk\Exception as HelpDeskException;

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
     * @return \Magento\Backend\Model\View\Result\Redirect
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

                /* @var $caseItemManager \Dem\HelpDesk\Model\CaseItemManagement */
                /* @var $case \Dem\HelpDesk\Model\CaseItem */
                $case = $this->caseItemManager->createCase(
                    $this->caseItemFactory->create(),
                    $data
                );

                /* @var $creator \Magento\User\Model\User */
                $creator = $this->helper->getBackendSession()->getUser();

                /* @var $replyManager \Dem\HelpDesk\Model\ReplyManagement */
                /* @var $initialReply \Dem\HelpDesk\Model\Reply */
                $initialReply = $this->replyManager->createInitialReply(
                    $this->replyFactory->create(),
                    $case,
                    $creator->getId(),
                    $data['message']
                );

                $case->addReplyToSave($initialReply);

                /* @var $department \Dem\HelpDesk\Model\Department */
                $department = $this->caseItemManager->getDepartment();

                /* @var $caseManagerName string */
                $caseManagerName = $department->getCaseManagerName();

                // Translate immediately for saving
                $systemMessage = __('New case created and assigned to `%1`', $caseManagerName)->render();

                /* @var $systemReply \Dem\HelpDesk\Model\Reply */
                $systemReply = $this->replyManager->createSystemReply(
                    $this->replyFactory->create(),
                    $case,
                    $systemMessage
                );

                $case->addReplyToSave($systemReply);

                $this->prepareDefaultFollowers($case, $department);

                /* @var $caseItemRepository \Dem\HelpDesk\Model\CaseItemRepository */
                $this->caseItemRepository->save($case);

                // Send emails
//                $this->caseItemManager->sendReplyMessages();



                // Done Saving customer, finish save action
                $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY, $case);
                $this->messageManager->addSuccessMessage($systemMessage);

                $resultRedirect->setPath('*/*/');
//                $resultRedirect->setPath('*/*/view', ['case_id' => $case->getId()]);

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
     * @param \Dem\HelpDesk\Model\CaseItem $case
     * @param \Dem\HelpDesk\Model\Department $department
     * @return void
     */
    protected function prepareDefaultFollowers($case, $department)
    {
        $defaultFollowers = $department->getDefaultFollowers();

        /* @var $followerManager \Dem\HelpDesk\Model\FollowerManagement */
        /* @var $follower \Dem\HelpDesk\Model\Follower */
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
