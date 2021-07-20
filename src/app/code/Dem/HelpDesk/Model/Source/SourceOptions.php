<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\Filter;
use Magento\Framework\Registry;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection;
use Magento\Store\Model\System\Store;
use Magento\Framework\App\Request\Http as RequestHttp;

use Dem\HelpDesk\Helper\Data as Helper;

/**
 * HelpDesk Source Model - Source Model Options
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
     * @var array
     */
    protected $optionArray = [];

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Api\SearchCriteria
     */
    protected $searchCriteria;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Magento\Framework\Data\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param RequestHttp $request
     * @param Store $store
     * @param Helper $helper
     * @param SearchCriteria $searchCriteria
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param Registry $coreRegistry
     * @codeCoverageIgnore
     */
    public function __construct(
        RequestHttp $request,
        Store $store,
        Helper $helper,
        SearchCriteria $searchCriteria,
        FilterGroupBuilder $filterGroupBuilder,
        Registry $coreRegistry
    ) {
        $this->request = $request;
        $this->store = $store;
        $this->helper = $helper;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->coreRegistry = $coreRegistry;
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
        return $this->optionArray;
    }

    /**
     * Get RequestHttp instance
     *
     * @return \Magento\Framework\App\Request\Http
     * @codeCoverageIgnore
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get Store instance
     *
     * @return \Magento\Store\Model\System\Store
     * @codeCoverageIgnore
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Get Helpdesk helper instance
     *
     * @return \Dem\HelpDesk\Helper\Data
     * @codeCoverageIgnore
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get SearchCriteria instance
     *
     * @return \Magento\Framework\Api\SearchCriteria
     * @codeCoverageIgnore
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Get FilterGroup instance
     *
     * @return \Magento\Framework\Api\Search\FilterGroupBuilder
     * @codeCoverageIgnore
     */
    public function getFilterGroupBuilder()
    {
        return $this->filterGroupBuilder;
    }

    /**
     * Get new FilterGroup instance
     *
     * @return \Magento\Framework\Api\Search\FilterGroup
     * @codeCoverageIgnore
     */
    public function getFilterGroup()
    {
        return ObjectManager::getInstance()->create(FilterGroup::class);
    }

    /**
     * Get new Filter instance
     *
     * @return \Magento\Framework\Api\Filter
     * @codeCoverageIgnore
     */
    public function getFilter()
    {
        return ObjectManager::getInstance()->create(Filter::class);
    }

    /**
     * Get Registry instance
     *
     * @return \Magento\Framework\Registry
     * @codeCoverageIgnore
     */
    public function getRegistry()
    {
        return $this->coreRegistry;
    }

    /**
     * Get new Collection instance
     *
     * @return \Magento\Framework\Data\Collection
     * @codeCoverageIgnore
     */
    public function getCollection()
    {
        // Use create() to get new Collection instance each time
        return ObjectManager::getInstance()->create(Collection::class);
    }
}
