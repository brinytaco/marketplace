<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;

/**
 * HelpDesk Model - Source CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Status
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
     * Get available case statuses as \Magento\Framework\Data\Collection
     *
     * @param boolean $auto | Include automatic status types
     * @return \Magento\Framework\Data\Collection
     */
    public static function getCaseStatusCollection($auto = true)
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

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collectionFactory = $objectManager->get('Magento\Framework\Data\CollectionFactory');
        $caseStatuses = $collectionFactory->create();
        foreach ($statuses as $status) {
            if (!$auto && (int)$status->getAutomatic()) {
                continue;
            }
            $caseStatuses->addItem($status);
        }

        return $caseStatuses;
    }
}
