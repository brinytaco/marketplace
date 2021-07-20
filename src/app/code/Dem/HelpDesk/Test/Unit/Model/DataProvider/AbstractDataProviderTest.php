<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\DataProvider;

use Dem\HelpDesk\Model\DataProvider\AbstractProvider;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * HelpDesk Unit Test - Model DataProvider Abstract
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AbstractDataProviderTest  extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|AbstractProvider
     */
    protected $providerModel;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->providerModel = $this->getMockBuilder(AbstractProvider::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCollection'
            ])
            ->getMock();

        $this->collection = $this->getMockBuilder(AbstractCollection::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getItems'
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Model\DataProvider\AbstractProvider::getData()
     */
    public function testGetData()
    {
        $item = $this->objectManager->get(\Magento\Framework\DataObject::class);
        $item->setData('id', 1);
        $items = [$item];

        $this->providerModel->expects($this->once())
            ->method('getCollection')
            ->willReturn($this->collection);

        $this->collection->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $this->assertIsArray($this->providerModel->getData());
    }
}
