<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;

/**
 * HelpDesk Api Interface - Reply Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface ReplyManagementInterface
{
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
    );

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
    );

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
    );

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    public function validate(array $data);

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields();

}
