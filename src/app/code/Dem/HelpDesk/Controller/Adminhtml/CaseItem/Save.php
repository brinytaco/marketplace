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

        $data = $this->getRequest()->getPostValue('case');

        if ($data) {
            try {

                /* @var $caseItemManager \Dem\HelpDesk\Api\Data\CaseItemManagementInterface */
                /* @var $case \Dem\HelpDesk\Api\Data\CaseItemInterface */
                $case = $this->caseItemManager->createCase(
                    $this->caseItemFactory->create(),
                    $data
                );

                /* @var $creator \Magento\User\Model\User */
                $creator = $this->helper->getBackendSession()->getUser();

                /* @var $replyManager \Dem\HelpDesk\Api\Data\ReplyManagementInterface */
                /* @var $initialReply \Dem\HelpDesk\Api\Data\ReplyInterface */
                $initialReply = $this->replyManager->createInitialReply(
                    $this->replyFactory->create(),
                    $case,
                    $creator->getId(),
                    $data['message']
                );

                $case->addReplyToSave($initialReply);

                /* @var $department \Dem\HelpDesk\Api\Data\DepartmentInterface */
                $department = $this->caseItemManager->getDepartment();

                // Get Case Manager Name
                $caseManagerName = $department->getCaseManagerName();

                // Translate immediately for saving
                $systemMessage = __('New case created and assigned to `%1`', $caseManagerName)->render();


                /* @var $initialReply \Dem\HelpDesk\Api\Data\ReplyInterface */
                $systemReply = $this->replyManager->createSystemReply(
                    $this->replyFactory->create(),
                    $case,
                    $systemMessage
                );

                $case->addReplyToSave($systemReply);

                // Get department users who are followers
                $case->addDefaultFollowers();

                /* @var $caseItemRepository \Dem\HelpDesk\Api\Data\CaseItemRepositoryInterface */
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
                    __('Something went wrong while saving the case')
                );
                $resultRedirect->setPath('*/*/');
            }
        }

        return $resultRedirect;
    }
}
