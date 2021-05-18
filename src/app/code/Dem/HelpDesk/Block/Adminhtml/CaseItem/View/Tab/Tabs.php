<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab;

use Magento\Framework\App\ObjectManager;
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
class Tabs extends \Magento\Backend\Block\Template implements TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Dem\HelpDesk\Model\ResourceModel\CaseItem
     */
    protected $caseResource;

    /**
     * @var \Dem\HelpDesk\Api\ReplyRepositoryInterface
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Api\Data\ReplyInterface []
     */
    protected $replies;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Dem\HelpDesk\Model\ResourceModel\CaseItem $caseResource
     * @param \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Dem\HelpDesk\Model\ResourceModel\CaseItem $caseResource,
        \Dem\HelpDesk\Api\ReplyRepositoryInterface $replyRepository,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->caseResource = $caseResource;
        $this->replyRepository = $replyRepository;
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
        return $this->_coreRegistry->registry(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY);
    }

    /**
     * Get visible case replies
     *
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface []
     * @since 1.0.0
     */
    public function getVisibleReplies($includeInitial = true, $includeSystem = true)
    {
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
        }
        return $visibleReplies;
    }
}
