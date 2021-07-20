<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\DataProvider;

use Magento\Framework\App\ObjectManager;
/**
 * HelpDesk DataProvider - CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItem extends \Dem\Base\Model\DataProvider\AbstractProvider
{
    /**
     * Additional constructor
     *
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->collection = ObjectManager::getInstance()->get(\Dem\HelpDesk\Model\ResourceModel\CaseItem\Collection::class);
        return parent::_construct();
    }

}
