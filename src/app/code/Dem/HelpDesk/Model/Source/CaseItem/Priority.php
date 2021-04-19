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
class Priority
{
    /**
     * Case priorities
     */
    const CASE_PRIORITY_NORMAL   = 0;
    const CASE_PRIORITY_URGENT   = 1;
    const CASE_PRIORITY_CRITICAL = 2;

    /**
     * Get available case priorities as \Magento\Framework\Data\Collection
     *
     * @return \Magento\Framework\Data\Collection
     */
    public static function getCasePriorityCollection()
    {
        $priorities = array(
            self::CASE_PRIORITY_NORMAL => new DataObject(array(
                'id' => self::CASE_PRIORITY_NORMAL,
                'label' => __('Normal'),
                'automatic' => 0
            )),
            self::CASE_PRIORITY_URGENT => new DataObject(array(
                'id' => self::CASE_PRIORITY_URGENT,
                'label' => __('Urgent'),
                'automatic' => 0
            )),
            self::CASE_PRIORITY_CRITICAL => new DataObject(array(
                'id' => self::CASE_PRIORITY_CRITICAL,
                'label' => __('Critical'),
                'automatic' => 0
            )),
        );

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collectionFactory = $objectManager->get('Magento\Framework\Data\CollectionFactory');
        $casePriorities = $collectionFactory->create();
        foreach ($priorities as $priority) {
            $casePriorities->addItem($priority);
        }

        return $casePriorities;
    }
}
