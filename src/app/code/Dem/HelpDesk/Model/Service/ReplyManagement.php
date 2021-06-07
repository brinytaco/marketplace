<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Api\ReplyManagementInterface;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Magento\Framework\Registry;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;


/**
 * HelpDesk Model - Reply Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class ReplyManagement implements ReplyManagementInterface
{

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Data constructor.
     *
     * @param Registry $coreRegistry
     * @param ManagerInterface $eventManager
     * @param LoggerInterface $logger
     * @param Helper $helper
     */
    public function __construct(
        Registry $coreRegistry,
        ManagerInterface $eventManager,
        LoggerInterface $logger,
        Helper $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->helper = $helper;
    }


    /**
     * Create standard reply
     *
     * @param Reply $reply
     * @param CaseItemInterface $case
     * @param int $authorId
     * @param string $authorType
     * @param string $replyText
     * @param int|null $statusId
     * @param bool|null $isInitial
     * @return ReplyInterface
     */
    public function createReply(
        ReplyInterface $reply,
        CaseItemInterface $case,
        $authorId,
        $authorType,
        $replyText,
        $isInitial = false
    ) {
        $data = [
            'case_id'     => $case->getId(),
            'author_id'   => $authorId,
            'author_type' => $authorType,
            'reply_text'  => $replyText,
            'remote_ip'   => ($authorType == ReplyInterface::AUTHOR_TYPE_SYSTEM)
                ? null : $this->helper::getServerRemoteIp(),
            'status_id'   => $case->getStatusId(),
            'is_initial'  => (int) $isInitial
        ];
        $this->validate($data);

        return $reply->addData($data);
    }

    /**
     * Create initial reply
     *
     * @param ReplyInterface $reply
     * @param CaseItemInterface $case
     * @param int $authorId
     * @param string $replyText
     * @return ReplyInterface
     */
    public function createInitialReply(
        ReplyInterface $reply,
        CaseItemInterface $case,
        $authorId,
        $replyText
    ) {
        return $this->createReply(
            $reply,
            $case,
            $authorId,
            $authorType = ReplyInterface::AUTHOR_TYPE_CREATOR,
            $replyText,
            $isInitial = true
        );
    }

    /**
     * Create system reply
     *
     * @param Reply $reply
     * @param CaseItem $case
     * @param string $replyText
     * @return ReplyInterface
     */
    public function createSystemReply(
        ReplyInterface $reply,
        CaseItemInterface $case,
        $replyText
    ) {
        return $this->createReply(
            $reply,
            $case,
            $authorId = null,
            $authorType = ReplyInterface::AUTHOR_TYPE_SYSTEM,
            $replyText
        );
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
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
                throw new HelpDeskException(__('The reply `%1` cannot be empty', $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The reply `%1` cannot be empty', $field));
            }
        }

        /** @todo Check for 10 word minimum in message body */
    }

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return [
            'reply_text',
            'author_type'
        ];
    }

}
