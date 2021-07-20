<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;
use Dem\HelpDesk\Model\Source\SourceOptions;

/**
 * HelpDesk Source Model - CaseItem Status
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Status extends SourceOptions
{
    /**
     * Case status
     */
    const CASE_STATUS_NEW = 0;
    const CASE_STATUS_ACTIVE = 10;
    const CASE_STATUS_ACTIVE_PENDING = 15;
    const CASE_STATUS_INACTIVE = 40;
    const CASE_STATUS_INACTIVE_PENDING = 45;
    const CASE_STATUS_RESOLVED = 70;
    const CASE_STATUS_ARCHIVED = 90;

    /**
     * Return array of case status options
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @return array
     * @since 1.0.0
     */
    public function toOptionArray()
    {
        parent::toOptionArray();

        $statusCollection = $this->getOptions(true);

        foreach ($statusCollection as $status) {
            $this->optionArray[] = ['label' => $status->getLabel(), 'value' => $status->getId()];
        }
        return $this->optionArray;
    }

    /**
     * Get available case statuses as \Magento\Framework\Data\Collection
     *
     * @param boolean $auto | Include automatic status types
     * @return \Magento\Framework\Data\Collection
     * @since 1.0.0
     */
    public function getOptions($auto = true)
    {
        $statuses = [
            self::CASE_STATUS_NEW => new DataObject([
                'id' => self::CASE_STATUS_NEW,
                'label' => __('New'),
                'css_class' => 'new',
                'automatic' => 0
            ]),
            self::CASE_STATUS_ACTIVE => new DataObject([
                'id' => self::CASE_STATUS_ACTIVE,
                'label' => __('Active'),
                'css_class' => 'active',
                'automatic' => 0
            ]),
            self::CASE_STATUS_ACTIVE_PENDING => new DataObject([
                'id' => self::CASE_STATUS_ACTIVE_PENDING,
                'label' => __('Active - Pending Request'),
                'css_class' => 'active-pending',
                'automatic' => 0
            ]),
            self::CASE_STATUS_INACTIVE => new DataObject([
                'id' => self::CASE_STATUS_INACTIVE,
                'label' => __('Awaiting CM Response'),
                'css_class' => 'awaiting-cm',
                'automatic' => 1
            ]),
            self::CASE_STATUS_INACTIVE_PENDING => new DataObject([
                'id' => self::CASE_STATUS_INACTIVE_PENDING,
                'label' => __('Awaiting Client Response'),
                'css_class' => 'awaiting-client',
                'automatic' => 1
            ]),
            self::CASE_STATUS_RESOLVED => new DataObject([
                'id' => self::CASE_STATUS_RESOLVED,
                'label' => __('Resolved'),
                'css_class' => 'resolved',
                'automatic' => 0
            ]),
            self::CASE_STATUS_ARCHIVED => new DataObject([
                'id' => self::CASE_STATUS_ARCHIVED,
                'label' => __('Archived'),
                'css_class' => 'archived',
                'automatic' => 1
            ]),
        ];

        $caseStatuses = $this->collectionFactory->create();
        foreach ($statuses as $status) {
            if (!$auto && (int)$status->getAutomatic()) {
                continue;
            }
            $caseStatuses->addItem($status);
        }

        return $caseStatuses;
    }
}
