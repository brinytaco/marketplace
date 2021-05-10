<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - Reply Search Results
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface ReplySearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get entity list.
     *
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface[]
     */
    public function getItems();

    /**
     * Set entity list.
     *
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
