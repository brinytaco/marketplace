<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Dem\HelpDesk\Api\DepartmentRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\Department;
use Dem\HelpDesk\Model\ResourceModel\Department\CollectionFactory;
use Dem\HelpDesk\Model\DepartmentFactory;

/**
 * HelpDesk Model Repository - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentRepository implements DepartmentRepositoryInterface
{
    /**
     * @var DepartmentFactory
     */
    private $factory;

    /**
     * @var Department
     */
    private $resource;

    /**
     * @var DepartmentCollectionFactory
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
        DepartmentFactory $factory,
        Department $resource,
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
     * @return \Dem\HelpDesk\Api\Data\DepartmentInterface|null
     */
    public function getById($id)
    {
        $object = $this->factory->create();
        $this->resource->load($object, $id);
        if (!$object->getId()) {
            return null;
        }
        return $object;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
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
     * @param \Dem\HelpDesk\Api\Data\DepartmentInterface $department
     * @return \Dem\HelpDesk\Api\Data\DepartmentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(DepartmentInterface $department)
    {
        $this->resource->save($department);
        return $department;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\DepartmentInterface $department
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DepartmentInterface $department)
    {
        try {
            $this->resource->delete($department);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\DepartmentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $object = $this->factory->create();
        $this->resource->load($object, $id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Unable to find object with ID `%1`', $id));
        }
        return $this->delete($object);
    }
}
