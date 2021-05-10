<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\Data\DepartmentUserInterface;
use Dem\HelpDesk\Api\Data\DepartmentUserSearchResultInterfaceFactory;
use Dem\HelpDesk\Api\DepartmentUserRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\DepartmentUser as Resource;
use Dem\HelpDesk\Model\ResourceModel\DepartmentUser\CollectionFactory;

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
class DepartmentUserRepository implements DepartmentUserRepositoryInterface
{
    /**
     * @var DepartmentUserFactory
     */
    private $factory;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var DepartmentUserCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DepartmentUserSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        DepartmentUserFactory $factory,
        Resource $resource,
        CollectionFactory $collectionFactory,
        DepartmentUserSearchResultInterfaceFactory $searchResultFactory,
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
     * @return \Dem\HelpDesk\Api\Data\DepartmentUserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $user = $this->factory->create();
        $this->resource->load($user, $id);
        if (!$user->getId()) {
            throw new NoSuchEntityException(__('Unable to find user with ID `%1`', $id));
        }
        return $user;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\DepartmentUserSearchResultInterface
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
     * @param \Dem\HelpDesk\Api\Data\DepartmentUserInterface $user
     * @return \Dem\HelpDesk\Api\Data\DepartmentUserInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(DepartmentUserInterface $user)
    {
        $this->resource->save($user);
        return $user;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\DepartmentUserInterface $user
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DepartmentUserInterface $user)
    {
        try {
            $this->resource->delete($user);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the user: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\DepartmentUserInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $user = $this->factory->create();
        $this->resource->load($user, $id);
        if (!$user->getId()) {
            throw new NoSuchEntityException(__('Unable to find user with ID `%1`', $id));
        }
        return $this->delete($user);
    }
}
