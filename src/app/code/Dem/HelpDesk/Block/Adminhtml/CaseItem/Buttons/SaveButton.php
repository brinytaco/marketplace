<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * HelpDesk Block - Adminhtml CaseItem View SaveButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class SaveButton implements ButtonProviderInterface
{
    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Retrieve button data
     *
     * @return array button configuration
     * @since 1.0.0
     */
    public function getButtonData()
    {
        // If is new case only
        $case = $this->coreRegistry->registry(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY);

        if (!$case) {
            return [
                'label' => __('Submit'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save']],
                    'form-role' => 'save',
                ],
                'sort_order' => 30,
            ];
        }

        return [];
    }
}
