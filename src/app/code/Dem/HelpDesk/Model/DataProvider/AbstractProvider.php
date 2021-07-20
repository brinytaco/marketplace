<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Dem\HelpDesk\Model\ResourceModel\Department\CollectionFactory;
use Dem\Base\Helper\Data as BaseHelper;

/**
 * HelpDesk DataProvider - AbstractModel
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AbstractProvider extends AbstractDataProvider
{
    /**
     * @var \Dem\Base\Helper\Data
     */
    protected $baseHelper;

    /**
     * @param CollectionFactory $collectionFactory
     * @param BaseHelper $baseHelper
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        BaseHelper $baseHelper,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->baseHelper = $baseHelper;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


    /**
     * Populate form data by fieldset
     *
     * Remember that each field in a fieldset is grouped by fieldset:
     *
     *      For example, if the fieldset name is "general", then the field "priority"
     *      will be identified by `name="general['priority']" `
     *
     *      So for this getData() method, you must identify which fieldset
     *      you plan to target your data population.
     *
     * @example $fieldsetName = 'general';
     *          $this->loadedData[$page->getId()][$fieldsetName] = $page->getData();
     *
     * @return array
     * @since 1.0.0
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->getCollection()->getItems();
        foreach ($items as $item) {
            $itemData = $item->getData();
            $this->formatDateValues($itemData);
            $this->loadedData[$item->getId()]['general'] = $itemData;
        }
        return $this->loadedData;
    }

    /**
     * Format date field values
     *
     * @param array $itemData
     * @return array
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function formatDateValues(&$itemData)
    {
        return $itemData;
    }
}
