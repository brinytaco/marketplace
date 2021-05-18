<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab;

use Magento\Framework\App\ObjectManager;

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
class Info extends Tabs
{
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Case Information');
    }

    /**
     * Get case number
     *
     * @return string
     * @since 1.0.0
     */
    public function getCaseNumber()
    {
        return $this->getCase()->getCaseNumber();
    }

    /**
     * Get case website name
     *
     * @return string
     * @since 1.0.0
     */
    public function getWebsiteName()
    {
        return $this->getCase()->getWebsiteName();
    }

    /**
     * Get case department name
     *
     * @return string
     * @since 1.0.0
     */
    public function getDepartmentName()
    {
        return $this->getCase()->getDepartmentName();
    }

    /**
     * Get case subject
     *
     * @return string
     * @since 1.0.0
     */
    public function getSubject()
    {
        return $this->getCase()->getSubject();
    }

    /**
     * Get created at as formatted string
     *
     * @return string
     * @since 1.0.0
     */
    public function getCreatedDate()
    {
        return $this->formatDate(
            $this->getCase()->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    /**
     * Get if creator is customer entity
     *
     * @return bool
     * @since 1.0.0
     */
    public function isCustomerCreator()
    {
        return (!empty($this->getCase()->getCreatorCustomerId()));
    }

    /**
     * Get case creator name
     *
     * @return string
     * @since 1.0.0
     */
    public function getCreatorName()
    {
        return $this->getCase()->getCreatorName();
    }

    /**
     * Get case creator customer edit url
     *
     * @return string
     * @since 1.0.0
     */
    public function getCustomerCreatorUrl()
    {
        return $this->getUrl('customer/index/edit', ['id' => $this->getCase()->getCreatorCustomerId()]);
    }

    /**
     * Get updated by name
     *
     * @return string
     * @since 1.0.0
     */
    public function getUpdaterName()
    {
        return $this->getCase()->getUpdaterName();
    }

    /**
     * Get case updated_at as formatted string
     *
     * @return string
     * @since 1.0.0
     */
    public function getUpdatedDate()
    {
        if ($this->getCase()->getUpdatedAt()) {
            return $this->formatDate(
                $this->getCase()->getUpdatedAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }
        return null;
    }

    /**
     * Get case manager name
     *
     * @return string
     * @since 1.0.0
     */
    public function getCaseManagerName()
    {
        return $this->getCase()->getCaseManagerName();
    }

    /**
     * Get case status as object
     *
     * @return DataObject
     */
    public function getStatusItem()
    {
        $objectManager = ObjectManager::getInstance();
        $source = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Status');

        /* @var $statusOptions \Magento\Framework\Data\Collection */
        $statusOptions = $source->getOptions();

        return $statusOptions
            ->getItemByColumnValue('id', $this->getCase()->getStatusId());
    }

    /**
     * Get case priority option label
     *
     * @return string
     * @since 1.0.0
     */
    public function getPriorityItem()
    {
        $objectManager = ObjectManager::getInstance();
        $source = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Priority');

        /* @var $statusOptions \Magento\Framework\Data\Collection */
        $statusOptions = $source->getOptions();

        return $statusOptions
            ->getItemByColumnValue('id', $this->getCase()->getPriority());
    }

    /**
     * Get remote ip of creator
     *
     * @return string
     * @since 1.0.0
     */
    public function getRemoteIp()
    {
        return $this->getCase()->getRemoteIp();
    }

    /**
     * Get http user agent of creator
     *
     * @return string
     * @since 1.0.0
     */
    public function getHttpUserAgent()
    {
        return $this->getCase()->getHttpUserAgent();
    }

    /**
     * Get user replies count (excluding initial and system)
     *
     * @return int
     * @since 1.0.0
     */
    public function getOtherUserReplies()
    {
        return count($this->getVisibleReplies(false, false));
    }

    /**
     * Get initial reply message
     *
     * @return string
     * @since 1.0.0
     */
    public function getInitialReplyMessage()
    {
        $initialReply = $this->getCase()->getInitialReply();
        if ($initialReply) {
            return $initialReply->getReplyText();
        }
        return '';
    }

}
