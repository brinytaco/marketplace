<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\System;

/**
 * HelpDesk System Model - Configuration
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Config extends \Magento\Framework\App\Config\Value
{
    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        return parent::afterSave();
    }
}
