<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\CaseItemSearchResultInterfaceFactory;
use Dem\HelpDesk\Api\CaseItemRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Dem\HelpDesk\Model\ResourceModel\CaseItem\CollectionFactory;

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
    private $caseItemFactory;

    /**
     * @var Resource
     */
    private $caseItemResource;

    /**
     * @var CaseItemCollectionFactory
     */
    private $caseItemCollectionFactory;

    /**
     * @var CaseItemSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        CaseItemFactory $caseItemFactory,
        Resource $caseItemResource,
        CollectionFactory $caseItemCollectionFactory,
        CaseItemSearchResultInterfaceFactory $caseItemSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->caseItemFactory = $caseItemFactory;
        $this->caseItemResource = $caseItemResource;
        $this->caseItemCollectionFactory = $caseItemCollectionFactory;
        $this->searchResultFactory = $caseItemSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\CaseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $caseItem = $this->caseItemFactory->create();
        $this->caseItemResource->load($caseItem, $id);
        if (!$caseItem->getId()) {
            throw new NoSuchEntityException(__('Unable to find CaseItem with ID "%1"', $id));
        }
        return $caseItem;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\CaseItemSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->caseItemCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\CaseItemInterface $caseItem
     * @return \Dem\HelpDesk\Api\Data\CaseItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(CaseItemInterface $caseItem)
    {
        $this->caseItemResource->save($caseItem);
        return $caseItem;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\CaseItemInterface $caseItem
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(CaseItemInterface $caseItem)
    {
        try {
            $this->caseItemResource->delete($caseItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\CaseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $caseItem = $this->caseItemFactory->create();
        $this->caseItemResource->load($caseItem, $id);
        if (!$caseItem->getId()) {
            throw new NoSuchEntityException(__('Unable to find CaseItem with ID "%1"', $id));
        }
        return $this->delete($caseItem);
    }
}
