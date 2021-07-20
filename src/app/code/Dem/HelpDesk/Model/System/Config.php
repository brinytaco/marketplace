<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\System;

use Magento\Framework\App\Config\Value;

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
class Config extends Value
{
    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        return parent::afterSave();
    }
}
