<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View;

use Dem\Base\Data\SearchResultsProcessor;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ReplyRepository;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Dem\HelpDesk\Model\Source\CaseItem\Priority;
use Dem\HelpDesk\Model\Source\CaseItem\Status;
use Dem\HelpDesk\Model\User as HelpDeskUser;
use Dem\HelpDesk\Model\UserRepository;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

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
     * @var \Dem\HelpDesk\Model\ReplyRepository
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Model\UserRepository
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
     * @var \Dem\HelpDesk\Model\Source\CaseItem\Priority
     */
    protected $prioritySource;

    /**
     * @var \Magento\Framework\Data\Collection
     */
    protected $priorityOptions;

    /**
     * @var \Dem\HelpDesk\Model\Reply[]
     */
    protected $replies;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param Resource $caseResource
     * @param ReplyRepository $replyRepository
     * @param UserRepository $userRepository
     * @param Status $statusSource
     * @param Priority $prioritySource
     * @param Helper $helper
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Resource $caseResource,
        ReplyRepository $replyRepository,
        UserRepository $userRepository,
        Status $statusSource,
        Priority $prioritySource,
        Helper $helper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->caseResource = $caseResource;
        $this->replyRepository = $replyRepository;
        $this->userRepository = $userRepository;
        $this->statusSource = $statusSource;
        $this->prioritySource = $prioritySource;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    /**
     * @return \Magento\Framework\Phrase|string
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return '';
    }

    /**
     * @return \Magento\Framework\Phrase|string
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return '';
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Tab class getter
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded through Ajax call
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * Retrieve registered Case model
     *
     * @return \Dem\HelpDesk\Model\CaseItem
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getCase()
    {
        return $this->coreRegistry->registry(CaseItem::CURRENT_KEY);
    }

    /**
     * Get Status instance
     *
     * @return \Dem\HelpDesk\Model\Source\CaseItem\Status
     * @codeCoverageIgnore
     */
    public function getStatusSource()
    {
        return $this->statusSource;
    }

    /**
     * Get Priority instance
     *
     * @return \Dem\HelpDesk\Model\Source\CaseItem\Priority
     * @codeCoverageIgnore
     */
    public function getPrioritySource()
    {
        return $this->prioritySource;
    }

    /**
     * Get Admin User instance
     *
     * @return \Magento\User\Model\User
     * @codeCoverageIgnore
     */
    public function getAdminUser()
    {
        return $this->helper->getBackendSession()->getUser();
    }

    /**
     * Get initial reply message
     *
     * @return \Dem\HelpDesk\Model\Reply|bool
     * @since 1.0.0
     * @codeCoverageIgnore
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
     * @return \Dem\HelpDesk\Model\Reply[]
     * @since 1.0.0
     */
    public function getVisibleReplies($limit = 0, $includeInitial = true, $includeSystem = true)
    {
        $v = ($limit) ? $limit : false;
        $visibleReplies = [];
        if (!isset($this->replies)) {
            $this->replies = $this->getCase()->getReplies();
        }
        /** @var Reply $reply */
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
     * @param \Dem\HelpDesk\Model\CaseItem|\Dem\HelpDesk\Model\Reply $object
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
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
     * @param \Dem\HelpDesk\Model\CaseItem|\Dem\HelpDesk\Model\Reply $object
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
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
     * @param \Dem\HelpDesk\Model\CaseItem|\Dem\HelpDesk\Model\Reply $object
     * @return \Magento\Framework\DataObject
     * @since 1.0.0
     */
    public function getStatusItem(AbstractModel $object)
    {
        if (!isset($this->statusOptions)) {
            $this->statusOptions = $this->getStatusSource()->getOptions();
        }

        return $this->statusOptions
            ->getItemByColumnValue('id', $object->getStatusId());
    }

    /**
     * Get priority as object
     *
     * @param \Dem\HelpDesk\Model\CaseItem $object
     * @return \Magento\Framework\DataObject
     * @since 1.0.0
     */
    public function getPriorityItem()
    {
        if (!isset($this->priorityOptions)) {
            $this->priorityOptions = $this->getPrioritySource()->getOptions();
        }

        return $this->priorityOptions
            ->getItemByColumnValue('id', $this->getCase()->getPriority());
    }

    /**
     * Get lowercase author_type for use as class name
     *
     * @param \Dem\HelpDesk\Model\Reply $reply
     * @return string
     * @since 1.0.0
     */
    public function getReplyClass(Reply $reply)
    {
        return strtolower(str_replace('_', '-', $reply->getAuthorType()));
    }

    /**
     * Get reply author name if set.
     *
     * If author is creator, get case creator_name
     *
     * @param \Dem\HelpDesk\Model\Reply $reply
     * @return string
     * @since 1.0.0
     */
    public function getAuthorName(Reply $reply)
    {
        if ($reply->getIsAuthorTypeCreator()) {
            return $this->getCase()->getCreatorName();
        }

        if ($reply->getAuthorId()) {
            return $reply->getAuthorName();
        }

        return '';
    }

    /**
     * Render reply from specified template
     *
     * @param \Dem\HelpDesk\Model\Reply $reply
     * @return string
     */
    public function renderReplyBlock(Reply $reply)
    {
        $templatePath = $reply->getIsAuthorTypeSystem()
                ? self::ADMIN_REPLY_TEMPLATE_PATH_SYSTEM
                : self::ADMIN_REPLY_TEMPLATE_PATH_USER;

        $templateFile = $this->getTemplateFile($templatePath);

        // Fetch content and perform string replacements
        $content = $this->fetchView($templateFile);
        $content = str_ireplace(
            '{{reply-class}}',
            $this->getReplyClass($reply),
            $content
        );
        $content = str_ireplace(
            '{{reply-date}}',
            $this->getCreatedDate($reply),
            $content
        );
        $content = str_ireplace(
            '{{reply-text}}',
            nl2br($reply->getReplyText()),
            $content
        );
        $content = str_ireplace(
            '{{reply-author}}',
            $this->getAuthorName($reply),
            $content
        );
        $content = str_ireplace(
            '{{reply-status}}',
            $this->getStatusItem($reply)->getLabel(),
            $content
        );
        $content = str_ireplace(
            '{{status-label}}',
            __('Current Status')->render(),
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
        $user = $this->getAdminUser();

        // User is the case creator
        if ($user->getId() == $this->getCase()->getCreatorAdminId()) {
            return false;
        }
        // User is the department case manager
        if ($user->getId() == $this->getCaseManagerId()) {
            return false;
        }

        /** @var HelpDeskUser $helpDeskUser */
        $helpDeskUser = $this->getHelpDeskUserById($user->getId());

        // User must be a helpdesk user
        if (!$helpDeskUser->getId()) {
            return false;
        }
        return true;
    }

    /**
     * Get Case manager id
     *
     * @return int
     * @codeCoverageIgnore
     */
    protected function getCaseManagerId()
    {
        return $this->getCase()->getCaseManager()->getId();
    }

    /**
     * Get HelpDesk User by id
     *
     * @param int $userId
     * @return \Dem\HelpDesk\Model\User
     * @codeCoverageIgnore
     */
    protected function getHelpDeskUserById($userId)
    {
        return $this->userRepository->getByField($userId, HelpDeskUser::ADMIN_ID);
    }

    /**
     * Check if current admin user is a case follower
     *
     * @return bool
     */
    public function getIsUserFollower()
    {
        $user = $this->getAdminUser();

        $followers = $this->getCase()->getFollowers();

        // Allows manipulation of items similar to \Magento\Framework\Data\Collection
        $followerCollection = new SearchResultsProcessor($followers);

        // null value returned if not matched
        /** @var Follower|null $follower */
        $follower = $followerCollection->getItemByColumnValue('user_id', $user->getId());

        return (bool) $follower;
    }
}
