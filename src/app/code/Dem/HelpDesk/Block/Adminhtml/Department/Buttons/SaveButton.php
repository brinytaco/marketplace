<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Department\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Registry;

/**
 * HelpDesk Block - Adminhtml Department View SaveButton
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
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param UrlInterface $urlBuilder
     * @param Registry $coreRegistry
     * @codeCoverageIgnore
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Registry $coreRegistry
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
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 30,
        ];
    }
}
