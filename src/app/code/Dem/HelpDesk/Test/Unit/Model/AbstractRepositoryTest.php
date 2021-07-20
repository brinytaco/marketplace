<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

include '/var/www/html/app/bootstrap.php';

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\AbstractRepository;
use Dem\HelpDesk\Model\ResourceModel\CaseItem\CollectionFactory;
use Dem\HelpDesk\Model\ResourceModel\CaseItem\Collection;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Repository CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AbstractRepositoryTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|CaseItemRepository
     */
    protected $repository;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->repository = $this->getMockBuilder(AbstractRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getObjectFactory',
                'getCollectionFactory',
                'getCollectionProcessor',
                'getSearchResultsInterface',
            ])
            ->getMock();

        $this->objectFactory = $this->getMockBuilder(CaseItemFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->collectionProcessor = $this->getMockBuilder(CollectionProcessorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['process'])
            ->getMock();

        $this->searchResultsInterface = $this->getMockBuilder(SearchResults::class)
            ->disableOriginalConstructor()
            ->setMethods(['setItems'])
            ->getMock();

        $this->resourceMock = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods(['delete','save','load'])
            ->getMock();

        $this->caseMock = $this->objectManager->get(CaseItem::class);

        $this->collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems', 'getResourceModelName'])
            ->getMock();

        $this->collectionMock->addItem(new \Magento\Framework\DataObject([]));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::getById()
     */
    public function testGetById()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
                'getResourceName',
            ])
            ->getMock();

        $this->repository->expects($this->any())
            ->method('getObjectFactory')
            ->willReturn($this->objectFactory);

        $this->objectFactory->expects($this->any())
            ->method('create')
            ->willReturn($caseItem);

        $this->assertInstanceOf(CaseItem::class, $this->repository->getById(1));

        $caseItem->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $caseItem->expects($this->once())
            ->method('getResourceName')
            ->willReturn(true);

        $this->resourceMock->expects($this->once())
            ->method('load')
            ->willReturn(true);

        $this->assertInstanceOf(CaseItem::class, $this->repository->getById(1));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::getByField()
     */
    public function testGetByField()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
                'getResourceName',
            ])
            ->getMock();

        $this->repository->expects($this->any())
            ->method('getObjectFactory')
            ->willReturn($this->objectFactory);

        $this->objectFactory->expects($this->any())
            ->method('create')
            ->willReturn($caseItem);

        $this->assertInstanceOf(CaseItem::class, $this->repository->getByField(1, CaseItem::CASE_ID));

        $caseItem->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $caseItem->expects($this->once())
            ->method('getResourceName')
            ->willReturn(true);

        $this->resourceMock->expects($this->once())
            ->method('load')
            ->willReturn(true);

        $this->assertInstanceOf(CaseItem::class, $this->repository->getByField(1, CaseItem::CASE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::getList()
     */
    public function testGetList()
    {
        $this->repository->expects($this->once())
            ->method('getCollectionFactory')
            ->willReturn($this->collectionFactory);

        $this->collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->collectionMock->expects($this->once())
            ->method('getResourceModelName')
            ->willReturn(true);

        $this->collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([]);

        $this->repository->expects($this->once())
            ->method('getCollectionProcessor')
            ->willReturn($this->collectionProcessor);

        $this->repository->expects($this->once())
            ->method('getSearchResultsInterface')
            ->willReturn($this->searchResultsInterface);

        /** @var SearchCriteriaInterface|MockObject $searchCriteria */
        $searchCriteria = $this->objectManager->get(SearchCriteriaInterface::class);
        $this->assertInstanceOf(SearchResults::class, $this->repository->getList($searchCriteria));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::save()
     */
    public function testSave()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->objectManager->get(CaseItem::class);
        $this->expectException(\Exception::class);
        $this->repository->save($caseItem);
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::save()
     */
    public function testSave2()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
                'getResourceName',
            ])
            ->getMock();

        $caseItem->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $caseItem->expects($this->once())
            ->method('getResourceName')
            ->willReturn(true);

        $this->resourceMock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $this->repository->save($caseItem);
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::delete()
     */
    public function testDelete()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->objectManager->get(CaseItem::class);

        $this->expectException(\Exception::class);
        $this->repository->delete($caseItem);
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::delete()
     */
    public function testDelete2()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
                'getResourceName',
            ])
            ->getMock();

        $caseItem->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $caseItem->expects($this->once())
            ->method('getResourceName')
            ->willReturn(true);

        $this->resourceMock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $this->repository->delete($caseItem);
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::deleteById()
     */
    public function testDeleteById()
    {
        /** @var CaseItem|MockObject $caseItem */
        $caseItem = $this->objectManager->get(CaseItem::class);

        $repository = $this->getMockBuilder(AbstractRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getById',
            ])
            ->getMock();

        $repository->expects($this->once())
            ->method('getById')
            ->willReturn($caseItem);

        $this->expectException(\Magento\Framework\Exception\CouldNotDeleteException::class);
        $repository->deleteById(1);
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItemRepository::deleteById()
     */
    public function testDeleteById2()
    {
        $caseItem = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
                'getResourceName',
            ])
            ->getMock();

        $caseItem->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $caseItem->expects($this->once())
            ->method('getResourceName')
            ->willReturn(true);

        $caseItem->setId(1);

        $repository = $this->getMockBuilder(AbstractRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getById',
            ])
            ->getMock();

        $repository->expects($this->once())
            ->method('getById')
            ->willReturn($caseItem);

        $this->assertTrue($repository->deleteById(1));
    }
}
