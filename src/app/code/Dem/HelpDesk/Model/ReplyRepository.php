<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\ReplyRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;
use Dem\HelpDesk\Model\ResourceModel\Reply\CollectionFactory;
use Dem\HelpDesk\Model\ReplyFactory;

/**
 * HelpDesk Model Repository - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ReplyRepository implements ReplyRepositoryInterface
{
    /**
     * @var ReplyFactory
     */
    private $factory;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        ReplyFactory $factory,
        Resource $resource,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->factory = $factory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return ReplyInterface|false
     */
    public function getById($id)
    {
        $object = $this->factory->create();
        $this->resource->load($object, $id);
        if (!$object->getId()) {
            return false;
        }
        return $object;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * @param ReplyInterface $reply
     * @return ReplyInterface
     * @throws LocalizedException
     */
    public function save(ReplyInterface $reply)
    {
        $this->resource->save($reply);
        return $reply;
    }

    /**
     * @param ReplyInterface $reply
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(ReplyInterface $reply)
    {
        try {
            $this->resource->delete($reply);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the reply: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return ReplyInterface
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $object = $this->getById($id);
        return $this->delete($object);
    }
}
