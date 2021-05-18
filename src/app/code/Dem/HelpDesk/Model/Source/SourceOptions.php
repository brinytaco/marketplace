<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;

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
abstract class SourceOptions implements OptionSourceInterface
{
    /**
     * @var string
     */
    const DEPT_OPTION_SOURCE_EMPTY_OPTION_TEXT = '-- Please Select --';

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
     * @var \Dem\HelpDesk\Api\DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Magento\Store\Model\System\Store $store
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Data\CollectionFactory $collectionFactory
     * @return void
     */
    public function __construct(
        \Dem\HelpDesk\Helper\Data $helper,
        \Magento\Store\Model\System\Store $store,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Data\CollectionFactory $collectionFactory
    ) {
        $this->helper = $helper;
        $this->store = $store;
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->departmentRepository = $departmentRepository;
        $this->coreRegistry = $coreRegistry;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get empty select option text
     *
     * @return string
     * @since 1.0.0
     */
    public static function getEmptySelectOptionText()
    {
        return __(self::DEPT_OPTION_SOURCE_EMPTY_OPTION_TEXT);
    }

    /**
     * Return array of select options
     *
     * @return array
     * @since 1.0.0
     */
    public function toOptionArray()
    {
        $this->optionArray[] = ['label' => self::getEmptySelectOptionText(), 'value' => ''];
        return $this;
    }

}
