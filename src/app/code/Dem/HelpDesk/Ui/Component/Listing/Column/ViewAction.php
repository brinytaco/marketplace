<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * HelpDesk Ui Component - Listing Column ViewAction
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ViewAction extends Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @since 1.0.0
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id_field_name'])) {
                    $viewUrlPath = $this->getData('config/viewUrlPath') ?: '#';
                    $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'id';
                    $indexField = $this->getData('config/indexField') ?: 'id';
                    $actionLabel = $this->getData('config/actionLabel') ?: 'View';
                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $this->getUrlBuilder()->getUrl(
                                $viewUrlPath,
                                [
                                    $urlEntityParamName => $item[$indexField]
                                ]
                            ),
                            // translate label
                            'label' => __($actionLabel)
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get UrlBuilder instance
     *
     * @return UrlInterface
     * @codeCoverageIgnore
     */
    protected function getUrlBuilder()
    {
        return $this->urlBuilder;
    }
}
