<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\FollowerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * HelpDesk Api Interface - Follower Repository
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface FollowerRepositoryInterface
{
    /**
     * @param FollowerInterface $entity
     * @return FollowerInterface
     * @throws CouldNotSaveException
     */
    public function save(FollowerInterface $entity);

    /**
     * @param $entityId
     * @return FollowerInterface|false
     */
    public function getById($entityId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param FollowerInterface $entity
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(FollowerInterface $entity);

    /**
     * @param $entityId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($entityId);
}
