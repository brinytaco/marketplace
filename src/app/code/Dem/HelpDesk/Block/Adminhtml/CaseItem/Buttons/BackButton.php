<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * HelpDesk Block - Adminhtml CaseItem View BackButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class BackButton implements ButtonProviderInterface
{
    /**
     * URL builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     * @codeCoverageIgnore
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
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
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getButtonUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get button url
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getButtonUrl()
    {
        return $this->urlBuilder->getUrl('*/caseitem/');
    }
}
