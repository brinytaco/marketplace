<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * HelpDesk Model Repository - Abstract
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
abstract class AbstractRepository
{
    /**
     * @var ObjectFactory
     */
    protected $objectFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var \Magento\Framework\Api\SearchResultsInterface
     */
    protected $searchResultsInterface;

    /**
     * Child repositories must set the $objectFactory
     * and $collectionFactory in their own constructors
     *
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterface $searchResultsInterface
     * @codeCoverageIgnore
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterface $searchResultsInterface
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsInterface = $searchResultsInterface;
    }

    /**
     * Get object factory
     *
     * @return ObjectFactory
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getObjectFactory()
    {
        return $this->objectFactory;
    }

    /**
     * Get collection factory instance
     *
     * @return CollectionFactory
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getCollectionFactory()
    {
        return $this->collectionFactory;
    }

    /**
     * Get SearchResultsInterface instance
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getSearchResultsInterface()
    {
        return $this->searchResultsInterface;
    }

    /**
     * Get CollectionProcessorInterface instance
     *
     * @return \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function getCollectionProcessor()
    {
        return $this->collectionProcessor;
    }

    /**
     * Get object by id
     *
     * @param int $id
     * @return \Magento\Framework\Model\AbstractModel
     * @since 1.0.0
     */
    public function getById($id)
    {
        $object = $this->getObjectFactory()->create();
        if ($object->getResourceName()) {
            $object->getResource()->load($object, $id);
        }
        return $object;
    }

    /**
     * Get object by field name/value
     *
     * @param int|string $value
     * @return \Magento\Framework\Model\AbstractModel
     * @since 1.0.0
     */
    public function getByField($value, $field = null)
    {
        $object = $this->getObjectFactory()->create();
        if ($object->getResourceName()) {
            $object->getResource()->load($object, $value, $field);
        }
        return $object;
    }

    /**
     * Get object collection as search result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws LocalizedException
     * @since 1.0.0
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->getCollectionFactory()->create();
        $this->getCollectionProcessor()->process($searchCriteria, $collection);

        $searchResultsInterface = $this->getSearchResultsInterface();
        $searchResultsInterface->setSearchCriteria($searchCriteria);

        if ($collection->getResourceModelName()) {
            $searchResultsInterface->setItems($collection->getItems());
        }

        return $searchResultsInterface;
    }

    /**
     * Save object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\AbstractModel
     * @throws CouldNotSaveException
     * @since 1.0.0
     */
    public function save(AbstractModel $object)
    {
        try {
            if (!$object->getResourceName()) {
                throw new \Exception('Resource not set');
            }
            $object->getResource()->save($object);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the entry: %1', $exception->getMessage())
            );
        }
        return $object;
    }

    /**
     * Delete object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @since 1.0.0
     */
    public function delete(AbstractModel $object)
    {
        try {
            if (!$object->getResourceName()) {
                throw new \Exception('Resource not set');
            }
            $object->getResource()->delete($object);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * Delete object by id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @since 1.0.0
     */
    public function deleteById($id)
    {
        try {
            $object = $this->getById($id);
            if (!$object->getId()) {
                throw new NoSuchEntityException(__('The entry does not exist: %1', $id));
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the entry: %1', $exception->getMessage())
            );
        }
        return $this->delete($object);
    }
}
