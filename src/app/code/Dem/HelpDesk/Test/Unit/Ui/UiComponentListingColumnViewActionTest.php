<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\CaseItem;

use Dem\HelpDesk\Ui\Component\Listing\Column\ViewAction as ViewAction;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Url;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem Index
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class UiComponentListingColumnViewActionTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|ViewAction
     */
    protected $viewAction;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->viewAction = $this->getMockBuilder(ViewAction::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUrlBuilder',
                'getData',
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Ui\Component\Listing\Column\ViewAction::prepareDataSource()
     */
    public function testPrepareDataSource()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'id_field_name' => 'id',
                        'id' => 1,
                        'action' => 'View'
                    ]
                ]
            ]
        ];

        $this->viewAction->setData('name', 'action');

        /** @var Url|MockObject  $urlBuilder*/
        $urlBuilder = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

        $this->viewAction->expects($this->any())
            ->method('getData')
            ->willReturn('action');

        $this->viewAction->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($urlBuilder);

        $urlBuilder->expects($this->any())
            ->method('getUrl')
            ->willReturn('helpdesk/*/*');

        $this->assertIsArray($this->viewAction->prepareDataSource($dataSource));
    }
}
