<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source;

use Dem\HelpDesk\Model\DepartmentRepository;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\System\Store;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\Registry;

use Dem\HelpDesk\Helper\Data as Helper;

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
     * @var Helper
     */
    protected $helper;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $optionArray = [];

    /**
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param Helper $helper
     * @param Store $store
     * @param RequestInterface $request
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DepartmentRepository $departmentRepository
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param Registry $coreRegistry
     * @param CollectionFactory $collectionFactory
     * @return void
     */
    public function __construct(
        Helper $helper,
        Store $store,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DepartmentRepository $departmentRepository,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        Registry $coreRegistry,
        CollectionFactory $collectionFactory
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
