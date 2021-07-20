<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\CaseItem;
use Magento\Framework\Registry;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * HelpDesk Service Model - Notifications
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Notifications
{
    /**
     * Send new case notifications
     *
     * Send notifications (emails) to:
     *   - creator
     *   - case manager
     *   - followers (dept followers initially)
     *
     * @param CaseItem $case
     * @return $this
     * @throws HelpDeskException
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function sendNewCaseNotifications(CaseItem $case)
    {
        /** @todo: Actually do something here */
        return $this;
    }

}
