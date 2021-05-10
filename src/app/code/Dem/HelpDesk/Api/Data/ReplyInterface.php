<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface ReplyInterface
{
    const REPLY_ID              = 'reply_id';
    const CASE_ID               = 'case_id';
    const AUTHOR_ID             = 'author_id';
    const AUTHOR_TYPE           = 'author_type';
    const REPLY_TEXT            = 'reply_text';
    const REMOTE_IP             = 'remote_ip';
    const STATUS_ID             = 'status_id';
    const IS_INITIAL            = 'is_initial';
    const CREATED_AT            = 'created_at';

    const AUTHOR_TYPE_SYSTEM        = 'SYSTEM';
    const AUTHOR_TYPE_HELPDESK_USER = 'HELPDESK_USER';
    const AUTHOR_TYPE_CREATOR       = 'CREATOR';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get author id
     *
     * @return int|null
     */
    public function getAuthorId();

    /**
     * Set author id
     *
     * @param int $authorId
     * @return ReplyInterface
     */
    public function setAuthorId($authorId);

    /**
     * Get author type
     *
     * @return string
     */
    public function getAuthorType();

    /**
     * Set author type
     *
     * @param string $authorType
     * @return ReplyInterface
     */
    public function setAuthorType($authorType);

    /**
     * Get reply text
     *
     * @return string
     */
    public function getReplyText();

    /**
     * Set reply text
     *
     * @param string $replyText
     * @return ReplyInterface
     */
    public function setReplyText($replyText);

    /**
     * Get remote ip address
     *
     * @return string
     */
    public function getRemoteIp();

    /**
     * Set remote ip address
     *
     * @param string $remoteIp
     * @return ReplyInterface
     */
    public function setRemoteIp($remoteIp);

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId();

    /**
     * Set status id
     *
     * @param int $statusId
     * @return ReplyInterface
     */
    public function setStatusId($statusId);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return ReplyInterface
     */
    public function setCreatedAt($createdAt);
}
