<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\CaseItemRepositoryInterface;
use Dem\HelpDesk\Model\CaseItemFactory;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as CaseItemResource;
use Dem\HelpDesk\Model\ResourceModel\CaseItem\CollectionFactory;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

/**
 * HelpDesk Model Repository - CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItemRepository implements CaseItemRepositoryInterface
{
    /**
     * @var CaseItemFactory
     */
    private $factory;

    /**
     * @var CaseItemResource
     */
    private $resource;

    /**
     * @var CaseItemCollectionFactory
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
        CaseItemFactory $factory,
        CaseItemResource $resource,
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
     * @return CaseItemInterface|false
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
     * @param CaseItemInterface $caseItem
     * @return CaseItemInterface
     * @throws CouldNotSaveException
     */
    public function save(CaseItemInterface $caseItem)
    {
        $this->resource->save($caseItem);
        return $caseItem;
    }

    /**
     * @param CaseItemInterface $caseItem
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CaseItemInterface $caseItem)
    {
        try {
            $this->resource->delete($caseItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $object = $this->getById($id);
        return $this->delete($object);
    }
}
