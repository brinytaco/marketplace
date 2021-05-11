<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Exception as HelpDeskException;


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
class ReplyManagement implements \Dem\HelpDesk\Api\ReplyManagementInterface
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
     * Data constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->helper = $helper;
    }


    /**
     * Create standard reply
     *
     * @param ReplyInterface $reply
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
        $data = array(
            'case_id'     => $case->getId(),
            'author_id'   => $authorId,
            'author_type' => $authorType,
            'reply_text'  => $replyText,
            'remote_ip'   => ($authorType == ReplyInterface::AUTHOR_TYPE_SYSTEM)
                ? null : $this->helper::getServerRemoteIp(),
            'status_id'   => $case->getStatusId(),
            'is_initial'  => (int) $isInitial
        );
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
     * @param ReplyInterface $reply
     * @param CaseItemInterface $case
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
    public function validate($data)
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
        return array(
            'reply_text',
            'author_type'
        );
    }

}
