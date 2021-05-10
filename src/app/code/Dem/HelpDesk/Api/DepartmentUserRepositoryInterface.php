<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\DepartmentUserInterface;

/**
 * HelpDesk Api Interface - DepartmentUser Repository
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface DepartmentUserRepositoryInterface
{
    /**
     * @param DepartmentUserInterface $entity
     * @return \Dem\HelpDesk\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(DepartmentUserInterface $entity);

    /**
     * @param $entityId
     * @return \Dem\HelpDesk\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($entityId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\DepartmentUserSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param DepartmentUserInterface $entity
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DepartmentUserInterface $entity);

    /**
     * @param $entityId
     * @return mixed
     */
    public function deleteById($entityId);
}
