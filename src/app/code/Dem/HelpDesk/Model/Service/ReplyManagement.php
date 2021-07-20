<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\CaseItem;


/**
 * HelpDesk Service Model - Reply Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class ReplyManagement extends AbstractManagement
{
    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = 'reply';

    /**
     * Create standard reply
     *
     * @param Reply $reply
     * @param CaseItem $case
     * @param int $authorId
     * @param string $authorType
     * @param string $replyText
     * @param int|null $statusId
     * @param bool|null $isInitial
     * @return Reply
     * @since 1.0.0
     */
    public function createReply(
        Reply $reply,
        CaseItem $case,
        $authorId,
        $authorType,
        $replyText,
        $isInitial = false
    ) {
        $data = [
            Reply::CASE_ID     => $case->getId(),
            Reply::AUTHOR_ID   => $authorId,
            Reply::AUTHOR_TYPE => $authorType,
            Reply::REPLY_TEXT  => $replyText,
            Reply::REMOTE_IP   => ($authorType == Reply::AUTHOR_TYPE_SYSTEM)
                ? null : \Dem\HelpDesk\Helper\Data::getServerRemoteIp(),
            Reply::STATUS_ID   => $case->getStatusId(),
            Reply::IS_INITIAL  => (int) $isInitial
        ];
        $this->validate($data);

        return $reply->addData($data);
    }

    /**
     * Create initial reply
     *
     * @param Reply $reply
     * @param CaseItem $case
     * @param int $authorId
     * @param string $replyText
     * @return Reply
     * @since 1.0.0
     */
    public function createInitialReply(
        Reply $reply,
        CaseItem $case,
        $authorId,
        $replyText
    ) {
        return $this->createReply(
            $reply,
            $case,
            $authorId,
            $authorType = Reply::AUTHOR_TYPE_CREATOR,
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
     * @return Reply
     * @since 1.0.0
     */
    public function createSystemReply(
        Reply $reply,
        CaseItem $case,
        $replyText
    ) {
        return $this->createReply(
            $reply,
            $case,
            $authorId = null,
            $authorType = Reply::AUTHOR_TYPE_SYSTEM,
            $replyText
        );
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
            Reply::REPLY_TEXT,
            REPLY::AUTHOR_TYPE
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

}
