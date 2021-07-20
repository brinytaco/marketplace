<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Source\CaseItem;

use Dem\HelpDesk\Model\Source\CaseItem\Priority;
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
class SourceCaseItemPriorityTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Priority
     */
    protected $sourceOptions;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->sourceOptions = $this->objectManager->get(Priority::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Priority::toOptionArray()
     */
    public function testGetToOptionArray()
    {
        $this->assertIsArray($this->sourceOptions->toOptionArray(true));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Priority::getOptions()
     */
    public function testGetOptions()
    {
        $this->assertInstanceOf(Collection::class, $this->sourceOptions->getOptions());
    }
}
