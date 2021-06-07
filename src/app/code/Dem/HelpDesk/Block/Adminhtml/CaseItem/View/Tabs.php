<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\FollowerInterface;
use Dem\HelpDesk\Api\ReplyRepositoryInterface;
use Dem\HelpDesk\Api\UserRepositoryInterface;
use Dem\HelpDesk\Model\Source\CaseItem\Status;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\Base\Data\SearchResultsProcessor;

use Magento\Backend\Block\Template\Context;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Framework\Phrase;
use Magento\Backend\Block\Template;
use Magento\Framework\Module\Dir;
use Magento\Framework\Registry;
use Magento\Framework\Data\Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\User\Model\User;

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
class Tabs extends Template implements TabInterface
{
    /**
     * Reply template constants
     */
    const ADMIN_REPLY_TEMPLATE_PATH_SYSTEM = 'case/view/tab/replies/system.phtml';
    const ADMIN_REPLY_TEMPLATE_PATH_USER   = 'case/view/tab/replies/user.phtml';

    /**
     * @var Dir
     */
    protected $moduleDir;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var Resource
     */
    protected $caseResource;

    /**
     * @var ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var Status
     */
    protected $statusSource;

    /**
     * @var Collection
     */
    protected $statusOptions;

    /**
     * @var ReplyInterface []
     */
    protected $replies;

    /**
     * @var Helper
     */
    protected $helper;


    /**
     * @param Context $context
     * @param Dir $moduleDir
     * @param Registry $registry
     * @param CaseItem $caseResource
     * @param ReplyRepositoryInterface $replyRepository
     * @param UserRepositoryInterface $userRepository
     * @param Status $statusSource
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Dir $moduleDir,
        Registry $registry,
        CaseItem $caseResource,
        ReplyRepositoryInterface $replyRepository,
        UserRepositoryInterface $userRepository,
        Status $statusSource,
        Helper $helper,
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
     * @return Phrase
     */
    public function getTabLabel()
    {
        return '';
    }

    /**
     * @return Phrase
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
     * Tab should be loaded through Ajax call
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
     * @return CaseItemInterface
     * @since 1.0.0
     */
    public function getCase()
    {
        return $this->coreRegistry->registry(CaseItem::CURRENT_KEY);
    }

    /**
     * Get initial reply message
     *
     * @return ReplyInterface|bool
     * @since 1.0.0
     */
    public function getInitialReply()
    {
        return $this->coreRegistry->registry(CaseItem::INITIAL_REPLY_KEY);
    }

    /**
     * Get visible case replies
     *
     * @param int $limit Limit results value (0 = no limit)
     * @param bool $includeInitial
     * @param bool $includeSystem
     * @return ReplyInterface[]
     * @since 1.0.0
     */
    public function getVisibleReplies($limit = 0, $includeInitial = true, $includeSystem = true)
    {
        $v = ($limit) ? $limit : false;
        $visibleReplies = [];
        if (!isset($this->replies)) {
            $this->replies = $this->getCase()->getReplies();
        }
        /** @var ReplyInterface $reply */
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
         * @param CaseItem|Reply $object
         * @return string
         * @since 1.0.0
         */
    public function getCreatedDate(AbstractModel $object)
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
     * @param CaseItem|Reply $object
     * @return string
     * @since 1.0.0
     */
    public function getUpdatedDate(AbstractModel $object)
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
     * @param CaseItem|Reply $object
     * @return DataObject
     * @since 1.0.0
     */
    public function getStatusItem(AbstractModel $object)
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
     * @param Reply $reply
     * @return string
     * @since 1.0.0
     */
    public function getReplyClass(ReplyInterface $reply)
    {
        return strtolower(str_replace('_', '-', $reply->getAuthorType()));
    }

    /**
     * Get reply author name if set.
     *
     * If author is creator, get case creator_name
     *
     * @param Reply $reply
     * @return string
     * @since 1.0.0
     */
    public function getAuthorName(ReplyInterface $reply)
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
     * @param Reply $reply
     * @return string
     */
    public function renderReplyBlock(ReplyInterface $reply)
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
        /** @var User $user */
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
        /** @var User $user */
        $user = $this->helper->getBackendSession()->getUser();

        /** @var \Dem\HelpDesk\Api\Data\FollowerInterface[] $followers */
        $followers = $this->getCase()->getFollowers();

        // Allows manipulation of items similar to \Magento\Framework\Data\Collection
        $followerCollection = new SearchResultsProcessor($followers);

        // null value returned if not matched
        /** @var FollowerInterface|null $follower */
        $follower = $followerCollection->getItemByColumnValue('user_id', $user->getId());

        return ($follower);
    }
}
