<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;

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
     * @return mixed
     */
    public function save(CaseItemInterface $entity);

    /**
     * @param $entityId
     * @return mixed
     */
    public function getById($entityId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\CaseItemSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CaseItemInterface $entity
     * @return mixed
     */
    public function delete(CaseItemInterface $entity);

    /**
     * @param $entityId
     * @return mixed
     */
    public function deleteById($entityId);
}
