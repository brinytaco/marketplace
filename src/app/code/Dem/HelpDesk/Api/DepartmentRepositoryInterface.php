<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\DepartmentInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * HelpDesk Api Interface - Department Repository
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface DepartmentRepositoryInterface
{
    /**
     * @param DepartmentInterface $entity
     * @return DepartmentInterface
     * @throws CouldNotSaveException
     */
    public function save(DepartmentInterface $entity);

    /**
     * @param $entityId
     * @return DepartmentInterface|false
     */
    public function getById($entityId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param DepartmentInterface $entity
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(DepartmentInterface $entity);

    /**
     * @param $entityId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($entityId);
}
