<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dem\HelpDesk\Model\System;

/**
 * Config category field backend
 *
 * @author      Magento Core Team <core@magentocommerce.com>
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
