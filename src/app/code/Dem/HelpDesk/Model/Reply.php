<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * HelpDesk Model - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Reply extends AbstractModel
{
    const AUTHOR_TYPE_CREATOR = 'CREATOR';
    const AUTHOR_TYPE_SYSTEM = 'SYSTEM';
    const AUTHOR_TYPE_HELPDESK_USER = 'HELPDESK_USER';
    
    /**
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_reply';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\CaseItem::class);
    }

}
