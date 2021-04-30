<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * HelpDesk Model - Source CaseItem Priority
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Priority implements OptionSourceInterface
{
    /**
     * Case priorities
     */
    const CASE_PRIORITY_NORMAL   = 0;
    const CASE_PRIORITY_URGENT   = 1;
    const CASE_PRIORITY_CRITICAL = 2;

    /**
     * Return array of case priority options
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @return array
     */
    public function toOptionArray()
    {
        $priorityCollection = \Dem\HelpDesk\Model\Source\CaseItem\Priority::getCasePriorityCollection();

        // Grid filter, always add empty option
        $options = [
            '' => ['label' => ' ', 'value' => '']
        ];

        foreach ($priorityCollection as $priority) {
            $options[] = ['label' => $priority->getLabel(), 'value' => $priority->getId()];
        }
        return $options;
    }

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
