<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View;

use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * HelpDesk Block - Adminhtml CaseItem View Tab Info
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Tabs extends \Magento\Backend\Block\Template implements TabInterface
{
    /**
     * Reply template constants
     */
    const ADMIN_REPLY_TEMPLATE_PATH_SYSTEM = 'case/view/tab/replies/system.phtml';
    const ADMIN_REPLY_TEMPLATE_PATH_USER   = 'case/view/tab/replies/user.phtml';

    /**
     * \Magento\Framework\Module\Dir
     */
    protected $moduleDir;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Dem\HelpDesk\Model\ResourceModel\CaseItem
     */
    protected $caseResource;

    /**
     * @var \Dem\HelpDesk\Api\ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Api\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var \Dem\HelpDesk\Model\Source\CaseItem\Status
     */
    protected $statusSource;

    /**
     * @var \Magento\Framework\Data\Collection
     */
    protected $statusOptions;

    /**
     * @var \Dem\HelpDesk\Api\Data\ReplyInterface []
     */
    protected $replies;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Module\Dir $moduleDir
     * @param \Magento\Framework\Registry $registry
     * @param \Dem\HelpDesk\Model\ResourceModel\CaseItem $caseResource
     * @param \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
     * @param \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository
     * @param \Dem\HelpDesk\Model\Source\CaseItem\Status $statusSource
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Module\Dir $moduleDir,
        \Magento\Framework\Registry $registry,
        \Dem\HelpDesk\Model\ResourceModel\CaseItem $caseResource,
        \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository,
        \Dem\HelpDesk\Api\UserRepositoryInterface $userRepository,
        \Dem\HelpDesk\Model\Source\CaseItem\Status $statusSource,
        \Dem\HelpDesk\Helper\Data $helper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->moduleDir = $moduleDir;
        $this->caseResource = $caseResource;
        $this->replyRepository = $replyRepository;
        $this->userRepository = $userRepository;
        $this->statusSource = $statusSource;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return '';
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * Retrieve registered Case model
     *
     * @return \Dem\HelpDesk\Api\Data\CaseItemInterface
     * @since 1.0.0
     */
    public function getCase()
    {
        return $this->coreRegistry->registry(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY);
    }

    /**
     * Get initial reply message
     *
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface|bool
     * @since 1.0.0
     */
    public function getInitialReply()
    {
        return $this->coreRegistry->registry(\Dem\HelpDesk\Model\CaseItem::INITIAL_REPLY_KEY);
    }

    /**
     * Get visible case replies
     *
     * @param int $limit Limit results value (0 = no limit)
     * @param bool $includeInitial
     * @param bool $includeSystem
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface []
     * @since 1.0.0
     */
    public function getVisibleReplies($limit = 0, $includeInitial = true, $includeSystem = true)
    {
        $v = ($limit) ? $limit : false;
        $visibleReplies = [];
        if (!isset($this->replies)) {
            $this->replies = $this->getCase()->getReplies();
        }
        foreach ($this->replies->getItems() as $reply) {
            if (!$includeInitial && $reply->getIsInitial()) {
                continue;
            }
            if (!$includeSystem && $reply->getIsAuthorTypeSystem()) {
                continue;
            }
            $visibleReplies[] = $reply;
            if ($v !== false && --$v === 0) {
                break;
            }
        }
        return $visibleReplies;
    }

    /**
     * Get created at as formatted string
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return string
     * @since 1.0.0
     */
    public function getCreatedDate(\Magento\Framework\Model\AbstractModel $object)
    {
        return $this->formatDate(
            $object->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    /**
     * Get updated_at as formatted string
     *
     * If null, do not return current date
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return string
     * @since 1.0.0
     */
    public function getUpdatedDate(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getUpdatedAt()) {
            return $this->formatDate(
                $object->getUpdatedAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }
        return null;
    }

    /**
     * Get status as object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return DataObject
     * @since 1.0.0
     */
    public function getStatusItem(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!isset($this->statusOptions)) {
            $this->statusOptions = $this->statusSource->getOptions();
        }

        return $this->statusOptions
            ->getItemByColumnValue('id', $object->getStatusId());
    }

    /**
     * Get lowercase author_type for use as class name
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return string
     * @since 1.0.0
     */
    public function getReplyClass(\Dem\HelpDesk\Api\Data\ReplyInterface $reply)
    {
        return strtolower(str_replace('_', '-', $reply->getAuthorType()));
    }

    /**
     * Get reply author name if set.
     *
     * If author is creator, get case creator_name
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return string
     * @since 1.0.0
     */
    public function getAuthorName(\Dem\HelpDesk\Api\Data\ReplyInterface $reply)
    {
        if ($reply->getAuthorName()) {
            return $reply->getAuthorName();
        }

        if ($reply->getIsAuthorTypeCreator()) {
            return $this->getCase()->getCreatorName();
        }

        return '';
    }

    /**
     * Render reply from specified template
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return string
     */
    public function renderReplyBlock(\Dem\HelpDesk\Api\Data\ReplyInterface $reply)
    {
        $templatePath = $reply->getIsAuthorTypeSystem()
                ? self::ADMIN_REPLY_TEMPLATE_PATH_SYSTEM
                : self::ADMIN_REPLY_TEMPLATE_PATH_USER;

        $templateFile = $this->getTemplateFile($templatePath);

        // Fetch content and perform string replacements
        $content = $this->fetchView($templateFile);
        $content = str_ireplace(
            '{{reply-class}}',
            $this->escapeHtml($this->getReplyClass($reply)),
            $content
        );
        $content = str_ireplace(
            '{{reply-date}}',
            $this->escapeHtml($this->getCreatedDate($reply)),
            $content
        );
        $content = str_ireplace(
            '{{reply-text}}',
            nl2br($this->escapeHtml($reply->getReplyText())),
            $content
        );
        $content = str_ireplace(
            '{{reply-author}}',
            $this->escapeHtml($this->getAuthorName($reply)),
            $content
        );
        $content = str_ireplace(
            '{{reply-status}}',
            $this->escapeHtml($this->getStatusItem($reply)->getLabel()),
            $content
        );
        $content = str_ireplace(
            '{{status-label}}',
            $this->escapeHtml(__('Current Status')),
            $content
        );

        return $content;
    }

    /**
     * Check if should render follower toggle.
     *
     * Current user cannot be the creator or case manager
     * and must be a helpdesk-user.
     *
     * @return bool
     */
    public function getCanRenderFollowerBlock()
    {
        /* @var $user \Magento\User\Model\User */
        $user = $this->helper->getBackendSession()->getUser();

        // User is the case creator
        if ($user->getId() == $this->getCase()->getCreatorAdminId()) {
            return false;
        }
        // User is the department case manager
        if ($user->getId() == $this->getCase()->getCaseManager()->getId()) {
            return false;
        }
        // User must be a helpdesk user
        if (!$this->userRepository->getById($user->getId())) {
            return false;
        }
        return true;
    }

    /**
     * Check if current admin user is a case follower
     *
     * @return bool
     */
    public function getIsUserFollower()
    {
        /* @var $user \Magento\User\Model\User */
        $user = $this->helper->getBackendSession()->getUser();

        /** @var $followers \Dem\HelpDesk\Model\FollowerInterface [] */
        $followers = $this->getCase()->getFollowers();

        $followerCollection = new \Dem\HelpDesk\Data\SearchResultsProcessor($followers);

        // null value returned if not matched
        $isFollower = $followerCollection->getItemByColumnValue('user_id', $user->getId());

        return ($isFollower);
    }
}
