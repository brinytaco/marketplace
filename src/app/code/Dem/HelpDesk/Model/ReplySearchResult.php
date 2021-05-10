<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchResults;
use Dem\HelpDesk\Api\Data\ReplySearchResultInterface;

/**
 * HelpDesk Model - Reply SearchResults
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ReplySearchResult extends SearchResults implements ReplySearchResultInterface
{

}