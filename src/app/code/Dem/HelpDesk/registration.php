<?php

namespace Dem\HelpDesk;

use Magento\Framework\Component\ComponentRegistrar;

/**
 * HelpDesk - Module Registration
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Dem_HelpDesk', __DIR__);
