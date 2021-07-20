<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;
use Dem\HelpDesk\Model\Source\SourceOptions;

/**
 * HelpDesk Source Model - CaseItem Priority
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Priority extends SourceOptions
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
     * @since 1.0.0
     */
    public function toOptionArray()
    {
        parent::toOptionArray();

        $priorityCollection = $this->getOptions();

        foreach ($priorityCollection as $priority) {
            $this->optionArray[] = ['label' => $priority->getLabel(), 'value' => $priority->getId()];
        }
        return $this->optionArray;
    }

    /**
     * Get available case priority options
     *
     * @return array
     * @since 1.0.0
     */
    public function getOptions()
    {
        $priorities = [
            self::CASE_PRIORITY_NORMAL => new DataObject([
                'id' => self::CASE_PRIORITY_NORMAL,
                'label' => __('Normal'),
                'css_class' => 'normal',
                'automatic' => 0
            ]),
            self::CASE_PRIORITY_URGENT => new DataObject([
                'id' => self::CASE_PRIORITY_URGENT,
                'label' => __('Urgent'),
                'css_class' => 'urgent',
                'automatic' => 0
            ]),
            self::CASE_PRIORITY_CRITICAL => new DataObject([
                'id' => self::CASE_PRIORITY_CRITICAL,
                'label' => __('Critical'),
                'css_class' => 'critical',
                'automatic' => 0
            ]),
        ];

        $casePriorities = $this->collectionFactory->create();
        foreach ($priorities as $priority) {
            $casePriorities->addItem($priority);
        }

        return $casePriorities;
    }
}
