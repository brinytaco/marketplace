<?php

namespace Dem\Base;

use Magento\Framework\Component\ComponentRegistrar;

/**
 * Dem Base - Module Registration
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Dem_Base', __DIR__);
