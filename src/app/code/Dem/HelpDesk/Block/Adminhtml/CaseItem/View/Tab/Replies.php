<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab;

/**
 * HelpDesk Block - Adminhtml CaseItem View Tab Replies
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Replies extends Tabs
{
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('All Replies');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('All Replies');
    }

    /**
     * Get lowercase author_type for use as class name
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return string
     * @since 1.0.0
     */
    public function getReplyClass($reply)
    {
        return strtolower($reply->getAuthorType());
    }

    /**
     * Get created at as formatted string
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return string
     * @since 1.0.0
     */
    public function getCreatedDate($reply)
    {
        return $this->formatDate(
            $reply->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    /**
     * Get case status as object
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return DataObject
     */
    public function getStatusItem($reply)
    {
        $objectManager = ObjectManager::getInstance();
        $source = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Status');

        /* @var $statusOptions \Magento\Framework\Data\Collection */
        $statusOptions = $source->getOptions();

        return $statusOptions
            ->getItemByColumnValue('id', $reply->getStatusId());
    }

}
