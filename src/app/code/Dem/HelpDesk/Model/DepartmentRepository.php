<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Dem\HelpDesk\Api\Data\DepartmentSearchResultInterfaceFactory;
use Dem\HelpDesk\Api\DepartmentRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\Department;
use Dem\HelpDesk\Model\ResourceModel\Department\CollectionFactory;

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
    private $departmentFactory;

    /**
     * @var Department
     */
    private $departmentResource;

    /**
     * @var DepartmentCollectionFactory
     */
    private $departmentCollectionFactory;

    /**
     * @var DepartmentSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        DepartmentFactory $departmentFactory,
        Department $departmentResource,
        CollectionFactory $departmentCollectionFactory,
        DepartmentSearchResultInterfaceFactory $departmentSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->departmentFactory = $departmentFactory;
        $this->departmentResource = $departmentResource;
        $this->departmentCollectionFactory = $departmentCollectionFactory;
        $this->searchResultFactory = $departmentSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return \Magento4u\SampleRepository\Api\Data\DepartmentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $department = $this->departmentFactory->create();
        $this->departmentResource->load($department, $id);
        if (!$department->getId()) {
            throw new NoSuchEntityException(__('Unable to find Department with ID "%1"', $id));
        }
        return $department;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento4u\SampleRepository\Api\Data\DepartmentSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->departmentCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @param \Magento4u\SampleRepository\Api\Data\DepartmentInterface $department
     * @return \Magento4u\SampleRepository\Api\Data\DepartmentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(DepartmentInterface $department)
    {
        $this->departmentResource->save($department);
        return $department;
    }

    /**
     * @param \Magento4u\SampleRepository\Api\Data\DepartmentInterface $department
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DepartmentInterface $department)
    {
        try {
            $this->departmentResource->delete($department);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Magento4u\SampleRepository\Api\Data\DepartmentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $department = $this->departmentFactory->create();
        $this->departmentResource->load($department, $id);
        if (!$department->getId()) {
            throw new NoSuchEntityException(__('Unable to find Department with ID "%1"', $id));
        }
        return $this->delete($department);
    }
}
