<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\Data\FollowerInterface;
use Dem\HelpDesk\Api\Data\FollowerSearchResultInterfaceFactory;
use Dem\HelpDesk\Api\FollowerRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\Follower as Resource;
use Dem\HelpDesk\Model\ResourceModel\Follower\CollectionFactory;
use Dem\HelpDesk\Model\FollowerFactory;

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
class FollowerRepository implements FollowerRepositoryInterface
{
    /**
     * @var FollowerFactory
     */
    private $factory;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var FollowerCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var FollowerSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        FollowerFactory $factory,
        Resource $resource,
        CollectionFactory $collectionFactory,
        FollowerSearchResultInterfaceFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->factory = $factory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\FollowerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $follower = $this->factory->create();
        $this->resource->load($follower, $id);
        if (!$follower->getId()) {
            throw new NoSuchEntityException(__('Unable to find follower with ID `%1`', $id));
        }
        return $follower;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\FollowerSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\FollowerInterface $follower
     * @return \Dem\HelpDesk\Api\Data\FollowerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(FollowerInterface $follower)
    {
        $this->resource->save($follower);
        return $follower;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\FollowerInterface $follower
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(FollowerInterface $follower)
    {
        try {
            $this->resource->delete($follower);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the follower: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\FollowerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $follower = $this->factory->create();
        $this->resource->load($follower, $id);
        if (!$follower->getId()) {
            throw new NoSuchEntityException(__('Unable to find follower with ID `%1`', $id));
        }
        return $this->delete($follower);
    }
}
