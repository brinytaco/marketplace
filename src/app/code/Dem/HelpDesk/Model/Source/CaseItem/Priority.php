<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\DataObject;
use Magento\Framework\Data\OptionSourceInterface;

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
class Priority implements OptionSourceInterface
{
    /**
     * Case priorities
     */
    const CASE_PRIORITY_NORMAL   = 0;
    const CASE_PRIORITY_URGENT   = 1;
    const CASE_PRIORITY_CRITICAL = 2;

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
     * Return array of case priority options
     *
     * Adminhtml view, so include Admin website,
     * but rewrite the label
     *
     * @return array
     */
    public function toOptionArray()
    {
        $priorityCollection = $this->getOptions();

        $this->optionArray[] = ['label' => __('-- Please Select --'), 'value' => ''];

        foreach ($priorityCollection as $priority) {
            $this->optionArray[] = ['label' => $priority->getLabel(), 'value' => $priority->getId()];
        }
        return $this->optionArray;
    }

    /**
     * Get available case priority options
     *
     * @return array
     */
    public function getOptions()
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

        $casePriorities = $this->collectionFactory->create();
        foreach ($priorities as $priority) {
            $casePriorities->addItem($priority);
        }

        return $casePriorities;
    }
}
