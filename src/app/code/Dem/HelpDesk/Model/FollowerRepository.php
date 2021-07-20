<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\Follower\CollectionFactory;
use Dem\HelpDesk\Model\FollowerFactory as ObjectFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * HelpDesk Model Repository - Follower
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class FollowerRepository extends AbstractRepository
{
    /**
     * @var \Dem\HelpDesk\Model\FollowerFactory
     */
    protected $objectFactory;

    /**
     * @var \Dem\HelpDesk\Model\ResourceModel\Follower\CollectionFactory
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
