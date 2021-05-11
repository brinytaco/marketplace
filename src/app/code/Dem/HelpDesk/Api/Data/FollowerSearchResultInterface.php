<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - Follower Search Results
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface FollowerSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get entity list.
     *
     * @return \Dem\HelpDesk\Api\Data\FollowerInterface[]
     */
    public function getItems();

    /**
     * Set entity list.
     *
     * @param \Dem\HelpDesk\Api\Data\FollowerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
