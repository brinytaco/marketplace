<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Source\CaseItem;

use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Source\CaseItem\Department;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \Magento\Framework\Api\SearchCriteria;

/**
 * HelpDesk Unit Test - Source Model Options
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class SourceCaseItemDepartmentTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Department
     */
    protected $sourceOptions;

    /**
     * @var MockObject|\Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var MockObject|\Magento\Framework\Api\SearchCriteria
     */
    protected $searchCriteria;

    /**
     * @var MockObject|\Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var MockObject|\Magento\Store\Api\Data\WebsiteInterface
     */
    protected $website;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->sourceOptions = $this->getMockBuilder(Department::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
                'getSearchCriteria',
                'getDepartmentRepository',
                'getRegistry',
                'getFilter',
                'getFilterGroup',
            ])
            ->getMock();

        $this->helper = $this->getMockBuilder(\Dem\HelpDesk\Helper\Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getIsAdminArea',
                'getWebsite',
                'getStore'
            ])
            ->getMock();

        $this->registry = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(['registry'])
            ->getMock();

        $websiteData = ['id' => 1];
        $this->website = $this->objectManager->create(\Magento\Store\Model\Website::class);
        $this->website->setData($websiteData);

        /** @var SearchCriteria|MockObject $searchCriteria */
        $this->searchCriteria = $this->objectManager->get(SearchCriteria::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::toOptionArray()
     */
    public function testGetToOptionArray()
    {
        $deptRepository = $this->getMockBuilder(\Dem\HelpDesk\Model\DepartmentRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getList'])
            ->getMock();

        $department = $this->objectManager->get(\Dem\HelpDesk\Model\Department::class);
        $searchResults = $this->objectManager->get(SearchResults::class);
        $searchResults->setItems([$department]);

        $this->sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->sourceOptions->expects($this->any())
            ->method('getSearchCriteria')
            ->willReturn($this->searchCriteria);

        $this->sourceOptions->expects($this->any())
            ->method('getDepartmentRepository')
            ->willReturn($deptRepository);

        $deptRepository->expects($this->any())
            ->method('getList')
            ->willReturn($searchResults);

        $this->assertIsArray($this->sourceOptions->toOptionArray(true));
        $this->assertIsArray($this->sourceOptions->toOptionArray(false));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::getCurrentWebsiteId()
     */
    public function testGetCurrentWebsiteIdAdmin()
    {
        $this->sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->sourceOptions->expects($this->any())
            ->method('getRegistry')
            ->willReturn($this->registry);

        $this->helper->expects($this->any())
            ->method('getIsAdminArea')
            ->willReturn(true);

        $this->registry->expects($this->any())
            ->method('registry')
            ->willReturn($this->website);

        $this->assertEquals(1, $this->sourceOptions->getCurrentWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::getCurrentWebsiteId()
     */
    public function testGetCurrentWebsiteIdFrontend()
    {
        $this->sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->helper->expects($this->any())
            ->method('getIsAdminArea')
            ->willReturn(false);

        $this->helper->expects($this->any())
            ->method('getWebsite')
            ->willReturn($this->website);

        $this->assertEquals(1, $this->sourceOptions->getCurrentWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::getCurrentWebsiteId()
     */
    public function testGetCurrentWebsiteIdFalse()
    {
        $this->sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->helper->expects($this->any())
            ->method('getIsAdminArea')
            ->willReturn(false);

        $this->helper->expects($this->any())
            ->method('getWebsite')
            ->willReturn(false);

        $this->assertFalse($this->sourceOptions->getCurrentWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::addWebsiteFilter()
     */
    public function testAddWebsiteFilter()
    {
        $filter = $this->objectManager->get(\Magento\Framework\Api\Filter::class);
        $filterGroup = $this->objectManager->get(\Magento\Framework\Api\Search\FilterGroup::class);

        $sourceOptions = $this->getMockBuilder(Department::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
                'getCurrentWebsiteId',
                'getFilter',
                'getFilterGroup'
            ])
            ->getMock();

        $sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $sourceOptions->expects($this->any())
            ->method('getCurrentWebsiteId')
            ->willReturn(1);

        $sourceOptions->expects($this->any())
            ->method('getFilter')
            ->willReturn($filter);

        $sourceOptions->expects($this->any())
            ->method('getFilterGroup')
            ->willReturn($filterGroup);

        $this->assertInstanceOf(Department::class, $sourceOptions->addWebsiteFilter($this->searchCriteria, 1));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Department::addActiveFilter()
     */
    public function testAddActiveFilter()
    {
        $filter = $this->objectManager->get(\Magento\Framework\Api\Filter::class);
        $filterGroup = $this->objectManager->get(\Magento\Framework\Api\Search\FilterGroup::class);

        $sourceOptions = $this->getMockBuilder(Department::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
                'getCurrentWebsiteId',
                'getFilter',
                'getFilterGroup'
            ])
            ->getMock();

        $sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $sourceOptions->expects($this->any())
            ->method('getCurrentWebsiteId')
            ->willReturn(1);

        $sourceOptions->expects($this->any())
            ->method('getFilter')
            ->willReturn($filter);

        $sourceOptions->expects($this->any())
            ->method('getFilterGroup')
            ->willReturn($filterGroup);

        $this->assertInstanceOf(Department::class, $sourceOptions->addActiveFilter($this->searchCriteria, 1));
    }
}
