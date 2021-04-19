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
class Status implements ArrayInterface
{
    /**
     * Return array of case status options
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @return array
     */
    public function toOptionArray($auto = true)
    {
        $statusCollection = \Dem\HelpDesk\Model\Source\CaseItem\Status::getCaseStatusCollection($auto);

        // Grid filter, always add empty option
        $options = [
            '' => ['label' => ' ', 'value' => '']
        ];

        foreach ($statusCollection as $status) {
            $options[] = ['label' => $status->getLabel(), 'value' => $status->getId()];
        }
        return $options;
    }
}
