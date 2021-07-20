<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Source\CaseItem;

use Dem\HelpDesk\Model\Source\CaseItem\Status;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
class SourceCaseItemStatusTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Status
     */
    protected $sourceOptions;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->sourceOptions = $this->objectManager->get(Status::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Status::toOptionArray()
     */
    public function testGetToOptionArray()
    {
        $this->assertIsArray($this->sourceOptions->toOptionArray(true));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Status::getOptions()
     */
    public function testGetOptions()
    {
        $this->assertInstanceOf(Collection::class, $this->sourceOptions->getOptions());
        $this->assertInstanceOf(Collection::class, $this->sourceOptions->getOptions(false));
    }
}
