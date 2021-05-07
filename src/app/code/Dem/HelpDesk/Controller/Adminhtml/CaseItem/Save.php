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

                /* @var $case \Dem\HelpDesk\Model\CaseItem */
                $case = $this->caseItemManager->createCase(
                    $this->caseItemFactory->create(),
                    $data
                );



//                $replyUserType = \Dem\HelpDesk\Model\Reply::AUTHOR_TYPE_CREATOR;
                // Add initial user message
//                $case->addReply(array(
//                    'author_id' => $creator->getId(),
//                    'author_type' => $userType,
//                    'reply_text' => $requestData['message'],
//                    'mark_as_read' => json_encode(array($userType => array($creator->getId()))),
//                    'remote_ip' => $_SERVER['REMOTE_ADDR'],
//                ));

                $caseManager = $case->getCaseManagerName();

                $statusChangeMessage = __('New case created and assigned to "%s"', $caseManager);
//                $case->addSystemMessage($statusChangeMessage);

                // Add followers to new case creation
//                $case->addDefaultFollowers();

                $this->caseItemRepository->save($case);

                // Saving status or case manager change
//                $session->addSuccess($statusChangeMessage);

/************************************/

                // Done Saving customer, finish save action
                $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY, $case);
                $this->messageManager->addSuccessMessage($statusChangeMessage);

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
                    __('Something went wrong while saving the case.')
                );
                $resultRedirect->setPath('*/*/');
            }
        }

        return $resultRedirect;
    }
}
