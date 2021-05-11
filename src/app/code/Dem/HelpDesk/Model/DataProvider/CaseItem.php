<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\DataProvider;

use Dem\HelpDesk\Model\ResourceModel\CaseItem\CollectionFactory;

/**
 * HelpDesk DataProvider - CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItem extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $caseItemCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $caseItemCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $caseItemCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get collection data
     *
     * @return array
     * @since 1.0.0
     */
    public function getData()
    {
        return parent::getData();
    }
}
