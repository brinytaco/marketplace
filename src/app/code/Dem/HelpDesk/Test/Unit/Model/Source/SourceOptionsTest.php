<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Source;

use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\Source\SourceOptions;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Collection;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
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
class SourceOptionsTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|SourceOptions
     */
    protected $sourceOptions;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->sourceOptions = $this->objectManager->get(SourceOptions::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\SourceOptions::getEmptySelectOptionText()
     */
    public function testGetEmptySelectOptionText()
    {
        $this->assertEquals(
            SourceOptions::DEPT_OPTION_SOURCE_EMPTY_OPTION_TEXT,
            $this->sourceOptions->getEmptySelectOptionText()
        );
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\SourceOptions::toOptionArray()
     */
    public function testGetToOptionArray()
    {
        $this->assertIsArray($this->sourceOptions->toOptionArray());
    }
}
