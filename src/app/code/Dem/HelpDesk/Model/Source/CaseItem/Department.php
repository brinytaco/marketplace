<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source\CaseItem;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * HelpDesk Source Model - CaseItem Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Department implements OptionSourceInterface
{
    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $optionArray = [];

    /**
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    private $departmentRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Magento\Store\Model\System\Store $store
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Dem\HelpDesk\Model\DepartmentRepository $departmentRepository
     * @return void
     */
    public function __construct(
        \Dem\HelpDesk\Helper\Data $helper,
        \Magento\Store\Model\System\Store $store,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Dem\HelpDesk\Model\DepartmentRepository $departmentRepository
    ) {
        $this->helper = $helper;
        $this->store = $store;
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Return array of available departments for all websites
     *
     * @return array
     */
    public function toOptionArray()
    {
        $departmentList = $this->departmentRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($departmentList as $department) {

        }

        return $this->optionArray;
    }

}
