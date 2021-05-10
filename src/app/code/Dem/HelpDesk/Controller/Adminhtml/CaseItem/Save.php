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
        $returnToEdit = false;

        if (!$this->isValidPostRequest()) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        /*
         * 1) Validate post
         * 2) Validate department is valid for current website
         * 3) caseFactory->create()
         * 4) case->setData()
         * 5) add initial reply to case
         * 6) add system reply to case
         * 7) add default followers to case
         * 8) save case
         *      a) afterSave -> create replies
         * 9) redirect on success/error
         */

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

                /* @var $initialReply \Dem\HelpDesk\Model\Reply */
                $initialReply = $this->replyManager->createInitialReply(
                    $this->replyFactory->create(),
                    $case,
                    $creator->getId(),
                    $data['message']
                );

                $case->addReplyToSave($initialReply);

                $department = $this->caseItemManager->getDepartment();

                // Get Case Manager Name
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

                // Add followers to new case creation
//                $case->addDefaultFollowers();

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
                    __('Something went wrong while saving the case.')
                );
                $resultRedirect->setPath('*/*/');
            }
        }

        return $resultRedirect;
    }
}
