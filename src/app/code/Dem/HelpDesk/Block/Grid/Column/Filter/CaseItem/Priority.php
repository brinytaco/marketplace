<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Grid\Column\Filter\CaseItem;

use Magento\Framework\Option\ArrayInterface;

/**
 * HelpDesk Block - Adminhtml Grid Column Filter CaseItem Status
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Priority implements ArrayInterface
{
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
}
