<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\CaseItemRepositoryInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterfaceFactory;
use Dem\HelpDesk\Api\Data\CaseItemSearchResultsInterfaceFactory;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as CaseItemResource;
use Dem\HelpDesk\Model\ResourceModel\CaseItem\CollectionFactory as CaseItemCollectionFactory;

/**
 * HelpDesk Model Repository - Case
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
     * @var array
     */
    protected $instances = [];

    /**
     * @var CaseItemResource
     */
    protected $resource;

    /**
     * @var CaseItemCollectionFactory
     */
    protected $caseItemCollectionFactory;

    /**
     * @var CaseItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CaseItemInterfaceFactory
     */
    protected $caseItemInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        CaseItemResource $resource,
        CaseItemCollectionFactory $caseItemCollectionFactory,
        CaseItemSearchResultsInterfaceFactory $caseItemSearchResultsInterfaceFactory,
        CaseItemInterfaceFactory $caseItemInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->caseItemCollectionFactory = $caseItemCollectionFactory;
        $this->searchResultsFactory = $caseItemSearchResultsInterfaceFactory;
        $this->caseItemInterfaceFactory = $caseItemInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param CaseItemInterface $entity
     * @return CaseItemInterface
     * @throws CouldNotSaveException
     */
    public function save(CaseItemInterface $entity)
    {
        try {
            /** @var CaseItemInterface|\Magento\Framework\Model\AbstractModel $entity */
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the entity: %1',
                $exception->getMessage()
            ));
        }
        return $entity;
    }

    /**
     * Get case record
     *
     * @param $entityId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($entityId)
    {
        if (!isset($this->instances[$entityId])) {
            /** @var \Dem\HelpDesk\Api\Data\CaseItemInterface|\Magento\Framework\Model\AbstractModel $entity */
            $entity = $this->caseItemInterfaceFactory->create();
            $this->resource->load($entity, $entityId);
            if (!$entity->getId()) {
                throw new NoSuchEntityException(__('Requested case doesn\'t exist'));
            }
            $this->instances[$entityId] = $entity;
        }
        return $this->instances[$entityId];
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\CaseItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Dem\HelpDesk\Api\Data\CaseItemSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Dem\HelpDesk\Model\ResourceModel\CaseItem\Collection $collection */
        $collection = $this->caseItemCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $field = 'entity_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $data = [];
        foreach ($collection as $datum) {
            $dataDataObject = $this->caseItemInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $datum->getData(), CaseItemInterface::class);
            $data[] = $dataDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($data);
    }

    /**
     * @param CaseItemInterface $entity
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(CaseItemInterface $entity)
    {
        /** @var \Dem\HelpDesk\Api\Data\CaseItemInterface|\Magento\Framework\Model\AbstractModel $entity */
        $id = $entity->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($entity);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to delete case %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * @param $entityId
     * @return bool
     */
    public function deleteById($entityId)
    {
        $entity = $this->getById($entityId);
        return $this->delete($entity);
    }
}
