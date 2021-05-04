<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;
use Magento\Framework\Data\OptionSourceInterface;

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
class Status implements OptionSourceInterface
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
     * @var array
     */
    protected $optionArray = [];

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\Data\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Return array of case status options
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @return array
     */
    public function toOptionArray()
    {
        $statusCollection = $this->getOptions(true);

        $this->optionArray[] = ['label' => __('-- Please Select --'), 'value' => ''];

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
     */
    public function getOptions($auto = true)
    {
        $statuses = array(
            self::CASE_STATUS_NEW => new DataObject(array(
                'id' => self::CASE_STATUS_NEW,
                'label' => __('New'),
                'automatic' => 0
            )),
            self::CASE_STATUS_ACTIVE => new DataObject(array(
                'id' => self::CASE_STATUS_ACTIVE,
                'label' => __('Active'),
                'automatic' => 0
            )),
            self::CASE_STATUS_ACTIVE_PENDING => new DataObject(array(
                'id' => self::CASE_STATUS_ACTIVE_PENDING,
                'label' => __('Active - Pending Request'),
                'automatic' => 0
            )),
            self::CASE_STATUS_INACTIVE => new DataObject(array(
                'id' => self::CASE_STATUS_INACTIVE,
                'label' => __('Awaiting CM Response'),
                'automatic' => 1
            )),
            self::CASE_STATUS_INACTIVE_PENDING => new DataObject(array(
                'id' => self::CASE_STATUS_INACTIVE_PENDING,
                'label' => __('Awaiting Client Response'),
                'automatic' => 1
            )),
            self::CASE_STATUS_RESOLVED => new DataObject(array(
                'id' => self::CASE_STATUS_RESOLVED,
                'label' => __('Resolved'),
                'automatic' => 0
            )),
            self::CASE_STATUS_ARCHIVED => new DataObject(array(
                'id' => self::CASE_STATUS_ARCHIVED,
                'label' => __('Archived'),
                'automatic' => 1
            )),
        );

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
