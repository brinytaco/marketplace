<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * HelpDesk Api Interface - CaseItem Repository
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface CaseItemRepositoryInterface
{
    /**
     * @param CaseItemInterface $entity
     * @return CaseItemInterface
     * @throws CouldNotSaveException
     */
    public function save(CaseItemInterface $entity);

    /**
     * @param $entityId
     * @return CaseItemInterface|false
     */
    public function getById($entityId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CaseItemInterface $entity
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CaseItemInterface $entity);

    /**
     * @param $entityId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById($entityId);
}
