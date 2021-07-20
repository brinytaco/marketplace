<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\DepartmentUser\CollectionFactory;
use Dem\HelpDesk\Model\DepartmentUserFactory as ObjectFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * HelpDesk Model Repository - DepartmentUser
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentUserRepository extends AbstractRepository
{
    /**
     * @var \Dem\HelpDesk\Model\DepartmentUserFactory
     */
    protected $objectFactory;

    /**
     * @var \Dem\HelpDesk\Model\ResourceModel\DepartmentUser\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param ObjectFactory $objectFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterface $searchResultsInterface
     * @codeCoverageIgnore
     */
    public function __construct(
        ObjectFactory $objectFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterface $searchResultsInterface
    ) {
        $this->objectFactory = $objectFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct(
            $collectionProcessor,
            $searchResultsInterface
        );
    }
}
